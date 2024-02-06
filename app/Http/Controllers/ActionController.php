<?php

namespace App\Http\Controllers;

use App\Models\changeTruckProduct;
use App\Models\Product;
use App\Models\Tracking;
use Illuminate\Http\Request;

class ActionController extends Controller
{
    public function edit_scan(Request $request)
    {
        $track  = Tracking::where(['product_id'=>$request->product,'driver_info_id'=>$request->driver])->get();
        $qty    = $request->val;
        $old    = $request->old;
        $change = $old - $qty;
        // dd($track);
        foreach($track as $item)
        {
            if($change >= $item->scanned_qty)
            {
                $sub_qty    = $item->scanned_qty-1;
                Tracking::where('id',$item->id)->update([
                    'scanned_qty'   => 1
                ]);
                $change = $change - $sub_qty;
            }else{
                $final      = $item->scanned_qty-$change;
                Tracking::where('id',$item->id)->update([
                    'scanned_qty'   => $final
                ]);
                break;
            }
        }
        $pd = Product::where('id',$request->product)->first();
        $product_scan = $pd->scanned_qty - ($old - $qty);
        Product::where('id',$request->product)->update([
            'scanned_qty'   => $product_scan
        ]);

        $track                  = new changeTruckProduct();
        $track->user_id         = getAuth()->id;
        $track->driver_info_id  = $request->driver;
        $track->product_id      = $request->product;
        $track->qty             = $old - $qty;
        $track->save();

        return response()->json(200);
    }
}
