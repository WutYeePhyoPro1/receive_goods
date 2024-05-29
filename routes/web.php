<?php

use App\Http\Controllers\ActionController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\authenticateController;
use App\Http\Controllers\ReportController;
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
            route::get('join_receive/{id}/{car}','join_receive')->name('join_receive');
            route::get('view_goods/{id}','view_goods')->name('view_goods');

            route::post('car_info','store_car_info')->name('store_car_info');
            route::post('doc_info','store_doc_info')->name('store_doc_info');

            //ajax
            // route::get('edit_goods/{id}','edit_goods')->name('edit_goods');

            route::get('get_driver_info/{id}','driver_info');
            route::post('active_user','active_user')->name('active_user');

            route::post('del_doc','del_doc')->name('del_doc');

            route::post('add_product_qty','add_product_qty')->name('add_product_qty');
            route::post('search_car','search_car')->name('search_car');
            route::post('get_car','get_car')->name('get_car');
            route::get('/search_document_no','search_document_no')->name('document_no_search');
        });

        route::group(['controller'=>ReportController::class],function(){
            route::get('product_list','product_list')->name('product_list');
            route::get('finished_documents','finished_documents')->name('finished_documents');
            route::get('truck_list','truck_list')->name('truck_list');
            route::get('remove_list','remove_list')->name('remove_list');
            route::get('po_to_list','po_to_list')->name('po_to_list');
            route::get('shortage_list','shortage_list')->name('shortage_list');
            route::get('print_list','print_list')->name('print_list');
            route::get('man_add','man_add')->name('man_add');

            route::get('detail_doc/{id}','detail_doc')->name('detail_doc');
            route::get('detail_truck/{id}','detail_truck')->name('detail_truck');
            route::get('detail_document/{id}','detail_document')->name('detail_document');

            route::get('Scan_count/{id}','Scan_count')->name('Scan_count');

            route::get('excel_view','excel_view')->name('excel_view');
            route::get('detail_excel_export/{id}/{action}','detail_excel_export')->name('detail_excel_export');

            route::get('product_pdf/{id}','product_pdf')->name('product_pdf');
            route::get('truck_detail_pdf/{id}','truck_detail_pdf')->name('truck_detail_pdf');
            route::get('document_detail_pdf/{id}','document_detail_pdf')->name('document_detail_pdf');
            route::get('doc_detail_pdf/{id}','doc_detail_pdf')->name('doc_detail_pdf');
            route::get('scan_count_pdf/{id}','scan_count_pdf')->name('scan_count_pdf');

            route::post('excel_export','excel_export')->name('excel_export');
            route::get('complete_doc_print/{id}','complete_doc_print')->name('complete_doc_print');
        });

        route::group(['controller'=>ActionController::class],function(){
            route::post('edit_scan','edit_scan')->name('edit_scan');

            //ajax
            route::post('barcode_scan','barcode_scan')->name('barcode_scan');
            route::post('search_doc','search_doc')->name('search_doc');
            route::post('confirm_btn','confirm')->name('confirm');
            route::get('finish_goods/{id}','finish_goods')->name('finish_goods');
            route::post('del_exceed','del_exceed')->name('del_exceed');

            route::post('pass_vali','pass_vali')->name('pass_vali');
            route::post('ajax/add_product','add_product')->name('add_product');

            route::get('ajax/show_remark/{id}','show_remark');
            route::post('ajax/store_remark','store_remark')->name('store_remark');
            route::get('ajax/get_variable/{code}','get_variable');

            route::post('ajax/show_image','show_image')->name('show_image');
            route::get('start_count/{id}','start_count');
            route::post('print_track','print_track')->name('print_track');

            route::get('change_branch/{id}','change_branch');
        });

        route::group(['controller'=>AdminController::class],function(){

            route::post('ajax/del_reg','del_reg')->name('del_reg');
            route::get('edit/{id}','edit_reg')->name('edit_reg');
            route::get('get_img/{id}','get_img')->name('get_image');
            route::post('show_one','show_one')->name('show_one');
            route::post('update_image','update_image')->name('update_image');
            route::get('del_one_img/{id}','del_one_img');
            route::get('user','user')->name('user');
            route::get('role','role')->name('role');
            route::get('permission','permission')->name('permission');

            route::get('gate','gate')->name('gate');
            route::get('gate/create','create_gate');
            route::get('gate/edit/{id}','edit_gate');
            route::post('gate/store','store_gate')->name('store_gate');
            route::post('store_user','store_user')->name('store_user');
            route::post('gate/update','update_gate')->name('update_gate');

            route::post('update_user','update_user')->name('update_user');
            route::post('store_role','store_role')->name('store_role');
            route::get('edit_role/{id}','edit_role')->name('edit_role');
            route::post('update_role','update_role')->name('update_role');

            route::post('store_permission','store_permission')->name('store_permission');
            route::get('view_permission/{id}','view_permission')->name('view_permission');

            route::get('user/edit/{id}','edit_user')->name('edit_user');
            route::get('role/create','create_role')->name('create_role');
            route::get('role/edit/{id}','edit_role')->name('edit_role');
            route::get('permission/create','create_permission')->name('create_permission');

            route::get('user/create','create_user')->name('create_user');
            route::get('car_type','car_type')->name('car_type');
            route::get('car_type/create','create_car_type');
            route::get('car_type/edit/{id}','edit_car_type');
            route::post('car_type /store','store_car_type')->name('store_car_type');
            route::post('car_type/update','update_car_type')->name('update_car_type');

            route::post('del','del')->name('del');
        });
});
