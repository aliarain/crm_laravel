<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\Setting\MenuController;
use App\Http\Controllers\Backend\Setting\ServiceController;
use App\Http\Controllers\Backend\Setting\FrontTeamController;
use App\Http\Controllers\Backend\Setting\PortfolioController;
use App\Http\Controllers\Frontend\Content\AllContentController;

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'admin']], function () {

    Route::group(['prefix' => 'content'], function () {
        Route::get('/list',                   [AllContentController::class, 'list'])->name('content.list')->middleware('PermissionCheck:content_menu');
        Route::get('data-table',              [AllContentController::class, 'dataTable'])->name('dataTable.content')->middleware('PermissionCheck:content_menu');
        Route::get('/create',                 [AllContentController::class, 'create'])->name('content.create')->middleware('PermissionCheck:content_create');
        Route::any('edit/{allContent}',       [AllContentController::class, 'edit'])->name('content.edit')->middleware('PermissionCheck:content_update')->where('allContent', '[0-9]+');
        Route::patch('update/{allContent}',   [AllContentController::class, 'update'])->name('content.update')->middleware('PermissionCheck:content_update')->where('content_update', '[0-9]+');
        Route::get('delete/{allContent}',     [AllContentController::class, 'delete'])->name('content.delete')->middleware('PermissionCheck:content_delete')->where('allContent', '[0-9]+');
    });

    // Menu

    Route::group(['prefix' => 'menu', 'middleware' => 'xss'], function () {
        Route::get('/',                       [MenuController::class, 'index'])->name('menu.index')->middleware('PermissionCheck:menu');
        Route::get('/create',                 [MenuController::class, 'create'])->name('menu.create')->middleware('PermissionCheck:menu_create');
        Route::post('delete-data',            [MenuController::class, 'deleteData'])->name('menu.delete_data')->middleware('PermissionCheck:menu_delete');
        Route::post('status-change',          [MenuController::class, 'statusUpdate'])->name('menu.statusUpdate')->middleware('PermissionCheck:menu_update');
        Route::get('edit/{id}',               [MenuController::class, 'edit'])->name('menu.edit')->middleware('PermissionCheck:menu_edit');
        Route::get('delete/{id}',             [MenuController::class, 'delete'])->name('menu.delete')->middleware('PermissionCheck:menu_delete');
        Route::post('update/{id}',            [MenuController::class, 'update'])->name('menu.update')->middleware('PermissionCheck:menu_update');


        Route::post('store',                 [MenuController::class, 'store'])->name('menu.store')->middleware('PermissionCheck:menu_store');
    });


    // service routes start
    Route::group(['prefix' => 'service'], function () {
        Route::get('/',                       [ServiceController::class, 'index'])->name('service.index')->middleware('PermissionCheck:service_menu');
        Route::get('/create',                 [ServiceController::class, 'create'])->name('service.create')->middleware('PermissionCheck:service_create');
        Route::post('delete-data',            [ServiceController::class, 'deleteData'])->name('service.delete_data')->middleware('PermissionCheck:service_delete');
        Route::post('status-change',          [ServiceController::class, 'statusUpdate'])->name('service.statusUpdate')->middleware('PermissionCheck:service_update');
        Route::get('edit/{id}',               [ServiceController::class, 'edit'])->name('service.edit')->middleware('PermissionCheck:service_edit');
        Route::get('delete/{id}',             [ServiceController::class, 'delete'])->name('service.delete')->middleware('PermissionCheck:service_delete');
        Route::post('update/{id}',            [ServiceController::class, 'update'])->name('service.update')->middleware('PermissionCheck:service_update');
        Route::get('data-table',              [ServiceController::class, 'dataTable'])->name('dataTable.service')->middleware('PermissionCheck:service_menu');
        Route::post('/store',                 [ServiceController::class, 'store'])->name('service.store')->middleware('PermissionCheck:service_store');
    });

    // portfolio routes start
    Route::group(['prefix' => 'portfolio'], function () {
        Route::get('/',                       [PortfolioController::class, 'index'])->name('portfolio.index')->middleware('PermissionCheck:portfolio_menu');
        Route::get('create',                  [PortfolioController::class, 'create'])->name('portfolio.create')->middleware('PermissionCheck:portfolio_create');
        Route::post('delete-data',            [PortfolioController::class, 'deleteData'])->name('portfolio.delete_data')->middleware('PermissionCheck:portfolio_delete');
        Route::post('status-change',          [PortfolioController::class, 'statusUpdate'])->name('portfolio.statusUpdate')->middleware('PermissionCheck:portfolio_update');
        Route::get('edit/{id}',               [PortfolioController::class, 'edit'])->name('portfolio.edit')->middleware('PermissionCheck:portfolio_edit');
        Route::get('delete/{id}',             [PortfolioController::class, 'delete'])->name('portfolio.delete')->middleware('PermissionCheck:portfolio_delete');
        Route::post('update/{id}',            [PortfolioController::class, 'update'])->name('portfolio.update')->middleware('PermissionCheck:portfolio_update');
        Route::get('data-table',              [PortfolioController::class, 'dataTable'])->name('dataTable.portfolio')->middleware('PermissionCheck:portfolio_menu');
        Route::post('store',                  [PortfolioController::class, 'store'])->name('portfolio.store')->middleware('PermissionCheck:portfolio_store');
    });

    // team member routes start
    Route::group(['prefix' => 'team-member'], function () {
        Route::get('/',                       [FrontTeamController::class, 'index'])->name('team-member.index')->middleware('PermissionCheck:team_member_menu');
        Route::get('create',                  [FrontTeamController::class, 'create'])->name('team-member.create')->middleware('PermissionCheck:team_member_create');
        Route::post('delete-data',            [FrontTeamController::class, 'deleteData'])->name('team-member.delete_data')->middleware('PermissionCheck:team_member_delete');
        Route::post('status-change',          [FrontTeamController::class, 'statusUpdate'])->name('team-member.statusUpdate')->middleware('PermissionCheck:team_member_update');
        Route::get('edit/{id}',               [FrontTeamController::class, 'edit'])->name('team-member.edit')->middleware('PermissionCheck:team_member_edit');
        Route::get('delete/{id}',             [FrontTeamController::class, 'delete'])->name('team-member.delete')->middleware('PermissionCheck:team_member_delete');
        Route::post('update/{id}',            [FrontTeamController::class, 'update'])->name('team-member.update')->middleware('PermissionCheck:team_member_update');
        Route::get('data-table',              [FrontTeamController::class, 'dataTable'])->name('dataTable.team-member')->middleware('PermissionCheck:team_member_menu');
        Route::post('store',                  [FrontTeamController::class, 'store'])->name('team-member.store')->middleware('PermissionCheck:team_member_store');
    });


});
