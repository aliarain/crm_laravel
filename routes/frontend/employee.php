<?php

use App\Http\Controllers\coreApp\Setting\AppSettingsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\Employee\EmployeeController;

Route::prefix('employee')->middleware(['xss','TimeZone'])->group(function () {
    Route::get('/dashboard',            [EmployeeController::class,'employeeDashboard'])->name('employeeDashboard');
    Route::get('base-settings',         [AppSettingsController::class, 'baseSettings']);
});