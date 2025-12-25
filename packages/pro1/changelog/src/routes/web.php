<?php

use Illuminate\Support\Facades\Route;
use Pro1\Changelog\Http\Controllers\WhatsNewsController;
use Pro1\Changelog\Http\Controllers\ChangeLogsController;

// Route::group(['middleware' => ['auth']], function () {
    Route::resource('changelogs', ChangeLogsController::class);
    Route::get("/changelogsfetchalldatas",[ChangeLogsController::class,"fetchalldatas"])->name("changelogs.fetchalldatas");


    Route::resource('whatsnews', WhatsNewsController::class);


    if(env('PORTAL_ID') == 3){
        Route::get('/home', function () {
            if (auth()->user()->emp_id == 'superadmin@mail.com') {
                return redirect('/admins');
            }
            if(roleCheck('Admin'))
            {
                return redirect()->route('doc_list');
            }
            return redirect()->route('pending_list');
        });
    }

// });
