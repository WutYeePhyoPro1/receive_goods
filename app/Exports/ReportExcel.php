<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Booking;
use App\Models\Product;
use App\Models\Document;
use App\Models\Tracking;
use App\Models\DriverInfo;
use App\Models\RemoveTrack;
use App\Models\GoodsReceive;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReportExcel implements FromView,WithColumnWidths,WithStyles
{
    protected $filter;

    public function __construct($filter)
    {
        $this->filter = $filter;
    }

    public  function view(): View
    {
        $report = $this->filter['report'];
        if($this->filter['report'] == 'product')
        {
            $product = [];

            if(isset($this->filter['search'])){
                if($this->filter['search'] == 'main_no')
            {
                $main   = GoodsReceive::where('document_no',$this->filter['search_data'])->first();
                if($main)
                {
                    $doc    = Document::where('received_goods_id',$main->id)->pluck('id');
                    $product = Product::whereIn('document_id',$doc)->pluck('id');
                }
                // dd($main);
            }
            else if($this->filter['search'] == 'document_no')
            {
                $doc    = Document::where('document_no',$this->filter['search_data'])->first();
                if($doc){
                    $product= Product::where('document_id',$doc->id)->pluck('id');
                }
            }
            }

            $product = Product::when(isset($this->filter['search']) && ($this->filter['search'] != 'main_no' || $this->filter['search'] != 'document_no' || $this->filter['search'] != 'product_code') && !$this->filter['search_data'] , function($q){
                                $q->whereYear('created_at',Carbon::now()->format('Y'))
                                ->whereMonth('created_at',Carbon::now()->format('m'));
            })
                                ->when( isset($this->filter['search']) && ($this->filter['search'] == 'main_no' || $this->filter['search'] == 'document_no') && $this->filter['search_data'],function($q) use($product){
                                    $q->whereIn('id',$product);
                                })
                                ->when( isset($this->filter['search']) && $this->filter['search'] == 'product_code' && $this->filter['search_data'],function($q){
                                    $q->where('bar_code',$this->filter['search_data']);
                                })
                                ->get();

            $all = $product;

///////////-----------------------------------------------
        }else if($this->filter['report'] == 'finish')
        {
            $ids=[];
            if(isset($this->filter['search'])){
                if($this->filter['search'] == 'truck_no' || $this->filter['search'] == 'driver_name'){
                    $ids = DriverInfo::where($this->filter['search'],$this->filter['search_data'])->pluck('received_goods_id');
                }
            }

            $data = GoodsReceive::when(isset($this->filter['search']) && ($this->filter['search'] == 'document_no' && $this->filter['search_data']),function($q){
                                $q->where('document_no',$this->filter['search_data']);
            })
                                ->when(isset($this->filter['search']) && ($this->filter['search'] != 'document_no' && $this->filter['search_data']),function($q) use($ids){
                                    $q->whereIn('id',$ids);
                                })
                                ->when(isset($this->filter['branch']),function($q){
                                    $q->where('branch_id',$this->filter['branch']);
                                })
                                ->when(isset($this->filter['status']),function($q){
                                    $q->where('status',$this->filter['status']);
                                })
                                ->when(isset($this->filter['from_date']),function($q){
                                    $q->where('start_date','>=',$this->filter['from date']);
                                })
                                ->when(isset($this->filter['to_date']),function($q){
                                    $q->where('start_date','<=',$this->filter['to_date']);
                                })
                                ->when(!isset($this->filter['search']) || !isset($this->filter['search_date']) || !isset($this->filter['branch']) || !isset($this->filter['search_data']) , function($q){
                                    $q->whereYear('created_at',Carbon::now()->format('Y'))
                                    ->whereMonth('created_at',Carbon::now()->format('m'));
                })
                                ->whereNotNull('total_duration')
                                ->where('status','complete')
                                ->orderBy('created_at','desc')
                                ->get();

                $all = $data;
                ///////////-----------------------------------------------
        }elseif($this->filter['report'] == 'truck')
        {
            $truck = [];
        if(isset($this->filter['search']) && $this->filter['search_data']){
            if($this->filter['search'] == 'main_no' && $this->filter['search_data'])
            {
                $main = GoodsReceive::where('document_no',$this->filter['search'])->first();
                if($main)
                {
                    $truck= DriverInfo::where('received_goods_id',$main->id)->pluck('id');
                }
            }elseif($this->filter['search'] == 'product_code' && $this->filter['search_data'])
            {

                $product = Product::where('bar_code',$this->filter['search_data'])->pluck('id');
                if($product)
                {
                    $truck   = Tracking::whereIn('product_id',$product)->pluck('driver_info_id');
                }
            }elseif(($this->filter['search'] == 'truck_no' || $this->filter['search'] == 'driver_name') && $this->filter['search_data'])
            {
                $truck = DriverInfo::where($this->filter['search'],$this->filter['search_data'])->pluck('id');
            }
        }
        dd($truck);

        $truck  = Driverinfo::when(!isset($this->filter['search']) || !isset($this->filter['search_data'])  || !isset($this->filter['gate']) , function($q){
                                $q->whereYear('created_at',Carbon::now()->format('Y'))
                                ->whereMonth('created_at',Carbon::now()->format('m'));
                        })
                            ->when(isset($this->filter['search']) && ($this->filter['search'] == 'main_no' || $this->filter['search'] == 'product_code' || $this->filter['search'] == 'truck_no' || $this->filter['search'] == 'driver_name') && $this->filter['search_data'],function($q) use($truck){
                                $q-> whereIn('id', $truck);
                        })
                            ->when(isset($this->filter['gate']),function($q){
                                $q-> where('gate',$this->filter['gate']);
                            })
                            ->get();

            $all = $truck;
            ///////////-----------------------------------------------
        }elseif($this->filter['report'] == 'remove')
        {
            $no = [];
        $product = '';
        $user    = '';
        if(isset($this->filter['search']))
        {
            if($this->filter['search'] == 'main_no' && $this->filter['search_data'])
            {
                $document   = GoodsReceive::where('document_no',$this->filter['search_data'])->first();
                if($document)
                {
                    $no       = RemoveTrack::where('received_goods_id',$document->id)->pluck('id');
                }
            }elseif($this->filter['search'] == 'product_code' && $this->filter['search_data'])
            {
                $product    = Product::where('bar_code',$this->filter['search_data'])->pluck('id');
            }elseif($this->filter['search'] == 'user' && $this->filter['search_data'])
            {
                $user   = User::where('name',$this->filter['search_data'])->first();
            }
        }

        $data = RemoveTrack::when(!isset($this->filter['search']),function($q)
                            {
                                $q->whereYear('created_at',Carbon::now()->format('Y'))
                                ->whereMonth('created_at',Carbon::now()->format('m'));
                            })
                            ->when( isset($this->filter['search']) && $this->filter['search'] == 'main_no' && $this->filter['search_data'] , function($q) use($no){
                                $q->whereIn('id',$no);
                            })
                            ->when(isset($this->filter['search']) && $this->filter['search'] == 'product_code' && $this->filter['search_data'],function($q) use($product){
                                $q->where('proudct_id',$product);
                            })
                            ->when( isset($this->filter['search']) && $this->filter['search'] == 'user' && $this->filter['search_data'],function($q) use($user){
                                $q->where('user_id',$user);
                            })
                            ->get();

            $all = $data;
        }
        // dd($all);
        return view('user.report.excel_report',compact('all','report'));
    }

    public function columnWidths(): array
    {
        return [
            'B' => 27,
            'C' => 27,
            'D' => 13,
            'E' => 25,
            'F' => 27,
            'K' => 35,
            'L' => 20,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true]],
           
        ];
    }
}
