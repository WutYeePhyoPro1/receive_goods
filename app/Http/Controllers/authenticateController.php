<?php

namespace App\Http\Controllers;

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
        return view('user.home');
    }
}
