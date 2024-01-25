<?php

use Carbon\Carbon;
use App\Models\Product;
use App\Models\Document;
use App\Models\DriverInfo;
use App\Models\GoodsReceive;
use App\Models\Tracking;
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

    function get_total_qty($id)
    {
        $doc = Document::where('received_goods_id',$id)->pluck('id');
        $total = (int)0;
        foreach($doc as $item)
        {
            $total += Product::where('document_id',$item)->sum('qty');
        }
        return $total;
    }

    function get_all_duration($id)
    {
        $data = GoodsReceive::where('id',$id)->first();
        $finish_driver  = DriverInfo::where('received_goods_id',$id)->whereNotNull('duration')->pluck('duration');
        $pending_driver = DriverInfo::where('received_goods_id',$id)->whereNull('duration')->first();
        // dd($pending_driver);
        $diff = (int)0;
        if($pending_driver)
        {
            $cur_sec = strtotime($pending_driver->start_date.' '.$pending_driver->start_time);
            $now     = Carbon::now()->timestamp;
            $diff = $now - $cur_sec;
        }
        $total_sec =(int)0;
        foreach($finish_driver as $item)
        {
            list($hour, $min, $sec) = explode(':', $item);
            $total_sec    += $hour*3600 + $min*60 + $sec;
        }
        $combine = $total_sec + $diff;
        $hour   = (int)($combine / 3600);
        $min    = (int)(($combine % 3600) / 60);
        $sec    = (int)(($combine % 3600) % 60);
        $sec_pass   = sprintf('%02d:%02d:%02d', $hour, $min, $sec);
        return $sec_pass;
    }

    function get_done_duration($id)
    {
        $data = GoodsReceive::where('id',$id)->first();
        $finish_driver  = DriverInfo::where('received_goods_id',$id)->whereNotNull('duration')->pluck('duration');
        $total_sec =(int)0;
        foreach($finish_driver as $item)
        {
            list($hour, $min, $sec) = explode(':', $item);
            $total_sec    += $hour*3600 + $min*60 + $sec;
        }

        return $total_sec;
    }

    function scan_zero($id)
    {
        $product = Product::where('document_id',$id)->pluck('scanned_qty');
        $zero = true;
        foreach($product as $item)
        {
            if($item > 0)
            {
                $zero = false;
                break;
            }
        }
        return $zero;
    }

    function get_scanned_qty($id)
    {
        $scan_goods =   Tracking::where('driver_info_id',$id)->sum('scanned_qty');
        return $scan_goods;
    }

    function check_empty($id)
    {
        $data = GoodsReceive::where('user_id',getAuth()->id)
                            ->whereNull('total_duration')
                            ->first();
        $empty = false;
        if($data){
            $empty = true;
        }
        return $empty;
    }

    function get_total_truck($id)
    {
        return DriverInfo::where('received_goods_id',$id)->count();
    }


