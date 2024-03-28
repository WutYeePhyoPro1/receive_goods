<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Document;
use App\Models\Tracking;
use App\Models\ScanTrack;
use App\Models\DriverInfo;
use App\Models\printTrack;
use App\Models\UploadImage;
use App\Models\GoodsReceive;
use Illuminate\Http\Request;
use App\Models\AddProductTrack;
use App\Models\changeTruckProduct;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public  function del_reg(Request $request)
    {

        $reg            = GoodsReceive::find($request->id);
        $driver         = DriverInfo::where('received_goods_id',$request->id)->get();
        $document       = Document::where('received_goods_id',$request->id)->get();
        $document_ids   = $document->pluck('id');
        $product        = Product::whereIn('document_id',$document_ids)->get();
        $product_ids    = $product->pluck('id');
        $track          = Tracking::whereIn('product_id',$product_ids)->get();
    $scan           = ScanTrack::whereIn('product_id',$product_ids)->get();
        $print          = printTrack::whereIn('product_id',$product_ids)->get();
        $add            = AddProductTrack::whereIn('product_id',$product_ids)->get();
        $change         = changeTruckProduct::whereIn('product_id',$product_ids)->get();
        $files          = UploadImage::where('received_goods_id',$request->id)->get();

        if(count($driver) > 0)
        {
            DriverInfo::where('received_goods_id',$request->id)->delete();
        }
        if(count($document) > 0)
        {
            Document::where('received_goods_id',$request->id)->delete();
        }
        if(count($product) > 0)
        {
            Product::whereIn('document_id',$document_ids)->delete();
        }
        if(count($track) > 0)
        {
            Tracking::whereIn('product_id',$product_ids)->delete();
        }
        if(count($scan) > 0)
        {
            ScanTrack::whereIn('product_id',$product_ids)->delete();
        }
        if(count($print) > 0)
        {
            printTrack::whereIn('product_id',$product_ids)->delete();
        }
        if(count($add) > 0)
        {
            AddProductTrack::whereIn('product_id',$product_ids)->delete();
        }
        if(count($change) > 0)
        {
            changeTruckProduct::whereIn('product_id',$product_ids)->delete();
        }
        if(count($files) > 0 )
        {
            foreach($files as $item)
            {
                if(Storage::exists('public/'.$item->file))
                {
                    Storage::delete('public/'.$item->file);
                }
                Storage::disk('ftp')->delete($item->file);
            }
            UploadImage::where('received_goods_id',$request->id)->delete();
        }
        $reg->delete();
        return response(200);
    }
}
