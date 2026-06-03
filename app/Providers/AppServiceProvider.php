<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*',function($view){
            $view->with("userdata",Auth::user());


            $statusClasses = [

                'pending rg' => 'bg-yellow-100 text-yellow-800',
                'already rg' => 'bg-red-100 text-red-800',
                'po partial' => 'bg-blue-100 text-blue-800',
                // 'rejected' => 'bg-red-100 text-red-800',
                // 'draft' => 'bg-gray-100 text-gray-800',
            ];
            $view->with('statusClasses',$statusClasses);
        });

        Paginator::useBootstrapFive();
    }
}
