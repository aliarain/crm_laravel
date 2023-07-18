<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\Task\TaskController;
use App\Http\Controllers\Backend\Task\TaskFileController;
use App\Http\Controllers\Backend\Task\TaskNoteController;
use App\Http\Controllers\Backend\Task\TaskDiscussionController;

Route::group(['middleware' => ['xss', 'admin', 'TimeZone'], 'prefix' => 'admin/task'], function () {

    // task route
    Route::controller(TaskController::class)->group(function () {
        Route::any('/', 'index')->name('task.index')->middleware('PermissionCheck:task_list');


        Route::post('status-change', 'statusUpdate')->name('hrm.task.statusUpdate')->middleware('PermissionCheck:task_update');
        Route::post('delete-data', 'deleteData')->name('hrm.task.deleteData')->middleware('PermissionCheck:task_delete');

        Route::get('/table', 'table')->name('task.table')->middleware('PermissionCheck:task_list');
        Route::get('create', 'create')->name('task.create')->middleware('PermissionCheck:task_create');
        Route::get('edit/{id}', 'edit')->name('task.edit')->middleware('PermissionCheck:task_edit');
        Route::get('view/{id}/{slug}', 'view')->name('task.view')->middleware('PermissionCheck:task_view');

        Route::get('delete/{id}', 'delete')->name('task.delete')->middleware('PermissionCheck:task_delete');
        Route::get('member-delete/{id}', 'member_delete')->name('task.member_delete')->middleware('PermissionCheck:project_member_delete');

        Route::get('complete', 'complete')->name('task.complete');
        // task.update.status
        Route::any('update-status', 'taskUpdateStatus')->name('task.update.status')->middleware('PermissionCheck:task_update');



        // discussion route
        Route::controller(TaskDiscussionController::class)->prefix('discussion')->group(function () {
            Route::get('/table/{id}', 'table')->name('task.discussion.table')->middleware('PermissionCheck:task_discussion_list');
            Route::get('create', 'create')->name('task.discussion.create')->middleware('PermissionCheck:task_discussion_create');
            Route::post('store', 'store')->name('task.discussion.store')->middleware('PermissionCheck:task_discussion_store');
            Route::get('edit/{id}', 'edit')->name('task.discussion.edit')->middleware('PermissionCheck:task_discussion_edit');
            Route::post('update/{id}', 'update')->name('task.discussion.update')->middleware('PermissionCheck:task_discussion_update');
            Route::get('delete/{id}', 'delete')->name('task.discussion.delete')->middleware('PermissionCheck:task_discussion_delete');
            Route::get('view/{id}/{data}', 'view')->name('task.discussion.view')->middleware('PermissionCheck:task_discussion_view');

            // discussion comment route

            Route::post('delete-data/{id}', 'deleteData')->name('task.discussion.deleteData')->middleware('PermissionCheck:task_discussion_delete');
        });

        // note route 
        Route::controller(TaskNoteController::class)->prefix('note')->group(function () {
            Route::get('create', 'create')->name('task.note.create')->middleware('PermissionCheck:task_notes_create');
            Route::post('store', 'store')->name('task.note.store')->middleware('PermissionCheck:task_notes_store');
            Route::get('edit', 'edit')->name('task.note.edit')->middleware('PermissionCheck:task_notes_edit');
            Route::post('update/{id}', 'update')->name('task.note.update')->middleware('PermissionCheck:task_notes_update');
            Route::get('delete/{id}', 'delete')->name('task.note.delete')->middleware('PermissionCheck:task_notes_delete');
        });
        // note route 

        // start file route
        Route::controller(TaskFileController::class)->prefix('file')->group(function () {
            Route::get('create', 'create')->name('task.file.create')->middleware('PermissionCheck:task_file_create');
            Route::post('store', 'store')->name('task.file.store')->middleware('PermissionCheck:task_file_store');
            Route::get('table/{id}', 'table')->name('task.file.table')->middleware('PermissionCheck:task_file_list');
            Route::get('download', 'download')->name('task.file.download')->middleware('PermissionCheck:task_file_download');

            // files comment routes

            Route::get('edit', 'edit')->name('task.file.edit')->middleware('PermissionCheck:task_files_edit');
            Route::post('update/{id}', 'update')->name('task.file.update')->middleware('PermissionCheck:task_files_update');
            Route::get('delete/{id}', 'delete')->name('task.file.delete')->middleware('PermissionCheck:task_files_delete');
            Route::get('view/{id}/{data}', 'view')->name('task.file.view')->middleware('PermissionCheck:task_files_view');

            Route::post('delete-data/{id}', 'deleteData')->name('task.file.deleteData')->middleware('PermissionCheck:task_files_delete');
        });
        // end file route
    });
});

Route::group(['middleware' => ['admin', 'TimeZone'], 'prefix' => 'admin/task'], function () {

    // task route
    Route::controller(TaskController::class)->group(function () {

        Route::post('store', 'store')->name('task.store')->middleware('PermissionCheck:task_store');
        Route::post('update/{id}', 'update')->name('task.update')->middleware('PermissionCheck:task_update');

        // discussion route
        Route::controller(TaskDiscussionController::class)->prefix('discussion')->group(function () {
            // discussion comment route
            Route::post('comment', 'comment')->name('task.discussion.comment')->middleware('PermissionCheck:task_discussion_comment');
        });

        // start file route
        Route::controller(TaskFileController::class)->prefix('file')->group(function () {
            // files comment routes
            Route::post('comment', 'comment')->name('task.file.comment')->middleware('PermissionCheck:task_file_comment');
        });
        // end file route
    });
});
