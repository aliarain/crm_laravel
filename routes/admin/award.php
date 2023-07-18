<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\Award\AwardController;
use App\Http\Controllers\Backend\Award\AwardTypeController;

Route::group(['middleware' => ['xss','admin', 'TimeZone'], 'prefix' => 'admin/award'], function () {

    // Awards route
    Route::controller(AwardController::class)->group(function () {
        Route::any('/', 'index')->name('award.index')->middleware('PermissionCheck:award_list');
        Route::get('/table', 'table')->name('award.table')->middleware('PermissionCheck:award_list');


        Route::post('delete-data',      'deleteData')->name('award.delete_data')->middleware('PermissionCheck:award_delete');
        Route::post('status-change',    'statusUpdate')->name('award.statusUpdate')->middleware('PermissionCheck:award_update');

        Route::get('create', 'create')->name('award.create')->middleware('PermissionCheck:award_create');
        Route::post('store', 'store')->name('award.store')->middleware('PermissionCheck:award_store');
        Route::get('edit/{id}', 'edit')->name('award.edit')->middleware('PermissionCheck:award_edit');
        Route::post('update/{id}', 'update')->name('award.update')->middleware('PermissionCheck:award_update');
        Route::get('delete/{id}', 'delete')->name('award.delete')->middleware('PermissionCheck:award_delete');
        Route::get('view/{id}', 'view')->name('award.view')->middleware('PermissionCheck:award_view');
    });

    // Award Type route
    Route::controller(AwardTypeController::class)->prefix('type')->group(function () {
        Route::any('/', 'index')->name('award_type.index')->middleware('PermissionCheck:award_type_list');
        Route::get('/table', 'table')->name('award_type.table')->middleware('PermissionCheck:award_type_list');
        Route::get('create', 'create')->name('award_type.create')->middleware('PermissionCheck:award_type_create');
        Route::post('store', 'store')->name('award_type.store')->middleware('PermissionCheck:award_type_store');
        Route::get('edit/{id}', 'edit')->name('award_type.edit')->middleware('PermissionCheck:award_type_edit');
        Route::post('update/{id}', 'update')->name('award_type.update')->middleware('PermissionCheck:award_type_update');
        Route::get('delete/{id}', 'delete')->name('award_type.delete')->middleware('PermissionCheck:award_type_delete');
        Route::get('view/{id}/{data}', 'view')->name('award_type.view')->middleware('PermissionCheck:award_type_view');


        Route::post('delete-data',      'deleteData')->name('award_type.delete_data')->middleware('PermissionCheck:award_type_delete');
        Route::post('status-change',    'statusUpdate')->name('award_type.statusUpdate')->middleware('PermissionCheck:award_type_update');
    });
});
