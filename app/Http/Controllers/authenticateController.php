<?php

namespace App\Http\Controllers;

use App\Models\DriverInfo;
use App\Models\GoodsReceive;
use App\Models\Product;
use App\Models\RemoveTrack;
use Illuminate\Http\Request;
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
        $products   = Product::sum('scanned_qty');
        $docs       = GoodsReceive::count();
        $com_doc       = GoodsReceive::where('status','complete')->count();
        $cars       = DriverInfo::count();
        $del        = RemoveTrack::sum('remove_qty');
        return view('user.home',compact('products','docs','cars','del','com_doc'));
    }
}
