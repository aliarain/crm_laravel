<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\Management\FileController;
use App\Http\Controllers\Backend\Management\NoteController;
use App\Http\Controllers\Backend\Management\ProjectController;
use App\Http\Controllers\Backend\Management\DiscussionController;

Route::group(['middleware' => ['xss','admin', 'TimeZone'], 'prefix' => 'admin/project'], function () {

    // Projects route
    Route::controller(ProjectController::class)->group(function () {
        Route::any('/', 'index')->name('project.index')->middleware('PermissionCheck:project_list');


        Route::post('status-change', 'statusUpdate')->name('hrm.project.statusUpdate')->middleware('PermissionCheck:project_update');
        Route::post('delete-data', 'deleteData')->name('hrm.project.deleteData')->middleware('PermissionCheck:project_delete');


        Route::get('/datatable', 'datatable')->name('project.datatable')->middleware('PermissionCheck:project_list');
        Route::get('create', 'create')->name('project.create')->middleware('PermissionCheck:project_create');
        // Route::post('store', 'store')->name('project.store')->middleware('PermissionCheck:project_store');
        Route::get('edit/{id}', 'edit')->name('project.edit')->middleware('PermissionCheck:project_edit');
        // Route::post('update/{id}', 'update')->name('project.update')->middleware('PermissionCheck:project_update');
        Route::get('delete/{id}', 'delete')->name('project.delete')->middleware('PermissionCheck:project_delete');
        Route::get('view/{id}/{data}', 'view')->name('project.view')->middleware('PermissionCheck:project_view');

        Route::get('member-delete/{id}', 'member_delete')->name('project.member_delete')->middleware('PermissionCheck:project_member_delete');
        Route::get('complete', 'complete')->name('project.complete')->middleware('PermissionCheck:project_complete');

        // get project task list
        Route::get('task-table/{id}', 'task_table')->name('project_task.table')->middleware('PermissionCheck:task_list');
        // project payment list
        Route::get('payment-modal/{id}', 'payment_modal')->name('project_modal.payment')->middleware('PermissionCheck:project_payment');
        Route::post('payment/{id}', 'payment')->name('project.payment')->middleware('PermissionCheck:project_payment');
        // project payment list

        // project invoice list
        Route::get('invoice/{id}', 'invoice')->name('project.invoice')->middleware('PermissionCheck:project_invoice_view');
        // project invoice list


        // discussion route
        Route::controller(DiscussionController::class)->prefix('discussion')->group(function () {
            Route::get('/datatable/{id}', 'datatable')->name('project.discussion.datatable')->middleware('PermissionCheck:discussion_list');
            Route::get('create', 'create')->name('project.discussion.create')->middleware('PermissionCheck:discussion_create');
            Route::post('store', 'store')->name('project.discussion.store')->middleware('PermissionCheck:discussion_store');
            Route::get('edit/{id}', 'edit')->name('project.discussion.edit')->middleware('PermissionCheck:discussion_edit');
            Route::post('update/{id}', 'update')->name('project.discussion.update')->middleware('PermissionCheck:discussion_update');
            Route::get('delete/{id}', 'delete')->name('project.discussion.delete')->middleware('PermissionCheck:discussion_delete');
            Route::get('view/{id}/{data}', 'view')->name('project.discussion.view')->middleware('PermissionCheck:discussion_view');


            // all deleted
            Route::post('delete-data/{id}', 'deleteData')->name('project.discussion.deleteData')->middleware('PermissionCheck:discussion_delete');
        });

        // note route 
        Route::controller(NoteController::class)->prefix('note')->group(function () {
            Route::get('create', 'create')->name('project.note.create')->middleware('PermissionCheck:project_notes_create');
            Route::post('store', 'store')->name('project.note.store')->middleware('PermissionCheck:project_notes_store');
            Route::get('edit', 'edit')->name('project.note.edit')->middleware('PermissionCheck:project_notes_edit');
            Route::post('update/{id}', 'update')->name('project.note.update')->middleware('PermissionCheck:project_notes_update');
            Route::get('delete/{id}', 'delete')->name('project.note.delete')->middleware('PermissionCheck:project_notes_delete');
        });
        // note route 

        // start file route
        Route::controller(FileController::class)->prefix('file')->group(function () {
            Route::get('create', 'create')->name('project.file.create')->middleware('PermissionCheck:project_file_create');
            Route::get('/datatable/{id}', 'datatable')->name('project.file.datatable')->middleware('PermissionCheck:project_file_list');

            Route::post('store', 'store')->name('project.file.store')->middleware('PermissionCheck:project_file_store');
            Route::get('/download', 'download')->name('project.file.download')->middleware('PermissionCheck:project_file_download');

            Route::get('edit', 'edit')->name('project.file.edit')->middleware('PermissionCheck:project_files_edit');
            Route::post('update/{id}', 'update')->name('project.file.update')->middleware('PermissionCheck:project_files_update');
            Route::get('delete/{id}', 'delete')->name('project.file.delete')->middleware('PermissionCheck:project_files_delete');
            Route::get('view/{id}/{data}', 'view')->name('project.file.view')->middleware('PermissionCheck:project_files_view');


            Route::post('delete-data/{id}', 'deleteData')->name('project.file.deleteData')->middleware('PermissionCheck:project_files_delete');
        });
        // end file route
    });


});

Route::group(['middleware' => ['admin', 'TimeZone'], 'prefix' => 'admin/project'], function () {

    // Projects route
    Route::controller(ProjectController::class)->group(function () {
        Route::post('store', 'store')->name('project.store')->middleware('PermissionCheck:project_store');
        Route::post('update/{id}', 'update')->name('project.update')->middleware('PermissionCheck:project_update');

        // discussion route
        Route::controller(DiscussionController::class)->prefix('discussion')->group(function () {
            // discussion comment route
            Route::post('comment', 'comment')->name('project.discussion.comment')->middleware('PermissionCheck:project_discussion_comment');
        });
        // start file route
        Route::controller(FileController::class)->prefix('file')->group(function () {
            // files comment routes
            Route::post('comment', 'comment')->name('project.file.comment')->middleware('PermissionCheck:project_file_comment');
        });
        // end file route
    });


});