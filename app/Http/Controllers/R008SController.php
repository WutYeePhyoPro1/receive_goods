<?php

namespace App\Http\Controllers;

use App\Models\R008Document;
use App\Models\R008DocumentFile;
use App\Models\R008Product;
use App\Models\ReceiveGoodDocument;
use App\Models\ReceiveGoodFile;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class R008SController extends Controller
{

    public function index(Request $request)
    {
        $docuno =  $request->form_doc_no;
        $branch = $request->branch_id;
        $start_date = $request->start_date;
        $end_date = $request->end_date;


        $results = R008Document::query();
        if ($docuno) {
            $results = $results->where(function ($query) use ($docuno) {
                    $query->where('po_no', 'like', '%' . $docuno . '%')
                    ->orWhereHas('receive_good_files', function ($q) use ($docuno) {
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

            $request['branch_id'] = $user->branch->id;

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


            for($i=0; $i<count($product_code);$i++){
                $data = [
                    'r008_document_id' => $r008_document->id,
                    'product_code' => $product_code[$i],
                    'product_name' => $product_name[$i],
                    'gr_qty' => $gr_qty[$i],
                    'physical_qty' => $physical_qty[$i],
                    'diff' => $diff[$i],
                    'status_id' => $status_ids[$i]
                ];
                R008Product::create($data);
            }
            // End R008 Product

            // // Start Auto RG To ERP
            $r008_document_id = $r008_document->id;
            $this->r8_document($r008_document_id, $request);
            // // End Auto RG To ERP


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


        // Start R8 Document Number Update
        $updated_r8_document_count = updateR008No($request->all(),$r008_document);
        // End R8 Document Number Update
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
        $pdf = Pdf::loadView('r008s.pdf');

        // return $pdf->download('invoice.pdf');
        return $pdf->stream('r008.pdf');
    }
}
