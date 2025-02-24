<?php

use Carbon\Carbon;
use App\Models\Branch;
use App\Models\CarGate;
use App\Models\Product;
use App\Models\Document;
use App\Models\Tracking;
use App\Models\ScanTrack;
use App\Models\DriverInfo;
use App\Models\UserBranch;
use App\Models\RemoveTrack;
use App\Models\UploadImage;
use App\Models\GoodsReceive;
use Illuminate\Support\Facades\DB;

    function getAuth()
    {
        return auth()->guard()->user();
    }

    function get_all_pd($id)
    {
        $doc = Document::where('id',$id)->first();
        $main = GoodsReceive::where('id',$doc->received_goods_id)->first();

        return Product::where('document_id',$id)
                        ->orderBy('id','asc')
                        ->get();
    }

    function search_pd($id)
    {
        $doc = Document::where('id',$id)->first();
        $main = GoodsReceive::where('id',$doc->received_goods_id)->first();
        if($main->status != 'complete')
        {
            return Product::where('document_id',$id)
                            ->where(DB::raw('scanned_qty'), '<', DB::raw('qty'))
                            ->orderBy('id','asc')
                            ->WhereNull('not_scan_remark') 
                            ->get();
        }
        return [];
    }

    function search_pd_barcode($id)
    {
        $doc = Document::find($id);
        if (!$doc) {
            return [];
        }

        $main = GoodsReceive::find($doc->received_goods_id);
        // if ($main && $main->status != 'complete') {
            return Product::where('document_id', $id)
                // ->whereColumn('scanned_qty', '<', 'qty')
                ->orderBy('id', 'asc')
                ->pluck('bar_code')
                ->toArray();
        // }
        return [];
    }

    function search_scanned_pd($id)
    {
        return Product::where('document_id',$id)
                        ->where('scanned_qty','>',0)
                        ->orderBy('updated_at','desc')
                        ->get();
    }

    function get_latest_scan_pd($id)
    {
        $doc_ids    = Document::where('received_goods_id',$id)->pluck('id');
        $pd_id      = Product::whereIn('document_id',$doc_ids)->orderBy('updated_at','desc')->first();
        return $pd_id->id;
    }

    function search_excess_pd($id)
    {
        $doc = Document::where('id', $id)->first();
        $main = GoodsReceive::where('id', $doc->received_goods_id)->first();

        if ($main->status == 'complete') {
            return Product::where('document_id', $id)
                            ->where(function($q) {
                                $q->where(DB::raw('scanned_qty'), '>', DB::raw('qty'))
                                  ->orWhere(DB::raw('scanned_qty'), '<', DB::raw('qty'));
                            })
                            ->get();
        } else {
            return  Product::where('document_id', $id)
                            ->where(function($q) {
                                $q->where(DB::raw('scanned_qty'), '>', DB::raw('qty'))
                                  ->orWhereNotNull('not_scan_remark');
                            })
                            ->get();
        }
        
        
        // // Detailed logging
        // Log::debug('search_excess_pd', [
        //     'id' => $id,
        //     'status' => $main->status,
        //     'result_count' => $result->count(),
        //     'result' => $result
        // ]);

        // return $result;

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

    function check_all_scan($id)
    {
      $finish = true;
         $pds = Product::where('document_id',$id)->get();
         foreach($pds as $item)
         {
             if($item->qty != $item->scanned_qty){
                    $finish    = false;
                    break;
            }
         }
         return $finish;
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
        // dd($finish_driver,$pending_driver);
        return $sec_pass;
    }

    function get_all_duration_second($id)
    {
        $all_driver_duration = DriverInfo::where('id',$id)->pluck('duration');
        $total_sec =(int)0;
        foreach($all_driver_duration as $item)
        {
            list($hour, $min, $sec) = explode(':', $item);
            $total_sec    += $hour*3600 + $min*60 + $sec;
        }
        $combine = $total_sec;
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

    function cur_truck_dur($id)
    {
        $cur    = DriverInfo::where('id',$id)->first();
        $cur_sec = strtotime($cur->start_date.' '.$cur->start_time);
        $now     = Carbon::now()->timestamp;
        $diff = $now - $cur_sec;
        $hour   = (int)($diff / 3600);
        $min    = (int)(($diff % 3600) / 60);
        $sec    = (int)(($diff % 3600) % 60);
        $sec_pass   = sprintf('%02d:%02d:%02d', $hour, $min, $sec);
        return $sec_pass;
    }

    function cur_truck_sec($id)
    {
        $cur    = DriverInfo::where('id',$id)->first();
        $cur_sec = strtotime($cur->start_date.' '.$cur->start_time);
        $now     = Carbon::now()->timestamp;
        $diff = $now - $cur_sec;

        return $diff;
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
        $data = DriverInfo::where('scan_user_id',getAuth()->id)
                            //->whereNull('duration')
                            // ->whereNull('car_scanning')
                            ->Where('car_scanning', 1)
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

    function get_truck_product($id,$driver)
    {
        $doc = Document::where('id',$id)->orderBy('id')->first();
        $pds = Product::where('document_id',$doc->id)->orderBy('id')->get();
        $track = Tracking::where('driver_info_id',$driver)->orderBy('id')->pluck('product_id')->toArray();
        $data = [];
        foreach($pds as $item)
        {
            if(in_array($item->id,$track))
            {

                $all = Tracking::where(['product_id'=>$item->id,'driver_info_id'=>$driver])->orderBy('id')->get();
                if(count($all) > 1)
                {
                    $data[] = Tracking::select('driver_info_id', 'product_id', DB::raw("SUM(scanned_qty) as scanned_qty"))
                            ->where('product_id', $item->id)
                            ->groupBy('product_id', 'driver_info_id')
                            ->orderBy('id')
                            ->first();

                }else{
                    $data[] = Tracking::Select('driver_info_id','product_id','scanned_qty')
                                        ->where('product_id',$item->id)
                                        ->where('driver_info_id',$driver)
                                        ->orderBy('id')
                                        ->first();
                }
            }
        }
        return $data;
    }


    function getDocument($id)
    {
        return Document::where('id',$id)->first();
    }

    function get_category($id)
    {
        return Product::where('document_id',$id)->count();
    }

    function get_truck_count($id)
    {
        $pd = Product::where('document_id',$id)->pluck('id');
        $tr = Tracking::whereIn('product_id',$pd)->distinct()->count('driver_info_id');
        return $tr;
    }

    function get_doc_total_qty($id,$action)
    {
        if($action == 'all')
        {
            return Product::where('document_id',$id)->sum('qty');
        }elseif($action == 'unloaded')
        {
            return Product::where('document_id',$id)->sum('scanned_qty');
        }
    }

    function get_product_per_truck($truck_id,$doc_id)
    {
        $pd_id  = Product::where('document_id',$doc_id)->pluck('id');
        $per_pd = Tracking::whereIn('product_id',$pd_id)
                            ->where('driver_info_id',$truck_id)
                            ->get();
        return $per_pd;
    }

    function truck_arrive($id)
    {
        $own    = DriverInfo::Where('car_scanning', 1)
                            // ->whereNull('duration')
                            ->where('scan_user_id',getAuth()->id)
                            ->first();
        $truck = [];
        if(!$own){
            $truck  = DriverInfo::where('received_goods_id',$id)
                                ->Where('car_scanning', 1)
                                //->whereNull('duration')
                                //->whereNot('user_id',getAuth()->id)
                                ->whereNot('scan_user_id',getAuth()->id)
                                ->get();
        }
        return $truck;
    }


    function get_remove_pd($id)
    {
        $remove_pd = 0 ;
        $exist = RemoveTrack::where('product_id',$id)->get();
        if($exist)
        {
            foreach($exist as $item)
            {
                $remove_pd += $item->remove_qty;
            }
        }
        return $remove_pd;
    }

    function get_per($pd,$unit)
    {
        $track = ScanTrack::where(['product_id'=>$pd,'unit'=>$unit])->sum('count');
        return $track;
    }

    function get_scan_count_truck($driver,$unit)
    {
        return ScanTrack::where(['driver_info_id'=>$driver,'unit'=>$unit])->sum('count');
    }

    function get_scan_truck_pd($driver,$pd,$unit)
    {
        return ScanTrack::where(['driver_info_id'=>$driver,'unit'=>$unit,'product_id'=>$pd])->sum('count');
    }

    function get_branch_truck()
    {
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
        $reg = new GoodsReceive();
        if($loc == 'dc')
        {
            $reg = $reg->whereIn('branch_id',$mgld_dc)->pluck('id');
        }elseif($loc == 'ho')
        {
            $reg = $reg->pluck('id');
        }else{
            $reg = $reg->where('branch_id',$user_branch)->pluck('id');
        }
        $truck_id = DriverInfo::Select('id','received_goods_id')->whereIn('received_goods_id',$reg)
                ->pluck('id');

        $data = [$truck_id, $loc,$reg];

        return $data;
    }

    function dc_staff()
    {
        $is = false;
        if(in_array($branch_id = getAuth()->branch_id,[17,19,20]))
        {
            $is = true;
        }
        return $is;
    }

    function gate_exist($branch_id)
    {
        $branch = Branch::find($branch_id);
        $gate   = CarGate::where('branch',$branch->branch_code)->first();
        if($gate)
        {
            return true;
        }else{
            return false;
        }
    }

    function image_exist($id)
    {
        $image = UploadImage::where('received_goods_id',$id)->get();
        return count($image)>0 ? true : false;
    }

    function multi_br()
    {
        $br = UserBranch::with('branch')->where('user_id',getAuth()->id)->get();
        return $br;
    }

    function get_client_ip()
    {
        return request()->ip();
    }


    function barcode_equal(array $product_barcodes, string $barcode): bool
    {
        $count = array_count_values($product_barcodes);
        return isset($count[$barcode]) && $count[$barcode] > 1;
    }



    function timeToTotalSeconds($time)
    {
        $parts = explode(':', $time);
        $hours = (int)$parts[0];
        $minutes = (int)$parts[1];
        $seconds = (int)$parts[2];
        
        // Calculate total seconds
        return ($hours * 3600) + ($minutes * 60) + $seconds;
    }

    



