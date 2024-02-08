<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Product;
use App\Models\Document;
use App\Models\Tracking;
use App\Models\DriverInfo;
use App\Models\RemoveTrack;
use App\Models\GoodsReceive;
use Illuminate\Http\Request;
use App\Models\changeTruckProduct;
use Illuminate\Support\Facades\DB;
use App\Repositories\ActionRepository;
use App\Interfaces\ActionRepositoryInterface;

class ActionController extends Controller
{
    private ActionRepositoryInterface $repository;

    public function __construct(ActionRepository $repository)
    {
        $this->repository = $repository;
    }

    //search doc
    public function search_doc(Request $request)
    {

        $val = $request->data;
        $type = substr($val,0,2);
        $docs = Document::pluck('document_no')->toArray();
        if(in_array($val,$docs)){
            return response()->json(['message'=>'dublicate'],400);
        }
        // dd($type);
        $conn = DB::connection('master_product');
        if($type == "PO")
        {
            $data = $conn->select("
                select purchaseno,vendorcode,vendorname,productcode,productname,unitcount as unit,goodqty
                from  purchaseorder.po_purchaseorderhd aa
                inner join  purchaseorder.po_purchaseorderdt bb on aa.purchaseid= bb.purchaseid
                left join master_data.master_branch br on aa.brchcode= br.branch_code
                where statusflag <> 'C' and statusflag in ('P','Y')
                and purchaseno= '$val'
            ");

        }else{
            $data = $conn->select("
                select tohd.transferdocno as to_docno
                ,(select branch_name_eng from master_data.master_branch br where tohd.desbrchcode = br.branch_code) as to_branch
                ,todt.productcode as product_code,todt.productname as product_name,todt.unitcount as unit
                ,todt.transferoutqty as qty
                from inventory.trs_transferouthd tohd
                left join inventory.trs_transferoutdt todt on tohd.transferid= todt.transferid
                where tohd.transferdocno in ('$val')
                and tohd.statusid <> 'C'
            ");
        }
        // dd($data);
        if($data){
            $receive = GoodsReceive::where('id', $request->id)->first();

            if (!$receive->vendor_name) {
                $receive->update([
                    'vendor_name' => $data[0]->vendorname
                ]);
            }
            $doc = Document::create([
            'document_no'       => $data[0]->purchaseno,
                'received_goods_id'  => $request->id
            ]);
            $dub_pd = [];
            for($i = 0 ; $i < count($data) ; $i++){
                if(!in_array($data[$i]->productcode,$dub_pd))
                {
                    $pd_code                = new Product();
                    $pd_code->document_id   = $doc->id;
                    $pd_code->bar_code       = $data[$i]->productcode;
                    $pd_code->supplier_name = $data[$i]->productname;
                    $pd_code->qty           = (int)($data[$i]->goodqty);
                    $pd_code->scanned_qty   = 0;
                    $pd_code->save();
                    $dub_pd[]    = $data[$i]->productcode;
                }else{
                    $search_dub = Product::where(['document_id'=>$doc->id,'bar_code'=>$data[$i]->productcode])->first();
                    $qty = $search_dub->qty;
                    $search_dub->update([
                        'qty'   => $qty+(int)($data[$i]->goodqty)
                    ]);
                }
            }
            return response()->json($data,200);
        }else{
            return response()->json(['message','not found'],404);
        }
    }

    //barcode scan
    public function barcode_scan(Request $request)
    {
        // dd('yes');
        $all    = $request->data;
        $id     = $request->id;
        $item   = preg_replace('/\D/','',$all);
        $unit   = preg_replace("/[^A-Za-z].*/", '', $all);
        $unit   = $unit == '' ? 'S' : $unit;

        $doc_ids = Document::where('received_goods_id',$request->id)->pluck('id');

        $product = Product::whereIn('document_id',$doc_ids)
                            ->where('bar_code',$item)
                            ->first();
        if($product){
            $all_product =Product::whereIn('document_id',$doc_ids)
                                ->where('bar_code',$item)
                                ->orderBy('id','asc')
                                ->get();
            $doc_no = $product->doc->document_no;
            $conn = DB::connection('master_product');
            try {
                $data = $conn->select("
                select * from
                (
                select	 product_code, qty
                from	dblink('dbname=pro1_awms host = 192.168.151.241 port=5432 user=superadmin password=super123',
                '
                SELECT product_code,qty FROM (
                SELECT product_code,product_code as barcode,product_unit_rate as qty FROM public.aw_master_product_rate UNION ALL
                SELECT product_code,pack_barcode as barcode,product_unit_rate as qty FROM public.aw_master_product_rate UNION ALL
                SELECT product_code,barcode_box as barcode,case when unit_rate_box=''0'' then product_unit_rate else unit_rate_box end as qty FROM public.aw_master_product_rate UNION ALL
                SELECT product_code,barcode_pallet as barcode,case when unit_rate_box=''0'' then (product_unit_rate*unit_rate_pallet) else (unit_rate_pallet*unit_rate_box) end as qty FROM public.aw_master_product_rate
                )rt
                WHERE barcode=''$all''')
                as temp(product_code varchar(50),qty varchar(50))
                )as erpdb
            ");
            $qty = (int)($data[0]->qty);
            $per        = $qty;
            $total_scan = $qty;
            $count = 0;
            if($request->car == '')
            {
                $driver_info = DriverInfo::where(['received_goods_id'=>$id , 'user_id'=>getAuth()->id])
                                        ->whereNull('duration')
                                        ->first();
            }else{
                $driver_info = DriverInfo::where('id',$request->car)
                                        ->first();
            }
            if(count($all_product) == 1)
            {
                $scanned = $product->scanned_qty + $qty;
                $product->update([
                    'scanned_qty' => $scanned
                ]);
            }elseif(count($all_product) > 1)
            {
                $full_pd =Product::whereIn('document_id',$doc_ids)
                        ->where('bar_code',$item)
                        ->where(DB::raw('qty'),'>',DB::raw('scanned_qty'))
                        ->get();
                $count      = 0;
                if(count($full_pd) > 0)
                {
                    if($request->car == '')
                    {
                        $driver_info = DriverInfo::where(['received_goods_id'=>$request->id , 'user_id'=>getAuth()->id])
                                                    ->whereNull('duration')
                                                    ->first();
                    }else{
                        $driver_info = DriverInfo::where('id',$request->car)
                                                ->first();
                    }

                    foreach($all_product as $index=>$item)
                    {

                        if($total_scan < 1)
                        {
                            break;
                        }
                        $sub = $item->qty - $item->scanned_qty;

                        if($item->qty > $item->scanned_qty && $sub >= $total_scan)
                        {
                            if($count > 0)
                            {
                                $update_time = Carbon::now()->addSecond();
                            }else{
                                $update_time = Carbon::now();
                            }

                            $scanned = $item->scanned_qty + $total_scan;
                            Product::where('id',$item->id)->update([
                                'scanned_qty'   => $scanned,
                                'updated_at'    => $update_time
                            ]);
                            $this->repository->add_track($driver_info->id,$item->id,$total_scan,$item->document_id,$update_time,$unit,$per);
                            $count ++;
                            break;
                        }else if($item->qty > $item->scanned_qty && $sub < $total_scan && $index != count($all_product)-1){
                            if($sub < $total_scan)
                            {
                                $scanned    = $item->qty;
                                $total_scan = $total_scan - $sub;
                                $added      = $sub;
                            }else{
                                $scanned    = $item->scanned_qty + $total_scan;
                                $total_scan = 0;
                                $added      = $total_scan;
                            }

                            if($count > 0)
                            {
                                $update_time = Carbon::now()->addSecond();
                            }else{
                                $update_time = Carbon::now();
                            }

                            Product::where('id',$item->id)->update([
                                'scanned_qty'   => $scanned,
                                'updated_at'    => $update_time
                            ]);

                            $this->repository->add_track($driver_info->id,$item->id,$added,$item->document_id,$update_time,$unit,$per);
                        }elseif($index == count($all_product)-1)
                        {
                                $scanned = $item->scanned_qty+$total_scan;
                                if($count > 0)
                                {
                                    $update_time = Carbon::now()->addSecond();
                                }else{
                                    $update_time = Carbon::now();
                                }
                                Product::where('id',$item->id)->update([
                                    'scanned_qty'   => $scanned,
                                    'updated_at'    => $update_time
                                ]);
                                $this->repository->add_track($driver_info->id,$item->id,$total_scan,$item->document_id,$update_time,$unit,$per);
                        }
                        $count ++;
                    }
                }else{
                    $exceed_pd =Product::whereIn('document_id',$doc_ids)
                                        ->where('bar_code',$item)
                                        ->orderBy('id','desc')
                                        ->first();

                    if($exceed_pd){
                        $exceed_qty = $exceed_pd->scanned_qty + $total_scan;
                        Product::where('id',$exceed_pd->id)->update([
                            'scanned_qty'   => $exceed_qty
                        ]);
                    }
                }
            }
            if(isset($driver_info) && $count == 0)
            {
                $this->repository->add_track($driver_info->id,$product->id,$total_scan,$product->document_id,null,$unit,$per);
            }
            return response()->json(['doc_no'=>$doc_no,'bar_code'=>$product->bar_code,'data'=>$product,'scanned_qty'=>$qty],200);
            } catch (\Exception $e) {
                logger($e);
                return response()->json(['message'=>'Not found'],500);
            }

        }else{
            return response()->json(['message'=>'Not found'],404);
        }
    }

    //edit scan
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

    //confirm/continue Button click
    public function confirm(Request $request)
    {
        $receive = GoodsReceive::where('id',$request->id)->first();
        $doc    = Document::where('received_goods_id',$request->id)->get();
        $driver =  DriverInfo::where('received_goods_id',$request->id)
                            ->where('user_id',getAuth()->id)
                            ->whereNull('duration')->first();

        $finish_driver = DriverInfo::where('received_goods_id',$request->id)
                            ->whereNotNull('duration')->get();
        if($driver)
        {
            $start = strtotime($driver->start_date.' '.$driver->start_time);
            $now    = Carbon::now()->timestamp;
            $diff = $now - $start;

            $data =  $this->repository->get_remain($request->id);

            $hour   = (int)($diff / 3600);
            $min    = (int)(($diff % 3600) / 60);
            $sec    = (int)(($diff % 3600) % 60);
            $pass   = sprintf('%02d:%02d:%02d', $hour, $min, $sec);
            $this_scanned = get_scanned_qty($driver->id);
            // dd(get_all_duration($request->id));
            if(cur_truck_sec($driver->id) < 86401)
            {
                $receive->update([
                    'total_duration'        => get_all_duration($request->id),
                    'remaining_qty'         => $data['remaining'],
                    'exceed_qty'            => $data['exceed'],
                    'status'                => 'incomplete'
                ]);

                $driver->update([
                    'scanned_goods' => $this_scanned,
                    'duration'      => $pass
                ]);
            }else{
                return response()->json(500);
            }


        }else{
            $receive->update([
                'total_duration' => '00:00:00',
                'status'         => 'incomplete'
            ]);
        }
        return response()->json(200);
    }

    //click complete btn
    public function finish_goods($id)
    {
        $receive = GoodsReceive::where('id',$id)->first();
        $driver = DriverInfo::where('received_goods_id',$id)
                            ->where('user_id',getAuth()->id)
                            ->whereNull('duration')
                            ->first();

        $finish_driver = DriverInfo::where('received_goods_id',$id)
                                    ->whereNotNull('duration')->get();

        $start_time = strtotime($driver->start_date.' '.$driver->start_time);
        $now        = strtotime(Carbon::now()->format('Y-m-d H:i:s'));

        $data =  $this->repository->get_remain($id);
        $diff = $now - $start_time;
        $hour   = (int)($diff / 3600);
        $min    = (int)(($diff % 3600) / 60);
        $sec    = (int)(($diff % 3600) % 60);
        $time   = sprintf('%02d:%02d:%02d', $hour, $min, $sec);
        $this_scanned = get_scanned_qty($id);
        if($driver)
        {
            $receive->update([
                'total_duration'        => get_all_duration($id),
                'remaining_qty'         => $data['remaining'],
                'exceed_qty'            => $data['exceed'],
                'status'                => 'complete'
            ]);

        if(cur_truck_sec($driver->id) < 86401)
        {
            $receive->update([
                'total_duration'        => get_all_duration($id),
                'remaining_qty'         => $data['remaining'],
                'exceed_qty'            => $data['exceed'],
                'status'                => 'complete'
            ]);

            $driver->update([
                'scanned_goods' => $this_scanned,
                'duration'      => $time
            ]);

            return response()->json(200);
        }
        return response()->json(500);
        }
    }

    //click delete excess btn
    public function del_exceed(Request $request)
    {
        $product = Product::where('id',$request->id)->first();
        $remove_qty = $product->scanned_qty - $product->qty;
        $product->update([
            'scanned_qty' => $product->qty
        ]);

        $del                        = new RemoveTrack();
        $del->received_goods_id      = $product->doc->received_goods_id;
        $del->user_id               = getAuth()->id;
        $del->product_id            = $request->id;
        $del->remove_qty            = $remove_qty;
        $del->save();

        return response()->json(200);
    }
}
