<?php

// use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Pro1\Changelog\Http\Controllers\FAQController;
use Pro1\Changelog\Models\ReturnAndExchangeReject;
use Pro1\Changelog\Http\Controllers\HomeController;
use Pro1\Changelog\Http\Controllers\RoleController;
use Pro1\Changelog\Http\Controllers\UserController;
use Pro1\Changelog\Http\Controllers\BranchController;
use Pro1\Changelog\Http\Controllers\ProductController;
use Pro1\Changelog\Http\Controllers\DocumentController;
use Pro1\Changelog\Http\Controllers\SupplierController;
use Pro1\Changelog\Http\Controllers\WhatsNewsController;
use Pro1\Changelog\Http\Controllers\Auth\LoginController;
use Pro1\Changelog\Http\Controllers\ChangeLogsController;
use Pro1\Changelog\Http\Controllers\DepartmentController;
use Pro1\Changelog\Http\Controllers\PercentageController;
use Pro1\Changelog\Http\Controllers\LocalizationController;
use Pro1\Changelog\Http\Controllers\ImportProductController;
use Pro1\Changelog\Http\Controllers\ChangeLogFilesController;
use Pro1\Changelog\Http\Controllers\SourcingProductController;
use Pro1\Changelog\Http\Controllers\LogisticDocumentController;
use Pro1\Changelog\Http\Controllers\SourcingDocumentController;
use Pro1\Changelog\Http\Controllers\SupplierDocumentController;
use Pro1\Changelog\Http\Controllers\UserChangeLogReadsController;
use Pro1\Changelog\Http\Controllers\ReturnAndExchangeRejectController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::get('/', function () {
    return view('auth.login');
});
Route::get('/user_login/{employee_id}/{password}', [LoginController::class, 'user_login'])->name('user_login');

Auth::routes();

