<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\Performance\GoalController;
use App\Http\Controllers\Backend\Performance\GoalTypeController;
use App\Http\Controllers\Backend\Performance\AppraisalController;
use App\Http\Controllers\Backend\Performance\IndicatorController;
use App\Http\Controllers\Backend\Performance\CompetenceController;
use App\Http\Controllers\Backend\Performance\CompetenceTypeController;

Route::group(['middleware' => ['xss','admin', 'TimeZone'], 'prefix' => 'admin/performance'], function () {

    // Performance Indicators

    Route::controller(IndicatorController::class)->prefix('indicator')->group(function () {
        Route::get('/', 'index')->name('performance.indicator.index')->middleware('PermissionCheck:performance_indicator_list');
        Route::post('delete-data', 'deleteData')->name('performance.indicator.delete_data')->middleware('PermissionCheck:performance_indicator_delete');

        Route::get('/table', 'table')->name('performance.indicator.table')->middleware('PermissionCheck:performance_indicator_list');
        Route::get('create', 'create')->name('performance.indicator.create')->middleware('PermissionCheck:performance_indicator_create');
        Route::post('store', 'store')->name('performance.indicator.store')->middleware('PermissionCheck:performance_indicator_store');
        Route::get('edit/{id}', 'edit')->name('performance.indicator.edit')->middleware('PermissionCheck:performance_indicator_edit');
        Route::post('update/{id}', 'update')->name('performance.indicator.update')->middleware('PermissionCheck:performance_indicator_update');
        Route::get('delete/{id}', 'delete')->name('performance.indicator.delete')->middleware('PermissionCheck:performance_indicator_delete');
        Route::get('view/{id}', 'view')->name('performance.indicator.view')->middleware('PermissionCheck:performance_indicator_view');
    });
    //  Appraisal
    Route::controller(AppraisalController::class)->prefix('appraisal')->group(function () {
        Route::get('/', 'index')->name('performance.appraisal.index')->middleware('PermissionCheck:performance_appraisal_list');
        Route::get('table', 'table')->name('performance.appraisal.table')->middleware('PermissionCheck:performance_appraisal_list');


        Route::post('delete-data', 'deleteData')->name('performance.appraisal.delete_data')->middleware('PermissionCheck:performance_appraisal_delete');


        Route::get('create', 'create')->name('performance.appraisal.create')->middleware('PermissionCheck:performance_appraisal_create');
        Route::post('store', 'store')->name('performance.appraisal.store')->middleware('PermissionCheck:performance_appraisal_store');
        Route::get('edit/{id}', 'edit')->name('performance.appraisal.edit')->middleware('PermissionCheck:performance_appraisal_edit');
        Route::post('update/{id}', 'update')->name('performance.appraisal.update')->middleware('PermissionCheck:performance_appraisal_update');
        Route::get('delete/{id}', 'delete')->name('performance.appraisal.delete')->middleware('PermissionCheck:performance_appraisal_delete');
        Route::get('view/{id}', 'view')->name('performance.appraisal.view')->middleware('PermissionCheck:performance_appraisal_view');
    });

    // Goals
    Route::controller(GoalController::class)->prefix('goal')->group(function () {
        Route::get('/', 'index')->name('performance.goal.index')->middleware('PermissionCheck:performance_goal_list');
        Route::get('table', 'table')->name('performance.goal.table')->middleware('PermissionCheck:performance_goal_list');
        Route::get('create', 'create')->name('performance.goal.create')->middleware('PermissionCheck:performance_goal_create');
        Route::post('store', 'store')->name('performance.goal.store')->middleware('PermissionCheck:performance_goal_store');
        Route::get('edit/{id}', 'edit')->name('performance.goal.edit')->middleware('PermissionCheck:performance_goal_edit');
        Route::post('update/{id}', 'update')->name('performance.goal.update')->middleware('PermissionCheck:performance_goal_update');
        Route::get('delete/{id}', 'delete')->name('performance.goal.delete')->middleware('PermissionCheck:performance_goal_delete');
        Route::get('view/{id}', 'view')->name('performance.goal.view')->middleware('PermissionCheck:performance_goal_view');

        Route::any('get-goal', 'getGoal')->name('get_goal');

        Route::post('delete-data', 'deleteData')->name('performance.goal.delete_data')->middleware('PermissionCheck:performance_goal_delete');
    });

    Route::prefix('settings')->group(function () {

        // competence type 
        Route::controller(CompetenceTypeController::class)->prefix('competence-type')->group(function () {
            Route::get('/', 'index')->name('performance.competence.type.index')->middleware('PermissionCheck:performance_competence_type_list');
            
            Route::get('/table', 'table')->name('performance.competence.type.table')->middleware('PermissionCheck:performance_competence_type_list');
            Route::get('create', 'create')->name('performance.competence.type.create')->middleware('PermissionCheck:performance_competence_type_create');
            Route::post('store', 'store')->name('performance.competence.type.store')->middleware('PermissionCheck:performance_competence_type_store');
            Route::get('edit/{id}', 'edit')->name('performance.competence.type.edit')->middleware('PermissionCheck:performance_competence_type_edit');
            Route::post('update/{id}', 'update')->name('performance.competence.type.update')->middleware('PermissionCheck:performance_competence_type_update');
            Route::get('delete/{id}', 'delete')->name('performance.competence.type.delete')->middleware('PermissionCheck:performance_competence_type_delete');


            Route::post('delete-data',      'deleteData')->name('performance.competence.type.delete_data')->middleware('PermissionCheck:performance_competence_type_update');
            Route::post('status-change',    'statusUpdate')->name('performance.competence.type.statusUpdate')->middleware('PermissionCheck:performance_competence_type_delete');
        });

        // competence
        Route::controller(CompetenceController::class)->prefix('competence')->group(function () {
            Route::get('/', 'index')->name('performance.competence.index')->middleware('PermissionCheck:performance_competence_list');
            Route::get('/table', 'table')->name('performance.competence.table')->middleware('PermissionCheck:performance_competence_list');
            Route::get('create', 'create')->name('performance.competence.create')->middleware('PermissionCheck:performance_competence_create');
            Route::post('store', 'store')->name('performance.competence.store')->middleware('PermissionCheck:performance_competence_store');
            Route::get('edit/{id}', 'edit')->name('performance.competence.edit')->middleware('PermissionCheck:performance_competence_edit');
            Route::post('update/{id}', 'update')->name('performance.competence.update')->middleware('PermissionCheck:performance_competence_update');
            Route::get('delete/{id}', 'delete')->name('performance.competence.delete')->middleware('PermissionCheck:performance_competence_delete');


            Route::post('delete-data',      'deleteData')->name('performance.competence.delete_data')->middleware('PermissionCheck:performance_competence_update');
            Route::post('status-change',    'statusUpdate')->name('performance.competence.statusUpdate')->middleware('PermissionCheck:performance_competence_delete');
        });



        // goal-type
        Route::controller(GoalTypeController::class)->prefix('goal-type')->group(function () {
            Route::get('/', 'index')->name('performance.goal_type.index')->middleware('PermissionCheck:performance_goal_type_list');
            Route::get('/table', 'table')->name('performance.goal_type.table')->middleware('PermissionCheck:performance_goal_type_list');
            Route::get('create', 'create')->name('performance.goal_type.create')->middleware('PermissionCheck:performance_goal_type_create');
            Route::post('store', 'store')->name('performance.goal_type.store')->middleware('PermissionCheck:performance_goal_type_store');
            Route::get('edit/{id}', 'edit')->name('performance.goal_type.edit')->middleware('PermissionCheck:performance_goal_type_edit');
            Route::post('update/{id}', 'update')->name('performance.goal_type.update')->middleware('PermissionCheck:performance_goal_type_update');
            Route::get('delete/{id}', 'delete')->name('performance.goal_type.delete')->middleware('PermissionCheck:performance_goal_type_delete');
            Route::get('view/{id}/{data}', 'view')->name('performance.goal_type.view')->middleware('PermissionCheck:performance_goal_type_view');


            Route::post('delete-data',      'deleteData')->name('performance.goal_type.delete_data')->middleware('PermissionCheck:performance_goal_type_update');
            Route::post('status-change',    'statusUpdate')->name('performance.goal_type.statusUpdate')->middleware('PermissionCheck:performance_goal_type_delete');
        });
    });
});
