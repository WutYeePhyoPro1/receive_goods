<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Product;
use App\Models\Document;
use App\Models\DriverInfo;
use App\Models\RemoveTrack;
use App\Models\GoodsReceive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class authenticateController extends Controller
{
    public function login(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'employee_code' => 'required',
            'password' => 'required',
        ]);

        if(Auth::attempt(['employee_code'=>$request->employee_code,'password'=>$request->password,'active'=>1])){
            // dd('yes');
            $request->session()->regenerate();

            return redirect()->intended('home');
        }
        // dd('no');
        return back()->withErrors([
            'employee_code' => 'The credentials do not match.',
        ])->onlyInput('employee_code');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        if ($request->hasSession()) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return redirect('/');
    }

    public function home()
    {
        $products   = Product::whereDate('created_at',Carbon::today())->sum('scanned_qty');
        $docs       = GoodsReceive::where('start_date',Carbon::today())->count();
        $com_doc    = GoodsReceive::where('status','complete')->whereDate('updated_at',Carbon::today())->count();
        $cars       = DriverInfo::whereDate('created_at',Carbon::today())->count();
        $del        = RemoveTrack::whereDate('created_at',Carbon::today())->sum('remove_qty');
        $po         = Document::whereDate('created_at',Carbon::today())->count();

        $fin_reg    = GoodsReceive::where('status','complete')
                                    ->whereDate('updated_at',Carbon::today())
                                    ->pluck('id');
        $fin_docs   = Document::whereIn('received_goods_id',$fin_reg)->pluck('id');
        $shortage   = Product::select(DB::raw('Floor(qty-scanned_qty) as sub'),'*')
                            ->whereIn('document_id',$fin_docs)
                            ->where(DB::raw('qty'),'>',DB::raw('scanned_qty'))
                            ->get();
        $shortage   = $shortage->sum('sub');
        return view('user.home',compact('products','docs','cars','del','com_doc','po','shortage'));
    }
}
