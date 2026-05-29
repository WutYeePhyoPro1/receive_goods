<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class R008SController extends Controller
{
    public function create()
    {
        $rg_no = session('rg_no');
        // dd($rg_no);



        return view("r008s.create",compact("rg_no"));
    } 


    public function store(Request $request){
        dd($request);
    }

}
