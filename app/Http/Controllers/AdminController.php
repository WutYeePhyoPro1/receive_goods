<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Truck;
use App\Models\CarGate;
use App\Models\Product;
use App\Models\Document;
use App\Models\Tracking;
use App\Customize\Common;
use App\Models\ScanTrack;
use App\Models\DriverInfo;
use App\Models\printTrack;
use App\Models\UploadImage;
use App\Models\GoodsReceive;
use App\Models\PrintReason;
use App\Models\Branch;
use App\Models\Department;
use App\Models\UserBranch;
use Illuminate\Http\Request;
use App\Models\AddProductTrack;
use App\Models\changeTruckProduct;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Storage;

use function PHPSTORM_META\type;

class AdminController extends Controller
{

    public function user()
    {
        $search = '';
        if(request('search_data'))
        {
            $search  =Ucwords(request('search_data'));
            $type = trim(substr(request('search_data'), 0, 3));
            $isint = ctype_digit($type);
        }
        $data = User::with('user_branches')
                    ->when(request('branch'),function($q){
                        $q->where('branch_id',request('branch'));
                    })
                    ->when(request('search_data') && $isint,function($q){
                        $q->where('employee_code',request('search_data'));
                    })
                    ->when(request('search_data') && !$isint,function($q) use($search) {
                        $q->where('name', 'like', "%{$search}%");
                    })
                    ->orderBy('id')
                    ->paginate(15);
        // dd($data);
        $branch= Branch::get();
        $type= 'user';
        return view('user.user',compact('data','branch','type'));
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
        $user->branch_id        = $request->branch[0];
        $user->status           = $request->status == 'active' ? 1 : 0;
        $user->role             = $request->role;
        $succ = $user->save();



        if($succ){
            foreach($request->branch as $item)
            {
                $user_br                = new UserBranch();
                $user_br->user_id       = $user->id;
                $user_br->branch_id     = $item;
                $user_br->save();
            }
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
        $active = $request->data == 1 ? 1 : 0;
        $user = User::where('id',$request->id)->update([
            'status'    => $active
        ]);
        if($user)
        {
            return response()->json(200);
        }else{
            return response()->json(['fails'=>'fails'],500);
        }
    }


    public function edit_user($id)
    {
        $data = User::where('id',$id)->first();
        $branch = Branch::get();
        $department = Department::get();
        $role       = Role::whereNot('name','admin')->get();
        $user_branch= UserBranch::where('user_id',$id)->pluck('branch_id');
        view()->share(['branch'=>$branch,'department'=>$department,'role'=>$role]);
        $type       = 'user';
        return view('user.create_edit',compact('data','type','user_branch'));
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
                'branch_id'     => $request->branch[0],
                'status'     => $request->status == 'active' ? 1 : 0,
                'role'     => $request->role
            ]);

            $user_br = UserBranch::where('user_id',$id)->first();
            if($user_br)
            {
                UserBranch::where('user_id',$id)->delete();
            }
            foreach($request->branch as $index=>$item)
            {
                UserBranch::create([
                    'user_id'   => $id,
                    'branch_id' => $item
                ]);
            }

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

    public function role()
    {
        $data = Role::paginate(15);
        $type= 'role';
        return view('user.user',compact('data','type'));
    }


