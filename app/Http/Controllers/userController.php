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
        Common::Log(route('view_goods',['id'=>$id]),"View REG Page");

        $main = GoodsReceive::where('id',$id)->first();
        $truck = Truck::get();
        $driver = DriverInfo::where('received_goods_id',$id)->get();
        $cur_driver = DriverInfo::where('received_goods_id',$id)->whereNull('duration')->first();
        $document = Document::where('received_goods_id',$id)->orderBy('id')->get();
        // dd($id);
        $scan_document = Document::where('received_goods_id',$id)->orderBy('updated_at','desc')->get();
        $reason         = PrintReason::get();
        $status = 'view';

        return view('user.receive_goods.receive_goods',compact('main','document','driver','cur_driver','truck','status','scan_document','reason'));
    }

    public function car_info()
    {
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
                        ->whereNull('driver_infos.duration')
                        ->first();
        $emp = GoodsReceive::where('user_id',getAuth()->id)
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
            $log->save();

            view()->share(['truck'=>$type,'gate'=>$gate]);
            return redirect()->route('receive_goods', ['id' => $data->received_goods_id ?? $emp->id]);
        }else{

            $log            = new Log();
            $log->user_id   = getAuth()->id;
            $log->history   = route('car_info');
            $log->action    = 'Go To Add Car Info Page';
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
        $document = Document::where('received_goods_id',$id)->orderBy('id')->get();
        $scan_document = Document::where('received_goods_id',$id)->orderBy('updated_at','desc')->get();
        // dd($scan_document);
        $gate   = CarGate::when($loc == 'dc',function($q) {
                        $q->whereIn('branch',['MM-505','MM-510','MM-511']);
                        })
                        ->when($loc == 'other',function($q) use($user_branch_code){
                            $q->where('branch',$user_branch_code);
                        })->get();

        $reason     = PrintReason::get();
        view()->share(['status'=>'scan','reason'=>$reason]);
        // $time_start = Carbon::parse($time_str)->format('H:i:s');
        return view('user.receive_goods.receive_goods',compact('main','document','driver','cur_driver','truck','gate','scan_document'));
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
        $scan_document = Document::where('received_goods_id',$id)->orderBy('updated_at','desc')->get();;
        $reason        = PrintReason::get();
        $status = 'join';
        return view('user.receive_goods.receive_goods',compact('main','document','driver','cur_driver','truck','scan_document','status','reason'));
    }


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
}
