<?php

use Illuminate\Support\Facades\Route; 
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Frontend\Auth\LoginController;
use App\Http\Controllers\Frontend\NavigatorController; 

Route::get('login', [LoginController::class, 'adminLogin'])->name('adminLogin');

Route::group(['middleware' => ['xss','MaintenanceMode','redirect']], function () {

    if(env('APP_CRM')){
        Route::get('/',                     [NavigatorController::class, 'index'])->name('home');
    }else{
        Route::get('/', [LoginController::class, 'adminLogin'])->name('adminLogin');
    }

    Route::get('support-24-7',          [NavigatorController::class, 'support'])->name('support');
    Route::get('privacy-policy',        [NavigatorController::class, 'privacyPolicy'])->name('privacyPolicy');
    Route::get('terms-and-conditions',  [NavigatorController::class, 'termsAndConditions'])->name('termsAndConditions');
});

//admin routes here
include_route_files(__DIR__ . '/admin/');

//frontend routes here
include_route_files(__DIR__ . '/frontend/'); 