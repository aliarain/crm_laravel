<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\V1\CrmApiController;
use App\Http\Controllers\Api\Visit\VisitController;
use App\Http\Controllers\Api\Auth\ProfileController;
use App\Http\Controllers\Backend\Event\EventController;
use App\Http\Controllers\Api\Leave\DailyLeaveController;
use App\Http\Controllers\Backend\Notice\NoticeController;
use App\Http\Controllers\Api\Leave\LeaveRequestController;
use App\Http\Controllers\Api\Report\BreakReportController;
use App\Http\Controllers\Backend\Finance\ExpenseController;
use App\Http\Controllers\Backend\Meeting\MeetingController;
use App\Http\Controllers\Api\Auth\FaceRecognitionController;
use App\Http\Controllers\Api\Employee\AppointmentController;
use App\Http\Controllers\Api\Appreciate\AppreciateController;
use App\Http\Controllers\Api\Attendance\AttendanceController;
use App\Http\Controllers\Backend\Firebase\FirebaseController;
use App\Http\Controllers\Backend\Expense\HrmExpenseController;
use App\Http\Controllers\coreApp\Setting\AppSettingsController;
use App\Http\Controllers\Api\Report\Leave\LeaveReportController;
use App\Http\Controllers\Backend\Support\SupportTicketController;
use App\Http\Controllers\Backend\Expense\ExpenseCategoryController;
use App\Http\Controllers\Api\Core\Settings\ProfileUpdateSettingController;
use App\Http\Controllers\Api\Report\Attendance\AttendanceReportController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

