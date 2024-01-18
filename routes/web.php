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
            route::get('car_info/{id}','car')->name('car');
            route::get('receive_goods/{id}','receive_goods')->name('receive_goods');
            route::get('view_goods/{id}','view_goods')->name('view_goods');
            route::get('user','user')->name('user');
            route::get('create_user','create_user')->name('create_user');
            route::get('edit_user/{id}','edit_user')->name('edit_user');

            route::post('car_info','store_car_info')->name('store_car_info');
            route::post('doc_info','store_doc_info')->name('store_doc_info');
            route::post('store_user','store_user')->name('store_user');
            route::post('update_user','update_user')->name('update_user');

            //ajax
            route::post('search_doc','search_doc')->name('search_doc');
            route::post('barcode_scan','barcode_scan')->name('barcode_scan');
            route::post('confirm_btn','confirm')->name('confirm');
            // route::get('edit_goods/{id}','edit_goods')->name('edit_goods');
            route::get('finish_goods/{id}','finish_goods')->name('finish_goods');
            route::get('get_driver_info/{id}','driver_info');
            route::post('active_user','active_user')->name('active_user');
            route::post('del_user','del_user')->name('del_user');
            route::post('del_doc','del_doc')->name('del_doc');
            route::post('del_exceed','del_exceed')->name('del_exceed');
        });
});
