<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\Meeting\MeetingWebController;

Route::group(['middleware' => ['xss','admin', 'TimeZone'], 'prefix' => 'admin'], function () {


    Route::controller(MeetingWebController::class)->prefix('meeting')->group(function () {
        Route::get('/', 'index')->name('meeting.index')->middleware('PermissionCheck:meeting_list');
        Route::post('delete-data', 'deleteData')->name('meeting.delete_data')->middleware('PermissionCheck:meeting_delete');

        Route::get('/table', 'table')->name('meeting.table')->middleware('PermissionCheck:meeting_list');
        Route::get('create', 'create')->name('meeting.create')->middleware('PermissionCheck:meeting_create');
        Route::post('store', 'store')->name('meeting.store')->middleware('PermissionCheck:meeting_store');
        Route::get('edit/{id}', 'edit')->name('meeting.edit')->middleware('PermissionCheck:meeting_edit');
        Route::post('update/{id}', 'update')->name('meeting.update')->middleware('PermissionCheck:meeting_update');
        Route::get('delete/{id}', 'delete')->name('meeting.delete')->middleware('PermissionCheck:meeting_delete');
        Route::get('view/{id}', 'view')->name('meeting.view')->middleware('PermissionCheck:meeting_view');
    });

});
