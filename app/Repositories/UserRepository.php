<?php

    namespace App\Repositories;

use Carbon\Carbon;
use App\Models\Product;
use App\Models\Document;
use App\Models\Tracking;
use App\Interfaces\UserRepositoryInterface;

Class UserRepository implements UserRepositoryInterface
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

    public function add_track($driver,$pd,$qty,$document,$update = null)
    {
        $track_dub = Tracking::where(['driver_info_id'=>$driver,'product_id'=>$pd])->first();
        if($track_dub)
        {
            logger(1);
            $track_scan = $track_dub->scanned_qty;
            $track_dub->update([
                'scanned_qty'   => $track_scan+$qty
            ]);
        }else{
            logger(2);
            $track                      = new Tracking();
            $track->driver_info_id      = $driver;
            $track->product_id          = $pd;
            $track->scanned_qty         = $qty;
            $track->user_id             = getAuth()->id;
            $track->save();
        }

        $update = $update ? $update : Carbon::now();

        Document::where('id',$document)->update([
            'updated_at'    => $update
        ]);
    }
}
