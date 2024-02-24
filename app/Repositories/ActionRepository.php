<?php

namespace App\Repositories;

use Carbon\Carbon;
use App\Models\Product;
use App\Models\Document;
use App\Models\Tracking;
use App\Models\ScanTrack;
use App\Models\GoodsReceive;
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
    }

    public function add_doc($data,$id)
    {

        $receive = GoodsReceive::where('id', $id)->first();

            if (!$receive->vendor_name) {
                $receive->update([
                    'vendor_name' => $data[0]->vendorname
                ]);
            }
            $doc = Document::create([
            'document_no'       => $data[0]->purchaseno,
                'received_goods_id'  => $id
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
    }
}
