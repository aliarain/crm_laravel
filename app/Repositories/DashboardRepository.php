<?php

namespace App\Repositories;

use App\Enums\AttendanceStatus;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Helpers\CoreApp\Traits\DateHandler;
use App\Helpers\CoreApp\Traits\TimeDurationTrait;
use App\Models\Accounts\IncomeExpense;
use App\Models\Company\Company;
use App\Models\coreApp\Relationship\RelationshipTrait;
use App\Models\Expenses\PaymentHistory;
use App\Models\Finance\Deposit;
use App\Models\Finance\Expense;
use App\Models\Hrm\Appoinment\Appoinment;
use App\Models\Hrm\AppSetting\AppScreen;
use App\Models\Hrm\Attendance\Attendance;
use App\Models\Hrm\Leave\LeaveRequest;
use App\Models\Hrm\Meeting\Meeting;
use App\Models\Hrm\Support\SupportTicket;
use App\Models\Leads\Lead;
use App\Models\Leads\LeadSource;
use App\Models\Management\Client;
use App\Models\Management\Project;
use App\Models\TaskManagement\Task;
use App\Models\User;
use App\Models\Visit\Visit;
use App\Repositories\Hrm\Attendance\AttendanceRepository;
use App\Repositories\Report\AttendanceReportRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Sale\Entities\Sale;

class DashboardRepository
{

    use ApiReturnFormatTrait, DateHandler, RelationshipTrait, TimeDurationTrait;

    protected $attendanceReportRepository;
    protected $attendance;
    protected $attendanceRepository;
    protected $appointment;
    protected $meeting;

    public function __construct(AttendanceReportRepository $attendanceReportRepository, Attendance $attendance, AttendanceRepository $attendanceRepository, Appoinment $appointment, Meeting $meeting)
    {
        $this->attendanceReportRepository = $attendanceReportRepository;
        $this->attendance = $attendance;
        $this->attendanceRepository = $attendanceRepository;
        $this->appointment = $appointment;
        $this->meeting = $meeting;
    }

    public function getIncomeExpenseGraph($request)
    {
        if (!empty($request->time)) {
            $year = $request->time;
        } else {
            $year = date("Y");
        }
        $months = [];
        $income = [];
        $expense = [];
        for ($i = 1; $i <= 12; $i++) {
            $month = date("m", mktime(0, 0, 0, $i, 1, $year));
            $total_income = IncomeExpense::where('type', 1)->whereYear('created_at', $year)->whereMonth('created_at', $month)->sum('amount');
            $total_expense = IncomeExpense::where('type', 2)->whereYear('created_at', $year)->whereMonth('created_at', $month)->sum('amount');
            if (empty($total_income)) {
                $total_income = 0;
            }
            $months[] = date('M', strtotime($i . '-' . $month . '-' . $year));
            $income[] = $total_income;
            $expense[] = $total_expense;
        }
        $data['label'] = $months;
        $data['income'] = $income;
        $data['expense'] = $expense;

        return $data;
    }

    public function getStatisticsImage($level)
    {
        $app_dashboard = config()->get('hrm.dashboard_images');
        return $app_dashboard[$level]['path'];
    }

    public function getNewStatisticsImage($level)
    {
        $app_dashboard = config()->get('hrm.company_dashboard_images');
        return @$app_dashboard[$level]['path'];
    }

