<?php

use Illuminate\Support\Facades\Route;
use Pro1\Changelog\Http\Controllers\Api\WhatsNewsController;
use Pro1\Changelog\Http\Controllers\Api\ChangeLogsController;
use Pro1\Changelog\Http\Controllers\Api\ChangeTypesController;

Route::apiResource("changelogs",ChangeLogsController::class,["as"=>"api"]);
Route::get('changelogs/{id}/changes',[ChangeLogsController::class,"changes"]);
Route::post('changelogs/{id}/changes',[ChangeLogsController::class,"storechanges"]);
Route::put('changelogs/{id}/changes/{changeid}',[ChangeLogsController::class,"updatechanges"]);
Route::delete('changelogs/{id}/changes/{changeid}',[ChangeLogsController::class,"deletechanges"]);
Route::get("/changelogs/{id}/agree",[ChangeLogsController::class,"agree"])->name("changelogs.agree");
Route::apiResource("changetypes",ChangeTypesController::class,["as"=>"api"]);
Route::get('changelogsdashboard',[ChangeLogsController::class,"dashboard"]);


Route::apiResource("whatsnews",WhatsNewsController::class,["as"=>"api"]);