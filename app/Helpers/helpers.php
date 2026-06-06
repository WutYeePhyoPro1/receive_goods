<?php

use App\Models\Branch;
use App\Models\CarGate;
use App\Models\Document;
use App\Models\DriverInfo;
use App\Models\GoodsReceive;
use App\Models\Product;
use App\Models\PurchaseOrderItem;
use App\Models\ReceiveGoodDocument;
use App\Models\ReceiveGoodProduct;
use App\Models\RemoveTrack;
use App\Models\ScanTrack;
use App\Models\Tracking;
use App\Models\UploadImage;
use App\Models\User;
use App\Models\UserBranch;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

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

    
    function getPODocument($purchaseno){
        $conn = DB::connection('master_product');
        $brch_con = '';
        // if (!dc_staff()) {
        //     $user_brch = getAuth()->branch->branch_code;
        //     $brch_con = "and brchcode = '$user_brch'";
        // }
        $val = trim(strtoupper($purchaseno), ' ');


        $data = $conn->select("
            select purchaseno,brchcode,vendorcode,vendorname,productcode,productname,unitcount as unit,goodqty,goodprice,bb.sumgoodamnt,creditday,purchasedate,aa.remark
            from purchaseorder.po_purchaseorderhd aa
            inner join purchaseorder.po_purchaseorderdt bb on aa.purchaseid= bb.purchaseid
            left join master_data.master_branch br on aa.brchcode= br.branch_code
            where statusflag <> 'C'
            and statusflag in ('P','Y') 
            $brch_con
            and purchaseno= '$val'
        ");
        // dd($data);
        return $data;
    }


    function generateRGDocHeader($data, $receive_good_document){
        $conn = DB::connection('master_product');

        $invoice_date = $receive_good_document->delivery_date;
        $receive_employee_code = $receive_good_document->user->employee_code;
        $transportation_by = $receive_good_document->ship_by;
        $purchase_no = $receive_good_document->po_no;
        $vendor_code = $receive_good_document->vendor_code;
        $branch_code = $receive_good_document->branch->branch_code;
        $approve_amount = $receive_good_document->total_amount;

        $invoice_no = $receive_good_document->delivery_note;
        $status_r008 = $receive_good_document->r008 ? 'N' : '';
        $employeecode = $receive_good_document->user->employee_code;
        $remark_id = $receive_good_document->receive_type;

        $document = $receive_good_document->document;
        $creditday = $document->creditday;
        $portal_mark = 'RGN-' . str_pad($receive_good_document->id, 4, '0', STR_PAD_LEFT); // marked portal receive good document id in ERP
        $remark = $document->remark . "/(PORTAL)$portal_mark";
        $purchase_date = $document->purchasedate;
        // dd($document);
        

        $result = $conn->select("
            INSERT INTO purchaseorder.receive_hd (
                receive_no, 
                receive_date, 
                invoice_date, 
                receive_employee_code, 
                transportation_by, 
                poinvoice_id, 
                purchase_no, 
                vendor_code, 
                branch_code, 
                credits, 
                approve_amount, 
                remark, 
                receive_status, 
                process_date, 
                invoice_no, 
                purchase_date, 
                ismulticurrency, 
                multicurrencyrate, 
                poststockflag, 
                status_r008, 
                r008_docuno, 
                employeecode, 
                remark_id
            )
            SELECT 
                -- Auto Generate Receive No 
                (select 
                    case
                    when
                    (
                                select count(receive_no) from purchaseorder.receive_hd where receive_date::date = now()::date and branch_code='$branch_code'
                    ) = 0
                    then
                    (
                        select doc_no||'-0001' as rgno from
                        (
                        select replace((select 'RG'||(select branch_short_name from master_data.master_branch where branch_code='$branch_code')||
                                (select right((select (now()::date)::text),8)::text)), '-', '') as doc_no
                        ) aa
                    )
                    else
                    (
                        select (left((select max(receive_no) as max_date from purchaseorder.receive_hd where receive_date::date = now()::date and branch_code='$branch_code'),-3)||
                        TO_CHAR(((right((select max(receive_no) as max_date from purchaseorder.receive_hd where receive_date::date = now()::date and branch_code='$branch_code'),3)::integer +1)), 'fm000')) as doc_no
                    )
                    end as receive_no
                ),
                NOW(),                          -- receive_date (complete နှိပ်လိုက်တဲ့အချိန်)
                '$invoice_date',                  -- invoice_date
                '$receive_employee_code',         -- receive_employee_code (portal login user)
                '$transportation_by',             -- transportation_by -- purchaseorder.po_transportation ကယူ
                '0',                            -- poinvoice_id (Fix)
                '$purchase_no',                   -- purchase_no --- From Portal
                '$vendor_code',                   -- vendor_code --- From Portal
                '$branch_code',                   -- branch_code --- From Portal
                '$creditday',                     --(select creditday from purchaseorder.po_purchaseorderhd where purchaseno='$purchase_no') , --- credits
                $approve_amount,                -- approve_amount -- from Portal Ma Wut Yee--
                '$remark',                       --(select remark from purchaseorder.po_purchaseorderhd where purchaseno='$purchase_no'),    -- remark -- from Portal--
                'N',                            -- receive_status (Fix)
                NOW(),                          -- process_date (complete နှိပ်လိုက်တဲ့အချိန်)
                '$invoice_no',                    -- invoice_no --- from Portal ?
                '$purchase_date',                 --(select purchasedate from purchaseorder.po_purchaseorderhd where purchaseno='$purchase_no'),  --- purchase_date
                'N',                            -- ismulticurrency --- (Fix)
                0,                              -- multicurrencyrate --- (Fix)
                'N',                            -- poststockflag (Fix)
                '$status_r008',                   -- status_r008 ---  N
                '',					            -- r008_docuno --- if status_r008=N , r008_docuno = blank
                '$employeecode',                  -- employeecode (portal login user)
                '$remark_id'                     -- remark_id --- purchaseorder.receive_type ကယူ
                RETURNING *;
            ");

        // dd($result);
        return $result;
    }


    function generateRGDocDetail($productRG){
        $conn = DB::connection('master_product');

        $list_no = $productRG['list_no'];
        $product_code = $productRG['product_code'];
        $product_name = $productRG['product_name'];
        $unit_count = $productRG['unit'];
        $approve_quantity = $productRG['po_qty'];
        $receive_quantity = $productRG['gr_qty'];
        $approve_price = $productRG['price'];
        $approve_amount = $productRG['amount'];
        $ref_list_no = $productRG['ref_list_no'];
        $receive_no = $productRG['receive_no'];


        $result = $conn->insert("
            INSERT INTO purchaseorder.receive_dt (
                list_no,
                product_code,
                product_name, 
                unit_count,
                approve_quantity,
                receive_quantity, 
                approve_price,
                approve_amount, 
                ref_list_no, 
                receive_no, 
                discount, 
                r008qty
            )
            SELECT 
                '$list_no',                 				-- Ma Wut Yee --
                '$product_code',                    -- product_code (From Portal)
                '$product_name',     -- product_name (From Portal) 
                '$unit_count',                               -- unit_count (From Portal)
                '$approve_quantity',                               -- approve_quantity (From PO)
                '$receive_quantity',                               -- receive_quantity (From Portal)
                '$approve_price',                             -- approve_price (From Portal)
                '$approve_amount',                            -- approve_amount (From Portal)
                $ref_list_no,                                  -- ref_list_no  (PO က item အရေအတွက်)
                '$receive_no',                -- receive_no (From Header)
                0,                                  -- discount (From Portal)
                0                                    -- r008qty
            ;
        ");

        return $result;
    }

    function generateR008Header($data,$r008_document){
        $conn = DB::connection('defective_product');

        $vendor_code = $r008_document->vendor_code;
        $r_poinvioce = $r008_document->rg_no;
        
        $rg_no = $r008_document->rg_no;
        $receive_good_document = ReceiveGoodDocument::with('vendor')
                                ->whereHas('receive_good_files', function ($q) use ($rg_no) {
                                    $q->where('file', $rg_no);
                                })
                                ->first();
        $r_invioce_tax = $receive_good_document->delivery_note;
        

        $portal_mark = 'RGN-' . str_pad($r008_document->id, 4, '0', STR_PAD_LEFT); // marked portal receive good document id in ERP
        $remark = $r008_document->remark . "/(PORTAL)$portal_mark";
        $r_usersave = $r008_document->user->employee_code;
        $r_brchcode = $r008_document->branch->branch_code;
        $truck_con_no = $r008_document->truck_container_no;
        $product_type = $r008_document->product_type;


        $result = $conn->select("
            INSERT INTO public.r008_branch_reciverhd (
                r_docuno, 
                r_doc_type, 
                r_status, 
                vendorcode, 
                r_poinvioce, 
                r_invioce_tax, 
                remark, 
                r_usersave, 
                r_brchcode, 
                r_docudate, 
                truck_con_no, 
                product_type
            )
                select 
                            case
                            when
                    (
                            select count(r_docuno) from r008_branch_reciverhd where r_docudate::date = now()::date and r_brchcode='$r_brchcode'
                    ) = 0
                            then
                    (
                            select doc_no||'-0001' as r_docuno from
                    (
                            select replace((select 'R008RG'||(select brchshortname from masterdata.master_branch where brchcode='$r_brchcode')||
                                (select right((select (now()::date)::text),8)::text)), '-', '') as doc_no
                        ) aa
                            )
                        else
                        (
                        select (left((select max(r_docuno) as max_date from r008_branch_reciverhd where r_docudate::date = now()::date and r_brchcode='$r_brchcode'),-3)||
                        TO_CHAR(((right((select max(r_docuno) as max_date from r008_branch_reciverhd where r_docudate::date = now()::date and r_brchcode='$r_brchcode'),3)::integer +1)), 'fm000')) as doc_no
                        )
                end as r_docuno,
                2,                        -- r_doc_type(fix)
                1,                        -- r_status (fix)
                '$vendor_code',             -- vendorcode (From RG)
                '$r_poinvioce',    -- r_poinvioce (From RG)
                '$r_invioce_tax',                       -- r_invioce_tax (From RG- delivery Note)
                '$remark',                       -- remark(From R008 - remark)
                '$r_usersave',             		  -- r_usersave (portal)
                '$r_brchcode',                 -- r_brchcode
                NOW(),                    -- r_docudate
                '$truck_con_no',                   -- truck_con_no 
                '$product_type'                  -- product_type (User's Choice)
                RETURNING *;
        ");
        // dd($result);
        return $result;
    }


    function generateR008Detail($product_r008){
        $conn = DB::connection('defective_product');

        $r_doc_id = $product_r008->r_doc_id;
        $r8item_id = $product_r008->list_no;
        $r8itembranch = $product_r008->branch_code;
        $goodcode = $product_r008->product_code;
        $goodname = $product_r008->product_name;
        $amountinbill = $product_r008->gr_qty;
        $amountcount = $product_r008->physical_qty;
        $amountdifference = $product_r008->diff;
        $status = $product_r008->status_id;
        $list_no = $product_r008->list_no;


        $result = $conn->select("
            INSERT INTO public.r008_branch_receivedt(
                r_doc_id, 
                r8item_id,
                r8date, 
                r8itembranch, 
                goodcode, 
                goodname, 
                amountinbill, 
                amountcount, 
                amountdifference, 
                amountdamaged, 
                attachs_id,   
                status, 
                list_no,
                amountsmalldamage
            )
            SELECT 
                
                '$r_doc_id',   --- Header က ID ကိုယူ  
                $r8item_id,    --- r8item_id (same , list_no)
                NOW() ,  --- r8date                                                           
                '$r8itembranch' , ---r8itembranch                                                         
                '$goodcode', ---goodcode                                                     
                '$goodname',  ---goodname                       
                $amountinbill ,  ---amountinbill    (From ref RG )                                                          
                $amountcount ,  ---amountcount      (From ref RG / actual)                                                          
                $amountdifference ,  ---amountdifference  (From ref RG / diff)                                                        
                0 ,  ---amountdamaged                                                                
                0 ,  ---attachs_id                                                                                                                               
                $status ,  ---status (Fix)                                                                      
                $list_no ,  ---list_no                                                                      
                0 ;  ---amountsmalldamage
        ");

        return $result;

    }

    function updatePOStatus($data, $receive_good_document){
        $conn = DB::connection('master_product');

        $po_no = $receive_good_document->po_no;
        $document = $receive_good_document->document;
        // $products = Product::where('document_id',$document->id)->get();
        $products = PurchaseOrderItem::where('document_id',$document->id)
            ->orderBy('id','desc')
            ->get();

        // $rg_doc_ids = ReceiveGoodDocument::where('po_no',$po_no)->pluck('id');
        // $received_sums = ReceiveGoodProduct::whereIn('receive_good_document_id', $rg_doc_ids)
        //     ->selectRaw(
        //         'product_code, SUM(gr_qty) as total_received'
        //     )
        //     ->groupBy('product_code')
        //     ->pluck('total_received', 'product_code');

        // => To Check total received products in both ERP and PORTAL  (we can update the latest status of PO)
        $received_sums = getReceivedSums($po_no);
        // dd($received_sums);

        $isComplete = true;

        foreach ($products as $product) {

            $receivedQty = $received_sums[$product->bar_code][$product->price] ?? 0;

            if ($receivedQty < $product->qty) {
                $isComplete = false;
                break;
            }
        }

        // dd($isComplete);
        if($isComplete){
            $modified = $conn->update("
                update purchaseorder.po_purchaseorderhd set statusflag='F' where purchaseno='$po_no'; --- when PO full
            ");

            $document->update(['status'=>'Already RG']);
        }else{
            $modified = $conn->update("
                update purchaseorder.po_purchaseorderhd set statusflag='P' where purchaseno='$po_no'; --- when PO Partial
            ");
            $document->update(['status'=>'PO Partial']);
        }

        return $modified;
        
    }

    function updateR008No($data, $r008_document){
        $conn = DB::connection('master_product');

        $r008_docuno = $r008_document->r008_files->first()->file;
        $receive_no = $r008_document->rg_no;

        $modified = $conn->update("
            update purchaseorder.receive_hd
            set r008_docuno='$r008_docuno' , status_r008='t'
            where receive_no='$receive_no'
        ");
    }

    function getPOHistory($po_no){
        $conn = DB::connection('master_product');
        $data = $conn->select("
            select purchaseno,rg.receive_no,product_code,product_name,approve_quantity,approve_price,receive_quantity
            from purchaseorder.po_purchaseorderhd po
            inner join purchaseorder.receive_hd rg
            on po.purchaseno=rg.purchase_no
            inner join purchaseorder.receive_dt dt
            on rg.receive_no=dt.receive_no
            where purchaseno='$po_no'
        ");
        return $data;
    }


    function getReceivedSums($po_no){
        $po_histories = collect(getPOHistory($po_no));
        // dd($po_histories);

        // Error: Method Illuminate\Support\Collection::selectRaw does not exist.",…}
        // $received_sums = $po_histories::selectRaw(
        //     'product_code, SUM(receive_quantity) as total_received'
        // )
        // ->groupBy('product_code')
        // ->pluck('total_received', 'product_code');

        // => Can use for different code in PO
        // $received_sums = $po_histories
        //                 ->groupBy('product_code');
                        // ->map(fn ($items) => $items->sum('receive_quantity'));

        $received_sums = $po_histories
        ->groupBy(['product_code', 'approve_price'])
        ->map(function ($priceGroups) {
            return $priceGroups->map(function ($items) {
                return $items->sum('receive_quantity');
            });
        });

        return $received_sums;
    }

    function manager($data){
        $user = User::where(['id' => getAuthUser()->id, 'role_id' => $role->id])->first();
        return $user;
    }

    function isManager($data){
        $user = auth()->user();
        
        $role = Role::where('name','manager')->first();
        $role_id = $role->id;

        $isManager = $user
                        && $user->role == $role_id
                        && $user->branch_id == $data->branch_id;

        return $isManager;
    }