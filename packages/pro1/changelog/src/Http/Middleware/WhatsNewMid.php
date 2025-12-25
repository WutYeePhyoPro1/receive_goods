<?php

namespace Pro1\Changelog\Http\Middleware;

use Closure;
use Pro1\Changelog\Models\WhatsNew;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class WhatsNewMid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {


        if (
            $request->is('login') || $request->is('checkLogin') || $request->is('check')
            || $request->is('register')
            || $request->is('password/*')
            || $request->is('logout') || $request->is('admins/logout') 
            || !auth()->check()
        ) {
            return $next($request);
        }

        if ($request->is('changelogs*') || $request->is('whatsnews*')) {
            return $next($request);
        }

        // Sharepoint use root as dashboard so don't need to forward login
        if(env('PORTAL_ID') != 2){
            if ($request->is('/')) {
                 if (Route::has('admins.login')) {
                return redirect()->route('admins.login');
                } elseif (Route::has('login')) {
                    return redirect()->route('login');
                } else {
                    return redirect('/login');
                }
            }
        }

        if($whatsnew = $this->userwhatnew()){
            return redirect()->route('changelogs.show',$whatsnew->change_log_id);
        }


        return $next($request);
    }

    public function userwhatnew(){
        $user = Auth::user();
        $user_id = $user->id;
        $whatnew = WhatsNew::where("user_id",$user_id)
        ->where('read_at',null)
        ->orderby("id",'desc')
        ->first();

        return $whatnew;
    }
}
