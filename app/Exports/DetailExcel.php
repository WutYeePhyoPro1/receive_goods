<?php
namespace App\Exports;

use App\Models\Tracking;
use App\Models\DriverInfo;
use App\Models\GoodsReceive;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

    class DetailExcel implements FromView,WithColumnWidths
    {
        private $id;

        public function __construct($id)
        {
            $this->id = $id;
        }

        public function view():View
        {
            $action     = 'excel';
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
            return view('user.report.detail_excel_report',compact('driver','reg','document','track','action'));
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
