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
use Illuminate\Http\Request;
use App\Models\AddProductTrack;
use App\Models\changeTruckProduct;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Storage;

use function PHPSTORM_META\type;

class AdminController extends Controller
{
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


        view()->share(['status'=>'edit']);
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
}