    public  function del_reg(Request $request)
    {

        $reg            = GoodsReceive::find($request->id);
        $driver         = DriverInfo::where('received_goods_id',$request->id)->get();
        $document       = Document::where('received_goods_id',$request->id)->get();
        $document_ids   = $document->pluck('id');
        $product        = Product::whereIn('document_id',$document_ids)->get();
        $product_ids    = $product->pluck('id');
        $track          = Tracking::whereIn('product_id',$product_ids)->get();
    $scan           = ScanTrack::whereIn('product_id',$product_ids)->get();
        $print          = printTrack::whereIn('product_id',$product_ids)->get();
        $add            = AddProductTrack::whereIn('product_id',$product_ids)->get();
        $change         = changeTruckProduct::whereIn('product_id',$product_ids)->get();
        $files          = UploadImage::where('received_goods_id',$request->id)->get();

        if(count($driver) > 0)
        {
            DriverInfo::where('received_goods_id',$request->id)->delete();
        }
        if(count($document) > 0)
        {
            Document::where('received_goods_id',$request->id)->delete();
        }
        if(count($product) > 0)
        {
            Product::whereIn('document_id',$document_ids)->delete();
        }
        if(count($track) > 0)
        {
            Tracking::whereIn('product_id',$product_ids)->delete();
        }
        if(count($scan) > 0)
        {
            ScanTrack::whereIn('product_id',$product_ids)->delete();
        }
        if(count($print) > 0)
        {
            printTrack::whereIn('product_id',$product_ids)->delete();
        }
        if(count($add) > 0)
        {
            AddProductTrack::whereIn('product_id',$product_ids)->delete();
        }
        if(count($change) > 0)
        {
            changeTruckProduct::whereIn('product_id',$product_ids)->delete();
        }
        if(count($files) > 0 )
        {
            foreach($files as $item)
            {
                if(Storage::exists('public/'.$item->file))
                {
                    Storage::delete('public/'.$item->file);
                }
                Storage::disk('ftp')->delete($item->file);
            }
            UploadImage::where('received_goods_id',$request->id)->delete();
        }
        $reg->delete();
        return response(200);
    }

    public function edit_reg($id)
    {
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
        $reason        = PrintReason::get();

        view()->share(['status'=>'edit','reason'=>$reason]);
        // $time_start = Carbon::parse($time_str)->format('H:i:s');
        return view('user.receive_goods.receive_goods',compact('main','document','driver','cur_driver','truck','scan_document'));
    }

    public function get_img($id)
    {
        $driver     = DriverInfo::find($id);
        if($driver)
        {
            $img     = UploadImage::where('driver_info_id',$id)->orderBy('id')->get();
        }
        return response()->json(['image'=>$img,'driver'=>$driver],200);
    }

    public function show_one(Request $request)
    {
        $image = UploadImage::find($request->id);
        $file    = $image->file;
        if(!Storage::exists('public/'.$file))
        {
            $svr_img    = Storage::disk('ftp')->get($file);
            Storage::disk('public')->put($file,$svr_img);
        }
        return response()->json($file,200);
    }

    public function update_image(Request $request)
    {
        $all_image  = UploadImage::where('received_goods_id',$request->reg_id)->orderBy('id')->get();

        if(isset($request->image_1) && $request->image1)
        {

            $img1    = $all_image[0];
            if(Storage::exists('public/'.$img1->file))
            {
                Storage::delete('public/'.$img1->file);
            }
            Storage::disk('ftp')->delete($img1->file);
            UploadImage::find($img1->id)->delete();
        }
        if(isset($request->image_2) && $request->image2)
        {
            $img2    = $all_image[1];
            if(Storage::exists('public/'.$img2->file))
            {
                Storage::delete('public/'.$img2->file);
            }
            Storage::disk('ftp')->delete($img2->file);
            UploadImage::find($img2->id)->delete();
        }
        if(isset($request->image_3) && $request->image3)
        {
            $img3    = $all_image[2];
            if(Storage::exists('public/'.$img3->file))
            {
                Storage::delete('public/'.$img3->file);
            }
            Storage::disk('ftp')->delete($img3->file);
            UploadImage::find($img3->id)->delete();
        }
        $main_doc = GoodsReceive::where('id',$request->reg_id)->first();
        foreach($request->only('image_1','image_2','image_3') as $item)
        {
            $document_no    = $main_doc->document_no;
            $name           = $item->getClientOriginalName();
            $file_name      = $document_no.'_'.$name;
            Storage::disk('ftp')->put($file_name, fopen($item, 'r+'));
            UploadImage::create([
                'name'      => $name,
                'file'      => $file_name,
                'received_goods_id' => $main_doc->id,
                'driver_info_id'    => $request->driver_id,
                'public'            => 0
            ]);
        }
        $driver = DriverInfo::where('id',$request->driver_id
        )->update([
            'truck_no'      => $request->truck_no
        ]);
        return back();
    }

