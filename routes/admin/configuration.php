<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\Attendance\ShiftController;
use App\Http\Controllers\Backend\Company\LocationController;
use App\Http\Controllers\coreApp\Setting\IpConfigController;
use App\Http\Controllers\Backend\Attendance\HolidayController;
use App\Http\Controllers\Backend\Attendance\WeekendsController;
use App\Http\Controllers\Backend\Attendance\DutyScheduleController;

Route::group(['prefix' => 'hrm', 'middleware' => ['xss','admin', 'MaintenanceMode']], function () {


    //weekend setup start
    Route::group(['prefix' => 'weekend/setup'], function () {
        Route::get('/',                          [WeekendsController::class, 'index'])->name('weekendSetup.index')->middleware('PermissionCheck:weekend_read');
        Route::get('show/{weekend}',             [WeekendsController::class, 'show'])->name('weekendSetup.show')->middleware('PermissionCheck:weekend_read');
        Route::post('update/{id}',                [WeekendsController::class, 'update'])->name('weekendSetup.update')->middleware('PermissionCheck:weekend_update');
    });
    //weekend setup end

    //holiday setup start
    Route::group(['prefix' => 'holiday/setup'], function () {
        Route::get('/',                         [HolidayController::class, 'index'])->name('holidaySetup.index')->middleware('PermissionCheck:holiday_read');
        Route::get('/create',                   [HolidayController::class, 'create'])->name('holidaySetup.create')->middleware('PermissionCheck:holiday_create');
        Route::post('store',                    [HolidayController::class, 'store'])->name('holidaySetup.store')->middleware('PermissionCheck:holiday_create');
        Route::get('show/{holiday}',            [HolidayController::class, 'show'])->name('holidaySetup.show')->middleware('PermissionCheck:holiday_update');
        Route::patch('update/{holiday}',        [HolidayController::class, 'update'])->name('holidaySetup.update')->middleware('PermissionCheck:holiday_update');
        Route::get('delete/{holiday_id}',       [HolidayController::class, 'delete'])->name('holidaySetup.delete')->middleware('PermissionCheck:holiday_delete');

        Route::post('delete-data',              [HolidayController::class, 'deleteData'])->name('holidaySetup.deleteData')->middleware('PermissionCheck:holiday_delete');
    });
    //holiday setup end


    //duty schedule start
    Route::group(['prefix' => 'duty/schedule'], function () {
        Route::get('/',                        [DutyScheduleController::class, 'index'])->name('dutySchedule.index')->middleware('PermissionCheck:schedule_read');
        Route::get('create',                   [DutyScheduleController::class, 'create'])->name('dutySchedule.create')->middleware('PermissionCheck:schedule_create');
        Route::post('store',                   [DutyScheduleController::class, 'store'])->name('dutySchedule.store')->middleware('PermissionCheck:schedule_create');
        Route::get('show/{schedule}',          [DutyScheduleController::class, 'show'])->name('dutySchedule.show')->middleware('PermissionCheck:schedule_update');
        Route::patch('update/{schedule}',      [DutyScheduleController::class, 'update'])->name('dutySchedule.update')->middleware('PermissionCheck:schedule_update');
        Route::get('delete/{schedule}',        [DutyScheduleController::class, 'delete'])->name('dutySchedule.delete')->middleware('PermissionCheck:schedule_delete');
        Route::get('list-data',                [DutyScheduleController::class, 'dutyScheduleDataTable'])->name('dutySchedule.dataTable');


        Route::post('delete-data',              [DutyScheduleController::class, 'deleteData'])->name('dutySchedule.deleteData')->middleware('PermissionCheck:schedule_delete');
    });
    //duty schedule end

    //shift start
    Route::group(['prefix' => 'shift'], function () {
        Route::get('/',                         [ShiftController::class, 'index'])->name('shift.index')->middleware('PermissionCheck:shift_read');
        Route::get('create',                    [ShiftController::class, 'create'])->name('shift.create')->middleware('PermissionCheck:shift_create');
        Route::get('data-table',                [ShiftController::class, 'dataTable'])->name('shift.dataTable')->middleware('PermissionCheck:shift_read');
        Route::post('store',                    [ShiftController::class, 'store'])->name('shift.store')->middleware('PermissionCheck:shift_create');
        Route::get('show/{shift}',              [ShiftController::class, 'show'])->name('shift.show')->middleware('PermissionCheck:shift_read');
        Route::get('edit/{shift}',              [ShiftController::class, 'edit'])->name('shift.edit')->middleware('PermissionCheck:shift_update');
        Route::post('update/{shift}',          [ShiftController::class, 'update'])->name('shift.update')->middleware('PermissionCheck:shift_update');
        Route::get('delete/{shift}',            [ShiftController::class, 'delete'])->name('shift.delete')->middleware('PermissionCheck:shift_delete');

        Route::post('status-change',           [ShiftController::class, 'statusUpdate'])->name('shift.statusUpdate')->middleware('PermissionCheck:shift_update');
        Route::post('delete-data',             [ShiftController::class, 'deleteData'])->name('shift.delete_data')->middleware('PermissionCheck:shift_delete');
    });

      //ip whitelist
      Route::group(['prefix' => 'ip-whitelist', 'middleware' => 'MaintenanceMode'], function () {
        Route::get('/',                           [IpConfigController::class, 'index'])->name('ipConfig.index')->middleware('PermissionCheck:ip_read');
        Route::get('create',                      [IpConfigController::class, 'create'])->name('ipConfig.create')->middleware('PermissionCheck:ip_create');
        Route::post('store',                      [IpConfigController::class, 'store'])->name('ipConfig.store')->middleware('PermissionCheck:ip_create');
        Route::get('datatable',                   [IpConfigController::class, 'datatable'])->name('ipConfig.datatable')->middleware('PermissionCheck:ip_read');
        Route::get('edit/{id}',                   [IpConfigController::class, 'edit'])->name('ipConfig.edit')->middleware('PermissionCheck:ip_update');
        Route::post('update/{id}',                [IpConfigController::class, 'update'])->name('ipConfig.update')->middleware('PermissionCheck:ip_update');
        Route::get('delete/{id}',                 [IpConfigController::class, 'destroy'])->name('ipConfig.destroy')->middleware('PermissionCheck:ip_delete');


        Route::post('status-change',             [IpConfigController::class, 'statusUpdate'])->name('ipConfig.statusUpdate')->middleware('PermissionCheck:ip_update');
        Route::post('delete-data',               [IpConfigController::class, 'deleteData'])->name('ipConfig.delete_data')->middleware('PermissionCheck:ip_delete');
    });

    // location
    Route::group(['prefix' => 'location', 'middleware' => 'MaintenanceMode'], function () {
        Route::get('/',                           [LocationController::class, 'location'])->name('company.settings.location')->middleware('PermissionCheck:company_setup_location');
        Route::get('create',                      [LocationController::class, 'locationCreate'])->name('company.settings.locationCreate')->middleware('PermissionCheck:location_create');
        Route::post('store',                      [LocationController::class, 'locationStore'])->name('company.settings.locationStore')->middleware('PermissionCheck:location_create');
        Route::get('datatable',                   [LocationController::class, 'datatable'])->name('company.settings.locationDatatable')->middleware('PermissionCheck:location_read');
        Route::get('edit/{id}',                   [LocationController::class, 'edit'])->name('company.settings.locationEdit')->middleware('PermissionCheck:location_edit');
        Route::post('update/{id}',                [LocationController::class, 'locationUpdate'])->name('company.settings.locationUpdate')->middleware('PermissionCheck:location_update');
        Route::get('delete/{id}',                 [LocationController::class, 'locationDestroy'])->name('company.settings.locationDestroy')->middleware('PermissionCheck:location_delete');
        // location picker modal
        Route::get('location-picker',             [LocationController::class, 'locationPicker'])->name('company.settings.locationPicker')->middleware('PermissionCheck:location_create');


        Route::post('status-change',              [LocationController::class, 'statusUpdate'])->name('location.statusUpdate')->middleware('PermissionCheck:location_update');
        Route::post('delete-data',                [LocationController::class, 'deleteData'])->name('location.delete_data')->middleware('PermissionCheck:location_delete');
    });


});