Route::group(['middleware' => ['api', 'cors', 'TimeZone', 'MaintenanceMode'], 'prefix' => 'V11'], function () {
    Route::post('login',                                [AuthController::class,'login']);
    Route::post('reset-password',                       [AuthController::class, 'sendResetLinkEmail']);
    Route::post('change-password',                      [AuthController::class, 'changePassword']);

    Route::group(['middleware' => ['auth:api', 'cors']], function () {

        Route::post('check-token',                      [AuthController::class, 'checkDeviceToken']);

        Route::group(['prefix' => 'user'], function () {
            Route::post('firebase-token',               [FirebaseController::class, 'firebaseToken']);
            Route::post('profile/{slug}',               [ProfileController::class, 'profile']);
            Route::get('details/{id}',                  [ProfileController::class, 'details']);
            Route::post('profile/update/{slug}',        [ProfileController::class, 'profileUpdate']);
            Route::post('password-update',              [ProfileController::class, 'passwordUpdate']);
            Route::post('avatar-update',                [ProfileController::class, 'avatarImageUpdate']);
            Route::get('notification',                  [ProfileController::class, 'notification']);
            Route::get('read-notification',             [ProfileController::class, 'readNotification']);
            Route::get('notification/clear',            [ProfileController::class, 'notificationClear']);
            Route::get('search/{keywords?}',            [ProfileController::class, 'getUserList']);

            //face recognition
            Route::post('face-recognition', [FaceRecognitionController::class, 'faceRecognition']);
            Route::get('get-face-data', [FaceRecognitionController::class, 'getFaceData']);

            Route::post('face-recognition-update', [FaceRecognitionController::class, 'faceRecognitionUpdate']);
            Route::post('face-recognition-delete', [FaceRecognitionController::class, 'faceRecognitionDelete']);

            Route::group(['prefix' => 'leave'], function () {
                Route::post('summary',                      [LeaveRequestController::class, 'leaveSummary']);
                Route::post('available',                    [LeaveRequestController::class, 'getAvailableLeave']);
                Route::post('list/view',                    [LeaveRequestController::class, 'leaveListView']);
                Route::post('request',                      [LeaveRequestController::class, 'store']);
                Route::post('details/{id}',                 [LeaveRequestController::class, 'leaveDetails']);
                Route::post('request/edit/{id}',            [LeaveRequestController::class, 'leaveDetails']);
                Route::post('request/update/{id}',          [LeaveRequestController::class, 'update']);
                Route::get('request/cancel/{id}',           [LeaveRequestController::class, 'cancelLeaveRequest']);

                Route::group(['prefix' => 'approval'], function () {
                    Route::post('list/view',                    [LeaveRequestController::class, 'approvalLeaveList']);
                    Route::get('status-change/{id}/{status}',   [LeaveRequestController::class, 'approveOrRejectLeaveRequest']);
                });

                //team member leaves
                Route::group(['prefix' => 'team-member'], function () {
                    Route::post('/',                                            [LeaveRequestController::class, 'teamMemberLeaveList']);
                    Route::get('leave-request-approval/{leave_id}/{status_id}', [LeaveRequestController::class, 'statusChange']);
                });
            });
            Route::group(['prefix' => 'attendance'], function () {
                Route::post('break-back/list/view',             [AttendanceController::class, 'breakBackListView']);
                Route::post('break-back/{slug}',                [AttendanceController::class, 'breakBack']);
                Route::any('break-status',                      [AttendanceController::class, 'breakBackHistory']);
                Route::post('break-history',                    [AttendanceController::class, 'breakBackHistory']);
                Route::post('user-break-history',               [AttendanceController::class, 'userBreakHistory']);
                Route::post('get-checkin-checkout-status',      [AttendanceController::class, 'checkInCheckoutStatus']);
                Route::post('check-in',                         [AttendanceController::class, 'checkIn']);
                Route::post('live-location-store',              [AttendanceController::class, 'liveLocationStore']);
                Route::patch('check-out/{attendance_id}',       [AttendanceController::class, 'checkOut']);
                Route::post('late-in-reason/{attendance_id}',   [AttendanceController::class, 'lateInOutReason']);
                Route::post('/attendance-from-device',          [AttendanceController::class, 'attendanceFromDevice'])->withoutMiddleware('auth:api');
            });
        });

        Route::group(['prefix' => 'app'], function () {
            Route::get('get-all-users/{designation}',       [ProfileUpdateSettingController::class, 'getDesignationWiseUsers']);
            Route::get('get-department',                    [ProfileUpdateSettingController::class, 'getDepartment']);
            Route::get('delete-department',                 [ProfileUpdateSettingController::class, 'deleteDepartment']);
            Route::post('store-department',                 [ProfileUpdateSettingController::class, 'storeDepartment']);
            Route::post('update-department',                [ProfileUpdateSettingController::class, 'UpdateDepartment']);
            Route::get('get-designation',                   [ProfileUpdateSettingController::class, 'getDesignation']);
            Route::get('delete-designation',                [ProfileUpdateSettingController::class, 'deleteDesignation']);
            Route::post('store-designation',                [ProfileUpdateSettingController::class, 'storeDesignation']);
            Route::post('update-designation',               [ProfileUpdateSettingController::class, 'updateDesignation']);
            Route::get('get-employment-type',               [ProfileUpdateSettingController::class, 'getEmployment']);
            Route::get('get-blood-group',                   [ProfileUpdateSettingController::class, 'getBloodGroup']);
            Route::post('get-users',                        [ProfileUpdateSettingController::class, 'getUsers'])->name('getUsers');
            Route::get('base-settings',                     [AppSettingsController::class, 'baseSettings']);
            Route::get('new-teammate',                      [AppSettingsController::class, 'newTeamMate']);
            Route::get('get-ip-address',                    [AppSettingsController::class, 'getIpAddress']);
            Route::get('all-contents/{slug}',               [AppSettingsController::class, 'allContents']);
        });

        Route::group(['prefix' => 'expense'], function () {
            Route::get('category',                      [ExpenseCategoryController::class, 'getExpenseCategory']);
            Route::post('list',                         [HrmExpenseController::class, 'expenseList']);
            Route::get('single-expense/{expense}',      [HrmExpenseController::class, 'show']);
            Route::post('add',                          [HrmExpenseController::class, 'store']);
            Route::post('update/{expense_id}',          [HrmExpenseController::class, 'expenseUpdate']);
            Route::delete('delete/{expense}',           [HrmExpenseController::class, 'delete']);
            Route::post('show/{expense}',               [ExpenseCategoryController::class, 'getExpenseCategory']);
            Route::post('send-claim',                   [HrmExpenseController::class, 'claimSend']);
            Route::post('claim-history',                [HrmExpenseController::class, 'claimHistory']);
            Route::post('claim-details/{id}',           [HrmExpenseController::class, 'claimDetails']);
            Route::post('payment-history',              [HrmExpenseController::class, 'paymentHistory']);
        });

        Route::group(['prefix' => 'accounts'], function () {

            Route::group(['prefix' => 'expense'], function () {
                Route::get('category-list',                 [ExpenseController::class, 'CategoryList']);
                Route::post('add',                          [ExpenseController::class, 'UserExpenseStore']);
                Route::post('list',                         [ExpenseController::class, 'UserExpenseList']);
                Route::get('view/{expense_id}',             [ExpenseController::class, 'UserExpenseView']);
            });
        });

        Route::prefix('visit')->group(function () {
            Route::get('/list',                             [VisitController::class, 'getVisitList']);
            Route::post('/history',                         [VisitController::class, 'getVisitHistory']);
            Route::post('/create',                          [VisitController::class, 'createVisit']);
            Route::get('/show/{visit_id}',                  [VisitController::class, 'getVisitById']);
            Route::post('/update',                          [VisitController::class, 'updateVisit']);
            Route::post('/image-upload',                    [VisitController::class, 'uploadImage']);
            Route::get('/images/{visit_id}',                [VisitController::class, 'visitImages']);
            Route::get('/remove-image/{visit_id}/{image_id}',[VisitController::class, 'removeVisitImage']);
            Route::post('/change-status',                    [VisitController::class, 'changeVisitStatus']);
            Route::post('/create-note',                      [VisitController::class, 'createNote']);
            Route::post('/create-schedule',                  [VisitController::class, 'createSchedule']);
        });

        Route::prefix('appreciate')->group(function () {
            Route::get('/list',                         [AppreciateController::class, 'index']);
            Route::post('/create',                      [AppreciateController::class, 'store']);
        });

        Route::prefix('dashboard')->group(function () {
            Route::get('/statistics',                   [DashboardController::class, 'statistics']);
        });

        Route::group(['prefix' => 'support-ticket'], function () {
            Route::post('add',                          [SupportTicketController::class, 'store']);
            Route::post('list',                         [SupportTicketController::class, 'listView']);
            Route::get('show/{id}',                     [SupportTicketController::class, 'show']);
        });

        Route::group(['prefix' => 'notice'], function () {
            Route::post('add',                          [NoticeController::class, 'storeNotice']);
            Route::post('list',                         [NoticeController::class, 'listView']);
            Route::get('show/{id}',                     [NoticeController::class, 'show']);
            Route::get('clear',                         [NoticeController::class, 'clear']);
        });

        Route::prefix('appoinment')->group(function () {
            Route::post('/get-list',                    [AppointmentController::class, 'index']);
            Route::get('/details/{id}',                 [AppointmentController::class, 'getDetails']);
            Route::post('/create',                      [AppointmentController::class, 'store']);
            Route::post('/change-status',               [AppointmentController::class, 'appoinmentChangeStatus']);
            Route::post('/update',                      [AppointmentController::class, 'update']);
            Route::get('/delete',                       [AppointmentController::class, 'delete']);
        });

        Route::prefix('upcoming-events')->group(function () {
            Route::post('/get-list', [EventController::class, 'index']);
        });

        Route::group(['prefix' => 'meeting'], function () {
            Route::post('/',                        [MeetingController::class, 'meetingList']);
            Route::post('create',                   [MeetingController::class, 'store']);
            Route::get('show/{meeting_id}',         [MeetingController::class, 'show']);
            Route::post('add/participants',         [MeetingController::class, 'addParticipants']);
            Route::get('participants/{meeting_id}', [MeetingController::class, 'participants']);
        });

        Route::group(['prefix' => 'report'], function () {
            //Attendance Report
            Route::group(['prefix' => 'attendance'], function () {
                Route::post('particular-month/{user}',  [AttendanceReportController::class, 'userMonthlyAttendanceReport']);
                Route::post('particular-date',          [AttendanceReportController::class, 'userDailyAttendanceReport']);
                Route::post('date-summary',             [AttendanceReportController::class, 'dateSummary'])->middleware('PermissionCheck:attendance_report_read');
                Route::post('summary-to-list',          [AttendanceReportController::class, 'summaryToList'])->middleware('PermissionCheck:attendance_report_read');
            });
            //Break Route group
            Route::group(['prefix' => 'break'], function () {
                Route::post('date-summary',         [BreakReportController::class, 'dateSummary'])->middleware('PermissionCheck:attendance_report_read');
                Route::post('user-break-history',   [BreakReportController::class, 'userBreakHistory'])->middleware('PermissionCheck:attendance_report_read');
            });
            //Leave Route group
            Route::group(['prefix' => 'leave'], function () {
                Route::post('date-summary',         [LeaveReportController::class, 'dateSummary']);
                Route::post('date-wise-leave',      [LeaveReportController::class, 'dateSummaryList'])->middleware('PermissionCheck:leave_request_read');
                Route::post('user-wise-list',       [LeaveReportController::class, 'dateUserLeaveList'])->middleware('PermissionCheck:leave_request_read');
            });
        });

        Route::prefix('daily-leave')->group(function () {
            Route::post('/leave-list',              [DailyLeaveController::class, 'monthlySummeryView'])->name('daily-leave.monthlySummeryView');
            Route::post('/store',                   [DailyLeaveController::class, 'store'])->name('daily-leave.store');
            Route::any('/list',                     [DailyLeaveController::class, 'listView'])->name('daily-leave.list');
            Route::post('/staff-list-view',         [DailyLeaveController::class, 'staffListView'])->name('daily-leave.staffListView');
            Route::post('/approve-reject',          [DailyLeaveController::class, 'approveRejectLeave'])->name('daily-leave.approveReject');
            Route::post('/single-view',             [DailyLeaveController::class, 'LeaveView'])->name('daily-leave.LeaveView');
        });
    });
});

