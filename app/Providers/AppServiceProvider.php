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
        View::share('statusClasses', [
            'pending rg' => 'bg-yellow-100 text-yellow-800',
            'already rg' => 'bg-green-100 text-green-800',
            'po partial' => 'bg-blue-100 text-blue-800',
            'cancel' => 'bg-red-100 text-red-800',
            'default' => 'bg-gray-100 text-gray-800',
        ]);

        View::composer('*',function($view){
            $view->with("userdata",Auth::user());
        });

        Paginator::useBootstrapFive();
    }
}
