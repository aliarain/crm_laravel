<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\Company\LocationController;
use App\Http\Controllers\coreApp\Setting\IpConfigController;
use App\Http\Controllers\Backend\Company\CompanyConfigController;

Route::group(['prefix' => 'admin/company-setup', 'middleware' => ['xss','admin']], function () {


    //start company setup
    Route::get('/',                               [CompanyConfigController::class, 'index'])->name('company.settings.view')->middleware(['PermissionCheck:company_settings_read']);
    Route::post('update',                         [CompanyConfigController::class, 'update'])->name('company.settings.update')->middleware('PermissionCheck:company_settings_update');
    Route::post('currencyInfo',                   [CompanyConfigController::class, 'currencyInfo'])->name('company.settings.currencyInfo')->middleware('PermissionCheck:company_settings_update');
    Route::get('location-api',                    [CompanyConfigController::class, 'locationApi'])->name('company.settings.locationApi')->middleware('PermissionCheck:locationApi');
    Route::post('update-api',                     [CompanyConfigController::class, 'updateApi'])->name('company.settings.updateApi')->middleware('PermissionCheck:locationApi');

    // activation company setup
    Route::get('activation',                      [CompanyConfigController::class, 'activation'])->name('company.settings.activation')->middleware('PermissionCheck:company_setup_activation');
    // configure ip address
    Route::get('configuration',                   [CompanyConfigController::class, 'configuration'])->name('company.settings.configuration')->middleware('PermissionCheck:company_setup_configuration');

  

});
