<?php

namespace App\Repositories;

use Carbon\Carbon;
use App\Models\Vendor;
use App\Models\Product;
use App\Models\Document;
use App\Models\Tracking;
use App\Models\ScanTrack;
use App\Models\GoodsReceive;
use Illuminate\Support\Facades\DB;
use App\Interfaces\ActionRepositoryInterface;

Class ActionRepository implements ActionRepositoryInterface
{
    public function get_remain($id)
    {
        $doc   = Document::where('received_goods_id',$id)->pluck('id');
        $goods = Product::whereIn('document_id',$doc)->get();
        $remaining  = 0;
        $exceed     = 0;
        foreach($goods as $item)
        {
            if($item->scanned_qty < $item->qty)
            {
                $remaining = $remaining + ($item->qty - $item->scanned_qty);
            }elseif($item->scanned_qty > $item->qty){
                $exceed = $exceed + ($item->scanned_qty - $item->qty);
            }
        }

        $goods['remaining'] = $remaining;
        $goods['exceed'] = $exceed;

        return $goods;
    }

    public function add_track($driver,$pd,$qty,$document,$update = null,$unit,$per)
    {
        $track_dub = Tracking::where(['driver_info_id'=>$driver,'product_id'=>$pd])->first();
        $product = Product::find($pd);
        if($track_dub)
        {
            $track_scan = $track_dub->scanned_qty;
            $track_dub->update([
                'scanned_qty'   => $track_scan+$qty
            ]);
        }else{
            $track                      = new Tracking();
            $track->driver_info_id      = $driver;
            $track->product_id          = $pd;
            $track->scanned_qty         = $qty;
            $track->user_id             = getAuth()->id;
            $track->save();
        }

        $unit = strlen($unit) > 1 ? 'S' : $unit;

        $scan_track = ScanTrack::where(['driver_info_id'=>$driver,'user_id'=>getAuth()->id,'unit'=>$unit,'product_id'=>$pd])->first();
        if($scan_track)
        {
            $count = $scan_track->count + 1;
            $scan_track->update([
                'count' => $count
            ]);
        }else{
            $scan                   = new ScanTrack();
            $scan->driver_info_id   = $driver;
            $scan->product_id       = $pd;
            $scan->user_id          = getAuth()->id;
            $scan->unit             = $unit;
            $scan->per              = $per;
            $scan->count            = 1;
            $scan->save();
        }

        $update = $update ? $update : Carbon::now();

        Document::where('id',$document)->update([
            'updated_at'    => $update
        ]);
        if(in_array(getAuth()->branch_id,[17,19,20]))
        {
            return $unit.$product->bar_code;
        }else{
            return $product->bar_code;
        }
        // $barcode = []
    }

    public function add_doc($data,$id)
    {

        $receive = GoodsReceive::where('id', $id)->first();

            if (!$receive->vendor_name) {
                $vendor_name = $data[0]->vendorname ?? $data[0]->to_branch;
                $receive->update([
                    'vendor_name' => $vendor_name
                ]);
                $vendor = Vendor::where('vendor_name',$vendor_name)->first();
                if(!$vendor)
                {
                    $conn = DB::connection('master_product');
                    $ven_info = $conn->select("
                    select vendor_name,vendor_code,vendor_addr,vendor_conttel from configure.setap_vendor where
                    vendor_name = '$vendor_name'
                    ");
                    $ven_info = $ven_info[0];
                    Vendor::create([
                        'vendor_code'   => $ven_info->vendor_code,
                        'vendor_name'   => $ven_info->vendor_name,
                        'vendor_address'=> $ven_info->vendor_addr,
                        'vendor_ph'     => $ven_info->vendor_conttel
                    ]);
                }
            }
            $doc = Document::create([
                'document_no'       => $data[0]->purchaseno ?? $data[0]->to_docno,
                'received_goods_id'  => $id
            ]);
            $dub_pd = [];
            for($i = 0 ; $i < count($data) ; $i++){
                if(!in_array($data[$i]->productcode ?? $data[$i]->product_code,$dub_pd))
                {
                    // $pd_code                = new Product();
                    // $pd_code->document_id   = $doc->id;
                    // $pd_code->bar_code       = $data[$i]->productcode ?? $data[$i]->product_code;
                    // $pd_code->supplier_name = $data[$i]->productname ?? $data[$i]->product_name;
                    // $pd_code->qty           = (float)($data[$i]->goodqty ?? $data[$i]->qty);
                    // $pd_code->scanned_qty   = 0;
                    // $pd_code->unit          = $data[$i]->unit;
                    // $pd_code->save();
                    $pd_code = Product::updateOrCreate(
                        [
                            'document_id' => $doc->id,
                            'bar_code'    => $data[$i]->productcode ?? $data[$i]->product_code,
                            'qty'         => (float)($data[$i]->goodqty ?? $data[$i]->qty),
                        ],
                        [
                            'supplier_name' => $data[$i]->productname ?? $data[$i]->product_name,
                            'scanned_qty'   => 0,
                            'unit'          => $data[$i]->unit ?? null
                        ]
                    );
                    $dub_pd[]    = $data[$i]->productcode ?? $data[$i]->product_code;
                }else{
                    $search_dub = Product::where(['document_id'=>$doc->id,'bar_code'=>$data[$i]->productcode])->first();
                    $qty = $search_dub->qty;
                    $search_dub->update([
                        'qty'   => $qty+(int)($data[$i]->goodqty)
                    ]);
                }
            }
    }
}