    public function getDashboardStatistics($request)
    {
        try {

            $date = date('Y-m-d');
            $time = explode('-', $date);
            $year = $time[0];
            $month = $time[1];
            // $date = $time[2];

            $screen_data = AppScreen::where('status_id', 1)->pluck('slug')->toArray();
            if (in_array('appointments', $screen_data)) {
                $data['today'][] = [
                    'image' => asset($this->getStatisticsImage('appoinment')),
                    'title' => 'Appointments',
                    'slug' => 'appointment',
                    'number' => Appoinment::where('date', $date)
                        ->where(function ($query) {
                            $query->where('created_by', auth()->user()->id)
                                ->orWhere('appoinment_with', auth()->user()->id);
                        })
                        ->count(),
                ];
            }

            $data['today'][] = [
                'image' => asset($this->getStatisticsImage('meeting')),
                'title' => 'Meetings',
                'slug' => 'meeting',
                'number' => Meeting::where('date', $date)->where('user_id', auth()->user()->id)->count(),
            ];
            if (in_array('visit', $screen_data)) {
                $data['today'][] = [
                    'image' => asset($this->getStatisticsImage('visit')),
                    'title' => 'Visit',
                    'slug' => 'visit',
                    'number' => Visit::where('date', $date)->where('user_id', auth()->user()->id)->count(),
                ];
            }

            if (in_array('support', $screen_data)) {
                $data['today'][] = [
                    'image' => asset($this->getStatisticsImage('support')),
                    'title' => 'Support Tickets',
                    'slug' => 'support_ticket',
                    'number' => SupportTicket::where('date', $date)
                        ->where('user_id', auth()->user()->id)
                        ->orWhere('assigned_id', auth()->user()->id)
                        ->where('status_id', 1)
                        ->where('type_id', 12)
                        ->count(),
                ];
            }
            //Current Month
            $request['month'] = null;
            $attendance_data = $this->attendanceReportRepository->singleAttendanceSummary(auth()->user(), $request);

            $data['current_month'][] = [
                'image' => asset($this->getStatisticsImage('late')),
                'title' => 'Late In',
                'slug' => 'late_in',
                'number' => str_replace(' days', '', $attendance_data['total_late_in']),
            ];
            if (in_array('leave', $screen_data)) {
                $data['current_month'][] = [
                    'image' => asset($this->getStatisticsImage('leave')),
                    'title' => 'Leave',
                    'slug' => 'leave',
                    'number' => str_replace(' days', '', $attendance_data['total_leave']),
                ];
            }
            if (in_array('daily-leave', $screen_data)) {
                $data['current_month'][] = [
                    'image' => asset($this->getStatisticsImage('early-leave')),
                    'title' => 'Early Leave',
                    'slug' => 'early_leave',
                    'number' => str_replace(' days', '', $attendance_data['total_left_early']),
                ];
            }
            $data['current_month'][] = [
                'image' => asset($this->getStatisticsImage('absent')),
                'title' => 'Absent',
                'slug' => 'absent',
                'number' => str_replace(' days', '', $attendance_data['absent']),
            ];
            if (in_array('visit', $screen_data)) {

                $data['current_month'][] = [
                    'image' => asset($this->getStatisticsImage('visit')),
                    'title' => 'Visits',
                    'slug' => 'visits',
                    'number' => Visit::where('date', 'LIKE', '%' . $year . '-' . $month . '%')
                        ->where('user_id', auth()->user()->id)
                        ->count(),
                ];
            }

            $data['current_month'][] = [
                'image' => asset($this->getStatisticsImage('rewards')),
                'title' => 'Rewards',
                'slug' => 'rewards',
                'number' => "0",
            ];

            return $this->responseWithSuccess("Dashboard Statistics Data", $data, 200);
        } catch (\Throwable$exception) {
            return $this->responseWithError($exception->getMessage(), [], 500);
        }
    }

    public function getSuperadminDashboardStatistic($request)
    {
        try {

            $date = date('Y-m');

            $data['current_month'][] = [
                'image' => asset($this->getStatisticsImage('employee')),
                'title' => 'Total Company',
                'slug' => 'total_company',
                'number' => Company::where('id', '!=', 1)->count(),
            ];

            return $this->responseWithSuccess("Dashboard Statistics Data", $data, 200);
        } catch (\Throwable$exception) {
            return $this->responseWithError($exception->getMessage(), [], 500);
        }
    }
    public function new_income_expense($request)
    {
        $date = date('Y-m-d');
        $time = explode('-', $date);
        $year = $time[0];
        $month = $time[1];
    }

