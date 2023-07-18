<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\Payroll\SalaryController;
use App\Http\Controllers\Backend\Finance\DepositController;
use App\Http\Controllers\Backend\Payroll\AdvanceController;
use App\Http\Controllers\Backend\Payroll\CommissionController;
use App\Http\Controllers\Backend\Finance\TransactionController;
use App\Http\Controllers\Backend\Payroll\AdvanceTypeController;
use App\Http\Controllers\Backend\Payroll\SalarySetUpController;
use App\Http\Controllers\Backend\Finance\PayrollAccountController;



Route::group(['middleware' => ['xss','admin', 'TimeZone'], 'prefix' => 'hrm'], function () {
   // payroll commission route
   Route::controller(CommissionController::class)->prefix('payroll/item')->group(function () {
      Route::any('/', 'index')->name('hrm.payroll_items.index')->middleware('PermissionCheck:list_payroll_item');
      Route::get('create', 'create')->name('hrm.payroll_items.create')->middleware('PermissionCheck:create_payroll_item');

      Route::post('delete-data',   'deleteData')->name('payroll_items.delete_data')->middleware('PermissionCheck:delete_payroll_item');
      Route::post('status-change', 'statusUpdate')->name('payroll_items.statusUpdate')->middleware('PermissionCheck:update_payroll_item');

      Route::get('edit/{id}', 'edit')->name('hrm.payroll_items.edit')->middleware('PermissionCheck:edit_payroll_item');
      Route::post('update/{id}/{company_id}', 'update')->name('hrm.payroll_items.update')->middleware('PermissionCheck:update_payroll_item');

      Route::get('/datatable', 'datatable')->name('hrm.payroll_items.datatable')->middleware('PermissionCheck:list_payroll_item');
      Route::post('store', 'store')->name('hrm.payroll_items.store')->middleware('PermissionCheck:store_payroll_item');
      Route::get('delete/{id}', 'delete')->name('hrm.payroll_items.delete')->middleware('PermissionCheck:delete_payroll_item');
      Route::get('show/{id}', 'show')->name('hrm.payroll_items.show')->middleware('PermissionCheck:view_payroll_item');
   });

   // payroll salary setup route
   Route::controller(SalarySetUpController::class)->prefix('payroll')->group(function () {
      Route::any('setup', 'index')->name('hrm.payroll_setup.index')->middleware('PermissionCheck:list_payroll_set');
      // set commission
      Route::get('user-commission-set/{id}', 'commissionSetUp')->name('hrm.payroll_setup.user_commission_setup')->middleware('PermissionCheck:view_payroll_set');
      Route::get('get-item-list-modal', 'item_list_create_modal')->name('hrm.payroll_setup.item_list')->middleware('PermissionCheck:create_payroll_set');
      Route::post('create-salary-setup/{id}', 'create_user_commission_setup')->name('hrm.payroll_setup.create_user_commission_setup')->middleware('PermissionCheck:store_payroll_set');

      Route::get('edit-set-commission-modal/{id}', 'item_list_edit_modal')->name('hrm.payroll_setup.item_list_edit_modal')->middleware('PermissionCheck:update_payroll_set');
      Route::post('update-commission-setup/{id}', 'update_user_commission_setup')->name('hrm.payroll_setup.update_user_commission_setup')->middleware('PermissionCheck:update_payroll_set');

      Route::get('delete-salary-setup/{id}', 'delete_salary_setup')->name('hrm.payroll_setup.delete_salary_setup')->middleware('PermissionCheck:delete_payroll_set');


      Route::post('update-salary-setup/{id}', 'update_salary_setup')->name('hrm.payroll_setup.update_salary_setup')->middleware('PermissionCheck:update_payroll_set');
      Route::get('user-setup/{id}/{slug}', 'profileSetUp')->name('hrm.payroll_setup.user_setup')->middleware('PermissionCheck:view_payroll_set');
      Route::post('user-setup-update/{id}/{slug}', 'profileSetUpdate')->name('hrm.payroll_setup.user_setup_update')->middleware('PermissionCheck:view_payroll_set');

      Route::get('setup/datatable', 'data')->name('hrm.payroll_setup.datatable')->middleware('PermissionCheck:list_payroll_set');
      Route::get('setup/{id}', 'setup')->name('hrm.payroll_setup.setup')->middleware('PermissionCheck:view_payroll_set');
      // 

      Route::post('store-salary-setup', 'store_salary_setup')->name('hrm.payroll_setup.store_salary_setup')->middleware('PermissionCheck:store_payroll_set');
      Route::get('edit-salary-setup/{id}', 'edit_salary_setup')->name('hrm.payroll_setup.edit_salary_setup')->middleware('PermissionCheck:edit_payroll_set');
   });


   // payroll advance type route

   Route::controller(AdvanceTypeController::class)->prefix('payroll/advance-type')->group(function () {
      Route::any('/', 'index')->name('hrm.payroll_advance_type.index')->middleware('PermissionCheck:advance_type_list');
      Route::get('create', 'create')->name('hrm.payroll_advance_type.create')->middleware('PermissionCheck:advance_type_create');
      Route::get('edit/{id}', 'edit')->name('hrm.payroll_advance_type.edit')->middleware('PermissionCheck:advance_type_edit');

      Route::get('/datatable', 'datatable')->name('hrm.payroll_advance_type.datatable')->middleware('PermissionCheck:advance_type_list');
      Route::post('store', 'store')->name('hrm.payroll_advance_type.store')->middleware('PermissionCheck:advance_type_store');
      Route::post('update/{id}/{company_id}', 'update')->name('hrm.payroll_advance_type.update')->middleware('PermissionCheck:advance_type_update');
      Route::get('delete/{id}', 'delete')->name('hrm.payroll_advance_type.delete')->middleware('PermissionCheck:advance_type_delete');
      Route::get('show/{id}', 'show')->name('hrm.payroll_advance_type.show')->middleware('PermissionCheck:advance_type_view');


      Route::post('status-change',           'statusUpdate')->name('payroll_advance_type.statusUpdate')->middleware('PermissionCheck:advance_type_update');
      Route::post('delete-data',             'deleteData')->name('payroll_advance_type.delete_data')->middleware('PermissionCheck:advance_type_delete');
   });

   // payroll advance salary route

   Route::controller(AdvanceController::class)->prefix('payroll/advance-salary')->group(function () {
      Route::any('/', 'index')->name('hrm.payroll_advance_salary.index')->middleware('PermissionCheck:advance_salaries_list');

      Route::get('/datatable', 'datatable')->name('hrm.payroll_advance_salary.datatable')->middleware('PermissionCheck:advance_salaries_list');

      Route::get('create', 'create')->name('hrm.payroll_advance_salary.create')->middleware('PermissionCheck:advance_salaries_create');
      Route::post('store', 'store')->name('hrm.payroll_advance_salary.store')->middleware('PermissionCheck:advance_salaries_store');
      Route::get('show/{id}', 'show')->name('hrm.payroll_advance_salary.show')->middleware('PermissionCheck:advance_salaries_view');
      Route::get('edit/{id}', 'edit')->name('hrm.payroll_advance_salary.edit')->middleware('PermissionCheck:advance_salaries_edit');
      Route::post('update/{id}/{company_id}', 'update')->name('hrm.payroll_advance_salary.update')->middleware('PermissionCheck:advance_salaries_update');
      Route::get('delete/{id}', 'delete')->name('hrm.payroll_advance_salary.delete')->middleware('PermissionCheck:advance_salaries_delete');

      Route::get('approve-modal/{id}', 'approveModal')->name('hrm.payroll_advance_salary.approve_modal')->middleware('PermissionCheck:advance_salaries_approve');

      Route::post('approve/{id}', 'approve')->name('hrm.payroll_advance_salary.approve')->middleware('PermissionCheck:advance_salaries_approve');
      Route::get('pay/{id}', 'pay')->name('hrm.payroll_advance_salary.pay')->middleware('PermissionCheck:advance_salaries_pay');
      Route::post('pay-store/{id}', 'payStore')->name('hrm.payroll_advance_salary.pay_store')->middleware('PermissionCheck:advance_salaries_pay');
      Route::get('invoice/{id}', 'invoice')->name('hrm.payroll_advance_salary.invoice')->middleware('PermissionCheck:advance_salaries_invoice');
   });


   // Salary Route
   Route::controller(SalaryController::class)->prefix('payroll/salary')->group(function () {
      Route::any('/', 'index')->name('hrm.payroll_salary.index')->middleware('PermissionCheck:salary_list');
      Route::get('/datatable', 'datatable')->name('hrm.payroll_salary.datatable')->middleware('PermissionCheck:salary_list');
      Route::get('generate-modal', 'generateModal')->name('hrm.payroll_salary.generate_modal')->middleware('PermissionCheck:salary_generate');
      Route::post('generate', 'generate')->name('hrm.payroll_salary.generate')->middleware('PermissionCheck:salary_generate');


      Route::post('store', 'store')->name('hrm.payroll_salary.store')->middleware('PermissionCheck:salary_store');
      Route::get('edit/{id}', 'edit')->name('hrm.payroll_salary.edit')->middleware('PermissionCheck:salary_edit');
      Route::post('update/{id}/{company_id}', 'update')->name('hrm.payroll_salary.update')->middleware('PermissionCheck:salary_update');
      Route::get('delete/{id}', 'delete')->name('hrm.payroll_salary.delete')->middleware('PermissionCheck:salary_delete');
      Route::get('show/{id}', 'show')->name('hrm.payroll_salary.show')->middleware('PermissionCheck:salary_view');

      //calculate salary route
      Route::get('calculate-modal/{id}', 'calculateModal')->name('hrm.payroll_salary.calculate_modal')->middleware('PermissionCheck:salary_calculate');
      Route::post('calculate/{id}', 'calculate')->name('hrm.payroll_salary.calculate')->middleware('PermissionCheck:salary_calculate');


      Route::get('pay/{id}', 'pay')->name('hrm.payroll_salary.pay')->middleware('PermissionCheck:salary_pay');
      Route::post('pay-store/{id}', 'payStore')->name('hrm.payroll_salary.pay_store')->middleware('PermissionCheck:salary_pay');
      Route::get('payslip/{id}', 'invoice')->name('hrm.payroll_salary.invoice')->middleware('PermissionCheck:salary_invoice');
   });
});
