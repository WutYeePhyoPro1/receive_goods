<?php

use App\Models\GoodsReceive;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

    function getAuth()
    {
        return auth()->guard()->user();
    }

    function search_pd($id)
    {
        return Product::where('document_id',$id)
                        ->where(DB::raw('scanned_qty'), '<', DB::raw('qty'))
                        ->orderBy('id','asc')
                        ->get();
    }

    function search_scanned_pd($id)
    {
        return Product::where('document_id',$id)
                        ->where('scanned_qty','>',0)
                        ->orderBy('id','asc')
                        ->get();
    }

    function search_excess_pd($id)
    {
        return Product::where('document_id',$id)
                        ->where(DB::raw('scanned_qty'), '>', DB::raw('qty'))
                        ->get();
    }

    function check_color($id)
    {
        $product = Product::where('id',$id)->first();
         $msg = "";

         if($product->scanned_qty > 0 && $product->scanned_qty < $product->qty){
            $msg = "bg-amber-200 text-amber-600";
         }

         return $msg;
    }

    function check_scanned_color($id){
        $product = Product::where('id',$id)->first();
        $msg = "";

        if($product->scanned_qty > 0 && $product->scanned_qty < $product->qty){
           $msg = "bg-amber-200 text-amber-600";
        }elseif($product->scanned_qty == $product->qty){
           $msg = "bg-green-200 text-green-600";
        }elseif($product->scanned_qty > $product->qty){
           $msg = "bg-rose-200 text-rose-600";
        }
        return $msg;
    }

    function get_duration($id)
    {
        $data = GoodsReceive::where('id',$id)->first();
        list($hour, $min, $sec) = explode(':', $data->duration);
        $total_sec    = $hour*3600 + $min*60 + $sec;
        $total_sec1 = 0;
        if($data->edit_duration){
            list($hour1, $min1, $sec1) = explode(':', $data->edit_duration);
            $total_sec1    = $hour1*3600 + $min1*60 + $sec1;
        }

        $combine = $total_sec + $total_sec1;
        $hour   = (int)($combine / 3600);
        $min    = (int)(($combine % 3600) / 60);
        $sec    = (int)(($combine % 3600) % 60);
        $sec_pass   = sprintf('%02d:%02d:%02d', $hour, $min, $sec);
        return $sec_pass;
    }
