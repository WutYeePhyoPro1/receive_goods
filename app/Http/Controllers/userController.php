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
use Spatie\Permission\Models\Permission;
use App\Interfaces\UserRepositoryInterface;
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
        $this->middleware(PermissionMiddleware::class . ':role-management')->only(['role', 'store_role', 'edit_role', 'update_role', 'del_role']);
        $this->middleware(PermissionMiddleware::class . ':permission-management')->only(['permission', 'store_permission', 'view_permission']);
        $this->middleware(PermissionMiddleware::class . ':barcode-scan')->only(['car_info','join_receive','receive_goods','car']);
    }

    public function list()
    {
        Common::Log(route('list'),"go to List Page");

        $user_branch    = getAuth()->branch_id;
        $mgld_dc        = [17,19,20];
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
        $scan_document = Document::where('received_goods_id',$id)->orderBy('updated_at','desc')->get();
        $status = 'view';

        return view('user.receive_goods.receive_goods',compact('main','document','driver','cur_driver','truck','status','scan_document'));
    }

    public function car_info()
    {

        $id = getAuth()->id;

        $data = get_branch_truck();
        $truck_id   = $data[0];
        $loc        = $data[1];
        $reg        = $data[2];
        $user_branch    = getAuth()->branch_id;
        $mgld_dc        = [17,19,20];


        $data = DriverInfo::select('driver_infos.*', 'goods_receives.user_id')
                        ->leftJoin('goods_receives', 'driver_infos.received_goods_id', 'goods_receives.id')
                        ->where('driver_infos.user_id', getAuth()->id)
                        ->whereNull('driver_infos.duration')
                        ->first();
        $emp = GoodsReceive::where('user_id',getAuth()->id)
                            ->whereNull('total_duration')
                            ->first();
        $type = Truck::get();
        $gate   = CarGate::get();
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


        $main = GoodsReceive::where('id',$id)->first();
        $truck = Truck::get();
        $driver = DriverInfo::where('received_goods_id',$id)->get();
        $cur_driver = DriverInfo::where(['received_goods_id'=>$id,'user_id'=>getAuth()->id])->whereNull('duration')->first();
        $document = Document::where('received_goods_id',$id)->orderBy('id')->get();
        $scan_document = Document::where('received_goods_id',$id)->orderBy('updated_at','desc')->get();
        $gate   = CarGate::get();

        $log            = new Log();
        $log->user_id   = getAuth()->id;
        $log->history   = route('receive_goods',['id' => $id]);
        $log->action    = 'Go To Receive Goods Page';
        $log->save();
        // $time_start = Carbon::parse($time_str)->format('H:i:s');
        return view('user.receive_goods.receive_goods',compact('main','document','driver','cur_driver','truck','gate','scan_document'));
    }

    public function join_receive($id,$car)
    {

        $log            = new Log();
        $log->user_id   = getAuth()->id;
        $log->history   = route('join_receive',['id'=>$id,'car'=>$car]);
        $log->action    = "Join To Other's Receive Goods Page";
        $log->save();

        $main = GoodsReceive::where('id',$id)->first();
        $truck = Truck::get();
        $driver = DriverInfo::where('received_goods_id',$id)->get();
        $cur_driver = DriverInfo::where('id',$car)->first();
        $document = Document::where('received_goods_id',$id)->orderBy('id')->get();
        $scan_document = Document::where('received_goods_id',$id)->orderBy('updated_at','desc')->get();;
        $status = 'join';
        return view('user.receive_goods.receive_goods',compact('main','document','driver','cur_driver','truck','scan_document','status'));
    }

    public function user()
    {
        $search = '';
        if(request('search_data'))
        {
            $search  =request('search_data');
            $type = trim(substr(request('search_data'), 0, 3));
            $isint = ctype_digit($type);
        }
        $data = User::when(request('branch'),function($q){
                        $q->where('branch_id',request('branch'));
                    })
                    ->when(request('search_data') && $isint,function($q){
                        $q->where('employee_code',request('search_data'));
                    })
                    ->when(request('search_data') && !$isint,function($q) use($search) {
                        $q->where('name', 'like', "%{$search}%");
                    })
                    ->paginate(15);
        $branch= Branch::get();
        $type= 'user';
        return view('user.user',compact('data','branch','type'));
    }

    public function role()
    {
        $data = Role::paginate(15);
        $type= 'role';
        return view('user.user',compact('data','type'));
    }

    public function permission()
    {
        $data = Permission::paginate(15);
        $type= 'permission';
        return view('user.user',compact('data','type'));
    }

    public function store_car_info(Request $request)
    {
        Common::Log(route('store_car_info'),"Store Car Infomation");

        $driver = DriverInfo::where('received_goods_id',$request->main_id)->get();
        $user_branch    = getAuth()->branch_id;
        $mgld_dc        = [17,19,20];
        if(in_array($user_branch,$mgld_dc))
        {
            $request->validate([
                'driver_name'       => 'required',
                'driver_phone'      => 'required|numeric',
                'driver_nrc'        => 'required',
                'truck_no'          => 'required',
                'truck_type'        => 'required',
                'gate'              => 'required'
            ]);
        }else{
            $request->validate([
                'driver_name'       => 'required',
                'driver_phone'      => 'required|numeric',
                'driver_nrc'        => 'required',
                'truck_no'          => 'required',
                'truck_type'        => 'required',
            ]);
        }
        if(count($driver) > 0){
            $driver = new DriverInfo();
            $driver->ph_no              = $request->driver_phone;
            $driver->type_truck         = $request->truck_type;
            $driver->received_goods_id  = $request->main_id;
            $driver->driver_name        = $request->driver_name;
            $driver->truck_no           = $request->truck_no;
            $driver->nrc_no             = $request->driver_nrc;
            $driver->start_date         = Carbon::now()->format('Y-m-d');
            $driver->start_time         = Carbon::now()->format('H:i:s');
            $driver->user_id            = getAuth()->id;
            $driver->gate               = 0;
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
        Common::Log(route('store_doc_info'),"Store PO/TO Infomation");

        $data = $request->validate([
            'source'            => 'required',
            'branch'            => 'required',
        ]);

        $same = GoodsReceive::where('start_date',Carbon::now()->format('Y-m-d'))->count();
        $branch = Branch::where('id',$request->branch)->first();
        $shr  = 'REG'.$branch->branch_short_name.str_replace('-', '', Carbon::now()->format('Y-m-d'));
        if($same > 0){
            $name = $shr.'-'.sprintf("%04d",$same+1);
        }else{
            $name = $shr.'-'.sprintf("%04d",1);
        }

        $main               = new GoodsReceive();
        $main->document_no  = $name;
        $main->branch_id    =$request->branch;
        $main->source       = $request->source;
        $main->user_id      = getAuth()->id;
        $main->save();
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

        $main   = GoodsReceive::where('id',$id)->first();
        $type = Truck::get();
        $source = Source::get();
        $gate   = CarGate::get();
        $branch   = Branch::get();
        view()->share(['truck'=>$type,'source'=>$source,'gate'=>$gate,'branch'=>$branch]);
        return view('user.receive_goods.driver_info',compact('main'));
        // dd($driver);
    }

    public function create_user()
    {
        $branch = Branch::get();
        $department = Department::get();
        $role       = Role::whereNot('name','admin')->get();
        view()->share(['branch'=>$branch,'department'=>$department,'role'=>$role]);
        $type       = 'user';
        return view('user.create_edit',compact('type'));
    }

    public function store_user(Request $request)
    {
        $request->validate([
            'name'          => 'required',
            'employee_code' => 'required|unique:users,employee_code',
            'password'      => 'required|confirmed',
            'password_confirmation'       => 'required|same:password',
            'department'    => 'required',
            'branch'        => 'required',
            'status'        => 'required'
        ]);
        // dd('yes');
        $user                   = new User();
        $user->name             = $request->name;
        $user->employee_code    = $request->employee_code;
        $user->password         = Hash::make($request->password);
        $user->password_str     = $request->password;
        $user->department_id    = $request->department;
        $user->branch_id        = $request->branch;
        $user->active           = $request->status == 'active' ? true : false;
        $user->role             = $request->role;
        $succ = $user->save();

        if($succ){
            $role = Role::where('id',$request->role)->first();
            $role = $role->name;
            $user->assignRole($role);
            return redirect()->route('user')->with('success','User Create Success');
        }else{
            return redirect()->route('user')->with('fails','User Create Fails');
        }
    }

    public function active_user(Request $request)
    {
        $active = $request->data == 1 ? true : false;
        $user = User::where('id',$request->id)->update([
            'active'    => $active
        ]);
        if($user)
        {
            return response()->json(200);
        }else{
            return response()->json(['fails'=>'fails'],500);
        }
    }

    public function del_user(Request $request)
    {
        // dd($request->all());
        $action = User::where('id',$request->id)->delete();
        if($action){
            return response()->json(200);
        }
    }

    public function edit_user($id)
    {
        $data = User::where('id',$id)->first();
        $branch = Branch::get();
        $department = Department::get();
        $role       = Role::whereNot('name','admin')->get();
        view()->share(['branch'=>$branch,'department'=>$department,'role'=>$role]);
        $type       = 'user';
        return view('user.create_edit',compact('data','type'));
    }

    public function update_user(Request $request)
    {
        $id = $request->id;
        $request->validate([
            'name'                      => 'required',
            'employee_code'             => "required|unique:users,employee_code,$id,id",
            'password'                  => 'required|confirmed',
            'password_confirmation'     => 'required|same:password',
            'department'                => 'required',
            'branch'                    => 'required',
            'status'                    => 'required',
        ]);

        if(getAuth()->role == 1)
        {
            $user = User::find($id);
            User::where('id',$id)->update([
                'name'          => $request->name,
                'employee_code' => $request->employee_code,
                'password'      => Hash::make($request->password),
                'password_str'  => $request->password,
                'department_id' => $request->department,
                'branch_id'     => $request->branch,
                'active'     => $request->status == 'active' ? true : false,
                'role'     => $request->role
            ]);

            $role = Role::where('id',$request->role)->first();
            $role = $role->name;
            $user->syncRoles([$role]);
            return redirect()->route('user')->with('success','User Update Success');
        }
    }

    public function create_role()
    {
        $permission       = Permission::get();
        view()->share(['permission'=>$permission]);
        $type       = 'role';
        return view('user.create_edit',compact('type'));
    }

    public function store_role(Request $request)
    {
        $request->validate([
            'role'      => 'required|unique:roles,name',
            'permission'=> 'required'
        ]);
        try{
            $permission = $request->permission;
            $permission = Permission::whereIn('id',$permission)->pluck('name')->all();
            $role = Role::create(['name'=>$request->role,'guard_name'=>'web']);
            $role->syncPermissions($permission);
            return redirect()->route('role')->with('success','Role Create Success');
        }catch(\Exception $e)
        {
            logger($e->getMessage());
            return redirect()->route('role')->with('fails','Role Create Fails');
        }
    }

    public function edit_role($id)
    {
        $permission     = Permission::get();
        $data           = Role::find($id);
        $type           = 'role';
        view()->share(['permission'=>$permission]);
        return view('user.create_edit',compact('type','data'));
    }

    public function update_role(Request $request)
    {
        $id     = $request->id;
        $request->validate([
            'role'      => "required|unique:roles,name,$id,id",
            'permission'=> 'required'
        ]);
        try{
            $role = Role::find($id);
            $role->update(['name'=>$request->role]);
            $permission = $request->permission;
            $permission = Permission::whereIn('id',$permission)->pluck('name')->all();
            $role->syncPermissions($permission);
            return redirect()->route('role')->with('success','Role Edit Success');
        }catch(\Exception $e)
        {
            logger($e->getMessage());
            return redirect()->route('role')->with('fails','Role Edit Fails');
        }
    }

    public function del_role(Request $request)
    {
        $id = $request->id;
        $role = Role::find($id);
        if($role)
        {
            $role->permissions()->detach();
            $role->delete();
            return response(200);
        }
        return response(404);
    }

    public function create_permission()
    {
        $type       = 'permission';
        return view('user.create_edit',compact('type'));
    }

    public function store_permission(Request $request)
    {
        $max = Permission::max('permission_id');
        $per = Permission::create([
            'permission_id' => $max+1,
            'name'          => $request->permission,
            'guard_name'    => 'web'
        ]);
        if($per)
        {
            return redirect()->route('permission')->with('success','Permission Create Success');
        }else{
            return redirect()->route('permission')->with('fails','Permission Create Fails');
        }
    }

    public function view_permission($id)
    {
        dd($id);
    }

    public function del_doc(Request $request)
    {
        $doc = Document::where(['document_no'=>$request->data , 'received_goods_id' => $request->id])->first();
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
            $doc->delete();
            return response()->json(200);
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
