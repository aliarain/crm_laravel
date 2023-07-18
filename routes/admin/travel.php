<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\Travel\TravelController;
use App\Http\Controllers\Backend\Travel\TravelTypeController;


Route::group(['middleware' => ['xss','admin', 'TimeZone'], 'prefix' => 'admin/travel'], function () {

    // Awards route
    Route::controller(TravelController::class)->group(function () {
        Route::any('/', 'index')->name('travel.index')->middleware('PermissionCheck:travel_list');
        Route::get('/table', 'table')->name('travel.table')->middleware('PermissionCheck:travel_list');

        Route::post('delete-data',      'deleteData')->name('travel.delete_data')->middleware('PermissionCheck:travel_delete');
        Route::post('status-change',    'statusUpdate')->name('travel.statusUpdate')->middleware('PermissionCheck:travel_update');

        Route::get('create', 'create')->name('travel.create')->middleware('PermissionCheck:travel_create');
        Route::post('store', 'store')->name('travel.store')->middleware('PermissionCheck:travel_store');
        Route::get('edit/{id}', 'edit')->name('travel.edit')->middleware('PermissionCheck:travel_edit');
        Route::post('update/{id}', 'update')->name('travel.update')->middleware('PermissionCheck:travel_update');
        Route::get('delete/{id}', 'delete')->name('travel.delete')->middleware('PermissionCheck:travel_delete');
        Route::get('view/{id}', 'view')->name('travel.view')->middleware('PermissionCheck:travel_view');

        //travel approve
        Route::get('approve/{id}', 'approve')->name('travel.approve')->middleware('PermissionCheck:travel_approve');
        Route::post('approve-store/{id}', 'approve_store')->name('travel.approve_store')->middleware('PermissionCheck:travel_approve');
    });

    // Travel Type route
    Route::controller(TravelTypeController::class)->prefix('type')->group(function () {
        Route::any('/', 'index')->name('travel_type.index')->middleware('PermissionCheck:travel_type_list');
        Route::get('/table', 'table')->name('travel_type.table')->middleware('PermissionCheck:travel_type_list');
        Route::get('create', 'create')->name('travel_type.create')->middleware('PermissionCheck:travel_type_create');
        Route::post('store', 'store')->name('travel_type.store')->middleware('PermissionCheck:travel_type_store');
        Route::get('edit/{id}', 'edit')->name('travel_type.edit')->middleware('PermissionCheck:travel_type_edit');
        Route::post('update/{id}', 'update')->name('travel_type.update')->middleware('PermissionCheck:travel_type_update');
        Route::get('delete/{id}', 'delete')->name('travel_type.delete')->middleware('PermissionCheck:travel_type_delete');
        Route::get('view/{id}/{data}', 'view')->name('travel_type.view')->middleware('PermissionCheck:travel_type_view');


        Route::post('delete-data',      'deleteData')->name('travel_type.delete_data')->middleware('PermissionCheck:travel_type_delete');
        Route::post('status-change',    'statusUpdate')->name('travel_type.statusUpdate')->middleware('PermissionCheck:travel_type_update');
    });
});