    public function del_one_img($id)
    {
        $image = UploadImage::find($id);

        if($image)
        {
            if(Storage::exists('public/'.$image->file)){
                Storage::delete('public/'.$image->file);
            }
            Storage::disk('ftp')->delete($image->file);
            $image->delete();
        }
    }

    public function gate()
    {
        $type = 'gate';
        $data = CarGate::with('branches')->paginate(15);
        return view('user.user',compact('type','data'));
    }

    public function car_type()
    {
        $type = 'car_type';
        $data = Truck::paginate(15);
        return view('user.user',compact('type','data'));
    }

    public function create_gate()
    {
        $type = 'gate';
        Common::branch_data();
        return view('user.create_edit',compact('type'));
    }

    public function store_gate(Request $request)
    {
        $gate   = new CarGate();
        $gate->name     = $request->gate;
        $gate->branch   = $request->branch;
        $create         = $gate->save();
        if($create)
        {
            return redirect()->route('gate')->with('success','Gate Create Success');
        }
        return redirect()->route('gate')->with('fails','Gate Create Fails');
    }

    public function edit_gate($id)
    {
        Common::branch_data();
        $type = 'gate';
        $data = CarGate::find($id);
        return view('user.create_edit',compact('data','type'));
    }

    public function update_gate(Request $request)
    {
        $gate = CarGate::find($request->id);
        $gate->name = $request->gate;
        $gate->branch = $request->branch;
        $up = $gate->save();
        if($up)
        {
            return redirect()->route('gate')->with('success','Gate Update Success');
        }
        return redirect()->route('gate')->with('fails','Gate Update Fails');

    }

    public function del(Request $request)
    {
        if($request->type == 'user')
        {
            $action = User::where('id',$request->id)->delete();
            if($action){
                return response()->json(200);
            }
        }elseif($request->type == 'role')
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
        }elseif($request->type == 'gate')
        {
            $id = $request->id;
            $gate = CarGate::find($id);

            if($gate)
            {
                $gate->delete();
                return response(200);
            }
            return response(404);
        }elseif($request->type == 'car_type')
        {
            $id = $request->id;
            $car_type   = Truck::find($id);
            if($car_type)
            {
                $car_type->delete();
                return response(200);
            }
            return response(404);
        }
    }

    public function create_car_type()
    {
        $type   = 'car_type';
        return view('user.create_edit',compact('type'));
    }

    public function store_car_type(Request $request)
    {
        $request->validate([
            'car_type'  => 'required'
        ]);
        $car_type = new Truck();
        $car_type ->truck_name = $request->car_type;
        $car_type->save();
        return redirect()->route('car_type')->with('success','Car Type Add Success');
    }

    public function edit_car_type($id)
    {
        $data = Truck::find($id);
        $type = 'car_type';
        return view('user.create_edit',compact('data','type'));
    }

    public function update_car_type(Request $request)
    {
        $id = $request->id;
        $request->validate([
            'car_type'    => "required|unique:trucks,truck_name,$id,id"
        ]);
        $truck  = Truck::find($id);
        $truck->truck_name  = $request->car_type;
        $update = $truck->save();
        if($update)
        {
            return redirect()->route('car_type')->with('success','Car Type Update Success');
        }
        return redirect()->route('car_type')->with('fails','Car Type Update Fails');
    }

    public function permission()
    {
        $data = Permission::paginate(15);
        $type= 'permission';
        return view('user.user',compact('data','type'));
    }
}