Route::group(['middleware' => ['auth',"whatsnew"]], function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/make_as_read/{notification_id}/{document_id}', [HomeController::class, 'make_as_read'])->name('home.make_as_read');
    Route::get('/see_document/{document_id}/{type}/{notification_id}', [HomeController::class, 'see_document'])->name('home.see_document');
    Route::get('/notifications', [HomeController::class, 'notifications'])->name('notification');
    Route::get('lang/{locale}', [LocalizationController::class, 'index'])->name('lang');

    Route::resource('roles', RoleController::class);
    Route::get('/users/profile', [UserController::class, 'profile'])->name('user.profile');
    Route::post('/users/update_profile', [UserController::class, 'update_profile'])->name('user.update_profile');

    Route::resource('users', UserController::class);

    Route::resource('branches', BranchController::class);
    Route::resource('departments', DepartmentController::class);
    Route::get('/faqs/faqlist', [FAQController::class, 'faqlist'])->name('faqs.faqlist');

    Route::resource('faqs', FAQController::class);

    Route::get('return_and_exchange_rejects/', [ReturnAndExchangeRejectController::class, 'index'])->name('return_and_exchange_rejects.get');
    Route::get('return_and_exchange_rejects/choose-branch',[ReturnAndExchangeRejectController::class,'chooseBranchPage'])->name('return_and_exchange_rejects.choose-branch');
    Route::get('return_and_exchange_rejects/create', [ReturnAndExchangeRejectController::class, 'createPage'])->name('return_and_exchange_rejects.createPage');
    Route::get('return_and_exchange_rejects/details/{id}', [ReturnAndExchangeRejectController::class, 'details'])->name('return_and_exchange_rejects.details');
    Route::post('return_and_exchange_rejects/', [ReturnAndExchangeRejectController::class, 'store'])->name('return_and_exchange_rejects.store');
    Route::post('return_and_exchange_rejects/update', [ReturnAndExchangeRejectController::class, 'update'])->name('return_and_exchange_rejects.update');
    Route::post('return_and_exchange_rejects/add', [ReturnAndExchangeRejectController::class, 'add'])->name('return_and_exchange_rejects.add');
    Route::post('return_and_exchange_rejects/change-status', [ReturnAndExchangeRejectController::class, 'changeStatus'])->name('return_and_exchange_rejects.change-status');
    Route::delete('return_and_exchange_rejects/{id}', [ReturnAndExchangeRejectController::class, 'delete'])->name('return_and_exchange_rejects.delete');

    Route::get('/documents/search_result', [DocumentController::class, 'search_result'])->name('document.search_result');
    Route::get('/documents/document_detail_search_result', [DocumentController::class, 'document_detail_search_result'])->name('document.document_detail_search_result');

    Route::get('/products/get_product_by_id/{id}/{branch}', [ProductController::class, 'get_product_by_id'])->name('get_product_by_id');
    Route::get('/products/product_list_by_document', [ProductController::class, 'product_list_by_document'])->name('document.product_list_by_document');

    Route::get('/documents/bm_approve', [DocumentController::class, 'bm_approve'])->name('document.bm_approve');

    Route::get('/documents/op_approve/', [DocumentController::class,'op_approve'])->name('document.op_approve');

    Route::get('/documents/bm_reject', [DocumentController::class, 'bm_reject'])->name('document.bm_reject');
    Route::get('/documents/bm_supplier_cancel', [DocumentController::class, 'bm_supplier_cancel'])->name('document.bm_supplier_cancel');
    // Route::get('/documents/change_to_return', [DocumentController::class, 'change_to_return'])->name('document.change_to_return');
    Route::get('/documents/change_to_previous_status', [DocumentController::class, 'change_to_previous_status'])->name('document.change_to_previous_status');

    Route::get('/documents/ch_approve', [DocumentController::class, 'ch_approve'])->name('document.ch_approve');
    Route::get('/documents/ch_reject', [DocumentController::class, 'ch_reject'])->name('document.ch_reject');

    Route::get('/documents/mm_approve', [DocumentController::class, 'mm_approve'])->name('document.mm_approve');
    Route::get('/documents/mm_reject', [DocumentController::class, 'mm_reject'])->name('document.mm_reject');
    Route::get('/documents/dc_approve', [DocumentController::class, 'dc_approve'])->name('document.dc_approve');

    Route::get('/documents/acc_reject', [DocumentController::class, 'acc_reject'])->name('document.acc_reject');


    Route::get('/documents/rg_out_complete', [DocumentController::class, 'rg_out_complete'])->name('document.rg_out_complete');

    Route::post('/documents/cn_complete', [DocumentController::class, 'cn_complete'])->name('document.cn_complete');
    Route::post('/documents/check_product_vat_types',[DocumentController::class,'check_product_vat_types'])->name('documents.check_product_vat_types');
    Route::get('/documents/dc_in_complete', [DocumentController::class, 'dc_in_complete'])->name('document.dc_in_complete');

    Route::get('/documents/rg_in_complete', [DocumentController::class, 'rg_in_complete'])->name('document.rg_in_complete');

    Route::post('/documents/db_complete', [DocumentController::class, 'db_complete'])->name('document.db_complete');
    Route::get('/documents/exchange_deducted', [DocumentController::class, 'exchange_deducted'])->name('document.exchange_deducted');

    Route::get('/document_detail_listing', [DocumentController::class, 'document_detail_listing'])->name('document_detail_listing');

    Route::get('/documents/download_pdf/{document_id}', [DocumentController::class, 'download_pdf'])->name('document.download_pdf');
    Route::get('/products/attach_file/{product_id}', [ProductController::class, 'view_product_attach_file'])->name('product.view_product_attach_file');
    Route::get('/documents/excel_export/{document_id}', [DocumentController::class, 'excel_export'])->name('document.excel_export');
    Route::get('/documents/document_export/{from_date?}/{to_date?}/{other?}', [DocumentController::class, 'document_export'])->name('document.document_export');
    Route::get('/documents/pending_document_export/{detail_type}', [DocumentController::class, 'pending_document_export'])->name('document.pending_document_export');
    Route::get('/documents/attach_file/{documnent_id}/{attach_type}', [DocumentController::class, 'view_document_attach_file'])->name('document.view_document_attach_file');
    Route::get('image/{filename}', 'DocumentController@displayImage')->name('image.displayImage');

    Route::get('/documents/{id}/delete', [DocumentController::class, 'destroy'])->name('destroy');

    //ajax
    route::post('/document/ajax/vali_doc',[DocumentController::class,'vali_doc'])->name('doc_vali');
    route::get('/documents/clearSession',[DocumentController::class,'clearSession'])->name('clearSession');

    Route::resource('documents', DocumentController::class);
    Route::get('/documents/{id}/edit', 'DocumentController@edit')->name('documents.edit');

    Route::resource('products', ProductController::class);
    Route::patch('/products/restore/{id}',[ProductController::class,"restore"])->name('products.restore');

    // storedata
    Route::post('products/storedata' , [ProductController::class, 'storedata']);
    Route::post('products/branchstore' , [ProductController::class, 'branchstore']);
    Route::post('products/rgcheck' , [ProductController::class, 'rg_check'])->name('rg_check');
    Route::resource('suppliers', SupplierController::class);
/////logistics & sourcing////
    // Route::get('/logistics_documents/search_result', [LogisticDocumentController::class, 'search_result'])->name('logistics_documents.search_result');
    Route::get('/import_products/improt_product_list_by_document', [ImportProductController::class, 'improt_product_list_by_document'])->name('logistics_document.improt_product_list_by_document');


    Route::get('/logistics_documents/attach_file/{documnent_id}/{attach_type}', [LogisticDocumentController::class, 'view_logistics_attach_file'])->name('logistics_document.view_document_attach_file');
    Route::post('/midea_link_store',[ImportProductController::class, 'midea_link_store'])->name('midea_link_store');
    Route::post('/sourcing_midea_link_store',[SourcingProductController::class, 'sourcing_midea_link_store'])->name('sourcing_midea_link_store');

    Route::get('/logistics_documents/bm_approve', [LogisticDocumentController::class, 'logistics_bm_approve'])->name('logistics_documents.logistics_bm_approve');
    Route::get('/logistics_documents/bm_reject', [LogisticDocumentController::class, 'logistics_bm_reject'])->name('logistics_documents.logistics_bm_reject');
    Route::get('/logistics_documents/logistics_mm_reject', [LogisticDocumentController::class, 'logistics_mm_reject'])->name('logistics_documents.logistics_mm_reject');
    Route::get('/sourcing_documents/sourcing_mm_reject', [SourcingDocumentController::class, 'sourcing_mm_reject'])->name('sourcing_documents.sourcing_mm_reject');
    Route::delete('logistics_destory/{document_id}', [LogisticDocumentController::class, 'logistics_destory'])->name('logistics_destory');

    Route::get('/logistics_ch_approve', [LogisticDocumentController::class, 'logistics_ch_approve'])->name('logistics_documents.logistics_ch_approve');
    Route::get('/account_issue_approve', [LogisticDocumentController::class, 'account_issue_approve'])->name('logistics_documents.account_issue_approve');
    Route::get('/logistics_log_approve', [LogisticDocumentController::class, 'logistics_log_approve'])->name('logistics_documents.logistics_log_approve');
    Route::get('/logistics_log_mm_approve', [LogisticDocumentController::class, 'logistics_log_mm_approve'])->name('logistics_documents.logistics_log_mm_approve');
    Route::get('/logistics_documents/pending_document_export/{document_type}/{detail_type}', [LogisticDocumentController::class, 'pending_document_export'])->name('logistics.pending_document_export');
    Route::get('/sourcing_documents/pending_document_export/{document_type}/{detail_type}', [SourcingDocumentController::class, 'pending_document_export'])->name('sourcing.pending_document_export');

    Route::get('/logistics_documents/ch_reject', [LogisticDocumentController::class, 'logistics_ch_reject'])->name('logistics_documents.logistics_ch_reject');

    Route::get('/finished_document', [LogisticDocumentController::class, 'finished_document'])->name('logistics_documents.finished_document');
    Route::get('/cn_db_complete_document', [LogisticDocumentController::class, 'cn_db_complete_document'])->name('logistics_documents.cn_db_complete_document');
    Route::get('/logistics_listing', [LogisticDocumentController::class, 'logistics_listing'])->name('logistics_listing');
    Route::get('/sourcing_listing', [SourcingDocumentController::class, 'sourcing_listing'])->name('sourcing_listing');

    Route::delete('/import_image_destory/{id}', [ImportProductController::class, 'import_image_destory'])->name('import_image_destory');

    Route::get('/check_image/{id}', [SourcingDocumentController::class, 'check_image'])->name('sourcing_document.check_image');
    Route::get('/check_damage_percentage/{id}', [LogisticDocumentController::class, 'check_damage_percentage'])->name('check_damage_percentage');
    Route::get('/check_log_percentage/{id}', [LogisticDocumentController::class, 'check_log_percentage'])->name('check_log_percentage');

    Route::get('/check_sourcing_percentage/{id}', [SourcingDocumentController::class, 'check_sourcing_percentage'])->name('check_sourcing_percentage');
    Route::get('/check_import_image/{id}', [LogisticDocumentController::class, 'check_import_image'])->name('logistics_documents.check_import_image');
    Route::get('/check_damage_type/{id}', [SourcingDocumentController::class, 'check_damage_type'])->name('check_damage_type');

    Route::get('/sourcing_documents/bm_approve', [SourcingDocumentController::class, 'sourcing_bm_approve'])->name('sourcing_document.sourcing_bm_approve');
    Route::get('/sourcing_documents/bm_reject', [SourcingDocumentController::class, 'sourcing_bm_reject'])->name('sourcing_document.sourcing_bm_reject');
    Route::get('/sourcing_documents/ch_reject', [SourcingDocumentController::class, 'sourcing_ch_reject'])->name('sourcing_documents.sourcing_ch_reject');
    Route::get('/sourcing_documents/mm_reject', [SourcingDocumentController::class, 'mm_reject'])->name('sourcing_documents.mm_reject');
    Route::delete('sourcing_destory/{document_id}', [SourcingDocumentController::class, 'sourcing_destory'])->name('sourcing_destory');

    Route::get('/sourcing_ch_approve', [SourcingDocumentController::class, 'sourcing_ch_approve'])->name('sourcing_document.sourcing_ch_approve');
    Route::get('/sourcing_account_issue_approve', [SourcingDocumentController::class, 'sourcing_account_issue_approve'])->name('sourcing_document.sourcing_account_issue_approve');
    // issued
    Route::get('/sourcing_issued_approve', [SourcingDocumentController::class, 'sourcing_issued_approve'])->name('sourcing_document.sourcing_issued_approve');
    // end
    Route::get('/sourcing_log_approve', [SourcingDocumentController::class, 'sourcing_log_approve'])->name('sourcing_document.sourcing_log_approve');
    Route::get('/sourcing_log_mm_approve', [SourcingDocumentController::class, 'sourcing_log_mm_approve'])->name('sourcing_document.sourcing_log_mm_approve');
    Route::get('/sourcing_finished_document', [SourcingDocumentController::class, 'sourcing_finished_document'])->name('sourcing_document.finished_document');
    Route::get('/accounting_cn_db_complete_document', [SourcingDocumentController::class, 'accounting_cn_db_complete_document'])->name('sourcing_document.accounting_cn_db_complete_document');
    Route::get('/sourcing_cn_complete_document', [SourcingDocumentController::class, 'sourcing_cn_complete_document'])->name('sourcing_cn_complete_document.finished_document');

    Route::get('/logistics_documents/change_to_previous_status', [LogisticDocumentController::class, 'change_to_previous_status'])->name('logistics_documents.change_to_previous_status');
    Route::get('/sourcing_documents/change_to_previous_status', [SourcingDocumentController::class, 'change_to_previous_status'])->name('sourcing_documents.change_to_previous_status');
    Route::get('/sourcing_documents/change_to_sourcing_approved_status', [SourcingDocumentController::class, 'change_to_sourcing_approved_status'])->name('sourcing_documents.change_to_sourcing_approved_status');
    Route::get('/import_products/product_image_by_product', [ImportProductController::class, 'product_image_by_product'])->name('products.product_image_by_product');


    Route::get('/update_product_status/{id}', [SourcingProductController::class, 'update_product_status'])->name('update_product_status');
    Route::get('/sourcing_products/product_image_by_product', [SourcingProductController::class, 'product_image_by_product'])->name('sourcings.product_image_by_product');
    Route::get('/sourcing_products/sourcing_product_list_by_document', [SourcingProductController::class, 'sourcing_product_list_by_document'])->name('sourcing_product_list_by_document');
    Route::get('/sourcing_products/update_stock_quantity', [SourcingProductController::class, 'update_stock_quantity'])->name('update_stock_quantity');
    // select product
    Route::get('/sourcing_products/select/{id}', [SourcingProductController::class, 'sourcing_select_product'])->name('sourcing_select');
    Route::get('/sourcing_products/next_step/{id}', [SourcingProductController::class, 'sourcing_next_step']);
    Route::post('/sourcing_products/move_to_other_doc', [SourcingProductController::class, 'move_to_other_doc']);
    //for logistics
    Route::get('/import_products/select/{id}', [ImportProductController::class, 'import_select_product'])->name('import_select');
    Route::get('/import_products/next_step/{id}', [ImportProductController::class, 'import_next_step']);
    Route::post('/import_products/move_to_other_doc', [ImportProductController::class, 'move_to_other_doc']);

    Route::delete('/sourcing_image_destory/{id}', [SourcingProductController::class, 'sourcing_image_destory'])->name('sourcing_image_destory');
    Route::delete('/sourcing_product_destory/{id}', [SourcingProductController::class, 'sourcing_product_destory'])->name('sourcing_product_destory');
    Route::delete('/import_product_destory/{id}', [ImportProductController::class, 'import_product_destory'])->name('import_product_destory');

    Route::get('/logistics_documents/logistics_search_result', [LogisticDocumentController::class, 'logistics_search_result'])->name('logistics_search_result');

    // Route::get('/sourcing_documents/search_result', [SourcingDocumentController::class, 'sourcing_search_result'])->name('sourcing_documents.search_result');
    Route::get('/sourcing_documents/sourcing_listing_search_result', [SourcingDocumentController::class, 'sourcing_listing_search_result'])->name('sourcing_listing_search_result');
    Route::get('/sourcing_documents/attach_file/{documnent_id}/{attach_type}', [SourcingDocumentController::class, 'view_logistics_attach_file'])->name('sourcing_document.view_document_attach_file');

    Route::get('/sourcing_documents/{id}/select_product', [SourcingDocumentController::class, 'select_product'])->name('sourcing_documents.select_product');
    Route::get('/sourcing_documents/{id}/test', [SourcingDocumentController::class, 'test'])->name('sourcing_documents.test');
    Route::post('/sourcing_documents/move_to_other_doc', [SourcingDocumentController::class, 'move_to_other_doc'])->name('sourcing_documents.move_to_other_doc');
    Route::get('/sourcing_products/delete/{id}', [SourcingProductController::class, 'delete']);

    Route::get('/download_import_userguide', [HomeController::class, 'getDownload'])->name('getDownload');
    Route::get('/download_excel_file/{documnent_id}', [LogisticDocumentController::class, 'download_excel_file'])->name('download_excel_file');
    Route::get('/sourcing_download_excel_file/{documnent_id}', [SourcingDocumentController::class, 'sourcing_download_excel_file'])->name('sourcing_download_excel_file');

    Route::resource('logistics_documents', LogisticDocumentController::class);
    Route::get('logistics_documents/', [LogisticDocumentController::class,'new_index'])->name('logistics_documents.index');

    Route::get('/logistics_documents/{id}/select_product', [LogisticDocumentController::class, 'select_product'])->name('logistics_documents.select_product');

    Route::get('/import_products/update_stock_quantity', [ImportProductController::class, 'update_stock_quantity'])->name('update_stock_quantity');
    Route::get('/import_products/import_product_destory/{id}', [ImportProductController::class, 'import_product_destory']);
    Route::post('/logistics_documents/move_to_other_doc', [LogisticDocumentController::class, 'move_to_other_doc']);
    Route::get('/logistics_documents/log_reject/{id}/{reason}', [LogisticDocumentController::class, 'logistics_reject']);

    // Route::get('/logistics_documents/new/search', [LogisticDocumentController::class, 'new_search_result'])->name('logistics_documents.new_search_result');
    route::post('/ajax/count_total_cost',[ProductController::class, 'count_total_cost'])->name('count_total_cost');

    Route::resource('import_products', ImportProductController::class);
    Route::resource('sourcing_products', SourcingProductController::class);
    Route::resource('sourcing_documents', SourcingDocumentController::class);
    // Route::get('sourcing_documents/', [SourcingDocumentController::class,'new_index'])->name('sourcing_documents.index');
    Route::get('sourcing_documents/{id}/edit', 'SourcingDocumentController@edit')->name('sourcing_documents.edit');

    Route::get('/check_sourcing_percentage/{id}',[PercentageController::class,'check_sourcing_percentage']);
    Route::get('/check_mer_percentage/{id}',[PercentageController::class,'check_mer_percentage']);
    // get issue no
    Route::get('/sourcing_documents/get_issue_no/{issue_no}/{branch_code}', [SourcingDocumentController::class, 'get_issue_no']);
    //---------------------------------------wyp--------------------------------------------------------------------------------
    Route::post('/logistic_doc/percentage_store',[PercentageController::class,'logistic_percentage'])->name('logistic_percentage');
    Route::delete('/logistic_doc/per_img/{id}/delete',[PercentageController::class,'img_delete'])->name('percentage_img_delete');
    Route::post('/logistic_doc/per_img/{id}/update',[PercentageController::class,'img_update'])->name('percentage_img_update');
    Route::get('/logistic_doc/per_update/{id}',[PercentageController::class,'percentage_update']);
    Route::get('/logistic_doc/log_per_update/{id}',[PercentageController::class,'log_percentage_update']);

    Route::get('/sourcing_doc/per_update/{id}',[PercentageController::class,'sourcing_percentage_update']);
    Route::get('/sourcing_doc/log_per_update/{id}',[PercentageController::class,'sourcing_log_percentage_update']);
    Route::get('/sourcing_doc/qty_update/{id}',[PercentageController::class,'sourcing_qty_update']);

    Route::delete('/logistic_doc/sourcing_per_img/{id}/delete',[PercentageController::class,'sourcing_img_delete'])->name('sourcing_percentage_img_delete');
    Route::post('/logistic_doc/sourcing_per_img/{id}/update',[PercentageController::class,'sourcing_img_update'])->name('sourcing_percentage_img_update');

    //------------------------------------------------------Sourcing --------------------------------------------------------------------------------------
    Route::get('sourcing/small_docs',[SourcingDocumentController::class,'small'])->name('sourcing_small');
    Route::get('/sourcing_documents/new/search', [SourcingDocumentController::class, 'new_search_result'])->name('sourcing_documents.new_search_result');
    Route::get('sourcing/big_docs',[SourcingDocumentController::class,'big'])->name('sourcing_big');
    Route::get('sourcing/big_docs_not_issue',[SourcingDocumentController::class,'big_not_issue'])->name('sourcing_big_not_issue');
    Route::get('sourcing/need_accessory_docs',[SourcingDocumentController::class,'need_accessory'])->name('sourcing_need_accessory');
    Route::get('/sourcing_detail_listing', [SourcingDocumentController::class, 'sourcing_detail_listing'])->name('sourcing_detail_listing');
    Route::get('/sourcing_detail_listing/new/search', [SourcingDocumentController::class, 'new_search_result_detail'])->name('sourcing_documents.new_search_result_detail');

    //------------------------------------------------------Logistics --------------------------------------------------------------------------------------
    Route::get('logistics/small_docs',[LogisticDocumentController::class,'small'])->name('logistics_small');
    Route::get('/logistics_documents/new/search', [LogisticDocumentController::class, 'new_search_result'])->name('logistics_documents.new_search_result');
    Route::get('/logistics_detail_listing/new/search', [LogisticDocumentController::class, 'new_search_result_detail'])->name('logistics_documents.new_search_result_detail');
    Route::get('logistics/big_docs',[LogisticDocumentController::class,'big'])->name('logistics_big');
    Route::get('logistics/need_accessory_docs',[LogisticDocumentController::class,'need_accessory'])->name('logistics_need_accessory');
    // issued
    Route::get('/logistics_issued_approve', [LogisticDocumentController::class, 'logistics_issued_approve'])->name('logistics_document.logistics_issued_approve');
    // get issue no
    Route::get('/logistics/get_issue_no/{issue_no}/{branch_code}', [LogisticDocumentController::class, 'get_issue_no']);

    Route::get('/logistics_detail_listing', [LogisticDocumentController::class, 'logistics_detail_listing'])->name('logistics_detail_listing');
    // end


    Route::get('/logistics_documents/damage/report/{logistic_type}', [LogisticDocumentController::class, 'damage_rp'])->name('logistics_documents.damage_rp');

    Route::get('/sourcing_documents/damage/report/{sourcing_type}', [SourcingDocumentController::class, 'damage_rp'])->name('sourcing_documents.damage_rp');


    Route::post('/set-excel-download-cookie', function () {
        Cookie::queue('excel_download_complete', '1', 1); // 1-minute expiry
        return response()->json(['message' => 'Download cookie set']);
    })->name('set.excel.download.cookie');



});

Route::group(['middleware' => ['auth']], function () {
    Route::resource('changelogs', ChangeLogsController::class);
    Route::get("/changelogsfetchalldatas",[ChangeLogsController::class,"fetchalldatas"])->name("changelogs.fetchalldatas");


    Route::resource('whatsnews', WhatsNewsController::class);

});
Route::get('/sourcing_documents_imageready', [SourcingDocumentController::class, 'image_ready'])->name('sourcing_documents.image_ready');
Route::get('/logistics_documents_imageready', [LogisticDocumentController::class, 'image_ready'])->name('logistics_documents.image_ready');
