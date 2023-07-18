
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\Visit\VisitController;
use App\Http\Controllers\Backend\Leave\LeaveTypeController;
use App\Http\Controllers\Backend\Expense\HrmExpenseController;
use App\Http\Controllers\Backend\Report\BreakReportController;
use App\Http\Controllers\Backend\Report\AttendanceReportController;

Route::group(['prefix' => 'hrm', 'middleware' => ['xss','admin', 'MaintenanceMode']], function () {

    //report start
    Route::group(['prefix' => 'report'], function () {
        Route::get('attendance',                               [AttendanceReportController::class, 'index'])->name('attendanceReport.index')->middleware('PermissionCheck:attendance_report_read');
        // attendance details start
        Route::get('check-in-details',                         [AttendanceReportController::class, 'checkInDetails'])->name('attendance.checkInDetails')->middleware('PermissionCheck:attendance_update');
        // Start live tracking
        Route::get('live-tracking',                            [AttendanceReportController::class, 'liveTracking'])->name('live_trackingReport.index')->middleware('PermissionCheck:attendance_report_read');
        Route::get('live-tracking-employee',                   [AttendanceReportController::class, 'liveTrackingEmployee'])->name('live_trackingReportEmployee.index')->middleware('PermissionCheck:attendance_report_read');
        Route::get('location-history',                         [AttendanceReportController::class, 'locationHistory'])->name('live_trackingReportHistory.index')->middleware('PermissionCheck:attendance_report_read');
        Route::get('employee-location-history',                [AttendanceReportController::class, 'employeeLocationHistory'])->name('locationReportHistory.index')->middleware('PermissionCheck:attendance_report_read');
        //End live tracking
        Route::get('attendance-history/{user}',                [AttendanceReportController::class, 'employeeAttendanceHistory'])->name('employeeAttendance');
        Route::get('list-data',                                [AttendanceReportController::class, 'reportDataTable'])->name('attendanceReport.dataTable');
        Route::get('single-attendance-list-data/{user}',       [AttendanceReportController::class, 'singleReportDataTable'])->name('singleAttendanceReport.dataTable');
        Route::post('single-attendance-summary-report/{user}', [AttendanceReportController::class, 'singleAttendanceSummaryReport'])->name('singleAttendanceSummaryReport');
    });
    //report end

    //break start
    Route::group(['prefix' => 'break'], function () {
        Route::get('list',                                     [BreakReportController::class, 'index'])->name('breakReport.index')->middleware('PermissionCheck:attendance_report_read');
        Route::get('data-table',                               [BreakReportController::class, 'dataTable'])->name('breakReport.dataTable')->middleware('PermissionCheck:attendance_report_read');
    });
    //break end

    //expense start
    Route::group(['prefix' => 'expense'], function () {
        Route::get('/',                                    [HrmExpenseController::class, 'index'])->name('expense.index')->middleware('PermissionCheck:expense_read');
        Route::get('edit/{expense}',                       [HrmExpenseController::class, 'show'])->name('expense.edit')->middleware('PermissionCheck:expense_update');
        Route::get('approve-or-reject/{expense}/{status}', [HrmExpenseController::class, 'approveOrReject'])->name('expense.approveOrReject')->middleware('PermissionCheck:expense_approve_or_reject');
        Route::get('data-table',                           [HrmExpenseController::class, 'expenseDatatable'])->name('expense.dataTable')->middleware('PermissionCheck:expense_read');
        //expense end

        //claim start
        Route::group(['prefix' => 'claim'], function () {
            Route::get('/',                                    [HrmExpenseController::class, 'claimIndex'])->name('claim.index')->middleware('PermissionCheck:claim_read');
            Route::get('edit/{expense}',                       [HrmExpenseController::class, 'show'])->name('claim.edit')->middleware('PermissionCheck:claim_update');
            Route::get('get-payment-info/{expenseClaim}',      [HrmExpenseController::class, 'showExpenseClaim'])->name('expenseClaim.show');
            Route::post('claim-amount-payment/{expenseClaim}', [HrmExpenseController::class, 'claimAmountPay'])->name('claim.amount.pay');
            Route::post('get-payment-info',                    [HrmExpenseController::class, 'showExpenseClaim'])->name('claim.payNow');
            Route::get('data-table',                           [HrmExpenseController::class, 'claimDatatable'])->name('claim.dataTable');
        });
        //claim end

        //payment start
        Route::group(['prefix' => 'payment'], function () {
            Route::get('/',                                   [HrmExpenseController::class, 'index'])->name('payment.index')->middleware('PermissionCheck:payment_read');
            Route::get('edit/{expense}',                      [HrmExpenseController::class, 'show'])->name('payment.edit')->middleware('PermissionCheck:payment_update');
            Route::get('data-table',                          [HrmExpenseController::class, 'expenseDatatable'])->name('payment.dataTable')->middleware('PermissionCheck:payment_read');
        });
        //payment start
    });

    Route::group(['prefix' => 'report'], function () {
        Route::get('visit',                                        [VisitController::class, 'index'])->name('report_visit.index');
    });
    //Visit end

    Route::group(['prefix' => 'report'], function () {
        Route::get('leave',                           [LeaveTypeController::class, 'index'])->name('report_leave.index')->middleware('PermissionCheck:leave_type_read');
    });
});
