<?php

use App\Http\Controllers\Leads\LeadController;
use App\Http\Controllers\Leads\LeadStatusController;
use App\Http\Controllers\Leads\LeadTypeController;
use App\Http\Controllers\Leads\ProposalController;
use App\Http\Controllers\Leads\SourceController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['xss', 'admin', 'TimeZone'], 'prefix' => 'admin/leads'], function () {

    // Leads route
    Route::middleware(['PermissionCheck:client_list'])->group(function () {

        // create calls to the lead
        Route::post('create-call',      [LeadController::class, 'createCall'])->name('lead.createCall');
        Route::any('/',                 [LeadController::class, 'index'])->name('lead.index');
        Route::get('datatable',         [LeadController::class, 'datatable'])->name('lead.datatable');
        Route::get('create',            [LeadController::class, 'create'])->name('lead.create')->middleware('PermissionCheck:client_create');
        Route::post('/status-change',   [LeadController::class, 'statusUpdate'])->name('lead.statusUpdate')->middleware('PermissionCheck:client_update');
        Route::post('/delete-data',     [LeadController::class, 'deleteData'])->name('lead.deleteData')->middleware('PermissionCheck:client_delete');
        Route::get('lead-details/{id}', [LeadController::class, 'leadDetails'])->name('lead.leadDetails');
        Route::any('ajax/details',      [LeadController::class, 'ajaxLeadDetails'])->name('lead.ajaxLeadDetails');
        Route::post('download',         [LeadController::class, 'download'])->name('lead.download');
        Route::any('delete-attachment', [LeadController::class, 'deleteAttachment'])->name('lead.deleteAttachment');
        
        Route::post('/',                [LeadController::class, 'store'])->name('lead.store')->middleware('PermissionCheck:client_store');
        Route::get('{id}/edit',         [LeadController::class, 'edit'])->name('lead.edit')->middleware('PermissionCheck:client_edit');
        Route::post('update',           [LeadController::class, 'update'])->name('lead.update')->middleware('PermissionCheck:client_update');
        Route::get('/{id}',             [LeadController::class, 'show'])->name('lead.show')->middleware('PermissionCheck:client_view');
        Route::get('delete/{id}',       [LeadController::class, 'delete'])->name('lead.delete')->middleware('PermissionCheck:client_delete');
        Route::post('ajax/details/store',[LeadController::class, 'storeLeadDetails'])->name('lead.details.store');
        // leadSummaryCategoryWise
    });
});



Route::group(['middleware' => ['xss', 'admin', 'TimeZone'], 'prefix' => 'admin/ajax'], function () {
    Route::any('lead-summary-category-wise',        [LeadController::class, 'leadSummaryCategoryWise'])->name('ajax.lead-summary-category-wise');
});

Route::group(['middleware' => ['xss', 'admin', 'TimeZone'], 'prefix' => 'admin/proposals'], function () {
    // start source route
    Route::middleware(['PermissionCheck:client_list'])->group(function () {
        Route::any('/',             [ProposalController::class, 'index'])->name('proposal.index');
        Route::get('datatable',     [ProposalController::class, 'datatable'])->name('proposal.datatable');
        Route::get('create',        [ProposalController::class, 'create'])->name('proposal.create')->middleware('PermissionCheck:client_create');
        Route::post('/',            [ProposalController::class, 'store'])->name('proposal.store')->middleware('PermissionCheck:client_store');
        Route::get('{id}/edit',     [ProposalController::class, 'edit'])->name('proposal.edit')->middleware('PermissionCheck:client_edit');
        Route::put('/{id}',         [ProposalController::class, 'update'])->name('proposal.update')->middleware('PermissionCheck:client_update');
        Route::get('/{id}',         [ProposalController::class, 'show'])->name('proposal.show')->middleware('PermissionCheck:client_view');
        Route::delete('/{id}',      [ProposalController::class, 'destroy'])->name('proposal.delete')->middleware('PermissionCheck:client_delete');
        Route::post('/status-change',[ProposalController::class, 'statusUpdate'])->name('proposal.statusUpdate')->middleware('PermissionCheck:client_update');
        Route::post('/delete-data',  [ProposalController::class, 'deleteData'])->name('proposal.deleteData')->middleware('PermissionCheck:client_delete');
    });
});

