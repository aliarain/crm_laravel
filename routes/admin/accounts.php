<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Accounts\PaymentController;
use App\Http\Controllers\Accounts\SubscriptionController;

// subscription
Route::middleware(['xss','TimeZone','MaintenanceMode'])->group(function () {
    Route::get('subscription',    [SubscriptionController::class, 'index'])->name('subscription');
    Route::post('subscription',   [SubscriptionController::class, 'subscribe'])->name('subscription1');
    Route::any('payments',        [PaymentController::class, 'index'])->name('payments');
    Route::post('payments',       [PaymentController::class, 'store'])->name('payments.store');
});

