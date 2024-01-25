<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Branch;
use App\Models\Product;
use App\Models\Document;
use App\Models\DriverInfo;
use App\Models\GoodsReceive;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function product_list()
    {
        $product = [];

        if(request('search_data') && !request('search'))
        {
            return back()->with('error','Please Choose Search Method');
        }
        if(request('search') == 'main_no')
        {
            $main   = GoodsReceive::where('document_no',request('search_data'))->first();
            $doc    = Document::where('received_goods_id',$main->id)->pluck('id');
            $product = Product::whereIn('document_id',$doc)->pluck('id');
            // dd($main);
        }
        else if(request('search') == 'document_no')
        {
            $doc    = Document::where('document_no',request('search_data'))->first();
            $product= Product::where('document_id',$doc->id)->pluck('id');
        }
        $report = 'product';
        $url    = 'product_list';
        $product = Product::when((request('search') != 'main_no' || request('search') != 'document_no' || request('search')) != 'product_code' && !request('search_data') , function($q){
                            $q->whereYear('created_at',Carbon::now()->format('Y'))
                            ->whereMonth('created_at',Carbon::now()->format('m'));
        })
                            ->when((request('search') == 'main_no' || request('search')) == 'document_no' && request('search_data'),function($q) use($product){
                                $q->whereIn('id',$product);
                            })
                            ->when(request('search') == 'product_code' && request('search_data'),function($q){
                                $q->where('barcode',request('product_code'));
                            })
                            ->paginate(15);

        return view('user.report.report',compact('report','product','url'));
    }

    public function finished_documents()
    {
        $report = 'finish';
        $url    = 'finished_documents';

        if(request('search') && !request('search_data'))
        {
            return back()->with('error','Please add search data');
        }else if(!request('search') && request('search_data')){
            return back()->with('error','Please add search method');
        }
        $ids=[];
        if(request('search') == 'truck_no' || request('search') == 'driver_name'){
            $ids = DriverInfo::where(request('search'),request('search_data'))->pluck('received_goods_id');
        }
        $data = GoodsReceive::when(request('search') == 'document_no' && request('search_data'),function($q){
                            $q->where('document_no',request('search_data'));
        })
                            ->when(request('search') != 'document_no' && request('search_data'),function($q) use($ids){
                                $q->whereIn('id',$ids);
                            })
                            ->when(request('branch'),function($q){
                                $q->where('branch_id',request('branch'));
                            })
                            ->when(request('status'),function($q){
                                $q->where('status',request('status'));
                            })
                            ->when(request('from_date'),function($q){
                                $q->where('start_date','>=',request('from_date'));
                            })
                            ->when(request('to_date'),function($q){
                                $q->where('start_date','<=',request('to_date'));
                            })
                            ->when(!request('search') || !request('search_data') || !request('branch') || !request('status') || !request('from_date') || !request('to_date') , function($q){
                                $q->whereYear('created_at',Carbon::now()->format('Y'))
                                ->whereMonth('created_at',Carbon::now()->format('m'));
            })
                            ->whereNotNull('total_duration')
                            ->where('status','complete')
                            ->orderBy('created_at','desc')
                            ->paginate(15);

        $branch = Branch::get();
        return view('user.report.report',compact('report','data','branch','url'));
    }

    public function truck_list()
    {
        dd('yes');
    }
}
