<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Backend\Setting\MenuController;
use App\Http\Controllers\coreApp\Setting\IpConfigController;
use App\Http\Controllers\coreApp\Setting\SettingsController;
use App\Http\Controllers\Frontend\Contact\ContactController;
use App\Http\Controllers\coreApp\Setting\AppSettingsController;
use App\Http\Controllers\Frontend\Content\AllContentController;
use App\Http\Controllers\Backend\Company\CompanyConfigController;
use App\Http\Controllers\coreApp\Setting\LanguageSettingController;

Route::group(['prefix' => 'admin/settings', 'middleware' => ['xss', 'auth', 'admin']], function () {
    //start settings
    Route::get('/',                            [SettingsController::class, 'newIndex'])->name('manage.settings.view')->middleware('PermissionCheck:general_settings_read');
    // Route::get('list',                            [SettingsController::class, 'index'])->name('manage.settings.view')->middleware('PermissionCheck:general_settings_read');
    Route::post('update',                         [SettingsController::class, 'update'])->name('manage.settings.update')->middleware('PermissionCheck:general_settings_update');
    //Email Setting
    Route::post('/email-setup',                   [SettingsController::class, 'emailSetup'])->name('manage.settings.update.email')->middleware('PermissionCheck:email_settings_update');
    Route::post('/test-email-setup',              [SettingsController::class, 'testEmailSetup'])->name('manage.settings.test.email')->middleware('PermissionCheck:email_settings_update');
    Route::post('/storage-setup',                 [SettingsController::class, 'storageSetup'])->name('manage.settings.update.storage')->middleware('PermissionCheck:storage_settings_update');
    //leave setting
    Route::any('leave',                           [SettingsController::class, 'leaveSettings'])->name('leaveSettings.view')->middleware('PermissionCheck:leave_settings_read');
    Route::any('leave/edit',                      [SettingsController::class, 'leaveSettingsEdit'])->name('leaveSettings.edit')->middleware('PermissionCheck:leave_settings_update');
    Route::patch('leave/update',                  [SettingsController::class, 'leaveSettingsUpdate'])->name('leaveSettings.update')->middleware('PermissionCheck:leave_settings_update');

    //Language Settings
    Route::group(['prefix' => 'language-setup'], function () {
        Route::get('/',                       [LanguageSettingController::class, 'language'])->name('language.index')->middleware('PermissionCheck:language_menu');
        Route::get('/dataTable',              [LanguageSettingController::class, 'dataTable'])->name('dataTable.language')->middleware('PermissionCheck:language_menu');
        Route::get('create',                  [LanguageSettingController::class, 'create'])->name('language.create')->middleware('PermissionCheck:language_create');
        Route::post('/add',                   [LanguageSettingController::class, 'store'])->name('language.add')->middleware('PermissionCheck:language_store');

        Route::get('edit/{id}',               [LanguageSettingController::class, 'edit'])->name('language.edit')->middleware('PermissionCheck:language_edit');

        Route::post('update/{id}',            [LanguageSettingController::class, 'update'])->name('language.update')->middleware('PermissionCheck:language_update');


        Route::get('/setup/{language}',       [LanguageSettingController::class, 'setup'])->name('language.setup')->middleware('PermissionCheck:language_update');
        Route::post('/get-translate-file',    [LanguageSettingController::class, 'get_translate_file'])->name('language.get_translate_file')->middleware('PermissionCheck:language_update');
        Route::post('/update-lang-term',      [LanguageSettingController::class, 'updateLangTerm'])->name('language.updateLangTerm')->middleware('PermissionCheck:language_update');
        Route::get('/make-default/{language}', [LanguageSettingController::class, 'makeDefault'])->name('language.makeDefault')->middleware('PermissionCheck:make_default');
        Route::get('/make-active/{language}', [LanguageSettingController::class, 'makeActive'])->name('language.makeActive')->middleware('PermissionCheck:setup_language');
        Route::get('/delete/{language}',      [LanguageSettingController::class, 'deleteLang'])->name('language.deleteLang')->middleware('PermissionCheck:language_delete');
    });

    // App setting
    Route::group(['prefix' => 'app-setting'], function () {
        Route::any('/dashboard',              [AppSettingsController::class, 'appScreenSetup'])->name('appScreenSetup')->middleware('PermissionCheck:app_settings_menu');
        Route::post('/update-icon',           [AppSettingsController::class, 'updateIcon'])->name('appSettingsIcon');
        Route::post('/update-title',          [AppSettingsController::class, 'updateTitle'])->name('appSettingsTitle');
        Route::post('/setup-status',          [AppSettingsController::class, 'appScreenSetupUpdate'])->name('appScreenSetupUpdate')->middleware('PermissionCheck:app_settings_update');
    });
});
Route::group(['prefix' => 'company/settings', 'middleware' => ['xss', 'admin']], function () {
    //start company settings
    Route::get('/',                               [CompanyConfigController::class, 'index'])->name('company.settings.view')->middleware(['PermissionCheck:company_settings_read', 'MaintenanceMode']);
    Route::post('update',                         [CompanyConfigController::class, 'update'])->name('company.settings.update')->middleware('PermissionCheck:company_settings_update');
    Route::post('currencyInfo',                   [CompanyConfigController::class, 'currencyInfo'])->name('company.settings.currencyInfo')->middleware('PermissionCheck:company_settings_update');
    Route::get('location-api',                    [CompanyConfigController::class, 'locationApi'])->name('company.settings.locationApi')->middleware('PermissionCheck:locationApi');
    Route::post('update-api',                     [CompanyConfigController::class, 'updateApi'])->name('company.settings.updateApi')->middleware('PermissionCheck:locationApi');
});

Route::group(['prefix' => 'admin', 'middleware' => ['xss', 'admin']], function () {
    Route::group(['prefix' => 'contact'], function () {
        Route::get('/',                       [ContactController::class, 'index'])->name('contact.index')->middleware('PermissionCheck:contact_menu');
        Route::get('/dataTable',              [ContactController::class, 'dataTable'])->name('dataTable.contact')->middleware('PermissionCheck:contact_menu');
    });
});
