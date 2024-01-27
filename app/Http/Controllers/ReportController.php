<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Models\User;
use App\Models\Branch;
use App\Models\CarGate;
use App\Models\Product;
use App\Models\Document;
use App\Models\Tracking;
use App\Models\DriverInfo;
use App\Models\RemoveTrack;
use App\Exports\ReportExcel;
use App\Models\GoodsReceive;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Symfony\Component\CssSelector\Node\FunctionNode;

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
            if($main)
            {
                $doc    = Document::where('received_goods_id',$main->id)->pluck('id');
                $product = Product::whereIn('document_id',$doc)->pluck('id');
            }
            // dd($main);
        }
        else if(request('search') == 'document_no')
        {
            $doc    = Document::where('document_no',request('search_data'))->first();
            if($doc){
                $product= Product::where('document_id',$doc->id)->pluck('id');
            }
        }
        $report = 'product';
        $url    = 'product_list';
        $product = Product::when((request('search') != 'main_no' || request('search') != 'document_no' || request('search')) != 'product_code' && !request('search_data') , function($q){
                                $q->whereYear('created_at',Carbon::now()->format('Y'))
                                ->whereMonth('created_at',Carbon::now()->format('m'));
        })
                            ->when((request('search') == 'main_no' || request('search') == 'document_no') && request('search_data'),function($q) use($product){
                                $q->whereIn('id',$product);
                            })
                            ->when(request('search') == 'product_code' && request('search_data'),function($q){
                                $q->where('bar_code',request('search_data'));
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
        $report = 'truck';
        $url    = 'truck_list';

        if(request('search') && !request('search_data'))
        {
            return back()->with('error','Please add search data');
        }else if(!request('search') && request('search_data')){
            return back()->with('error','Please add search method');
        }

        $truck = [];
        if(request('search') == 'main_no' && request('search_data'))
        {
            $main = GoodsReceive::where('document_no',request('search'))->first();
            if($main)
            {
                $truck= DriverInfo::where('received_goods_id',$main->id)->pluck('id');
            }
        }elseif(request('search') == 'product_code' && request('search_data'))
        {

            $product = Product::where('bar_code',request('search_data'))->pluck('id');
            if($product)
            {
                $truck   = Tracking::whereIn('product_id',$product)->pluck('driver_info_id');
            }
        }elseif((request('search') == 'truck_no' || request('search') == 'driver_name') && request('search_data'))
        {
            $truck = DriverInfo::where(request('search'),request('search_data'))->pluck('id');
        }


        $truck  = Driverinfo::when(!request('search') || !request('search_data') || !request('gate') , function($q){
                                $q->whereYear('created_at',Carbon::now()->format('Y'))
                                ->whereMonth('created_at',Carbon::now()->format('m'));
                        })
                            ->when((request('search') == 'main_no' || request('search') == 'product_code' || request('search') == 'truck_no' || request('search') == 'driver_name') && request('search_data'),function($q) use($truck){
                                $q-> whereIn('id', $truck);
                        })
                            ->when(request('gate'),function($q){
                                $q-> where('gate',request('gate'));
                            })

                            ->paginate(15);

        $branch = Branch::get();
        $gate   = CarGate::get();
        return view('user.report.report',compact('report','truck','branch','gate','url'));
    }

    public function remove_list()
    {
        $report = 'remove';
        $url = 'remove_list';

        if(request('search') && !request('search_data'))
        {
            return back()->with('error','Please add search data');
        }else if(!request('search') && request('search_data')){
            return back()->with('error','Please add search method');
        }
        $no = [];
        $product = '';
        $user    = '';
        if(request('search') == 'main_no' && request('search_data'))
        {
            $document   = GoodsReceive::where('document_no',request('search_data'))->first();
            if($document)
            {
                $no       = RemoveTrack::where('received_goods_id',$document->id)->pluck('id');
            }
        }elseif(request('search') == 'product_code' && request('search_data'))
        {
            $product    = Product::where('bar_code',request('search_data'))->pluck('id');
        }elseif(request('search') == 'user' && request('search_data'))
        {
            $user   = User::where('name',request('search_data'))->first();
        }

        $data = RemoveTrack::when(!request('search') ,function($q)
                            {
                                $q->whereYear('created_at',Carbon::now()->format('Y'))
                                ->whereMonth('created_at',Carbon::now()->format('m'));
                            })
                            ->when(request('search') == 'main_no' && request('search_data') , function($q) use($no){
                                $q->whereIn('id',$no);
                            })
                            ->when(request('search') == 'product_code' && request('search_data'),function($q) use($product){
                                $q->where('proudct_id',$product);
                            })
                            ->when(request('search') == 'user' && request('search_data'),function($q) use($user){
                                $q->where('user_id',$user);
                            })
                            ->paginate(15);

        return view('user.report.report',compact('data','report','url'));
    }

    public function detail_doc($id)
    {
        $reg        = GoodsReceive::where('id',$id)->first();
        $document   = Document::where('received_goods_id',$id)->get();
        $driver     = DriverInfo::where('received_goods_id',$id)->get();
        return view('user.report.detail_report',compact('reg','document','driver'));
    }

    public function excel_export(Request $request)
    {
        // dd($request->all());
        $date = Carbon::now()->format('Ymd');
        $search = $request->except('_token');

        return Excel::download(new ReportExcel($search),"reg$date.xlsx");
    }

    public function product_pdf($id)
    {
        $docs = Document::where('received_goods_id',$id)->pluck('id');
        $data = Product::whereIn('document_id',$docs)->get();
        $doc_no = GoodsReceive::where('id',$id)->first();
        $date = Carbon::now()->format('Ymd');
        view()->share(['data'=>$data]);
        $pdf = PDF::loadView('user.exports.product_pdf', compact('data'));
        return $pdf->stream("$doc_no->document_no.$date.pdf");
        // return view('user.exports.product_pdf',compact('data'));
    }
}
