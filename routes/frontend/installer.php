<?php

use App\Http\Controllers\Installer\InstallController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web'])->group(function () {
    Route::prefix('install')->group(function () {

        Route::get('/',                             [InstallController::class, 'index'])->name('service.install');
        Route::get('check-environment',             [InstallController::class, 'CheckEnvironment'])->name('service.checkEnvironment'); 
        Route::get('license-verification',          [InstallController::class, 'license'])->name('service.license');
        Route::post('license-verification-post',    [InstallController::class, 'post_license'])->name('service.license_post');
        Route::get('database-setup-connection',     [InstallController::class, 'database'])->name('service.database');
        Route::post('database-setup-connection',    [InstallController::class, 'post_database'])->name('service.database_post');
        Route::get('uninstall',                     [InstallController::class, 'uninstall'])->name('service.uninstall');
        Route::get('service/verification',          [InstallController::class,  'verify'])->name('service.verify');
        Route::get('admin-setup',                   [InstallController::class, 'AdminSetup'])->name('service.user');
        Route::any('admin-setup-post',              [InstallController::class, 'post_user'])->name('service.user_post');
        Route::get('done',                          [InstallController::class, 'done'])->name('service.done');
        Route::get('reinstall',                     [InstallController::class, 'reinstall'])->name('service.reinstall');
        Route::get('import-sql',                    [InstallController::class, 'import_sql'])->name('service.import_sql');
        Route::post('import-sql',                   [InstallController::class, 'import_sql_post'])->name('service.import_sql_post');
    });
 
});
