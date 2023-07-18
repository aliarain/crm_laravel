<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\Finance\DepositController;
use App\Http\Controllers\Backend\Finance\ExpenseController;
use App\Http\Controllers\Backend\Finance\CategoryController;
use App\Http\Controllers\Backend\Finance\TransactionController;
use App\Http\Controllers\Backend\Finance\PaymentMethodController;
use App\Http\Controllers\Backend\Finance\PayrollAccountController;



Route::group(['middleware' => ['xss','admin', 'TimeZone'], 'prefix' => 'hrm'], function () {

    // Accounts route
    Route::controller(PayrollAccountController::class)->prefix('accounts')->group(function () {
        Route::any('/', 'index')->name('hrm.accounts.index')->middleware('PermissionCheck:account_list');
        Route::post('status-change', 'statusUpdate')->name('hrm.accounts.statusUpdate')->middleware('PermissionCheck:account_update');
        Route::post('delete-data', 'deleteData')->name('hrm.accounts.deleteData')->middleware('PermissionCheck:account_delete');

        Route::get('/datatable', 'datatable')->name('hrm.accounts.datatable')->middleware('PermissionCheck:account_list');
        Route::get('create', 'create')->name('hrm.accounts.create')->middleware('PermissionCheck:account_create');
        Route::post('store', 'store')->name('hrm.accounts.store')->middleware('PermissionCheck:account_store');
        Route::get('edit/{id}', 'edit')->name('hrm.accounts.edit')->middleware('PermissionCheck:account_edit');
        Route::post('update/{id}/{company_id}', 'update')->name('hrm.accounts.update')->middleware('PermissionCheck:account_update');
        Route::get('delete/{id}', 'delete')->name('hrm.accounts.delete')->middleware('PermissionCheck:account_delete');
        Route::get('show/{id}', 'show')->name('hrm.accounts.show')->middleware('PermissionCheck:account_view');

        //balance sheet
        Route::get('balance-sheet', 'balanceSheet')->name('hrm.accounts.balance-sheet')->middleware('PermissionCheck:account_list');
    });

    // transactions route

    Route::controller(TransactionController::class)->prefix('transactions')->group(function () {
        Route::any('/', 'index')->name('hrm.transactions.index')->middleware('PermissionCheck:transaction_list');

        Route::get('/datatable', 'datatable')->name('hrm.transactions.datatable')->middleware('PermissionCheck:transaction_list');
        Route::get('create', 'create')->name('hrm.transactions.create')->middleware('PermissionCheck:transaction_create');
        Route::post('store', 'store')->name('hrm.transactions.store')->middleware('PermissionCheck:transaction_store');
        Route::get('edit/{id}', 'edit')->name('hrm.transactions.edit')->middleware('PermissionCheck:transaction_edit');
        Route::post('update/{id}/{company_id}', 'update')->name('hrm.transactions.update')->middleware('PermissionCheck:transaction_update');
        Route::get('delete/{id}', 'delete')->name('hrm.transactions.delete')->middleware('PermissionCheck:transaction_delete');
        Route::get('show/{id}', 'show')->name('hrm.transactions.show')->middleware('PermissionCheck:transaction_view');
    });

    // Deposits route
    Route::controller(DepositController::class)->prefix('deposit')->group(function () {

        Route::any('/', 'index')->name('hrm.deposits.index')->middleware('PermissionCheck:deposit_list');
        Route::post('delete-data', 'deleteData')->name('hrm.deposits.deleteData')->middleware('PermissionCheck:deposit_delete');


        Route::get('/datatable', 'datatable')->name('hrm.deposits.datatable')->middleware('PermissionCheck:deposit_list');
        Route::get('create', 'create')->name('hrm.deposits.create')->middleware('PermissionCheck:deposit_create');
        Route::post('store', 'store')->name('hrm.deposits.store')->middleware('PermissionCheck:deposit_store');
        Route::get('edit/{id}', 'edit')->name('hrm.deposits.edit')->middleware('PermissionCheck:deposit_edit');
        Route::post('update/{id}/{company_id}', 'update')->name('hrm.deposits.update')->middleware('PermissionCheck:deposit_update');
        Route::get('delete/{id}', 'delete')->name('hrm.deposits.delete')->middleware('PermissionCheck:deposit_delete');
    });

    // Expenses route
    Route::controller(ExpenseController::class)->prefix('expenses')->group(function () {
        Route::any('/', 'index')->name('hrm.expenses.index')->middleware('PermissionCheck:expense_list');
        Route::post('delete-data', 'deleteData')->name('hrm.expenses.deleteData')->middleware('PermissionCheck:expense_delete');

        Route::get('/datatable', 'datatable')->name('hrm.expenses.datatable')->middleware('PermissionCheck:expense_list');
        Route::get('create', 'create')->name('hrm.expenses.create')->middleware('PermissionCheck:expense_create');
        Route::post('store', 'store')->name('hrm.expenses.store')->middleware('PermissionCheck:expense_store');
        Route::get('edit/{id}', 'edit')->name('hrm.expenses.edit')->middleware('PermissionCheck:expense_edit');
        Route::post('update/{id}/{company_id}', 'update')->name('hrm.expenses.update')->middleware('PermissionCheck:expense_update');
        Route::get('delete/{id}', 'delete')->name('hrm.expenses.delete')->middleware('PermissionCheck:expense_delete');
        Route::get('show/{id}', 'show')->name('hrm.expenses.show')->middleware('PermissionCheck:expense_view');


        Route::get('approve-modal/{id}', 'approveModal')->name('hrm.expenses.approve_modal')->middleware('PermissionCheck:expense_approve');

        Route::post('approve/{id}', 'approve')->name('hrm.expenses.approve')->middleware('PermissionCheck:expense_approve');
        Route::get('pay/{id}', 'pay')->name('hrm.expenses.pay')->middleware('PermissionCheck:expense_pay');
        Route::post('pay-store/{id}', 'payStore')->name('hrm.expenses.pay_store')->middleware('PermissionCheck:expense_pay');
        Route::get('invoice/{id}', 'invoice')->name('hrm.expenses.invoice')->middleware('PermissionCheck:expense_invoice');
    });



    // categories route for account settings

    Route::controller(CategoryController::class)->prefix('account-settings')->group(function () {
        
        Route::get('/datatable/{type}', 'datatable')->name('hrm.deposit_category.datatable')->middleware('PermissionCheck:deposit_category_list');
        Route::get('create', 'create')->name('hrm.deposit_category.create')->middleware('PermissionCheck:deposit_category_create');
        Route::post('store', 'store')->name('hrm.deposit_category.store')->middleware('PermissionCheck:deposit_category_store');
        Route::get('edit/{id}', 'edit')->name('hrm.deposit_category.edit')->middleware('PermissionCheck:deposit_category_edit');
        Route::post('update/{id}', 'update')->name('hrm.deposit_category.update')->middleware('PermissionCheck:deposit_category_update');
        Route::get('delete/{id}', 'delete')->name('hrm.deposit_category.delete')->middleware('PermissionCheck:deposit_category_delete');

        //deposit category
        Route::any('deposit', 'deposit')->name('hrm.deposit_category.deposit')->middleware('PermissionCheck:deposit_category_list');

        Route::post('status-change', 'statusUpdate')->name('hrm.deposit_category.statusUpdate')->middleware('PermissionCheck:deposit_category_update');
        Route::post('delete-data', 'deleteData')->name('hrm.deposit_category.deleteData')->middleware('PermissionCheck:deposit_category_delete');

        Route::any('expense', 'expense')->name('hrm.deposit_category.expense')->middleware('PermissionCheck:deposit_category_list');
    });

    Route::controller(PaymentMethodController::class)->prefix('payment-methods')->group(function () {
        Route::any('/', 'index')->name('hrm.payment_method.index')->middleware('PermissionCheck:payment_method_list');


        Route::post('status-change', 'statusUpdate')->name('hrm.payment_method.statusUpdate')->middleware('PermissionCheck:payment_method_update');
        Route::post('delete-data', 'deleteData')->name('hrm.payment_method.deleteData')->middleware('PermissionCheck:payment_method_delete');

        Route::get('/datatable', 'datatable')->name('hrm.payment_method.datatable')->middleware('PermissionCheck:payment_method_list');
        Route::get('create', 'create')->name('hrm.payment_method.create')->middleware('PermissionCheck:payment_method_create');
        Route::post('store', 'store')->name('hrm.payment_method.store')->middleware('PermissionCheck:payment_method_store');
        Route::get('edit/{id}', 'edit')->name('hrm.payment_method.edit')->middleware('PermissionCheck:payment_method_edit');
        Route::post('update/{id}', 'update')->name('hrm.payment_method.update')->middleware('PermissionCheck:payment_method_update');
        Route::get('delete/{id}', 'delete')->name('hrm.payment_method.delete')->middleware('PermissionCheck:payment_method_delete');
        Route::get('show/{id}', 'show')->name('hrm.payment_method.show')->middleware('PermissionCheck:payment_method_view');
    });
});