Route::group(['middleware' => ['api', 'auth:api', 'cors', 'TimeZone', 'MaintenanceMode'], 'prefix' => 'V11'], function () {

    Route::group(['prefix' => 'app'], function () {

        Route::get('dashboard-screen',              [CrmApiController::class, 'AppDashboardScreen']);
        Route::get('home-screen',                   [CrmApiController::class, 'AppHomeScreen']);
        Route::get('users-list/{type}/{id}',        [CrmApiController::class, 'UsersList']);

        Route::group(['prefix' => 'projects'], function () {
            Route::get('/',                         [CrmApiController::class, 'AppProjectsScreen']);
            Route::get('/status',                   [CrmApiController::class, 'GetAppProjectsStatusList']);
            Route::get('/list',                     [CrmApiController::class, 'AppProjectsList']);
            Route::get('/details/{project_id}',     [CrmApiController::class, 'AppProjectsDetails']);
            Route::post('/store',                   [CrmApiController::class, 'AppProjectsStore']);
            Route::get('/delete/{project_id}',      [CrmApiController::class, 'AppProjectsDelete']);
            Route::post('/update',                  [CrmApiController::class, 'AppProjectsUpdate']);
            Route::post('/file-upload',             [CrmApiController::class, 'AppProjectsFileUpload']);
            Route::get('/change-status',            [CrmApiController::class, 'AppTProjectsChangeStatus']);
        });
        Route::group(['prefix' => 'clients'], function () {
            Route::get('/',                         [CrmApiController::class, 'AppClientsScreen']);
            Route::get('/list',                     [CrmApiController::class, 'AppClientsList']);
            Route::get('/details',                  [CrmApiController::class, 'AppClientsDetails']);
            Route::get('/delete/{id}',              [CrmApiController::class, 'AppClientsDelete']);
            Route::get('/projects-list/{client_id}',[CrmApiController::class, 'AppClientsProjects']);
            Route::post('/store',                   [CrmApiController::class, 'AppClientsStore']);
            Route::post('/update',                  [CrmApiController::class, 'AppClientsUpdate']);
        });

        Route::group(['prefix' => 'employees'], function () {
            Route::get('/',             [CrmApiController::class, 'AppEmployeesScreen']);
            Route::get('/list',         [CrmApiController::class, 'AppEmployeeList']);
            Route::get('/details',      [CrmApiController::class, 'AppEmployeeDetails']);
            Route::post('/store',       [CrmApiController::class, 'AppEmployeeStore']);
            Route::get('/delete/{id}',  [CrmApiController::class, 'AppEmployeeDelete']);
            Route::post('/update',      [CrmApiController::class, 'AppEmployeeUpdate']);
        });

        Route::group(['prefix' => 'sales'], function () {
            Route::get('/', [CrmApiController::class, 'AppSalesScreen']);
        });

        Route::group(['prefix' => 'stock'], function () {
            Route::get('/',             [CrmApiController::class, 'AppStockScreen']);

            Route::group(['prefix' => 'purchase'], function () {
                Route::get('/',         [CrmApiController::class, 'AppPurchaseProducts']);
            });

            Route::group(['prefix' => 'categories'], function () {
                Route::get('/',         [CrmApiController::class, 'AppStockCategories']);
            });
            Route::group(['prefix' => 'brands'], function () {
                Route::get('/',         [CrmApiController::class, 'AppStockBrands']);
            });
            Route::group(['prefix' => 'products'], function () {
                Route::get('/',         [CrmApiController::class, 'AppStockProducts']);
            });
        });

        //98989
        Route::group(['prefix' => 'tasks'], function () {
            Route::get('/',                     [CrmApiController::class, 'AppTaskScreen']);
            Route::get('/change-status',        [CrmApiController::class, 'AppTaskChangeStatus']);
            Route::get('/delete',               [CrmApiController::class, 'AppTaskDelete']);
            Route::get('/list',                 [CrmApiController::class, 'AppTaskList']);
            Route::get('/create',               [CrmApiController::class, 'AppTaskCreate']);
            Route::get('/{task_id}',            [CrmApiController::class, 'AppTaskDetails']);
            Route::get('/delete/{task_id}',     [CrmApiController::class, 'AppTaskDelete']);
            Route::post('/store',               [CrmApiController::class, 'AppTaskStore']);
            Route::post('/update',              [CrmApiController::class, 'AppTaskUpdate']);
            Route::post('/store-comment',       [CrmApiController::class, 'AppTaskStoreComment']);
            Route::post('/update-comment',      [CrmApiController::class, 'AppTaskUpdateComment']);
            Route::get('/delete-comment/{id}',  [CrmApiController::class, 'AppTaskDeleteComment']);
            Route::post('/like-feedback',       [CrmApiController::class, 'AppTaskLikeFeedback']);
        });

        Route::group(['prefix' => 'income'], function () {
            Route::get('/',             [CrmApiController::class, 'AppIncomeScreen']);
            Route::get('/list',         [CrmApiController::class, 'AppIncomeList']);
            Route::get('/create',       [CrmApiController::class, 'AppIncomeCreate']);
            Route::post('/add',         [CrmApiController::class, 'AppIncomeAdd']);
        });

        Route::group(['prefix' => 'accounts'], function () {
            Route::get('/',             [CrmApiController::class, 'AppAccountsScreen']);
        });
    });
});