    public function getCompanyDashboardStatistics($request)
    {
        try {

            $date = date('Y-m-d');
            $time = explode('-', $date);
            $year = $time[0];
            $month = $time[1];
            // $date = $time[2];

            $data['today'][] = [
                'image' => asset($this->getStatisticsImage('employee')),
                'title' => _trans('dashboard.Total Employees'),
                'number' => number_format(User::where('company_id', auth()->user()->company->id)->count()),
            ];
            $data['today'][] = [
                'image' => asset($this->getStatisticsImage('expense')),
                'title' => _trans('dashboard.Total Expenses'),
                'number' => number_format(PaymentHistory::where('company_id', auth()->user()->company->id)->count()),
            ];
            $data['today'][] = [
                'image' => asset($this->getStatisticsImage('meeting')),
                'title' => _trans('dashboard.Total Meetings'),
                'number' => number_format(Meeting::where('company_id', auth()->user()->company->id)->count()),
            ];

            $data['today'][] = [
                'image' => asset($this->getStatisticsImage('support')),
                'title' => _trans('dashboard.Support Tickets'),
                'number' => number_format(SupportTicket::where('status_id', 1)
                        ->where('company_id', auth()->user()->company->id)
                        ->count()),
            ];

            //Current Month
            $request['month'] = null;
            $attendance_data = $this->attendanceReportRepository->monthlyAttendanceSummary(auth()->user(), $request);

            $data['current_month'][] = [
                'image' => asset($this->getNewStatisticsImage('late')),
                'title' => _trans('dashboard.Late In'),
                'slug' => 'late_in',
                'number' => str_replace(' days', '', $attendance_data['total_late_in']),
            ];
            $data['current_month'][] = [
                'image' => asset($this->getNewStatisticsImage('leave')),
                'title' => _trans('dashboard.Leave'),
                'slug' => 'leave',
                'number' => str_replace(' days', '', $attendance_data['total_leave']),
            ];
            $data['current_month'][] = [
                'image' => asset($this->getNewStatisticsImage('early-leave')),
                'title' => _trans('dashboard.Early Leave'),
                'slug' => 'early_leave',
                'number' => str_replace(' days', '', $attendance_data['total_left_early']),
            ];
            $data['current_month'][] = [
                'image' => asset($this->getNewStatisticsImage('absent')),
                'title' => _trans('dashboard.Absent'),
                'slug' => 'absent',
                'number' => str_replace(' days', '', $attendance_data['absent']),
            ];
            $data['current_month'][] = [
                'image' => asset($this->getNewStatisticsImage('visit')),
                'title' => _trans('dashboard.Visits'),
                'slug' => 'visits',
                'number' => Visit::where('date', 'LIKE', '%' . $year . '-' . $month . '%')
                    ->where('user_id', auth()->user()->id)
                    ->count(),
            ];

            $data['current_month'][] = [
                'image' => asset($this->getNewStatisticsImage('rewards')),
                'title' => _trans('dashboard.Rewards'),
                'slug' => 'rewards',
                'number' => "0",
            ];

            return $this->responseWithSuccess("Dashboard Statistics Data", $data, 200);
        } catch (\Throwable$exception) {
            return $this->responseWithError($exception->getMessage(), [], 500);
        }
    }

    public function getCompanyDashboardCurrentMonthStatistics($request)
    {
        try {

            $date = date('Y-m-d');
            $time = explode('-', $date);
            $year = $time[0];
            $month = $time[1];
            $date = $time[2];

            //Current Month
            $request['month'] = null;
            $attendance_data = $this->attendanceReportRepository->companyAttendanceSummary(auth()->user(), $request);
            $data['all_data'][] = [
                'data' => $attendance_data,
            ];
            $data['current_month'][] = [
                'image' => asset($this->getStatisticsImage('late')),
                'title' => _trans('dashboard.Late In'),
                'number' => str_replace(' days', '', $attendance_data['total_late_in']),
            ];
            $data['current_month'][] = [
                'image' => asset($this->getStatisticsImage('leave')),
                'title' => _trans('dashboard.Leave'),
                'number' => str_replace(' days', '', $attendance_data['total_leave']),
            ];
            $data['current_month'][] = [
                'image' => asset($this->getStatisticsImage('early-leave')),
                'title' => _trans('dashboard.Early Leave'),
                'number' => str_replace(' days', '', $attendance_data['total_left_early']),
            ];
            $data['current_month'][] = [
                'image' => asset($this->getStatisticsImage('absent')),
                'title' => _trans('dashboard.Absent'),
                'number' => str_replace(' days', '', $attendance_data['absent']),
            ];
            $data['current_month'][] = [
                'image' => asset($this->getStatisticsImage('visit')),
                'title' => _trans('dashboard.Visits'),
                'number' => Visit::where('date', 'LIKE', '%' . $year . '-' . $month . '%')
                    ->count(),
            ];

            $data['current_month'][] = [
                'image' => asset($this->getStatisticsImage('rewards')),
                'title' => _trans('dashboard.Rewards'),
                'number' => "0",
            ];

            return $this->responseWithSuccess("Dashboard Statistics Data", $data, 200);
        } catch (\Throwable$exception) {
            return $this->responseWithError($exception->getMessage(), [], 500);
        }
    }

