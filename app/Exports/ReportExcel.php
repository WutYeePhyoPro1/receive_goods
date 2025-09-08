<?php

namespace App\Exports;

use App\Models\AddProductTrack;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Booking;
use App\Models\Product;
use App\Models\Document;
use App\Models\Tracking;
use App\Models\DriverInfo;
use App\Models\printTrack;
use App\Models\RemoveTrack;
use App\Models\GoodsReceive;
use Illuminate\Support\Facades\DB;
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
        $data = get_branch_truck();
        $truck_id   = $data[0];
        $loc        = $data[1];
        $reg        = $data[2];
        $user_branch    = getAuth()->branch_id;
        $mgld_dc        = [17,19,20];

        if($this->filter['report'] == 'product')
        {
            $product = [];
            $doc = Document::whereIn('received_goods_id',$reg)->pluck('id');
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

            if(!isset($this->filter['search']) && !isset($this->filter['from_date']) &&  !isset($this->filter['to_date'])  && !isset($this->filter['search_data'])){
                $pd_ids = Product::whereIn('document_id',$doc)->pluck('id');

                $product = Tracking::with('product')->whereIn('product_id',$pd_ids)
                                    ->whereIn('driver_info_id',$truck_id)
                                    ->whereDate('created_at',Carbon::today())
                                    ->get();
            }else{
                $product = Product::when(!isset($this->filter['search']) && !isset($this->filter['from_date']) &&  !isset($this->filter['to_date'])  && !isset($this->filter['search_data']) , function($q){
                    $q->whereDate('created_at',Carbon::today());
})
                    ->when( isset($this->filter['search']) && ($this->filter['search'] == 'main_no' || $this->filter['search'] == 'document_no') && $this->filter['search_data'],function($q) use($product){
                        $q->whereIn('id',$product);
                    })
                    ->when( isset($this->filter['search']) && $this->filter['search'] == 'product_code' && $this->filter['search_data'],function($q){
                        $q->where('bar_code',$this->filter['search_data']);
                    })
                    ->when(isset($this->filter['from_date']),function($q){
                        $q->where('created_at','>=',$this->filter['from_date']);
                    })
                    ->when(isset($this->filter['to_date']),function($q){
                        $q->where('created_at','<=',$this->filter['to_date']);
                    })
                    ->get();
            }



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
                                    $q->where('start_date','>=',$this->filter['from_date']);
                                })
                                ->when(isset($this->filter['to_date']),function($q){
                                    $q->where('start_date','<=',$this->filter['to_date']);
                                })
                                ->when(!isset($this->filter['search']) && !isset($this->filter['from_date']) &&  !isset($this->filter['to_date']) && !isset($this->filter['branch']) && !isset($this->filter['search_data']) , function($q){
                                    $q->whereDate('updated_at',Carbon::today());
                })
                                ->when($loc =='dc',function($q) use($mgld_dc){
                                    $q->whereIn('branch_id',$mgld_dc);
                                })
                                ->when($loc == 'other',function($q) use ($user_branch){
                                    $q->where('branch_id',$user_branch);
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

        $truck  = Driverinfo::when(!isset($this->filter['search']) && !isset($this->filter['search_data'])  && !isset($this->filter['from_date']) &&  !isset($this->filter['to_date'])  && !isset($this->filter['gate']) , function($q){
                            $q->whereDate('created_at',Carbon::today());
                        })
                            ->when(isset($this->filter['search']) && ($this->filter['search'] == 'main_no' || $this->filter['search'] == 'product_code' || $this->filter['search'] == 'truck_no' || $this->filter['search'] == 'driver_name') && $this->filter['search_data'],function($q) use($truck){
                                $q-> whereIn('id', $truck);
                        })
                            ->when(isset($this->filter['gate']),function($q){
                                $q-> where('gate',$this->filter['gate']);
                            })
                            ->when(isset($this->filter['from_date']),function($q){
                                $q->where('created_at','>=',$this->filter['from_date']);
                            })
                            ->when(isset($this->filter['to_date']),function($q){
                                $q->where('created_at','<=',$this->filter['to_date']);
                            })
                            ->when($loc != 'ho',function($q) use($truck_id){
                                $q->whereIn('id',$truck_id);
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

        $data = RemoveTrack::when(!isset($this->filter['search']) && !isset($this->filter['from_date']) &&  !isset($this->filter['to_date']) && !isset($this->filter['search_data']),function($q)
                            {
                                $q->whereDate('created_at',Carbon::today());
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
                            ->when(isset($this->filter['from_date']),function($q){
                                $q->where('created_at','>=',$this->filter['from_date']);
                            })
                            ->when(isset($this->filter['to_date']),function($q){
                                $q->where('created_at','<=',$this->filter['to_date']);
                            })
                            ->get();

            $all = $data;
        }elseif($report == 'po_to')
        {


                $no = [];
                $doc = [];

                if(isset($this->filter['search']))
                {
                    if($this->filter['search'] == 'main_no' && $this->filter['search_data'])
                    {
                        $document   = GoodsReceive::where('document_no',request('search_data'))->first();
                        if($document)
                        {
                            $no       = Document::where('received_goods_id',$document->id)->pluck('id');
                        }
                    elseif($this->filter['search'] == 'product_code' && $this->filter['search_data'])
                    {
                        $id = Product::where('bar_code',request('search_data'))->first();
                        $doc = Document::where('id',$id->document_id)->first();
                    }
                    }

                }
                    $docs = Document::when(!isset($this->filter['search']) && !isset($this->filter['from_date']) &&  !isset($this->filter['to_date']) && !isset($this->filter['search_data']),function($q)
                                    {
                                        $q->whereDate('created_at',Carbon::today());
                                    })
                                    ->when(isset($this->filter['search']) && $this->filter['search'] == 'main_no' && $this->filter['search_data'],function($q) use($no) {

                                        $q->whereIn('id',$no);
                                    })
                                    ->when(isset($this->filter['search']) && $this->filter['search'] == 'product_code' && $this->filter['search_data'],function($q) use($doc) {

                                        $q->where('id' , $doc->id);
                                    })
                                    ->when(isset($this->filter['search']) && $this->filter['search'] == 'document_no' && $this->filter['search_data'],function($q) {

                                        $q->where('document_no' , request('search_data'));
                                    })
                                    ->when(request('from_date'),function($q){
                                        $q->whereDate('created_at', '>=', request('from_date'));
                                    })
                                    ->when(request('to_date'),function($q){
                                        $q->whereDate('created_at','<=',request('to_date'));
                                    })
                                    ->whereIn('received_goods_id',$reg)
                                    ->get();
                                    $all = $docs;

                                $all = $docs;
    }elseif($report == 'shortage')
    {
        $pd_ids = [];

        if(isset($this->filter['search']))
        {
            if($this->filter['search'] == 'main_no' && $this->filter['search_data'])
            {
                $document   = GoodsReceive::where('document_no',$this->filter['search_data'])->where('status','complete')->first();
                if($document)
                {
                    $no         = Document::where('received_goods_id',$document->id)->pluck('id');
                    $pd_ids     = Product::whereIn('document_id',$no)->where(DB::raw('qty'),'>',DB::raw('scanned_qty'))->pluck('id');
                }
            }elseif($this->filter['search'] == 'document_no' && $this->filter['search_data'])
            {
                $doc = Document::where('document_no',$this->filter['search_data'])->first();
                $pd_ids = Product::where('document_id',$doc->id)->pluck('id');
            }
        }


        $reg        = GoodsReceive::where('status','complete')
                                        ->when($loc =='dc',function($q) use($mgld_dc){
                                            $q->whereIn('branch_id',$mgld_dc);
                                        })
                                        ->when($loc == 'other',function($q) use($user_branch){
                                            $q->where('branch_id',$user_branch);
                                        });
        $reg_ids        = $reg->pluck('id');
        $document_ids   = Document::whereIn('received_goods_id',$reg_ids)->pluck('id');

        $data   = Product::when(!isset($this->filter['search']) && !isset($this->filter['from_date']) &&  !isset($this->filter['to_date']) && !isset($this->filter['search_data']),function($q) use($reg)
                        {
                            $tdy_reg = $reg->whereDate('updated_at',Carbon::today())->pluck('id');
                            $tdy_doc = Document::whereIn('received_goods_id',$tdy_reg)->pluck('id');
                            $q->whereIn('document_id',$tdy_doc);
                        })
                        ->when(isset($this->filter['search']) && $this->filter['search'] != 'product_code' && $this->filter['search_data'],function($q) use($pd_ids) {

                            $q->whereIn('id',$pd_ids);
                        })
                        ->when(isset($this->filter['search']) && $this->filter['search'] == 'product_code' && $this->filter['search_data'],function($q){

                            $q->where('bar_code' , request('search_data'));
                        })
                        ->when(isset($this->filter['action']) && $this->filter['action'] == 'excess',function($q){

                            $q->where(DB::raw("qty"),'<',DB::raw('scanned_qty'));
                        })
                        ->when(isset($this->filter['action']) && $this->filter['action'] == 'shortage',function($q){
                            $q->where(DB::raw("qty"),'>',DB::raw('scanned_qty'));
                        })
                        ->when(request('from_date'),function($q){
                            $q->whereDate('created_at', '>=', request('from_date'));
                        })
                        ->when(request('to_date'),function($q){
                            $q->whereDate('created_at','<=',request('to_date'));
                        })
                            ->whereIn('document_id',$document_ids)
                            ->where(DB::raw("qty"),'!=',DB::raw('scanned_qty'))
                            ->get();

        $all = $data;
    }elseif($report == 'print')
    {
        $reg        = GoodsReceive::where('status','complete')
                                ->when($loc =='dc',function($q) use($mgld_dc){
                                    $q->whereIn('branch_id',$mgld_dc);
                                })
                                ->when($loc == 'other',function($q) use($user_branch){
                                    $q->where('branch_id',$user_branch);
                                });
        $reg_ids        = $reg->pluck('id');
        $document_ids   = Document::whereIn('received_goods_id',$reg_ids)->pluck('id');
        $pd_ids = Product::whereIn('document_id',$document_ids)->pluck('id');

        if(isset($this->filter['search']))
        {
            if($this->filter['search'] == 'main_no' && $this->filter['search_data'])
            {
                $document   = GoodsReceive::where('document_no',$this->filter['search_data'])->where('status','complete')->first();
                if($document)
                {
                    $no         = Document::where('received_goods_id',$document->id)->pluck('id');
                    $pd_ids     = Product::whereIn('document_id',$no)->where(DB::raw('qty'),'>',DB::raw('scanned_qty'))->pluck('id');
                }
            }elseif($this->filter['search'] == 'document_no' && $this->filter['search_data'])
            {
                $doc = Document::where('document_no',$this->filter['search_data'])->first();
                $pd_ids = Product::where('document_id',$doc->id)->pluck('id');
            }elseif($this->filter['search'] == 'product_code' && $this->filter['search_data'])
            {
               $pd_ids = Product::where('bar_code',$this->filter['search_data'])->pluck('id');
            }
        }


        $data   = printTrack::when(!isset($this->filter['search']) && !isset($this->filter['from_date']) &&  !isset($this->filter['to_date']) && !isset($this->filter['search_data']),function($q)
                        {
                            $q->whereDate('created_at',Carbon::today());
                        })

                        ->when(request('from_date'),function($q){
                            $q->whereDate('created_at', '>=', request('from_date'));
                        })
                        ->when(request('to_date'),function($q){
                            $q->whereDate('created_at','<=',request('to_date'));
                        })
                        ->whereIn('product_id',$pd_ids)
                        ->get();


        $all = $data;
    }
    elseif($report == 'man_add')
    {
        $pd_ids = [];

        if(isset($this->filter['search']))
        {
            if($this->filter['search'] == 'main_no' && $this->filter['search_data'])
            {
                $document   = GoodsReceive::where('document_no',$this->filter['search_data'])->where('status','complete')->first();
                if($document)
                {
                    $no         = Document::where('received_goods_id',$document->id)->pluck('id');
                    $pd_ids     = Product::whereIn('document_id',$no)->where(DB::raw('qty'),'>',DB::raw('scanned_qty'))->pluck('id');
                }
            }elseif($this->filter['search'] == 'document_no' && $this->filter['search_data'])
            {
                $doc = Document::where('document_no',$this->filter['search_data'])->first();
                $pd_ids = Product::where('document_id',$doc->id)->pluck('id');
            }elseif($this->filter['search'] == 'product_code' && $this->filter['search_data'])
            {
               $pd_ids = Product::where('bar_code',$this->filter['search_data'])->pluck('id');
            }
        }

        $data   = AddProductTrack::when(!isset($this->filter['search']) && !isset($this->filter['from_date']) &&  !isset($this->filter['to_date']) && !isset($this->filter['search_data']),function($q)
                            {
                                $q->whereDate('created_at',Carbon::today());
                            })
                            ->when(isset($this->filter['search']) && $this->filter['search'] && $this->filter['search_data'],function($q) use($pd_ids) {

                                $q->whereIn('product_id',$pd_ids);
                            })
                            ->when(request('from_date'),function($q){
                                $q->whereDate('created_at', '>=', request('from_date'));
                            })
                            ->when(request('to_date'),function($q){
                                $q->whereDate('created_at','<=',request('to_date'));
                            })
                            ->whereNotNull('product_id')
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
