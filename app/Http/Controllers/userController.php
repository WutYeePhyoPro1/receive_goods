<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Log;
use App\Models\User;
use App\Models\Truck;
use App\Models\Branch;
use App\Models\Source;
use App\Models\CarGate;
use App\Models\Product;
use App\Models\Document;
use App\Models\Tracking;
use App\Customize\Common;
use App\Models\CarHistory;
use App\Models\Department;
use App\Models\DriverInfo;
use App\Models\RemoveTrack;
use App\Models\GoodsReceive;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use App\Interfaces\UserRepositoryInterface;
use App\Models\PrintReason;
use App\Models\UploadImage;
use App\Models\UserBranch;
use Symfony\Component\CssSelector\Node\FunctionNode;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Milon\Barcode\DNS1D;

class userController extends Controller
{
    private UserRepositoryInterface $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
        $this->middleware('auth');
        $this->middleware(PermissionMiddleware::class . ':user-management')->only(['user', 'store_user', 'edit_user', 'update_user', 'del_user']);
        // $this->middleware(PermissionMiddleware::class . ':role-management')->only(['role', 'store_role', 'edit_role', 'update_role', 'del_role']);
        $this->middleware(PermissionMiddleware::class . ':permission-management')->only(['permission', 'store_permission', 'view_permission']);
        $this->middleware(PermissionMiddleware::class . ':barcode-scan')->only(['car_info','join_receive','receive_goods','car']);
    }

    public function list()
    {
        Common::Log(route('list'),"go to List Page");
        $user_branch    = getAuth()->branch_id;
        $mgld_dc        = ['17','19','20'];
        if(in_array($user_branch,$mgld_dc))
        {
            $loc    = 'dc';
        }elseif($user_branch == 1){
            $loc    = 'ho';
        }else{
            $loc    = 'other';
        }
        if(request('search') && !request('search_data'))
        {
            return back()->with('error','Please add search data');
        }else if(!request('search') && request('search_data')){
            return back()->with('error','Please add search method');
        }
        $ids=[];
        if(request('search') == 'truck_no' || request('search') == 'driver_name'){
            $ids = DriverInfo::where(request('search'),request('search_data'))->pluck('received_goods_id');
        }
        $data = GoodsReceive::when(request('search') == 'document_no' && request('search_data'),function($q){
                            $q->where('document_no',request('search_data'));
        })
                            ->when(request('search') != 'document_no' && request('search_data'),function($q) use($ids){
                                $q->whereIn('id',$ids);
                            })
                            ->when(request('branch'),function($q){
                                $q->where('branch_id',request('branch'));
                            })
                            ->when(request('status'),function($q){
                                $q->where('status',request('status'));
                            })
                            ->when(request('from_date'),function($q){
                                $q->where('start_date','>=',request('from_date'));
                            })
                            ->when(request('to_date'),function($q){
                                $q->where('start_date','<=',request('to_date'));
                            })
                            ->when($loc == 'dc',function($q) use($mgld_dc){
                                $q->whereIn('branch_id',$mgld_dc);

                            })
                            ->when($loc == 'other',function($q) use($user_branch){
                                $q->where('branch_id',$user_branch);
                            })
                            ->whereNotNull('status')
                            ->orderBy('created_at','desc')
                            ->paginate(15);
        $branch = Branch::get();
        view()->share(['branch'=>$branch]);
        return view('user.list',compact('data'));
    }

    public function view_goods($id)
    {
        // dd($id);
        Common::Log(route('view_goods',['id'=>$id]),"View REG Page");
        $main = GoodsReceive::where('id',$id)->first();
        $truck = Truck::get();
        $driver = DriverInfo::where('received_goods_id',$id)->get();
        $cur_driver = DriverInfo::where('received_goods_id',$id)->whereNull('duration')->first();
        $driver_last = DriverInfo::where('received_goods_id', $id)->orderBy('id', 'desc')->first();
        $document = Document::where('received_goods_id',$id)->orderBy('id')->get();
        $scan_document = Document::where('received_goods_id',$id)->orderBy('updated_at','desc')->get();
        $scan_document_no = Document::where('received_goods_id', $id)->pluck('document_no');
        $reason         = PrintReason::get();
        $status = 'view';
        $page = 'view';

        $document_id = Document::where('received_goods_id',$id)->pluck('id');
        $product_barcode = Product::whereIn('document_id',$document_id)
                            ->WhereNull('not_scan_remark')
                            ->pluck('bar_code')->toArray();

        return view('user.receive_goods.receive_goods',compact('main','document','driver','cur_driver','truck','status','scan_document','reason','id','scan_document_no','page','driver_last','product_barcode'));
    }

    public function car_info()
    {

        //$ip_address = get_client_ip();
        //$ip_address = request()->ip();
        $id = getAuth()->id;
        $data = get_branch_truck();
        $truck_id   = $data[0];
        $loc        = $data[1];
        $reg        = $data[2];
        $mgld_dc    = [17,19,20];
        $user_branch    = getAuth()->branch_id;
        $user_branch_code    = getAuth()->branch->branch_code;


        $data = DriverInfo::select('driver_infos.*', 'goods_receives.user_id')
                        ->leftJoin('goods_receives', 'driver_infos.received_goods_id', 'goods_receives.id')
                        ->where('driver_infos.user_id', getAuth()->id)
                        ->whereNull('goods_receives.deleted_at')
                        ->whereNull('driver_infos.duration')
                        ->first();

        $data = DriverInfo::select('driver_infos.*', 'goods_receives.user_id')
                        ->leftJoin('goods_receives', 'driver_infos.received_goods_id', 'goods_receives.id')
                        ->where('driver_infos.user_id', getAuth()->id)
                        ->whereNull('goods_receives.deleted_at')
                        ->where(function ($query) {
                            $query->whereNull('driver_infos.duration')
                            // ->orWhereNull('driver_infos.car_scanning')
                                ->orWhere('driver_infos.car_scanning',1);
                        })
                        ->first();



        $emp = GoodsReceive::where('user_id',getAuth()->id)
                            ->whereNull('deleted_at')
                            ->whereNull('total_duration')
                            ->first();


        $type = Truck::get();
        $gate   = CarGate::when($loc == 'dc',function($q) {
                        $q->whereIn('branch',['MM-505','MM-510','MM-511']);
                        })
                        ->when($loc == 'other',function($q) use($user_branch_code){
                            $q->where('branch',$user_branch_code);
                        })->get();

        if($data || $emp){
            $log            = new Log();
            $log->user_id   = getAuth()->id;
            $log->history   = route('receive_goods',['id' => $data->received_goods_id ?? $emp->id]);
            $log->action    = 'Go To Receive Goods Page';
            $log->ip_address = request()->ip();
            $log->save();

            view()->share(['truck'=>$type,'gate'=>$gate]);
            return redirect()->route('receive_goods', ['id' => $data->received_goods_id ?? $emp->id]);
        }else{

            $log            = new Log();
            $log->user_id   = getAuth()->id;
            $log->history   = route('car_info');
            $log->action    = 'Go To Add Car Info Page';
            $log->ip_address = request()->ip();
            $log->save();

            $source = Source::when($loc == 'other',function($q){
                            $q->where('name','Local Supplier');
            })
                            ->get();
            $branch = Branch::when($loc == 'dc',function($q) use($mgld_dc){
                            $q->whereIn('id',$mgld_dc);
            })
                            ->when($loc == 'other',function($q) use($user_branch){
                                $q->where('id',$user_branch);
                })
                            ->get();
            view()->share(['truck'=>$type,'source'=>$source,'gate'=>$gate,'branch'=>$branch]);
            return view('user.receive_goods.driver_info');
        }
    }

    public function receive_goods($id)
    {
        // dd('yes');
       

        $data = get_branch_truck();
        $truck_id   = $data[0];
        $loc        = $data[1];
        $reg        = $data[2];
        $user_branch    = getAuth()->branch_id;
        $user_branch_code    = getAuth()->branch->branch_code;
        $main = GoodsReceive::where('id',$id)->first();
        $truck = Truck::get();
        $driver = DriverInfo::where('received_goods_id',$id)->get();
        $cur_driver = DriverInfo::where(['received_goods_id'=>$id,'user_id'=>getAuth()->id])->whereNull('duration')->first();
        $driver_last = DriverInfo::where('received_goods_id', $id)->orderBy('id', 'desc')->first();

        if ($cur_driver) {
            $cur_driver->update([
                'car_scanning' => 1,
            ]);
        } elseif ($driver_last) {
            $driver_last->update([
                'car_scanning' => 1,
            ]);
        }
        

        $document = Document::where('received_goods_id',$id)->orderBy('id')->get();
        $scan_document = Document::where('received_goods_id',$id)->orderBy('updated_at','desc')->get();
        $scan_document_no = Document::where('received_goods_id', $id)->pluck('document_no');
        $gate   = CarGate::when($loc == 'dc',function($q) {
                        $q->whereIn('branch',['MM-505','MM-510','MM-511']);
                        })
                        ->when($loc == 'other',function($q) use($user_branch_code){
                            $q->where('branch',$user_branch_code);
                        })->get();
        $reason     = PrintReason::get();
        $page = 'receive';

        $document_id = Document::where('received_goods_id',$id)->pluck('id');
        $product_barcode = Product::whereIn('document_id',$document_id)
                            ->WhereNull('not_scan_remark')
                            ->pluck('bar_code')->toArray();

        view()->share(['status'=>'scan','reason'=>$reason]);
        return view('user.receive_goods.receive_goods',compact('main','document','driver','cur_driver','truck','gate','scan_document','id','scan_document_no', 'page','driver_last','product_barcode'));
    }

    public function join_receive($id,$car)
    {

        $log            = new Log();
        $log->user_id   = getAuth()->id;
        $log->history   = route('join_receive',['id'=>$id,'car'=>$car]);
        $log->action    = "Join To Other's Receive Goods Page";
        // $log->save();

        $main = GoodsReceive::where('id',$id)->first();
        $truck = Truck::get();
        $driver = DriverInfo::where('received_goods_id',$id)->get();
        $cur_driver = DriverInfo::where('id',$car)->first();
        $document = Document::where('received_goods_id',$id)->orderBy('id')->get();
        $scan_document = Document::where('received_goods_id',$id)->orderBy('updated_at','desc')->get();
        $reason        = PrintReason::get();
        $status = 'join';
        return redirect()->route('receive_goods', ['id' => $id]);
        // ->with([
        //     'main' => $main,
        //     'truck' => $truck,
        //     'driver' => $driver,
        //     'cur_driver' => $cur_driver,
        //     'document' => $document,
        //     'scan_document' => $scan_document,
        //     'reason' => $reason,
        //     'status' => $status,
        // ]);
    }


    // public function store_car_info(Request $request)
    // {
    //     dd($request);
    // }

    public function store_car_info(Request $request)
    {

        Common::Log(route('store_car_info'),"Store Car Infomation");
        $status = 'scan';
        $driver = DriverInfo::where('received_goods_id',$request->main_id)->get();

        if(dc_staff())
        {
            $validator = Validator::make($request->all(),[
                'driver_name'       => 'required',
                'driver_phone'      => 'required|numeric',
                'driver_nrc'        => 'required',
                'truck_no'          => 'required',
                'truck_type'        => 'required',
                'gate'              => 'required'
            ]);

            $validator->after(function ($validator) use($request) {
                if ($request->image_1 == null && $request->image_2 == null && $request->image_3 == null) {
                    $validator->errors()->add(
                        'atLeastOne', 'Please Fill Atleast One Image'
                    );
                }
            });

            if ($validator->fails()) {
                return back()->withErrors($validator)
                            ->withInput();
            }

        }else{
            $request->validate([
                'driver_name'       => 'required',
                'truck_no'          => 'required',
                'truck_type'        => 'required',
                'gate'              => 'required'
            ]);
        }

        if(count($driver) > 0){
            $driver = new DriverInfo();
            $driver->ph_no              = $request->driver_phone ?? null;
            $driver->type_truck         = $request->truck_type;
            $driver->received_goods_id  = $request->main_id;
            $driver->driver_name        = $request->driver_name;
            $driver->truck_no           = $request->truck_no;
            $driver->nrc_no             = $request->driver_nrc ?? null;
            $driver->start_date         = Carbon::now()->format('Y-m-d');
            $driver->start_time         = Carbon::now()->format('H:i:s');
            $driver->user_id            = getAuth()->id;
            $driver->gate               = $request->gate ?? 0;
            $driver->save();


        }else{

            // $branch_id = getAuth()->branch->id;

            $main               = GoodsReceive::find($request->main_id);
            $main->start_date   = Carbon::now()->format('Y-m-d');
            $main->start_time   = Carbon::now()->format('H:i:s');
            $main->status       = 'incomplete';
            $main->save();


                $driver = new DriverInfo();
                $driver->ph_no              = $request->driver_phone;
                $driver->type_truck         = $request->truck_type;
                $driver->received_goods_id  = $main->id;
                $driver->driver_name        = $request->driver_name;
                $driver->truck_no           = $request->truck_no;
                $driver->nrc_no             = $request->driver_nrc;
                $driver->start_date         = Carbon::now()->format('Y-m-d');
                $driver->start_time         = Carbon::now()->format('H:i:s');
                $driver->user_id            = getAuth()->id;
                $driver->gate               = 0;

                $driver->save();
        }

            if($request->only('image_1','image_2','image_3') != [])
            {
                $main_doc = GoodsReceive::where('id',$request->main_id)->first();
                foreach($request->only('image_1','image_2','image_3') as $item)

                $document_no    = $main_doc->document_no;
                $name           = $item->getClientOriginalName();
                $file_name      = $document_no.'_'.$name;
                Storage::disk('ftp')->put($file_name, fopen($item, 'r+'));
                UploadImage::create([
                    'name'      => $name,
                    'file'      => $file_name,
                    'received_goods_id' => $main_doc->id,
                    'driver_info_id'    => $driver->id,
                    'public'            => 0
                ]);
            }

        view()->share(['status'=>$status]);
        $history = CarHistory::where(['car_no'=>$request->truck_no,'car_type'=>$request->truck_type,'driver_name'=>$request->driver_name])->first();
        if(!$history)
        {
            CarHistory::create([
                'car_no'        => $request->truck_no,
                'car_type'      => $request->truck_type,
                'driver_name'   => $request->driver_name
            ]);
        }
        return redirect()->route('receive_goods',$request->main_id);

    }

    public function store_doc_info(Request $request)
    {

        Common::Log(route('store_doc_info'),"Store Infomation and Generate REG");
        if(dc_staff())
        {
            $data = $request->validate([
                'source'            => 'required',
                'branch'            => 'required',
            ]);
            $branch = Branch::where('id',$request->branch)->first();
            $shr  = 'REG'.$branch->branch_short_name.str_replace('-', '', Carbon::now()->format('Y-m-d'));
        }else{
            if($request->no_car == 0)
            {
                $validator = Validator::make($request->all(),[
                    'truck_no'      => 'required',
                    'driver_name'   => 'required',
                    'gate'          => 'required',
                ]);
            }else{
                $validator = Validator::make($request->all(),[
                    'driver_name'   => 'required',
                    'gate'          => 'required',
                ]);
            }


            // $validator->after(function ($validator) use($request) {
            //     if ($request->image_1 == null && $request->image_2 == null && $request->image_3 == null) {
            //         $validator->errors()->add(
            //             'atLeastOne', 'Please Fill Atleast One Image'
            //         );
            //     }
            // });
            if ($validator->fails()) {
                return back()->withErrors($validator)
                            ->withInput();
            }
            $shr  = 'REG'.getAuth()->branch->branch_short_name.str_replace('-', '', Carbon::now()->format('Y-m-d'));
        }


        $same = GoodsReceive::whereDate('created_at',Carbon::now()->format('Y-m-d'))->where('branch_id',getAuth()->branch_id)->withTrashed()->get();
        $same = count($same);
        if($same > 0){
            $name = $shr.'-'.sprintf("%04d",$same+1);
        }else{
            $name = $shr.'-'.sprintf("%04d",1);
        }

        $main               = new GoodsReceive();
        $main->document_no  = $name;
        $main->branch_id    =$request->branch ?? getAuth()->branch_id;
        $main->user_id      = getAuth()->id;
        if(dc_staff())
        {
            $main->source       = $request->source;

            $main->save();
        }else{

            $main->status       = 'incomplete';
            $main->source       = 1;


            if($request->action == 'count')
            {

                $main->start_date   =Carbon::now()->format('Y-m-d');
                $main->start_time   =Carbon::now()->format('H:i:s');
                $main->save();

                $driver                     = new DriverInfo();
                $driver->received_goods_id  = $main->id;
                $driver->type_truck         = $request->truck_type ?? null;
                $driver->driver_name        = $request->driver_name;
                $driver->truck_no           = $request->truck_no;
                $driver->user_id            = getAuth()->id;
                $driver->gate               = $request->gate;
                $driver->start_date         = Carbon::now()->format('Y-m-d');
                $driver->start_time         = Carbon::now()->format('H:i:s');
                $driver->save();
            }
            elseif($request->action == 'no_count')
            {
                $main->save();

                $driver                     = new DriverInfo();
                $driver->received_goods_id  = $main->id;
                $driver->type_truck         = $request->truck_type ?? null;
                $driver->driver_name        = $request->driver_name;
                $driver->truck_no           = $request->truck_no;
                $driver->user_id            = getAuth()->id;
                $driver->gate               = $request->gate;
                $driver->save();

            }

            if($request->only('image_1','image_2','image_3') != [])
            {
                foreach($request->only('image_1','image_2','image_3') as $item)
                {
                    $document_no    = $main->document_no;
                    $name           = $item->getClientOriginalName();
                    $file_name      = $document_no.'_'.$name;
                    Storage::disk('ftp')->put($file_name, fopen($item, 'r+'));
                    UploadImage::create([
                        'name'      => $name,
                        'file'      => $file_name,
                        'received_goods_id' => $main->id,
                        'driver_info_id'    => $driver->id,
                        'public'            => 0
                    ]);
                }
            }
        }

        return redirect()->route('receive_goods',$main->id);
    }

    public function add_product_qty(Request $request)
    {
        // dd($request->all());
        $product = Product::where('id',$request->id)->first();
        $driver_info = DriverInfo::where(['received_goods_id'=>$product->doc->received->id , 'user_id'=>getAuth()->id])
                        ->whereNull('duration')
                        ->first();
        $track_dub = Tracking::where(['driver_info_id'=>$driver_info->id,'product_id'=>$product->id])->first();

        $scanned_qty = $product->scanned_qty+$request->qty;
        $product->update([
            'scanned_qty'   => $scanned_qty
        ]);
        $track_scan = $track_dub->scanned_qty;
        $track_dub->update([
            'scanned_qty'   => $track_scan+$request->qty
        ]);
        return response()->json(200);
    }

    // public  function edit_goods($id)
    // {
    //     $user_id =getAuth()->id;
    //     $data = GoodsReceive::where('id',$id)->first();

    //     return response()->json(200);
    // }

    public function car($id)
    {

        Common::Log(route('car',['id'=>$id]),"Store Car Infomation");
        $data = get_branch_truck();
        $truck_id   = $data[0];
        $loc        = $data[1];
        $reg        = $data[2];
        $user_branch    = getAuth()->branch_id;
        $user_branch_code    = getAuth()->branch->branch_code;

        $main   = GoodsReceive::where('id',$id)->first();
        $type = Truck::get();
        $source = Source::get();
        $gate   = CarGate::when($loc == 'dc',function($q) {
            $q->whereIn('branch',['MM-505','MM-510','MM-511']);
            })
            ->when($loc == 'other',function($q) use($user_branch_code){
                $q->where('branch',$user_branch_code);
            })->get();
        $branch   = Branch::get();
        view()->share(['truck'=>$type,'source'=>$source,'gate'=>$gate,'branch'=>$branch]);
        return view('user.receive_goods.driver_info',compact('main'));
        // dd($driver);
    }

    public function del_doc(Request $request)
    {
        $doc = Document::where(['document_no'=>$request->data , 'received_goods_id' => $request->id])->first();
        $count_doc = Document::where('received_goods_id',$request->id)->get();
        $count_doc = count($count_doc);
        $product = Product::where('document_id',$doc->id)->pluck('scanned_qty')->toArray();
        $zero = true;
        foreach($product as $item)
        {
            if($item > 0)
            {
                $zero = false;
                break;
            }
        }
        if($zero)
        {
            Product::where('document_id',$doc->id)->delete();
            if($count_doc == 1)
            {
                $reg = GoodsReceive::find($request->id);
                $reg->update(['vendor_name' => null]);
            }
            $doc->delete();
            return response()->json(['count'=>$count_doc],200);
        }else{
            return response()->json(['message'=>"You Cannot Remove"],404);
        }

        // $doc->delete();

    }

    public function driver_info($id)
    {
        $data = DriverInfo::where('id',$id)->first();
        return response()->json($data,200);
    }

    public function search_car(Request $request)
    {

        $search = $request->data;
        $data = CarHistory::select('car_no')
                            ->where('car_no','like',"%$search%")
                            ->distinct()
                            ->get();
        return response()->json($data,200);
    }

    public function get_car(Request $request)
    {
        $data = CarHistory::where('car_no',$request->data)
                            ->latest()
                            ->first();
        return response()->json($data,200);
    }

    public function search_document_no(Request $request)
    {
        $id = $request->input('id');
        $input_document_no = $request->input('document_no');
        $input_barcode_no = $request->input('barcode_no');
        $isDcStaff = dc_staff();

        $curDriverFirst = DriverInfo::where('received_goods_id',$id)->whereNull('duration')->first();
        if($curDriverFirst) {
            $curDriver = DriverInfo::where('received_goods_id',$id)->whereNull('duration')->first();
            $cur_driver_start_date = $curDriver->start_date;
        } else {
            $curDriver = [];
            $cur_driver_start_date = [];
        }

        $driver_last_first = DriverInfo::where('received_goods_id', $id)->orderBy('id', 'desc')->first();
        if($curDriverFirst) {
            $driver_last = DriverInfo::where('received_goods_id', $id)->orderBy('id', 'desc')->first();
        } else {
            $driver_last = [];
        }

        $document_id = Document::where('received_goods_id',$request->id)->pluck('id');
        $product_barcode = Product::whereIn('document_id',$document_id)
        ->WhereNull('not_scan_remark')
        ->pluck('bar_code')->toArray();

        $authId = getAuth()->id;
        $response = [];
        $scan_response = [];
        $excess_response = [];
        $merged_need_document_inform = [];
        if (($input_document_no && $input_barcode_no) || ($input_document_no && !$input_barcode_no) || (!$input_document_no && $input_barcode_no)) {
            if ($input_document_no) {
                $query = Document::where('received_goods_id', $id)->where('document_no', $input_document_no);
            } elseif ($input_barcode_no) {
                $documentIds = Product::where('bar_code', $input_barcode_no)->pluck('document_id');
                if ($documentIds->isEmpty()) {
                    return response()->json(['documents' => $response, 'scan_documents' => $scan_response, 'excess_documents' => $excess_response, 'need_document_inform' => $merged_need_document_inform]);
                }
                $query = Document::whereIn('id', $documentIds)->where('received_goods_id', $id);
            } else {
                return response()->json(['documents' => $response, 'scan_documents' => $scan_response, 'excess_documents' => $excess_response, 'need_document_inform' => $merged_need_document_inform]);
            }

            $documents = $query->orderBy('id')->get();
            if ($documents->isEmpty()) {
                return response()->json(['documents' => $response, 'scan_documents' => $scan_response, 'excess_documents' => $excess_response, 'need_document_inform' => $merged_need_document_inform]);
            }
            $d = new DNS1D();
            foreach ($documents as $doc) {
                $document_id = $doc->id;
                $search_pd = collect(search_pd($document_id));
                $search_scaned_pd = collect(search_scanned_pd($document_id));
                $search_excess_pd = collect(search_excess_pd($document_id));
                if ($search_pd->isNotEmpty()) {
                    $barcode_id = [];
                    $bar_codes = [];
                    $supplier_names = [];
                    $qtys = [];
                    $scanned_qtys = [];
                    $color = [];
                    $search_pd_id = [];
                    $unit = [];
                    $barcode_htmls = [];

                    foreach ($search_pd as $pd_data) {
                        if($input_barcode_no) {
                            if ($pd_data->bar_code == $input_barcode_no) {
                                $barcode_id[] = $pd_data->id;
                                $bar_codes[] = $pd_data->bar_code;
                                $supplier_names[] = $pd_data->supplier_name;
                                $qtys[] = $pd_data->qty;
                                $scanned_qtys[] = $pd_data->scanned_qty;
                                $color[] = check_color($pd_data->id);
                                $search_pd_id[] = $pd_data->id;
                                $unit[] = $pd_data->unit;

                                $barcode_htmls[] = [
                                    'bar_stick1' => $d->getBarcodeHTML($pd_data->bar_code ?? '1', 'C128', 2, 50),
                                    'bar_stick2' => $d->getBarcodeHTML($pd_data->bar_code ?? '1', 'C128', 2, 22),
                                    'bar_stick3' => $d->getBarcodeHTML($pd_data->bar_code ?? '1', 'C128', 2, 50)
                                ];
                            }
                        } else {
                            $scan_id[] = $pd_data->id;
                            $bar_codes[] = $pd_data->bar_code;
                            $supplier_names[] = $pd_data->supplier_name;
                            $qtys[] = $pd_data->qty;
                            $scanned_qtys[] = $pd_data->scanned_qty;
                            $color[] = check_color($pd_data->id);
                            $search_pd_id[] = $pd_data->id;
                            $unit[] = $pd_data->unit;

                            $barcode_htmls[] = [
                                'bar_stick1' => $d->getBarcodeHTML($pd_data->bar_code ?? '1', 'C128', 2, 50),
                                'bar_stick2' => $d->getBarcodeHTML($pd_data->bar_code ?? '1', 'C128', 2, 22),
                                'bar_stick3' => $d->getBarcodeHTML($pd_data->bar_code ?? '1', 'C128', 2, 50)
                            ];
                        }

                    }

                    $merged_data = [
                        'id' => $doc->id,
                        'document_no' => $doc->document_no,
                        'received_goods_id' => $doc->received_goods_id,
                        'remark' => $doc->remark,
                        'document_id' => $document_id,
                        'bar_code' => $bar_codes,
                        'supplier_name' => $supplier_names,
                        'qty' => $qtys,
                        'scanned_qty' => $scanned_qtys,
                        'check_color' => $color,
                        'scan_zero' => scan_zero($document_id),
                        'search_pd_id' => $search_pd_id,
                        'unit' => $unit,
                        'barcode_htmls' => $barcode_htmls,
                        'barcode_id' =>  $barcode_id 
                    ];

                    $response[] = $merged_data;
                }
                if($search_scaned_pd->isNotEmpty()) {
                    $scan_id = [];
                    $scan_bar_codes = [];
                    $scan_supplier_names = [];
                    $scan_qtys = [];
                    $scan_scanned_qtys = [];
                    $scan_colors = [];
                    $scann_count = [];
                    $scann_pause = [];
                    $barcode_equal = [];

                    foreach ($search_scaned_pd as $scan_pd_data) {
                        if($input_barcode_no) {
                            if($scan_pd_data->bar_code == $input_barcode_no) {
                                $scan_id[] = $scan_pd_data->id;
                                $scan_bar_codes[] = $scan_pd_data->bar_code;
                                $scan_supplier_names[] = $scan_pd_data->supplier_name;
                                $scan_qtys[] = $scan_pd_data->qty;
                                $scan_scanned_qtys[] = $scan_pd_data->scanned_qty;
                                $scan_colors[] = check_scanned_color($scan_pd_data->id);
                                $scann_count[] = $scan_pd_data->scann_count;
                                $scann_pause[] = $scan_pd_data->scann_pause;
                                $barcode_equal[] = barcode_equal($product_barcode,$scan_pd_data->bar_code);
                            }
                        } else {
                            $scan_id[] = $scan_pd_data->id;
                            $scan_bar_codes[] = $scan_pd_data->bar_code;
                            $scan_supplier_names[] = $scan_pd_data->supplier_name;
                            $scan_qtys[] = $scan_pd_data->qty;
                            $scan_scanned_qtys[] = $scan_pd_data->scanned_qty;
                            $scan_colors[] = check_scanned_color($scan_pd_data->id);
                            $scann_count[] = $scan_pd_data->scann_count;
                            $scann_pause[] = $scan_pd_data->scann_pause;
                            $barcode_equal[] = barcode_equal($product_barcode,$scan_pd_data->bar_code);
                        }
                    }

                    $scan_merged_data = [
                        'id' => $doc->id,
                        'document_no' => $doc->document_no,
                        'received_goods_id' => $doc->received_goods_id,
                        'remark' => $doc->remark,
                        'document_id' => $document_id,
                        'bar_code' => $scan_bar_codes,
                        'supplier_name' => $scan_supplier_names,
                        'qty' => $scan_qtys,
                        'scanned_qty' => $scan_scanned_qtys,
                        'scan_color' => $scan_colors,
                        'all_scanned' => check_all_scan($document_id),
                        'scann_count' => $scann_count,
                        'scan_id' => $scan_id,
                        'scann_pause' => $scann_pause,
                        'barcode_equal' => $barcode_equal
                    ];
                    $scan_response[] = $scan_merged_data;
                }
                if($search_excess_pd->isNotEmpty()) {
                    $excess_id = [];
                    $excess_bar_codes = [];
                    $excess_supplier_names = [];
                    $excess_qtys = [];
                    $excess_scanned_qtys = [];

                    foreach($search_excess_pd as $excess_pd_data) {
                        if($input_barcode_no) {
                            if($excess_pd_data->bar_code == $input_barcode_no) {
                                $excess_id = $excess_pd_data->id;
                                $excess_bar_codes[] = $excess_pd_data->bar_code;
                                $excess_supplier_names[] = $excess_pd_data->supplier_name;
                                $excess_qtys[] = $excess_pd_data->qty;
                                $excess_scanned_qtys[] = $excess_pd_data->scanned_qty;
                            }
                        } else {
                            $excess_id = $excess_pd_data->id;
                            $excess_bar_codes[] = $excess_pd_data->bar_code;
                            $excess_supplier_names[] = $excess_pd_data->supplier_name;
                            $excess_qtys[] = $excess_pd_data->qty;
                            $excess_scanned_qtys[] = $excess_pd_data->scanned_qty;
                        }
                    }
                    $excess_merged_data = [
                        'id' => $doc->id,
                        'document_no' => $doc->document_no,
                        'received_goods_id' => $doc->received_goods_id,
                        'remark' => $doc->remark,
                        'document_id' => $document_id,
                        'bar_code' => $excess_bar_codes,
                        'supplier_name' => $excess_supplier_names,
                        'qty' => $excess_qtys,
                        'scanned_qty' => $excess_scanned_qtys,
                        'excess_id' => $excess_id
                    ];
                    $excess_response[] = $excess_merged_data;
                }
                $merged_need_document_inform = [
                    'isDcStaff' => $isDcStaff,
                    'curDriver' => $curDriver,
                    'authId' => $authId,
                    'cur_driver_start_date' => $cur_driver_start_date,
                    'driver_last' => $driver_last
                ];
            }
            //dd($response, $scan_response, $excess_response);
            return response()->json(['documents' => $response, 'scan_documents' => $scan_response, 'excess_documents' => $excess_response, 'need_document_inform' => $merged_need_document_inform]);
        }else {
            return response()->json(['documents' => $response, 'scan_documents' => $scan_response, 'excess_documents' => $excess_response, 'need_document_inform' => $merged_need_document_inform]);
        }
        return response()->json(['documents' => $response, 'scan_documents' => $scan_response, 'excess_documents' => $excess_response, 'need_document_inform' => $merged_need_document_inform]);

    }

}
