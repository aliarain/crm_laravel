<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\Firebase\FirebaseController;

Route::group(['prefix' => 'hrm', 'middleware' => ['xss','auth','MaintenanceMode']], function () {

    Route::post('firebase-token/assign',   [FirebaseController::class,'firebaseToken'])->name('updateFirebaseToken');
    Route::get('firebase/sw-js',           [FirebaseController::class,'initFirebase'])->name('firebase.init');
    // firebase test
    Route::get('firebase/test',            [FirebaseController::class,'test']);


});
