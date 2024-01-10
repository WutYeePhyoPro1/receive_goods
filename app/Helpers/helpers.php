<?php

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
