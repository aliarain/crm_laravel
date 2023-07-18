<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\SignUpController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Frontend\Policy\PolicyController;
use App\Http\Controllers\Frontend\Socialite\SocialController;

//admin login route
Route::post('admin-login',                  [LoginController::class, 'authenticate'])->name('admin.login')->middleware('xss');

Route::group(['middleware' => ['xss','MaintenanceMode']], function () {

    Route::get('auth/redirect/{provider}',  [SocialController::class, 'redirectToProvider'])->name('social.login');
    Route::get('callback/{provider}',       [SocialController::class, 'handleProviderCallback']);

    Route::get('forgot-password',           [ForgotPasswordController::class, 'index'])->middleware('guest')->name('password.forget');
    Route::post('reset-password',           [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.reset');
    Route::get('reset-password',            [ForgotPasswordController::class, 'resetPassword'])->name('password.reset');
    Route::post('change-password',          [ForgotPasswordController::class, 'changePassword'])->name('password.change');
    Route::post('change-admin-password',    [ForgotPasswordController::class, 'adminChangePassword'])->name('admin.password.change');
});
Route::post('get-country',                  [SignUpController::class, 'getCountry'])->name('add.getCountry')->middleware('xss');