// START:: LEAD TYPE ROUTES
Route::group(['middleware' => ['xss', 'admin', 'TimeZone'], 'prefix' => 'admin/types'], function () {
    // start source route
    Route::middleware(['PermissionCheck:client_list'])->group(function () {
        Route::any('/',                 [LeadTypeController::class, 'index'])->name('type.index');
        Route::get('datatable',         [LeadTypeController::class, 'datatable'])->name('type.datatable');
        Route::get('create',            [LeadTypeController::class, 'create'])->name('type.create');
        Route::post('/',                [LeadTypeController::class, 'store'])->name('type.store');
        Route::get('{id}/edit',         [LeadTypeController::class, 'edit'])->name('type.edit');
        Route::post('update',           [LeadTypeController::class, 'update'])->name('type.update');
        Route::get('/{id}',             [LeadTypeController::class, 'show'])->name('type.show');
        Route::get('delete/{id}',       [LeadTypeController::class, 'destroy'])->name('type.delete');
        Route::post('/status-change',   [LeadTypeController::class, 'statusUpdate'])->name('type.statusUpdate');
        Route::post('/delete-data',     [LeadTypeController::class, 'deleteData'])->name('type.deleteData');
    });
});

// START:: LEAD SOURCE  ROUTES
Route::group(['middleware' => ['xss', 'admin', 'TimeZone'], 'prefix' => 'admin/sources'], function () {
    // start source route
    Route::middleware(['PermissionCheck:client_list'])->group(function () {
        Route::any('/',                 [SourceController::class, 'index'])->name('source.index');
        Route::get('datatable',         [SourceController::class, 'datatable'])->name('source.datatable');
        Route::get('create',            [SourceController::class, 'create'])->name('source.create');
        Route::post('/',                [SourceController::class, 'store'])->name('source.store');
        Route::get('{id}/edit',         [SourceController::class, 'edit'])->name('source.edit');
        Route::post('update',           [SourceController::class, 'update'])->name('source.update');
        Route::get('/{id}',             [SourceController::class, 'show'])->name('source.show');
        Route::get('delete/{id}',       [SourceController::class, 'destroy'])->name('source.delete');
        Route::post('/status-change',   [SourceController::class, 'statusUpdate'])->name('source.statusUpdate');
        Route::post('/delete-data',     [SourceController::class, 'deleteData'])->name('source.deleteData');
    });
});

// START:: LEAD STATUS ROUTES
Route::group(['middleware' => ['xss', 'admin', 'TimeZone'], 'prefix' => 'admin/statuses'], function () {
    // start source route
    Route::middleware(['PermissionCheck:client_list'])->group(function () {
        Route::any('/',                 [LeadStatusController::class, 'index'])->name('status.index');
        Route::get('datatable',         [LeadStatusController::class, 'datatable'])->name('status.datatable');
        Route::get('create',            [LeadStatusController::class, 'create'])->name('status.create');
        Route::post('/',                [LeadStatusController::class, 'store'])->name('status.store');
        Route::get('{id}/edit',         [LeadStatusController::class, 'edit'])->name('status.edit');
        Route::post('update',           [LeadStatusController::class, 'update'])->name('status.update');
        Route::get('/{id}',             [LeadStatusController::class, 'show'])->name('status.show');
        Route::get('delete/{id}',       [LeadStatusController::class, 'destroy'])->name('status.delete');
        Route::post('/status-change',   [LeadStatusController::class, 'statusUpdate'])->name('status.statusUpdate');
        Route::post('/delete-data',     [LeadStatusController::class, 'deleteData'])->name('status.deleteData');
    });
});
