<?php

namespace App\Http\Controllers;

use App\Models\R008Document;
use App\Models\R008DocumentFile;
use App\Models\R008Product;
use App\Models\ReceiveGoodDocument;
use App\Models\ReceiveGoodFile;
use Barryvdh\DomPDF\Facade\Pdf;
use Mccarlosen\LaravelMpdf\Facades\LaravelMpdf as MPDF;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class R008SController extends Controller
{

    public function index(Request $request)
    {
        $docuno =  $request->form_doc_no;
        $branch = $request->branch_id;
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        $user = auth()->user();
        $user_id = $user->id;


        $results = R008Document::query();
        if ($docuno) {
            $results = $results->where(function ($query) use ($docuno) {
                    $query->where('rg_no', 'like', '%' . $docuno . '%')
                    ->orWhereHas('r008_files', function ($q) use ($docuno) {
                        $q->where('file', 'like', '%' . $docuno . '%');
                    });
                    // ->orWhere('remark', 'like', '%' . $docuno . '%');
            });
        }
        
        if ($branch) {
            $results = $results->where(function ($q) use ($branch) {
                $q->where('from_branch', $branch);
            });
        } 

        $role = Role::where('name','admin')->first();
        $role_id = $role->id;
        if($user->role != $role_id){
            $user_branches = $user->user_branches;
            $branch_ids = $user_branches->pluck('branch_id');
            $branch_ids[] = $user->branch_id;
            // dd($branch_ids);

            $results = $results->whereIn('branch_id',$branch_ids);
        }


        if($start_date || $end_date){
            $start_date = $request->start_date
                        ? Carbon::parse($request->start_date)->startOfDay()
                        : Carbon::createFromTimestamp(0)->startOfDay();
            $end_date = $request->end_date
                        ? Carbon::parse($request->end_date)->endOfDay()
                        : Carbon::today()->endOfDay();
            $results = $results->whereBetween('created_at', [$start_date , $end_date]);
        }else{
            $results->whereDate('created_at','>=',now()->subMonth());
        }

        $data = $results->orderBy('created_at','desc')->paginate(15);

        return view('r008s.index',compact("data"));
    }

    public function create()
    {
        $rg_no = session('rg_no');
        // dd($rg_no);
        return view("r008s.create",compact("rg_no"));
    } 


    public function store(Request $request){
        // dd($request);

        DB::beginTransaction();
        DB::connection('defective_product')->beginTransaction();
        DB::connection('master_product')->beginTransaction();

        try {
            $user = auth()->user();
            $user_id = $user->id;

            // $request['branch_id'] = $user->branch->id;

            // Start R008 Document
            $r008_document = R008Document::create([
                "document_date" => $request->document_date,
                "product_type" => $request->product_type,
                "rg_no" => $request->rg_no,
                "vendor_code" => $request->vendor_code,
                "truck_container_no" => $request->truck_container_no,
                "remark" => $request->remark,
                "branch_id" => $request->branch_id,
                "user_id" => $user_id
            ]);
            // End R008 Document


            // Start R008 Product
            $status_ids = $request['status_ids'];
            $product_code = $request['product_code'];
            $product_name = $request['product_name'];
            $gr_qty = $request['gr_qty'];
            $physical_qty = $request['physical_qty'];
            $diff = $request['diff'];

            $bd_qty = $request['bd_qty'];
            $sd_qty = $request['sd_qty'];
            $line_remark = $request['line_remark'];


            for($i=0; $i<count($product_code);$i++){
                $data = [
                    'r008_document_id' => $r008_document->id,
                    'product_code' => $product_code[$i],
                    'product_name' => $product_name[$i],
                    'gr_qty' => $gr_qty[$i],
                    'physical_qty' => $physical_qty[$i],
                    'diff' => $diff[$i],
                    'status_id' => $status_ids[$i],
                    "bdqty" => $bd_qty[$i],
                    "sdqty" => $sd_qty[$i],
                    "remark" => $line_remark[$i],
                ];
                R008Product::create($data);
            }
            // End R008 Product

            // // Start Auto RG To ERP
            $r008_document_id = $r008_document->id;
            $this->r8_document($r008_document_id, $request);
            // // End Auto RG To ERP

            // Start PO Full Update
            $receive_good_document = $r008_document->receive_good_document();
            $updated_po_document_count = updatePOFull($request->all(),$receive_good_document);
            // End PO Full Update
            
            // throw new \Exception("RG Document Update Error: ");

            DB::commit();
            DB::connection('defective_product')->commit();
            DB::connection('master_product')->commit();


            return response()->json([
                'success' => true,
                'message' => "R008 Document created in ERP software successfully!",
                'data' => $r008_document,
            ]);        

        } catch (Exception $e) {
            DB::rollBack();
            DB::connection('defective_product')->rollBack();
            DB::connection('master_product')->rollBack();


            Log::info($e);
            Log::info($e->getMessage());

            return response()->json([
                'success'=>false,
                'message'=> 'There is an error in saving R008 Document.'
            ]);

        }
    }

    public function show(string $id){
        $r008_document = R008Document::find($id);
        // dd($r008_document);


        $conn = DB::connection('defective_product');
        $statuses = $conn->select("
            SELECT * 
            FROM public.r008_subject
            ORDER BY subjectr008_id ASC
            LIMIT 100
        ");

        return view('r008s.show',compact("r008_document","statuses"));
    }


    public function r8_document($r008_document_id, Request $request){
        $r008_document = R008Document::find($r008_document_id);

        $insert_r008_document = generateR008Header($request->all(),$r008_document);
        $r8_document = $insert_r008_document[0];


        $r008_products = $r008_document->r008_products;
        $list_no = 0;

        foreach($r008_products as $product){
            $list_no++;
            $product['list_no'] = $list_no;
            $product['branch_id'] = $r008_document->branch_id;
            $product['branch_code'] = $r008_document->branch->branch_code;
            $product['r_doc_id'] = $r8_document->r_doc_id;

            $r008_document_detail = generateR008Detail($product);
        }

        // => Store R008 Document No In Portal
        $r008_doc_no = $r8_document->r_docuno;

        $r008_file = new R008DocumentFile(); 
        $r008_file->r008_document_id = $r008_document_id;
        $r008_file->name = 'R008';
        $r008_file->file = $r008_doc_no;
        $r008_file->save();


        $rg_no = $r008_document->rg_no;
        $receive_good_document = ReceiveGoodDocument::with('vendor')
        ->whereHas('receive_good_files', function ($q) use ($rg_no) {
            $q->where('file', $rg_no);
        })
        ->first();
        $receive_good_document_id = $receive_good_document->id;
        
        $receive_good_file = new ReceiveGoodFile(); 
        $receive_good_file->receive_good_document_id = $receive_good_document_id;
        $receive_good_file->name = 'R008';
        $receive_good_file->file = $r008_doc_no;
        $receive_good_file->save();


        // Start Update R008 Data to Portal
        foreach($r008_products as $product){
            $receive_good_products = $receive_good_document->receive_good_products()
                                    ->where('product_code',$product->product_code)
                                    ->update([
                                        'r8damqty' => $product->bdqty,
                                    ]);
        }
        // End Update R008 Data to Portal


        // Start Update R008 data to RG
            // Start R8 Document Number Update
            $updated_r8_document_count = updateR008No($request->all(),$r008_document);
            // End R8 Document Number Update


            // Start R8Qty for each items
            foreach($r008_products as $product){
                $product['status'] = $r008_document->status;
                $product['rg_no'] = $r008_document->rg_no;
                $rg_item_updated_count = updateR8DamQty($product);
            }
            // End R8Qty for each items
        // End Update R008 Data to RG

    }

    public function printPDF(string $id){
        $r008_document = R008Document::find($id);
        $conn = DB::connection('defective_product');
        $statuses = $conn->select("
            SELECT * 
            FROM public.r008_subject
            ORDER BY subjectr008_id ASC
            LIMIT 100
        ");

        view()->share(['r008_document' => $r008_document, 'statuses' => $statuses]);
        $pdf = MPDF::loadView('r008s.pdf');

        // return $pdf->download('invoice.pdf');
        return $pdf->stream('r008.pdf');
    }

    public function approve_form($id,Request $request){
        DB::beginTransaction();
        DB::connection('defective_product')->beginTransaction();
        DB::connection('master_product')->beginTransaction();
        try {

            $r008_document = R008Document::find($id);
            $r008_products = $r008_document->r008_products;
            $receive_good_document = $r008_document->receive_good_document();

            $status = $request->status;
            // dd($status);

            $user = auth()->user();
            $user_id = $user->id;

            if ($request->status == "Cancel") {

                $origianl_status = $r008_document->status;
                $r008_document->update(['status' => $request->status]);
                
                if ($origianl_status == 'Default') {
                   

                    $r008_document->update([
                        'rejected_by'=> $user_id,
                        'rejected_at' => now()
                    ]);

                    $r008_document->receive_good_document()->receive_good_files->where('name','R008')->first()?->delete();

                    // Start Update R008 Data to Portal
                    foreach($r008_products as $product){
                        $receive_good_products = $receive_good_document->receive_good_products()
                                                ->where('product_code',$product->product_code)
                                                ->update([
                                                    'r8damqty' => 0,
                                                ]);
                    }

                    $receive_good_document = $r008_document->receive_good_document();
                    $document = $receive_good_document->document;
                    $document->update(['status'=>'PO Partial']);


                    $receive_good_document->update(['r008'=>false]);
                    // End Update R008 Data to Portal
                }

                // Start RG Cancel In ERP
                    $updated_r8_document_count = cancelR8Doc($request->all(),$r008_document);
                
                    // Start Zero R8Qty for each items
                    foreach($r008_products as $product){
                        $product['status'] = $r008_document->status;
                        $product['rg_no'] = $r008_document->rg_no;
                        $rg_item_updated_count = updateR8DamQty($product);
                    }
                    // End Zero R8Qty for each items
                // End RG Cancel In EERP

            }
            // throw new \Exception("RG Document Update Error: ");

                
            DB::commit();
            DB::connection('defective_product')->commit();
            DB::connection('master_product')->commit();
            return back();
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            DB::rollBack();
            DB::connection('defective_product')->rollBack();
            DB::connection('master_product')->rollBack();

            return back()
            ->with('fails', "There is an error in processing R008 Form.");
        }

    }

}