    public function currentMonthPieChart($request)
    {
        $data = [];
        $totalPresent = 0;
        $totalAbsent = 0;
        $totalLeave = 0;
        $totalOnTimeIn = 0;
        $totalLateIn = 0;
        $totalLeftTimely = 0;
        $totalLeftEarly = 0;
        if ($request->month) {
            $monthArray = $this->getSelectedMonthDays($request->month);
        } else {
            $monthArray = $this->getCurrentMonthDays();
        }

        foreach ($monthArray as $day) {

            $todayDateInSqlFormat = $day->format('Y-m-d');
            $leaveDate = LeaveRequest::where(['company_id' => $this->companyInformation()->id, 'user_id' => auth()->id(), 'status_id' => 1])
                ->where('leave_from', '<=', $todayDateInSqlFormat)
                ->where('leave_to', '>=', $todayDateInSqlFormat)
                ->first();

            if ($leaveDate) {
                $totalLeave += 1;
            }
            $attendance = $this->attendance->query()->where(['user_id' => auth()->id(), 'date' => $todayDateInSqlFormat])->first();
            if ($attendance) {
                $totalPresent += 1;
                if ($attendance->in_status == AttendanceStatus::ON_TIME) {
                    $totalOnTimeIn += 1;
                } elseif ($attendance->in_status == AttendanceStatus::LATE) {
                    $totalLateIn += 1;
                } else {
                    $totalOnTimeIn += 1;
                }

                if ($attendance->check_out) {
                    if ($attendance->out_status == AttendanceStatus::LEFT_TIMELY || $attendance->out_status == AttendanceStatus::LEFT_LATER) {
                        $totalLeftTimely += 1;
                    } elseif ($attendance->out_status == AttendanceStatus::LEFT_EARLY) {
                        $totalLeftEarly += 1;
                    } else {
                        $totalLeftTimely += 1;
                    }
                }
            } else {
                $totalAbsent += 1;
            }
        }

        $data['leave early'] = $totalLeftEarly;
        $data['on time'] = $totalOnTimeIn;
        $data['late'] = $totalLateIn;
        $data['leave'] = $totalLeave;
        $chartArray = [];
        foreach ($data as $key => $item) {
            if ($key === 'leave early') {
                $chartArray['series'][] = $item;
                $chartArray['labels'][] = $key;
            }
            if ($key === 'leave') {
                $chartArray['series'][] = $item;
                $chartArray['labels'][] = $key;
            }
            if ($key === 'late') {
                $chartArray['series'][] = $item;
                $chartArray['labels'][] = $key;
            }
            if ($key === 'on time') {
                $chartArray['series'][] = $item;
                $chartArray['labels'][] = $key;
            }
        }
        return $this->responseWithSuccess('Pie chart', $chartArray, 200);
    }

    // new functions

    public function getProjectDashboard($id = null)
    {
        if (@$id) {
            $data = DB::table('projects')
                ->join('project_membars', 'projects.id', '=', 'project_membars.project_id')
                ->where('project_membars.user_id', auth()->id())
                ->where('projects.company_id', auth()->user()->company_id);
        } else {
            $data = DB::table('projects')->where('company_id', auth()->user()->company_id);
        }
        return [
            [
                'name' => _trans('dashboard.Cancelled'),
                'value' => $data->clone()->where('projects.status_id', 28)->count(),
            ],
            [
                'name' => _trans('dashboard.Completed'),
                'value' => $data->clone()->where('projects.status_id', 27)->count(),
            ],
            [
                'name' => _trans('dashboard.In Progress'),
                'value' => $data->clone()->where('projects.status_id', 26)->count(),
            ],
            [
                'name' => _trans('dashboard.On Hold'),
                'value' => $data->clone()->where('projects.status_id', 25)->count(),
            ],
            [
                'name' => _trans('dashboard.Not Started'),
                'value' => $data->clone()->where('projects.status_id', 24)->count(),
            ],
        ];
    }

    public function getTaskDashboard($id = null)
    {
        if (@$id) {
            $data = DB::table('tasks')
                ->join('task_members', 'tasks.id', '=', 'task_members.task_id')
                ->where('task_members.user_id', auth()->id())
                ->where('tasks.company_id', auth()->user()->company_id);
        } else {
            $data = DB::table('tasks')->where('company_id', auth()->user()->company_id);
        }
        return [
            [
                'name' => _trans('dashboard.Cancelled'),
                'value' => $data->clone()->where('tasks.status_id', 28)->count(),
            ],
            [
                'name' => _trans('dashboard.Completed'),
                'value' => $data->clone()->where('tasks.status_id', 27)->count(),
            ],
            [
                'name' => _trans('dashboard.In Progress'),
                'value' => $data->clone()->where('tasks.status_id', 26)->count(),
            ],
            [
                'name' => _trans('dashboard.On Hold'),
                'value' => $data->clone()->where('tasks.status_id', 25)->count(),
            ],
            [
                'name' => _trans('dashboard.Not Started'),
                'value' => $data->clone()->where('tasks.status_id', 24)->count(),
            ],
        ];
    }

