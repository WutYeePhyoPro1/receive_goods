<?php
namespace App\Exports;

use App\Models\Product;
use App\Models\Document;
use App\Models\Tracking;
use App\Models\DriverInfo;
use App\Models\GoodsReceive;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

    class DetailExcel implements FromView,WithColumnWidths
    {
        private $id;
        private $action;

        public function __construct($id,$action)
        {
            $this->id = $id;
            $this->action = $action;
        }

        public function view():View
        {
            $action     = 'excel';
            $type       = $this->action;
            if($type == 'truck')
            {
                $detail     = 'truck';
                $driver     = DriverInfo::where('id',$this->id)->first();
                $reg        = GoodsReceive::where('id',$driver->received_goods_id)->first();
                $document   = [];
                $track      = Tracking::where('driver_info_id', $this->id)->get();
                foreach($track as $item)
                {
                    if(!in_array($item->product->doc->id,$document))
                    {
                        $document[] = $item->product->doc->id;
                    }
                }
                return view('user.report.detail_excel_report',compact('driver','reg','document','track','action','detail'));
            }elseif($type == 'document')
            {
                $detail     = 'document';
                $document   = Document::where('id',$this->id)->first();
                $reg        = GoodsReceive::where('id',$document->received_goods_id)->first();
                $product    = Product::where('document_id',$this->id)->get();
                $pd_id      = Product::where('document_id',$this->id)->pluck('id');
                $track      = Tracking::whereIn('product_id',$pd_id);
                $truck      = $track->distinct()->pluck('driver_info_id');
                $truck      = DriverInfo::whereIn('id',$truck)->get();
                $track      = $track->get();
                return view('user.report.detail_excel_report',compact('detail','truck','document','product','track','reg','action'));
            }
        }

        public function columnWidths(): array
        {
            return [
                'A' => 27,
                'B' => 27,
                'C' => 27,
                'D' => 27,
                'E' => 25,
                'F' => 27,
                'K' => 35,
                'L' => 20,
            ];
        }
    }
