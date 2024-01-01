<?php

namespace App\Http\Controllers;

use App\Models\DriverInfo;
use Carbon\Carbon;
use App\Models\GoodsReceive;
use Illuminate\Http\Request;

class userController extends Controller
{
    public function list()
    {
        return view('user.list');
    }

    public function car_info()
    {
        $id = getAuth()->id;
        $data = GoodsReceive::where('user_id',$id)
                                    ->whereNull('duration')
                                    ->first();


        if($data){
            return redirect()->route('receive_goods', ['id' => $data->id]);
        }else{
            return view('user.receive_goods.driver_info');
        }
    }

    public function receive_goods($id)
    {
        $data = GoodsReceive::where('id',$id)->first();
        $time_str = strtotime(Carbon::now())-strtotime($data->start_date.' '.$data->start_time);
        $hour   = (int)($time_str / 3600);
        $min    = (int)(($time_str % 3600) / 60);
        $sec    = (int)(($time_str % 3600) % 60);
        $pass   = $hour .':'. $min .':'. $sec;
        // $time_start = Carbon::parse($time_str)->format('H:i:s');
        return view('user.receive_goods.receive_goods',compact('data','pass'));
    }

    public function store_car_info(Request $request)
    {
        // dd($request->all());
        // dd(Carbon::now()->format('H:i:s'));
        $data = $request->validate([
            'driver_name'       => 'required',
            'driver_phone'      => 'required|numeric',
            'driver_nrc'        => 'required',
            'truck_no'          => 'required',
            'truck_type'        => 'required'
        ]);

        $main = new GoodsReceive();
        $main->start_date = Carbon::now()->format('Y-m-d');
        $main->start_time = Carbon::now()->format('H:i:s');
        $main->user_id = getAuth()->id;

        $main_data = $main->save();

        if($main_data){
            $driver = new DriverInfo();
            $driver->ph_no              = $request->driver_phone;
            $driver->type_truck         = $request->truck_type;
            $driver->received_goods_id  = $main->id;
            $driver->driver_name        = $request->driver_name;
            $driver->truck_no           = $request->truck_no;
            $driver->nrc_no           = $request->driver_nrc;
            $driver->save();

            return redirect()->route('receive_goods',$main->id);
        }
        return back()->with('fails','Fail To Store Driver Info');
    }
}