    public function getAppointmentDashboard($id = null)
    {

        try {
            $where = [];
            if (@$id) {
                $where['created_by'] = auth()->id();
            }
            $appointment = $this->appointment->query()->where('company_id', auth()->user()->company_id)->where($where)->where('date', '>=', date('Y-m-d'))->latest()->take(6)->get()->map(function ($data) {
                return [
                    'title' => $data->title,
                    'with' => @$data->appoinmentWith->name,
                    'date_time' => showDate($data->date) . ' <small class="badge-basic-success-text"> ' . ($data->appoinment_start_at) . ' - ' . ($data->appoinment_end_at) . '</small>',
                    'location' => $data->location,
                ];
            });
            return $this->responseWithSuccess("Dashboard appointment data", $appointment, 200);
        } catch (\Throwable$th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }
    public function getMeetingDashboard($id = null)
    {

        try {
            $meeting = $this->meeting->where('company_id', auth()->user()->company_id)->where('date', '>=', date('Y-m-d'))->latest();

            if (@$id) {
                $meeting = $meeting->where('user_id', $id)->whereHas('meetingParticipants', function ($q) {
                    $q->orWhere('user_id', auth()->id());
                });
            }

            $meeting = $meeting->take(6)->get()->map(function ($data) {
                return [
                    'title' => $data->title,
                    'with' => teams(@$data->meetingParticipants),
                    'date_time' => @$data->start_at ? showDate($data->date) . ' <small class="badge-basic-success-text"> ' . ($data->start_at) . ' - ' . ($data->end_at) . '</small>' : showDate($data->date),
                    'location' => $data->location,
                ];
            });
            return $this->responseWithSuccess("Dashboard meeting data", $meeting, 200);
        } catch (\Throwable$th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }
    public function getNewDashboardStatistics($request)
    {
        try {

            $date = date('Y-m-d');
            $time = explode('-', $date);
            $year = $time[0];
            $month = $time[1];
            // $date = $time[2];

            $data['today'][] = [
                'image' => $this->getNewStatisticsImage('project'),
                'title' => _trans('dashboard.Total Projects'),
                'color_class' => 'circle-primary',
                $project = DB::table('projects')
                    ->join('project_membars', 'projects.id', '=', 'project_membars.project_id')
                    ->where('project_membars.user_id', auth()->id())
                    ->where('projects.company_id', auth()->user()->company_id)
                    ->count(),
                'number' => (number_format_short($project)),
            ];
            $data['today'][] = [
                'image' => $this->getNewStatisticsImage('tasks'),
                'title' => _trans('dashboard.Total Tasks'),
                'color_class' => 'circle-warning',
                $task = DB::table('tasks')
                    ->join('task_members', 'tasks.id', '=', 'task_members.task_id')
                    ->where('task_members.user_id', auth()->id())
                    ->where('tasks.company_id', auth()->user()->company_id)
                    ->count(),
                'number' => (number_format_short($task)),

            ];
            $data['today'][] = [
                'image' => $this->getNewStatisticsImage('visit'),
                'title' => _trans('dashboard.Total Visit'),
                'color_class' => 'circle-lightseagreen',
                'number' => number_format_short(DB::table('visits')->where('company_id', auth()->user()->company_id)->where('user_id', auth()->id())->count()),
            ];
            $data['today'][] = [
                'image' => $this->getNewStatisticsImage('appointment'),
                'title' => _trans('dashboard.Total Appointments'),
                'color_class' => 'circle-danger',
                'number' => number_format_short(DB::table('appoinments')->where('company_id', auth()->user()->company_id)->where('created_by', auth()->id())->count()),
            ];
            return $this->responseWithSuccess("Dashboard Statistics Data", $data, 200);
        } catch (\Throwable$exception) {
            return $this->responseWithError($exception->getMessage(), [], 500);
        }
    }
    public function getNewCompanyDashboardStatistics($request)
    {
        try {

            $date = date('Y-m-d');
            $time = explode('-', $date);
            $year = $time[0];
            $month = $time[1];
            // $date = $time[2];

            $data['today'][] = [
                'image' => $this->getNewStatisticsImage('employee'),
                'color_class' => 'circle-violet',
                'title' => _trans('dashboard.Total Employees'),
                'number' => number_format_short(User::where('company_id', auth()->user()->company->id)->where('status_id', 1)->count()),
            ];
            $data['today'][] = [
                'image' => $this->getNewStatisticsImage('client'),
                'color_class' => 'circle-hotpink',
                'title' => _trans('dashboard.Total Clients'),
                'number' => number_format_short(Client::where('company_id', auth()->user()->company->id)->count()),
            ];
            $data['today'][] = [
                'image' => $this->getNewStatisticsImage('expense'),
                'title' => _trans('dashboard.Total Expenses'),
                'color_class' => 'circle-brown',
                'number' => showAmount(number_format(Expense::where('company_id', auth()->user()->company_id)->where('pay', 8)->where('status_id', 5)->sum('amount'))),
            ];
            $data['today'][] = [
                'image' => $this->getNewStatisticsImage('deposit'),
                'title' => _trans('dashboard.Total Deposits'),
                'color_class' => 'circle-success',
                'number' => showAmount(number_format(Deposit::where('company_id', auth()->user()->company_id)->where('pay', 8)->where('status_id', 5)->sum('amount'))),
            ];
            $data['today'][] = [
                'image' => $this->getNewStatisticsImage('project'),
                'title' => _trans('dashboard.Total Projects'),
                'color_class' => 'circle-primary',
                'number' => (number_format_short(Project::where('company_id', auth()->user()->company_id)->count())),
            ];
            $data['today'][] = [
                'image' => $this->getNewStatisticsImage('tasks'),
                'title' => _trans('dashboard.Total Tasks'),
                'color_class' => 'circle-warning',
                'number' => (number_format_short(Task::where('company_id', auth()->user()->company_id)->count())),
            ];
            $data['today'][] = [
                'image' => $this->getNewStatisticsImage('visit'),
                'title' => _trans('dashboard.Total Visit'),
                'color_class' => 'circle-lightseagreen',
                'number' => number_format_short(Visit::where('company_id', auth()->user()->company_id)->count()),
            ];
            $data['today'][] = [
                'image' => $this->getNewStatisticsImage('appointment'),
                'title' => _trans('dashboard.Total Appointments'),
                'color_class' => 'circle-danger',
                'number' => number_format_short(DB::table('appoinments')->where('company_id', auth()->user()->company_id)->count()),
            ];

            $data['crm'][] = [
                'image' => $this->getNewStatisticsImage('Sales'),
                'title' => _trans('dashboard.Sales'),
                'color_class' => 'circle-success',
                'number' => number_format_short(Sale::count()),
            ];
            $data['crm'][] = [
                'image' => $this->getNewStatisticsImage('Revenue'),
                'title' => _trans('dashboard.Revenue'),
                'color_class' => 'circle-warning',
                'number' => number_format_short(Sale::sum('grand_total')),
            ];
            $data['crm'][] = [
                'image' => $this->getNewStatisticsImage('Income'),
                'title' => _trans('dashboard.Income'),
                'color_class' => 'circle-lightseagreen',
                'number' => number_format_short(Sale::where('payment_status', 4)->sum('total_price')),
            ];
            $data['crm'][] = [
                'image' => $this->getNewStatisticsImage('Expenses'),
                'title' => _trans('dashboard.Expenses'),
                'color_class' => 'circle-danger',
                'number' => number_format_short(400000),
            ];

            $data['task'] = Task::where('company_id', Auth::user()->company_id)
                ->where('status_id', '!=', 27)
                ->latest()
                ->take(15)
                ->select('id', 'name', 'progress', 'start_date', 'end_date', 'status_id', 'priority')
                ->get();

            $data['projects'] = Lead::where('company_id', Auth::user()->company_id)
                ->latest()
                ->take(15)
                ->get();

            $data['leads'] = Lead::where('company_id', Auth::user()->company_id)
                ->latest()
                ->take(15)
                ->get();

            $data['categories'] = LeadSource::where('company_id', Auth::user()->company_id)
                ->latest()
                ->take(15)
                ->get();

            $data['clients'] = Client::where('company_id', Auth::user()->company_id)
                ->latest()
                ->take(15)
                ->get();

            $data['channels'] = LeadSource::where('company_id', Auth::user()->company_id)
                ->latest()
                ->take(15)
                ->select('id', 'title')
                ->orderBy('title', 'asc')
                ->get();
            return $this->responseWithSuccess("Dashboard Statistics Data", $data, 200);
        } catch (\Throwable$exception) {
            return $this->responseWithError($exception->getMessage(), [], 500);
        }
    }
}
