<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Log;
use App\Models\Product;
use App\Models\Document;
use App\Models\Tracking;
use App\Customize\Common;
use App\Models\AddProductTrack;
use App\Models\DriverInfo;
use App\Models\RemoveTrack;
use App\Models\GoodsReceive;
use App\Models\printTrack;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class authenticateController extends Controller
{
    public function login(Request $request)
    {
        // dd(route('home'));
        $request->validate([
            'employee_code' => 'required',
            'password' => 'required',
        ]);

        if(Auth::attempt(['employee_code'=>$request->employee_code,'password'=>$request->password,'status'=>1])){

            Common::Log(route('home'),"LogIn");
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
        Common::Log(route('logout'),"LogOut");

        Auth::logout();
        if ($request->hasSession()) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return redirect('/');
    }

    public function home()
    {
        $user_branch    = getAuth()->branch_id;
        $mgld_dc        = [17,19,20];
        $data = get_branch_truck();
        $truck_id   = $data[0];
        $loc        = $data[1];
        $reg        = $data[2];
        $product_ids   = Tracking::when($loc != 'ho',function($q) use($truck_id){
            $q->whereIn('driver_info_id',$truck_id);
                })
            ->pluck('product_id');
        $products   = Tracking::when($loc != 'ho',function($q) use($truck_id){
                            $q->whereIn('driver_info_id',$truck_id);
                                })
                            ->whereDate('created_at',Carbon::today())->sum('scanned_qty');
        $docs       = GoodsReceive::where('start_date',Carbon::today())->count();
        $com_doc    = GoodsReceive::where('status','complete')
                                    ->when($loc =='dc',function($q) use($mgld_dc){
                                        $q->whereIn('branch_id',$mgld_dc);
                                    })
                                    ->when($loc == 'other',function($q) use($user_branch){
                                        $q->where('branch_id',$user_branch);
                                    })
                                    ->whereDate('updated_at',Carbon::today())->count();
        $cars       = DriverInfo::when($loc != 'ho',function($q) use($truck_id){
                                $q->whereIn('id',$truck_id);
                                    })
                                ->whereDate('created_at',Carbon::today())
                                ->count();
        $del        = RemoveTrack::when($loc != 'ho',function($q) use($reg){
                                $q->whereIn('received_goods_id',$reg);
                                    })
                                ->whereDate('created_at',Carbon::today())
                                ->sum('remove_qty');
        $po         = Document::whereDate('created_at',Carbon::today())
                                ->whereIn('received_goods_id',$reg)
                                ->count();

        $fin_reg    = GoodsReceive::where('status','complete')
                                    ->when($loc =='dc',function($q) use($mgld_dc){
                                        $q->whereIn('branch_id',$mgld_dc);
                                    })
                                    ->when($loc == 'other',function($q) use($user_branch){
                                        $q->where('branch_id',$user_branch);
                                    })
                                    ->whereDate('updated_at',Carbon::today())
                                    ->pluck('id');
          
        $fin_docs   = Document::whereIn('received_goods_id',$fin_reg)->pluck('id');
        $shortage   = Product::select(DB::raw('Floor(qty-scanned_qty) as sub'),'*')
                            ->whereIn('document_id',$fin_docs)
                            ->where(DB::raw('qty'),'>',DB::raw('scanned_qty'))
                            ->get();
        $shortage   = $shortage->sum('sub');
        $print      = printTrack::whereDate('created_at',Carbon::today())
                                ->whereIn('product_id',$product_ids)
                                ->sum('quantity');
        $non_scan   = AddProductTrack::whereDate('created_at',Carbon::today())
                                        ->whereIn('truck_id',$truck_id)
                                        ->whereNotNull('product_id')
                                        ->sum('added_qty');
        return view('user.home',compact('products','docs','cars','del','com_doc','po','shortage','print','non_scan'));
    }
}
