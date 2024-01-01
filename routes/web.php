<?php

use App\Http\Controllers\authenticateController;
use App\Http\Controllers\userController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('login', function () {
    return view('login');
})->middleware('guest');

Route::redirect('/','login');

route::group(['controller'=>authenticateController::class],function(){
    route::post('login','login')->name('login');
    route::post('logout','logout')->name('logout');
});

Route::middleware(['auth:sanctum'])->group(function () {
        // route::group(['controller'=>])
        route::get('/home',[authenticateController::class,'home'])->name('home');

        route::group(['controller'=>userController::class],function(){
            route::get('list','list')->name('list');
            route::get('car_info','car_info')->name('car_info');
            route::get('receive_goods/{id}','receive_goods')->name('receive_goods');

            route::post('car_info','store_car_info')->name('store_car_info');
        });
});
