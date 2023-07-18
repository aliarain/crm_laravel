<?php

namespace App\Repositories\Report;

use Validator;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use App\Enums\AttendanceStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Hrm\Attendance\Holiday;
use App\Models\Hrm\Attendance\Weekend;
use App\Models\Hrm\Leave\LeaveRequest;
use App\Models\Hrm\Attendance\Attendance;
use Illuminate\Database\Eloquent\Builder;
use App\Helpers\CoreApp\Traits\DateHandler;
use App\Models\Hrm\Attendance\DutySchedule;
use App\Models\Hrm\Attendance\EmployeeBreak;
use App\Helpers\CoreApp\Traits\TimeDurationTrait;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Models\coreApp\Relationship\RelationshipTrait;
use App\Repositories\Hrm\Attendance\AttendanceRepository;

class AttendanceReportRepository
{
    use RelationshipTrait, TimeDurationTrait, ApiReturnFormatTrait, DateHandler;

    protected $attendance;
    protected $attendanceRepository;

    public function __construct(
        Attendance           $attendance,
        AttendanceRepository $attendanceRepository
    ) {
        $this->attendance = $attendance;
        $this->attendanceRepository = $attendanceRepository;
    }

    public function lastCheckout($data)
    {
        $last_checkout = Attendance::where('user_id', $data->user_id)->where('date', $data->date)->whereNotNull('check_out')->orderBy('id', 'desc')->first();
        return $last_checkout;
    }

    public function attendanceDatatable($request)
    {
        $attendance = $this->attendance->query()->with('user', 'user.department', 'lateInReason', 'earlyOutReason')->where('company_id', $this->companyInformation()->id);

        if (auth()->user()->role->slug == 'staff') {
            $attendance = $attendance->where('user_id', auth()->id());
        }

        $attendance->when($request->date, function (Builder $builder) use ($request) {
            $dateRange = explode(' - ', $request->date);

            $from = date('Y-m-d', strtotime($dateRange[0]));
            $to = date('Y-m-d', strtotime($dateRange[1]));
            return $builder->whereBetween('date', start_end_datetime($from, $to));
        });
        if ($request->user_id != null) {
            $attendance->when($request->user_id, function (Builder $builder) use ($attendance) {
                return $attendance->where('user_id', request()->get('user_id'));
            });
        }
        $attendance->when(\request()->get('department'), function (Builder $builder) {
            return $builder->whereHas('user.department', function ($builder) {
                return $builder->where('id', request()->get('department'));
            });
        });

        $attendance->groupBy('user_id', 'date');


        return datatables()->of($attendance->latest()->get())

            ->addColumn('date', function ($data) {
                return $this->getMonthDate($data->date);
            })
            ->addColumn('name', function ($data) {
                return $status = '<a href="' . route('employeeAttendance', $data->user->id) . '" target="_blank">' . @$data->user->name . '</a>';
            })
            ->addColumn('department', function ($data) {
                return @$data->user->department->title;
            })
            ->addColumn('totalBreak', function ($data) {
                $totalBreak = RawTable('employee_breaks')->where(['date' => $data->date, 'user_id' => $data->user_id])->count();
                return $totalBreak;
            })
            ->addColumn('breakDuration', function ($data) {
                $hours = 0;
                $minutes = 0;
                $seconds = 0;
                $totalBreakBacks = RawTable('employee_breaks')->where(['date' => $data->date, 'user_id' => $data->user_id])->get();

                foreach ($totalBreakBacks as $item) {
                    $startTime = strtotime($item->break_time);
                    $endTime = strtotime($item->back_time);
                    if ($endTime > 0) {
                        $totalSeconds = $endTime - $startTime;
                    } else {
                        $totalSeconds = 0;
                    }


                    $hours += floor($totalSeconds / 3600);
                    $minutes += floor(($totalSeconds / 60) % 60);
                    $seconds += $totalSeconds % 60;
                }
                if ($hours > 0) {
                    // hour greater than 1 it will be plural
                    $hours = $hours . ' ' . Str::plural('hr', $hours);
                    if ($minutes > 0) {
                        $minutes = $minutes . ' ' . Str::plural('min', $minutes);
                        return $hours . ', ' . $minutes;
                    } else {
                        return $hours;
                    }
                } else {
                    $minutes = $minutes . ' ' . Str::plural('min', $minutes);
                    return $minutes;
                }
            })
            ->addColumn('checkin', function ($data) {
                if ($data->in_status === 'OT') {
                    $status = '';
                    $status .= '<div class="d-flex badge-responsive">';
                    $status .= '<span class="badge badge-success">' . $this->dateTimeInAmPm($data->check_in) . '</span>';
                    $status .= '<span class="badge badge-primary ml-2" data-bs-toggle="tooltip" data-bs-placement="top" title="' . $data->check_in_location . '"> <i class="fa fa-h-square"></i> </span>';
                    $status .= '</div>';
                    return $status;
                } elseif ($data->in_status === 'L') {
                    $status = '';
                    $status .= '<div class="d-flex badge-responsive">';
                    $status .= '<span class="badge badge-danger">' . $this->dateTimeInAmPm($data->check_in) . '</span>';
                    $status .= '<span class="badge badge-primary ml-2" data-bs-toggle="tooltip" data-bs-placement="top" title="' . $data->check_in_location . '"> <i class="fa fa-h-square"></i> </span>';
                    $status .= '<span class="badge badge-primary ml-2" data-bs-toggle="tooltip" data-bs-placement="top" title="' . @$data->lateInReason->reason . '"> <i class="fa fa-file"></i> </span>';
                    $status .= '</div>';
                    return $status;
                } else {
                    return '';
                }
            })
            ->addColumn('checkout', function ($data) {
                $data = $this->lastCheckout($data);
                if (@$data->check_out) {
                    if ($data->out_status === 'LT') {
                        $status = '';
                        $status .= '<div class="d-flex badge-responsive">';
                        $status .= $data->check_out ? '<span class="badge badge-success">' . $this->dateTimeInAmPm($data->check_out) . '</span>' : '';
                        $status .= '<span class="badge badge-primary ml-2" data-bs-toggle="tooltip" data-bs-placement="top" title="' . $data->check_out_location . '"> <i class="fa fa-h-square"></i> </span>';
                        $status .= '</div>';
                        return $status;
                    } elseif ($data->out_status === 'LE') {
                        $status = '';
                        $status .= '<div class="d-flex badge-responsive">';
                        $status .= $data->check_out ? '<span class="badge badge-danger">' . $this->dateTimeInAmPm($data->check_out) . '</span>' : '';
                        $status .= '<span class="badge badge-primary ml-2" data-bs-toggle="tooltip" data-bs-placement="top" title="' . $data->check_out_location . '"> <i class="fa fa-h-square"></i> </span>';
                        $status .= '<span class="badge badge-primary ml-2" data-bs-toggle="tooltip" data-bs-placement="top" title="' . @$data->earlyOutReason->reason . '"> <i class="fa fa-file"></i> </span>';
                        $status .= '</div>';
                        return $status;
                    } elseif ($data->out_status === 'LL') {
                        return $status = '<span class="badge badge-info">' . $this->dateTimeInAmPm($data->check_out) . '</span>';
                    }
                } else {
                    return null;
                }
            })
            ->addColumn('hours', function ($data) {
                if ($data->check_out) {
                    return $this->hourOrMinute($data->check_in, $this->lastCheckout($data)->check_out);
                }
            })
            ->addColumn('overtime', function ($data) {
                if ($data->check_out) {
                    return $this->overTimeCount($this->lastCheckout($data));
                }
            })
            ->addColumn('action', function ($data) {
                $action_button = '';
                if (hasPermission('attendance_update')) {
                    // $action_button .= actionButton('Edit', route('attendance.checkInEdit', $data->id), 'profile');
                    $action_button .= actionButton('Details', 'mainModalOpen(`' . route('attendance.checkInDetails', 'user_id=' . $data->user_id . '&date=' . $data->date) . '`)', 'modal');
                }
                $button = '<div class="flex-nowrap">
                    <div class="dropdown">
                        <button class="btn btn-white dropdown-toggle align-text-top action-dot-btn" data-boundary="viewport" data-bs-toggle="dropdown">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">' . $action_button . '</div>
                    </div>
                </div>';
                return $button;
            })
            ->rawColumns(array('date', 'name', 'department', 'totalBreak', 'breakDuration', 'checkin', 'checkout', 'hours', 'overtime', 'action'))
            ->make(true);
    }

    public function attendanceProfileDatatable($request)
    {

        $attendance = $this->attendance->query()->where('company_id', $this->companyInformation()->id);

        if (auth()->user()->role->slug == 'staff') {
            $attendance = $attendance->where('user_id', auth()->id());
        }

        $attendance->when($request->from, function (Builder $builder) use ($attendance) {
            return $attendance->where('date', request()->get('from'));
        });
        $attendance->when($request->date, function (Builder $builder) use ($request) {
            $date = explode(' - ', $request->date);
            return $builder->whereBetween('date', [$this->databaseFormat($date[0]), $this->databaseFormat($date[1])]);
        });

        $attendance->when($request->user_id, function (Builder $builder) use ($attendance) {
            return $attendance->where('user_id', request()->get('user_id'));
        });

        $attendance->when(\request()->get('department'), function (Builder $builder) {
            return $builder->whereHas('user.department', function ($builder) {
                return $builder->where('id', request()->get('department'));
            });
        });

        return datatables()->of($attendance->latest()->get())
            ->addColumn('name', function ($data) {
                return $status = '<a href="' . route('employeeAttendance', $data->user->id) . '" target="_blank">' . @$data->user->name . '</a>';
            })
            ->addColumn('department', function ($data) {
                return @$data->user->department->title;
            })
            ->addColumn('checkin', function ($data) {
                $checkInStatus = $this->checkInStatus($data->user_id, $data->check_in);
                if (is_array($checkInStatus)) {
                    if ($checkInStatus[0] === 'OT') {
                        $status = '';
                        $status .= '<div class="d-flex badge-responsive">';
                        $status .= '<span class="badge badge-success">' . $this->dateTimeInAmPm($data->check_in) . '</span>';
                        $status .= '<span class="badge badge-primary ml-2" data-bs-toggle="tooltip" data-bs-placement="top" title="' . $data->check_in_location . '"> <i class="fa fa-h-square"></i> </span>';
                        $status .= '</div>';
                        return $status;
                    } elseif ($checkInStatus[0] === 'L') {
                        $status = '';
                        $status .= '<div class="d-flex badge-responsive">';
                        $status .= '<span class="badge badge-danger">' . $this->dateTimeInAmPm($data->check_in) . '</span>';
                        $status .= '<span class="badge badge-primary ml-2" data-bs-toggle="tooltip" data-bs-placement="top" title="' . $data->check_in_location . '"> <i class="fa fa-h-square"></i> </span>';
                        $status .= '<span class="badge badge-primary ml-2" data-bs-toggle="tooltip" data-bs-placement="top" title="' . @$data->lateInReason->reason . '"> <i class="fa fa-file"></i> </span>';
                        $status .= '</div>';
                        return $status;
                    } else {
                        return '';
                    }
                }
            })
            ->addColumn('checkout', function ($data) {
                if ($data->check_out) {
                    $checkOutStatus = $this->checkOutStatus($data->user_id, $data->check_out);
                    if (is_array($checkOutStatus)) {
                        if ($checkOutStatus[0] === 'LT') {
                            $status = '';
                            $status .= '<div class="d-flex badge-responsive">';
                            $status .= $data->check_out ? '<span class="badge badge-success">' . $this->dateTimeInAmPm($data->check_out) . '</span>' : '';
                            $status .= '<span class="badge badge-primary ml-2" data-bs-toggle="tooltip" data-bs-placement="top" title="' . $data->check_out_location . '"> <i class="fa fa-h-square"></i> </span>';
                            $status .= '</div>';
                            return $status;
                        } elseif ($checkOutStatus[0] === 'LE') {
                            $status = '';
                            $status .= '<div class="d-flex badge-responsive">';
                            $status .= $data->check_out ? '<span class="badge badge-danger">' . $this->dateTimeInAmPm($data->check_out) . '</span>' : '';
                            $status .= '<span class="badge badge-primary ml-2" data-bs-toggle="tooltip" data-bs-placement="top" title="' . $data->check_out_location . '"> <i class="fa fa-h-square"></i> </span>';
                            $status .= '<span class="badge badge-primary ml-2" data-bs-toggle="tooltip" data-bs-placement="top" title="' . @$data->earlyOutReason->reason . '"> <i class="fa fa-file"></i> </span>';
                            $status .= '</div>';
                            return $status;
                        } elseif ($checkOutStatus[0] === 'LL') {
                            return $status = '<span class="badge badge-info">' . $this->dateTimeInAmPm($data->check_out) . '</span>';
                        }
                    }
                } else {
                    return null;
                }
            })
            ->addColumn('hours', function ($data) {
                if ($data->check_out) {
                    return $this->timeDifference($data->check_in, $data->check_out);
                }
            })
            ->addColumn('overtime', function ($data) {
                return $this->overTimeCount($data);
            })
            ->addColumn('action', function ($data) {
                $action_button = '';
                if (hasPermission('attendance_update')) {
                    $action_button .= actionButton('Edit', route('attendance.checkInEdit', $data->id), 'profile');
                }
                $button = '<div class="flex-nowrap">
                    <div class="dropdown">
                        <button class="btn btn-white dropdown-toggle align-text-top action-dot-btn" data-boundary="viewport" data-bs-toggle="dropdown">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">' . $action_button . '</div>
                    </div>
                </div>';
                return $button;
            })
            ->rawColumns(array('name', 'department', 'checkin', 'checkout', 'hours', 'overtime', 'action'))
            ->make(true);
    }
    public function getAttendanceDataTable($request)
    {

        $attendance = $this->attendance->query()->where('company_id', $this->companyInformation()->id);

        $attendance = $attendance->where('user_id', auth()->id());

        $attendance->when($request->from, function (Builder $builder) use ($attendance) {
            return $attendance->where('date', request()->get('from'));
        });
        $attendance->when($request->date, function (Builder $builder) use ($request) {
            $date = explode(' - ', $request->date);
            return $builder->whereBetween('date', [$this->databaseFormat($date[0]), $this->databaseFormat($date[1])]);
        });

        $attendance->when($request->user_id, function (Builder $builder) use ($attendance) {
            return $attendance->where('user_id', request()->get('user_id'));
        });

        $attendance->when(\request()->get('department'), function (Builder $builder) {
            return $builder->whereHas('user.department', function ($builder) {
                return $builder->where('id', request()->get('department'));
            });
        });

        return datatables()->of($attendance->latest()->get())
            ->addColumn('name', function ($data) {
                return $status = '<a href="' . route('employeeAttendance', $data->user->id) . '" target="_blank">' . @$data->user->name . '</a>';
            })
            ->addColumn('department', function ($data) {
                return @$data->user->department->title;
            })
            ->addColumn('totalBreak', function ($data) {
                $totalBreak = RawTable('employee_breaks')->where(['date' => $data->date, 'user_id' => $data->user_id])->count();
                return $totalBreak;
            })
            ->addColumn('breakDuration', function ($data) {
                $hours = 0;
                $minutes = 0;
                $seconds = 0;
                $totalBreakBacks = RawTable('employee_breaks')->where(['date' => $data->date, 'user_id' => $data->user_id])->get();

                foreach ($totalBreakBacks as $item) {
                    $startTime = strtotime($item->break_time);
                    $endTime = strtotime($item->back_time);
                    if ($endTime > 0) {
                        $totalSeconds = $endTime - $startTime;
                    } else {
                        $totalSeconds = 0;
                    }


                    $hours += floor($totalSeconds / 3600);
                    $minutes += floor(($totalSeconds / 60) % 60);
                    $seconds += $totalSeconds % 60;
                }
                if ($hours > 0) {
                    // hour greater than 1 it will be plural
                    $hours = $hours . ' ' . Str::plural('hr', $hours);
                    if ($minutes > 0) {
                        $minutes = $minutes . ' ' . Str::plural('min', $minutes);
                        return $hours . ', ' . $minutes;
                    } else {
                        return $hours;
                    }
                } else {
                    $minutes = $minutes . ' ' . Str::plural('min', $minutes);
                    return $minutes;
                }
            })
            ->addColumn('checkin', function ($data) {
                $checkInStatus = $this->checkInStatus($data->user_id, $data->check_in);
                if (is_array($checkInStatus)) {
                    if ($checkInStatus[0] === 'OT') {
                        $status = '';
                        $status .= '<div class="d-flex badge-responsive">';
                        $status .= '<span class="badge badge-success">' . $this->dateTimeInAmPm($data->check_in) . '</span>';
                        $status .= '<span class="badge badge-primary ml-2" data-bs-toggle="tooltip" data-bs-placement="top" title="' . $data->check_in_location . '"> <i class="fa fa-h-square"></i> </span>';
                        $status .= '</div>';
                        return $status;
                    } elseif ($checkInStatus[0] === 'L') {
                        $status = '';
                        $status .= '<div class="d-flex badge-responsive">';
                        $status .= '<span class="badge badge-danger">' . $this->dateTimeInAmPm($data->check_in) . '</span>';
                        $status .= '<span class="badge badge-primary ml-2" data-bs-toggle="tooltip" data-bs-placement="top" title="' . $data->check_in_location . '"> <i class="fa fa-h-square"></i> </span>';
                        $status .= '<span class="badge badge-primary ml-2" data-bs-toggle="tooltip" data-bs-placement="top" title="' . @$data->lateInReason->reason . '"> <i class="fa fa-file"></i> </span>';
                        $status .= '</div>';
                        return $status;
                    } else {
                        return '';
                    }
                }
            })
            ->addColumn('checkout', function ($data) {
                if ($data->check_out) {
                    $checkOutStatus = $this->checkOutStatus($data->user_id, $data->check_out);
                    if (is_array($checkOutStatus)) {
                        if ($checkOutStatus[0] === 'LT') {
                            $status = '';
                            $status .= '<div class="d-flex badge-responsive">';
                            $status .= $data->check_out ? '<span class="badge badge-success">' . $this->dateTimeInAmPm($data->check_out) . '</span>' : '';
                            $status .= '<span class="badge badge-primary ml-2" data-bs-toggle="tooltip" data-bs-placement="top" title="' . $data->check_out_location . '"> <i class="fa fa-h-square"></i> </span>';
                            $status .= '</div>';
                            return $status;
                        } elseif ($checkOutStatus[0] === 'LE') {
                            $status = '';
                            $status .= '<div class="d-flex badge-responsive">';
                            $status .= $data->check_out ? '<span class="badge badge-danger">' . $this->dateTimeInAmPm($data->check_out) . '</span>' : '';
                            $status .= '<span class="badge badge-primary ml-2" data-bs-toggle="tooltip" data-bs-placement="top" title="' . $data->check_out_location . '"> <i class="fa fa-h-square"></i> </span>';
                            $status .= '<span class="badge badge-primary ml-2" data-bs-toggle="tooltip" data-bs-placement="top" title="' . @$data->earlyOutReason->reason . '"> <i class="fa fa-file"></i> </span>';
                            $status .= '</div>';
                            return $status;
                        } elseif ($checkOutStatus[0] === 'LL') {
                            return $status = '<span class="badge badge-info">' . $this->dateTimeInAmPm($data->check_out) . '</span>';
                        }
                    }
                } else {
                    return null;
                }
            })
            ->addColumn('hours', function ($data) {
                if ($data->check_out) {
                    return $this->timeDifference($data->check_in, $data->check_out);
                }
            })
            ->addColumn('overtime', function ($data) {
                return $this->overTimeCount($data);
            })
            ->addColumn('action', function ($data) {
                $action_button = '';
                if (hasPermission('attendance_update')) {
                    $action_button .= actionButton('Edit', route('attendance.checkInEdit', $data->id), 'profile');
                }
                $button = '<div class="flex-nowrap">
                    <div class="dropdown">
                        <button class="btn btn-white dropdown-toggle align-text-top action-dot-btn" data-boundary="viewport" data-bs-toggle="dropdown">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">' . $action_button . '</div>
                    </div>
                </div>';
                return $button;
            })
            ->rawColumns(array('name', 'department', 'checkin', 'checkout', 'hours', 'overtime', 'action'))
            ->make(true);
    }

    public function dataTable($request, $id = null)
    {
        $items = $this->attendance->query()->where('user_id', $request->user_id);

        return datatables()->of($items->latest()->get())
            ->addColumn('action', function ($data) {
                $action_button = '';
                if (hasPermission('role_update')) {
                    $action_button .= '<a href="' . route('roles.edit', $data->id) . '" class="dropdown-item"> Edit</a>';
                }
                if (hasPermission('role_delete')) {
                    $action_button .= actionButton(_trans('common.Delete'), '__globalDelete(' . $data->id . ',`hrm/roles/delete/`)', 'delete');
                }
                $button = '<div class="flex-nowrap">
                    <div class="dropdown">
                        <button class="btn btn-white dropdown-toggle align-text-top action-dot-btn" data-boundary="viewport" data-bs-toggle="dropdown">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">' . $action_button . '</div>
                    </div>
                </div>';
                return $button;
            })
            ->addColumn('permissions', function ($data) {
                return $data->permissions != null ? count($data->permissions) : 0;
            })
            ->addColumn('status', function ($data) {
                return '<span class="badge badge-' . @$data->status->class . '">' . @$data->status->name . '</span>';
            })
            ->rawColumns(array('title', 'status', 'action'))
            ->make(true);
    }

    public function singleAttendanceDatatable($user, $request, $monthArray)
    {
        return datatables()->of($monthArray)
            ->addColumn('date', function ($data) {
                return Carbon::parse($data)->format('d/m/y');
            })
            ->addColumn('checkin', function ($data) use ($user) {
                $day = Carbon::parse($data);
                $attendance = $this->attendance->query()->where(['user_id' => $user->id, 'date' => $day->format('Y-m-d')])->first();

                $todayDateName = strtolower($day->format('l'));
                $todayDateInSqlFormat = $day->format('Y-m-d');
                $weekEnds = Weekend::where(['company_id' => $user->company->id, 'is_weekend' => 'yes'])->pluck('name')->toArray();
                if (in_array($todayDateName, $weekEnds)) {
                    return '<span class="badge badge-info ml-2" data-bs-toggle="tooltip" data-bs-placement="top"> Weekend </span>';
                }

                $holidays = Holiday::where('company_id', $user->company->id)->where('start_date', '>=', $todayDateInSqlFormat)->where('end_date', '<=', $todayDateInSqlFormat)->select('start_date', 'end_date')->first();
                if ($holidays != '') {
                    return '<span class="badge badge-info ml-2" data-bs-toggle="tooltip" data-bs-placement="top"> Holiday </span>';
                }

                $leaveDate = LeaveRequest::where(['company_id' => $user->company->id, 'user_id' => $user->id, 'status_id' => 1])->where('leave_from', '<=', $todayDateInSqlFormat)->where('leave_to', '>=', $todayDateInSqlFormat)->first();

                if ($leaveDate != '') {
                    return '<span class="badge badge-info ml-2" data-bs-toggle="tooltip" data-bs-placement="top"> Leave </span>';
                }

                if ($attendance) {
                    if ($attendance->in_status == AttendanceStatus::ON_TIME) {
                        $status = '';
                        $status .= '<span class="badge badge-success">' . $this->dateTimeInAmPm($attendance->check_in) . '</span>';
                        $status .= '<span class="badge badge-primary ml-2" data-bs-toggle="tooltip" data-bs-placement="top" title="' . $attendance->check_in_location . '"> <i class="fa fa-h-square"></i> </span>';
                        return $status;
                    } elseif ($attendance->in_status == AttendanceStatus::LATE) {
                        $status = '';
                        $status .= '<span class="badge badge-danger">' . $this->dateTimeInAmPm($attendance->check_in) . '</span>';
                        $status .= '<span class="badge badge-primary ml-2" data-bs-toggle="tooltip" data-bs-placement="top" title="' . $attendance->check_in_location . '"> <i class="fa fa-h-square"></i> </span>';
                        $status .= '<span class="badge badge-primary ml-2" data-bs-toggle="tooltip" data-bs-placement="top" title="' . @$attendance->lateInReason->reason . '"> <i class="fa fa-file"></i> </span>';
                        return $status;
                    }
                } else {
                    return '<span class="badge badge-dark ml-2" data-bs-toggle="tooltip" data-bs-placement="top"> Absent </span>';
                }
            })
            ->addColumn('checkout', function ($data) use ($user) {
                $day = Carbon::parse($data);
                $attendance = $this->attendance->query()->where(['user_id' => $user->id, 'date' => $day->format('Y-m-d')])->first();

                $todayDateName = strtolower($day->format('l'));
                $todayDateInSqlFormat = $day->format('Y-m-d');
                $weekEnds = Weekend::where(['company_id' => $user->company->id, 'is_weekend' => 'yes'])->pluck('name')->toArray();
                if (in_array($todayDateName, $weekEnds)) {
                    return null;
                }

                $holidays = Holiday::where('company_id', $user->company->id)->where('start_date', '>=', $todayDateInSqlFormat)->where('end_date', '<=', $todayDateInSqlFormat)->select('start_date', 'end_date')->first();
                if ($holidays != '') {
                    return null;
                }

                $leaveDate = LeaveRequest::where(['company_id' => $user->company->id, 'user_id' => $user->id, 'status_id' => 1])->where('leave_from', '<=', $todayDateInSqlFormat)->where('leave_to', '>=', $todayDateInSqlFormat)->first();

                if ($leaveDate != '') {
                    return null;
                }

                if ($attendance) {
                    if ($attendance->check_out) {
                        if ($attendance->out_status == AttendanceStatus::LEFT_TIMELY) {
                            $status = '';
                            $status .= '<span class="badge badge-success">' . $this->dateTimeInAmPm($attendance->check_out) . '</span>';
                            $status .= '<span class="badge badge-primary ml-2" data-bs-toggle="tooltip" data-bs-placement="top" title="' . $attendance->check_out_location . '"> <i class="fa fa-h-square"></i> </span>';
                            return $status;
                        } elseif ($attendance->out_status == AttendanceStatus::LEFT_EARLY) {
                            $status = '';
                            $status .= '<span class="badge badge-danger">' . $this->dateTimeInAmPm($attendance->check_out) . '</span>';
                            $status .= '<span class="badge badge-primary ml-2" data-bs-toggle="tooltip" data-bs-placement="top" title="' . $attendance->check_out_location . '"> <i class="fa fa-h-square"></i> </span>';
                            $status .= '<span class="badge badge-primary ml-2" data-bs-toggle="tooltip" data-bs-placement="top" title="' . @$attendance->earlyOutReason->reason . '"> <i class="fa fa-file"></i> </span>';
                            return $status;
                        } elseif ($attendance->out_status == AttendanceStatus::LEFT_LATER) {
                            return $status = '<span class="badge badge-info">' . $this->dateTimeInAmPm($attendance->check_out) . '</span>';
                        }
                    }
                } else {
                    return '';
                }
            })
            ->addColumn('hours', function ($data) use ($user) {

                $day = Carbon::parse($data);
                $attendance = $this->attendance->query()->where(['user_id' => $user->id, 'date' => $day->format('Y-m-d')])->first();

                $todayDateName = strtolower($day->format('l'));
                $todayDateInSqlFormat = $day->format('Y-m-d');
                $weekEnds = Weekend::where(['company_id' => $user->company->id, 'is_weekend' => 'yes'])->pluck('name')->toArray();
                if (in_array($todayDateName, $weekEnds)) {
                    return null;
                }

                $holidays = Holiday::where('company_id', $user->company->id)->where('start_date', '>=', $todayDateInSqlFormat)->where('end_date', '<=', $todayDateInSqlFormat)->select('start_date', 'end_date')->first();
                if ($holidays != '') {
                    return null;
                }

                $leaveDate = LeaveRequest::where(['company_id' => $user->company->id, 'user_id' => $user->id, 'status_id' => 1])->where('leave_from', '<=', $todayDateInSqlFormat)->where('leave_to', '>=', $todayDateInSqlFormat)->first();

                if ($leaveDate != '') {
                    return null;
                }

                if ($attendance) {
                    if ($attendance->check_out) {
                        return $this->hourOrMinute($attendance->check_in, $attendance->check_out);
                    }
                } else {
                    return '';
                }
            })
            ->addColumn('overtime', function ($data) use ($user) {

                $day = Carbon::parse($data);
                $attendance = $this->attendance->query()->where(['user_id' => $user->id, 'date' => $day->format('Y-m-d')])->first();

                $todayDateName = strtolower($day->format('l'));
                $todayDateInSqlFormat = $day->format('Y-m-d');
                $weekEnds = Weekend::where(['company_id' => $user->company->id, 'is_weekend' => 'yes'])->pluck('name')->toArray();
                if (in_array($todayDateName, $weekEnds)) {
                    return null;
                }

                $holidays = Holiday::where('company_id', $user->company->id)->where('start_date', '>=', $todayDateInSqlFormat)->where('end_date', '<=', $todayDateInSqlFormat)->select('start_date', 'end_date')->first();
                if ($holidays != '') {
                    return null;
                }

                $leaveDate = LeaveRequest::where(['company_id' => $user->company->id, 'user_id' => $user->id, 'status_id' => 1])->where('leave_from', '<=', $todayDateInSqlFormat)->where('leave_to', '>=', $todayDateInSqlFormat)->first();

                if ($leaveDate != '') {
                    return null;
                }

                if ($attendance) {
                    return $this->overTimeCount($attendance);
                } else {
                    return '';
                }
            })
            ->addColumn('totalBreak', function ($data) use ($user) {
                $totalBreak = EmployeeBreak::where(['date' => Carbon::parse($data)->format('Y-m-d'), 'user_id' => $user->id])->count();
                return $totalBreak;
            })
            ->addColumn('breakDuration', function ($data) use ($user) {
                $totalBreakTimeCount = 0;
                $totalBreakTime = 0;
                $totalBackTime = 0;
                $totalBreakBacks = EmployeeBreak::where(['date' => Carbon::parse($data)->format('Y-m-d'), 'user_id' => $user->id])->get();
                foreach ($totalBreakBacks as $item) {
                    $totalBreakTime += $this->timeToSeconds($item->break_time);
                    $totalBackTime += $this->timeToSeconds($item->back_time);
                }
                $totalBreakTimeCount = $this->totalSpendTime($totalBreakTime, $totalBackTime);
                return $totalBreakTimeCount;
            })
            ->rawColumns(array('date', 'checkin', 'checkout', 'totalBreak', 'breakDuration', 'hours', 'overtime'))
            ->make(true);
    }


    public function checkInStatus($user_id, $check_in_time): array
    {
        /*
         *  OT = On time
         * E = Early
         * L = Late
         */

        $user_info = User::find($user_id);
        $schedule = DutySchedule::where('shift_id', $user_info->shift_id)->where('status_id', 1)->first();
        if ($schedule) {
            $startTime = strtotime($schedule->start_time);
            $check_in_time = strtotime($check_in_time);
            $diffFromStartTime = ($check_in_time - $startTime) / 60;
            //check employee check-in on time
            if ($check_in_time <= $startTime) {
                return [AttendanceStatus::ON_TIME, $diffFromStartTime];
            } else {
                $considerTime = $schedule->consider_time;
                // check if employee come late and have some consider time
                if ($diffFromStartTime > $considerTime) {
                    return [AttendanceStatus::LATE, $diffFromStartTime];
                } else {
                    return [AttendanceStatus::ON_TIME, $diffFromStartTime];
                }
            }
        } else {
            return [];
        }
    }

    public function checkOutStatus($user_id, $check_out_time): array
    {
        /*
         *  LE = Left Early
         *  LT = Left Timely
         *  LL = Left Later
         */

        $user_info = User::find($user_id);
        $schedule = DutySchedule::where('shift_id', $user_info->shift_id)->first();
        if ($schedule) {


            $check_out_time = \Carbon\Carbon::parse($check_out_time)->format('g:i A');

            $endTime = strtotime($schedule->end_time);
            $check_out_time = strtotime($check_out_time);
            $diffFromEndTime = ($endTime - $check_out_time) / 60;

            //check employee check-out after end time
            if ($check_out_time > $endTime) {
                return [AttendanceStatus::LEFT_LATER, $diffFromEndTime];
            } //check employee check-out timely
            elseif ($check_out_time == $endTime) {
                return [AttendanceStatus::LEFT_TIMELY, $diffFromEndTime];
            } //check employee check-out before end time
            elseif ($check_out_time < $endTime) {
                return [AttendanceStatus::LEFT_EARLY, $diffFromEndTime];
            } //in general an employee check-out timely
            else {
                return [AttendanceStatus::LEFT_TIMELY, $diffFromEndTime];
            }
        } else {
            return [];
        }
    }


    public function dateAttendanceSummary($request)
    {
        $data = [];
        $present = [];
        $absent = [];
        $totalPresent = 0;
        $totalAbsent = 0;
        $totalWorkTime = 0;
        $totalLeave = 0;
        $totalOnTimeIn = 0;
        $totalEarlyIn = 0;
        $totalLateIn = 0;
        $totalLeftTimely = 0;
        $totalLeftEarly = 0;
        $totalLeftLater = 0;
        $workDayWithoutWeekend = 0;
        $totalHoliday = 0;
        $totalWeekend = 0;
        //total users
        $totalUsers = User::where(['company_id' => auth()->user()->company_id, 'status_id' => 1])->count();
        $day = Carbon::parse($request->date);
        $todayDateName = strtolower($day->format('l'));
        $todayDateInSqlFormat = $day->format('Y-m-d');

        $weekEnds = Weekend::where(['company_id' => auth()->user()->company_id, 'is_weekend' => 'yes'])
            ->pluck('name')->toArray();
        if (in_array($todayDateName, $weekEnds)) {
            $totalWeekend += 1;
        } else {
            $workDayWithoutWeekend += 1;
        }

        $holidays = Holiday::where('company_id', auth()->user()->company_id)
            ->where('start_date', '>=', $todayDateInSqlFormat)
            ->where('end_date', '<=', $todayDateInSqlFormat)
            ->select('start_date', 'end_date')
            ->first();
        if ($holidays) {
            $totalHoliday += 1;
        }
        $attendance_users = $this->attendance->query()->where(['company_id' => $this->companyInformation()->id, 'date' => $request->date])->pluck('user_id')->toArray();
        $leaveDate = LeaveRequest::where(['company_id' => auth()->user()->company_id, 'status_id' => 1])
            ->whereNotIn('user_id', $attendance_users)
            ->where('leave_from', '<=', $todayDateInSqlFormat)
            ->where('leave_to', '>=', $todayDateInSqlFormat)
            ->first();
        if ($leaveDate) {
            $totalLeave += 1;
        }
        $attendances = $this->attendance->query()->where(['company_id' => $this->companyInformation()->id, 'date' => $todayDateInSqlFormat])->get();
        foreach ($attendances as $key => $attendance) {
            if ($attendance) {
                $totalPresent += 1;
                if ($attendance->check_out) {
                    $totalWorkTime += $this->totalTimeDifference($attendance->check_in, $attendance->check_out);
                }
                if ($attendance->in_status == 'OT') {
                    $totalOnTimeIn = $attendances->where('in_status', 'OT')->count();
                } elseif ($attendance->in_status == 'E') {
                    $totalEarlyIn = $attendances->where('in_status', 'E')->count();
                } elseif ($attendance->in_status == 'L') {
                    $totalLateIn = $attendances->where('in_status', 'L')->count();
                } else {
                    $totalOnTimeIn += 1;
                }
                if ($attendance->check_out) {
                    if ($attendance->out_status == 'LT') {
                        $totalLeftTimely = $attendances->where('out_status', 'LT')->count();
                    } elseif ($attendance->out_status == 'LE') {
                        $totalLeftEarly = $attendances->where('out_status', 'LE')->count();
                    } elseif ($attendance->out_status == 'LL') {
                        $totalLeftLater = $attendances->where('out_status', 'LL')->count();
                    } else {
                        $totalLeftTimely += 1;
                    }
                }
            } else {
                $totalAbsent += 1;
            }
        }

        $totalOffday = $totalWeekend + $totalHoliday;
        $totalAbsentDays = ($totalUsers - $totalPresent);
        $totalWorkTime = number_format($totalWorkTime, 2);
        $data['present'] = "{$totalPresent}";
        $data['absent'] = "{$totalAbsentDays}";
        $data['on_time_in'] = "{$totalOnTimeIn}";
        $data['leave'] = "{$totalLeave}";
        $data['early_in'] = "{$totalEarlyIn}";
        $data['late_in'] = "{$totalLateIn}";
        $data['left_timely'] = "{$totalLeftTimely}";
        $data['left_early'] = "{$totalLeftEarly}";
        $data['left_later'] = "{$totalLeftLater}";
        return $data;
    }
    public function singleAttendanceSummary($user, $request)
    {
        $data = [];
        $present = [];
        $absent = [];
        $totalPresent = 0;
        $totalAbsent = 0;
        $totalWorkTime = 0;
        $totalLeave = 0;
        $totalOnTimeIn = 0;
        $totalEarlyIn = 0;
        $totalLateIn = 0;
        $totalLeftTimely = 0;
        $totalLeftEarly = 0;
        $totalLeftLater = 0;
        $workDayWithoutWeekend = 0;
        $totalHoliday = 0;
        $holiday_dates = [];
        $totalWeekend = 0;
        if ($request->month) {
            $monthArray = $this->getSelectedMonthDays($request->month);
        } else {
            $monthArray = $this->getCurrentMonthDays();
        }

        foreach ($monthArray as $day) {
            $todayDateName = strtolower($day->format('l'));
            $todayDateInSqlFormat = $day->format('Y-m-d');

            $weekEnds = Weekend::where(['company_id' => $user->company->id, 'is_weekend' => 'yes'])
                ->pluck('name')->toArray();
            if (in_array($todayDateName, $weekEnds)) {
                $totalWeekend += 1;
            } else {
                $workDayWithoutWeekend += 1;
            }

            $holidays = Holiday::where('company_id', $user->company->id)
                ->where('start_date', '<=', $todayDateInSqlFormat)
                ->where('end_date', '>=', $todayDateInSqlFormat)
                ->select('start_date', 'end_date')
                ->first();
            if ($holidays) {
                $holiday_dates[] = $todayDateInSqlFormat;
                $totalHoliday += 1;
            }

            $leaveDate = LeaveRequest::where(['company_id' => $user->company->id, 'user_id' => $user->id, 'status_id' => 1])
                ->where('leave_from', '<=', $todayDateInSqlFormat)
                ->where('leave_to', '>=', $todayDateInSqlFormat)
                ->first();

            if ($leaveDate) {
                $totalLeave += 1;
            }
            $attendance = $this->attendance->query()->where(['company_id' => $this->companyInformation()->id, 'user_id' => $user->id, 'date' => $todayDateInSqlFormat])->first();
            if ($attendance) {
                $totalPresent += 1;
                if ($attendance->check_out) {
                    $totalWorkTime += $this->totalTimeDifference($attendance->check_in, $attendance->check_out);
                }
                if ($attendance->in_status == 'OT') {
                    $totalOnTimeIn += 1;
                } elseif ($attendance->in_status == 'E') {
                    $totalEarlyIn += 1;
                } elseif ($attendance->in_status == 'L') {
                    $totalLateIn += 1;
                } else {
                    $totalOnTimeIn += 1;
                }

                if ($attendance->check_out) {
                    if ($attendance->out_status == 'LT') {
                        $totalLeftTimely += 1;
                    } elseif ($attendance->out_status == 'LE') {
                        $totalLeftEarly += 1;
                    } elseif ($attendance->out_status == 'LL') {
                        $totalLeftLater += 1;
                    } else {
                        $totalLeftTimely += 1;
                    }
                }
            } else {
                $month_date = date('Y-m-d', strtotime($todayDateInSqlFormat));
                $current_date = strtotime(date('Y-m-d'));
                $month_date = strtotime($month_date);
                if ($month_date < $current_date) {
                    $day = Carbon::createFromFormat('Y-m-d', $todayDateInSqlFormat)->format('l');
                    $day = strtolower($day);
                    if (!in_array($day, $weekEnds) && !in_array($todayDateInSqlFormat, $holiday_dates)) {
                        $totalAbsent += 1;
                    }
                }
            }
        }


        $totalDayOfThisMonth = count($monthArray);
        $totalOffday = $totalWeekend + $totalHoliday;
        $totalWorkingDays = ($totalDayOfThisMonth - $totalOffday);
        $totalAbsentDays = ($totalWorkingDays - $totalPresent);
        $totalWorkTime = number_format($totalWorkTime, 2);

        $data['working_days'] = "{$totalWorkingDays} days";
        $data['present'] = "{$totalPresent} days";
        $data['work_time'] = "{$totalWorkTime} min";
        $data['absent'] = "{$totalAbsent} days";
        $data['total_on_time_in'] = "{$totalOnTimeIn} days";
        $data['total_leave'] = "{$totalLeave} days";
        $data['total_early_in'] = "{$totalEarlyIn} days";
        $data['total_late_in'] = "{$totalLateIn} days";
        $data['total_left_timely'] = "{$totalLeftTimely} days";
        $data['total_left_early'] = "{$totalLeftEarly} days";
        $data['total_left_later'] = "{$totalLeftLater} days";
        return $data;
    }


    public function monthlyAttendanceSummary($user, $request)
    {
        $data = [];
        $present = [];
        $absent = [];
        $totalPresent = 0;
        $totalAbsent = 0;
        $totalWorkTime = 0;
        $totalLeave = 0;
        $totalOnTimeIn = 0;
        $totalEarlyIn = 0;
        $totalLateIn = 0;
        $totalLeftTimely = 0;
        $totalLeftEarly = 0;
        $totalLeftLater = 0;
        $workDayWithoutWeekend = 0;
        $totalHoliday = 0;
        $holiday_dates = [];
        $totalWeekend = 0;
        if ($request->month) {
            $monthArray = $this->getSelectedMonthDays($request->month);
        } else {
            $monthArray = $this->getCurrentMonthDays();
        }

        foreach ($monthArray as $day) {
            $todayDateName = strtolower($day->format('l'));
            $todayDateInSqlFormat = $day->format('Y-m-d');

            $weekEnds = Weekend::where(['company_id' => $user->company->id, 'is_weekend' => 'yes'])
                ->pluck('name')->toArray();
            if (in_array($todayDateName, $weekEnds)) {
                $totalWeekend += 1;
            } else {
                $workDayWithoutWeekend += 1;
            }

            $holidays = Holiday::where('company_id', $user->company->id)
                ->where('start_date', '<=', $todayDateInSqlFormat)
                ->where('end_date', '>=', $todayDateInSqlFormat)
                ->select('start_date', 'end_date')
                ->first();
            if ($holidays) {
                $holiday_dates[] = $todayDateInSqlFormat;
                $totalHoliday += 1;
            }

            $leaveDate = LeaveRequest::where(['company_id' => $user->company->id, 'user_id' => $user->id, 'status_id' => 1])
                ->where('leave_from', '<=', $todayDateInSqlFormat)
                ->where('leave_to', '>=', $todayDateInSqlFormat)
                ->first();

            if ($leaveDate) {
                $totalLeave += 1;
            }
            $attendance = $this->attendance->query()->where(['company_id' => $this->companyInformation()->id, 'user_id' => $user->id, 'date' => $todayDateInSqlFormat])->first();
            if ($attendance) {
                $totalPresent += 1;
                if ($attendance->check_out) {
                    $totalWorkTime += $this->totalTimeDifference($attendance->check_in, $attendance->check_out);
                }
                if ($attendance->in_status == 'OT') {
                    $totalOnTimeIn += 1;
                } elseif ($attendance->in_status == 'E') {
                    $totalEarlyIn += 1;
                } elseif ($attendance->in_status == 'L') {
                    $totalLateIn += 1;
                } else {
                    $totalOnTimeIn += 1;
                }

                if ($attendance->check_out) {
                    if ($attendance->out_status == 'LT') {
                        $totalLeftTimely += 1;
                    } elseif ($attendance->out_status == 'LE') {
                        $totalLeftEarly += 1;
                    } elseif ($attendance->out_status == 'LL') {
                        $totalLeftLater += 1;
                    } else {
                        $totalLeftTimely += 1;
                    }
                }
            } else {
                $month_date = date('Y-m-d', strtotime($todayDateInSqlFormat));
                $current_date = strtotime(date('Y-m-d'));
                $month_date = strtotime($month_date);
                if ($month_date < $current_date) {
                    $day = Carbon::createFromFormat('Y-m-d', $todayDateInSqlFormat)->format('l');
                    $day = strtolower($day);
                    if (!in_array($day, $weekEnds) && !in_array($todayDateInSqlFormat, $holiday_dates)) {
                        $totalAbsent += 1;
                    }
                }
            }
        }


        $totalDayOfThisMonth = count($monthArray);
        $totalOffday = $totalWeekend + $totalHoliday;
        $totalWorkingDays = ($totalDayOfThisMonth - $totalOffday);
        $totalAbsentDays = ($totalWorkingDays - $totalPresent);
        $totalWorkTime = number_format($totalWorkTime, 2);

        $data['working_days'] = "{$totalWorkingDays} days";
        $data['present'] = "{$totalPresent} days";
        $data['work_time'] = "{$totalWorkTime} min";
        $data['absent'] = "{$totalAbsent} days";
        $data['total_on_time_in'] = "{$totalOnTimeIn} days";
        $data['total_leave'] = "{$totalLeave} days";
        $data['total_early_in'] = "{$totalEarlyIn} days";
        $data['total_late_in'] = "{$totalLateIn} days";
        $data['total_left_timely'] = "{$totalLeftTimely} days";
        $data['total_left_early'] = "{$totalLeftEarly} days";
        $data['total_left_later'] = "{$totalLeftLater} days";
        return $data;
    }

    public function companyAttendanceSummary($user, $request)
    {
        $data = [];
        $present = [];
        $absent = [];

        $totalPresent = 0;
        $totalAbsent = 0;
        $totalWorkTime = 0;
        $totalLeave = 0;
        $totalOnTimeIn = 0;
        $totalEarlyIn = 0;
        $totalLateIn = 0;
        $totalLeftTimely = 0;
        $totalLeftEarly = 0;
        $totalLeftLater = 0;
        $workDayWithoutWeekend = 0;
        $totalHoliday = 0;
        $totalWeekend = 0;

        //Main count
        $report_present = 0;
        $report_absent = 0;
        $report_work_time = 0;
        $report_leave = 0;
        $report_on_time_in = 0;
        $report_early_in = 0;
        $report_late_in = 0;
        $report_left_timely = 0;
        $report_left_early = 0;
        $report_left_later = 0;


        if ($request->month) {
            $monthArray = $this->getSelectedMonthDays($request->month);
        } else {
            $monthArray = $this->getCurrentMonthDays();
        }
        $users = User::where('company_id', $user->company_id)->get();
        foreach ($users as $key => $user) {
            foreach ($monthArray as $day) {
                $todayDateName = strtolower($day->format('l'));
                $todayDateInSqlFormat = $day->format('Y-m-d');

                $weekEnds = Weekend::where(['company_id' => $user->company->id, 'is_weekend' => 'yes'])
                    ->pluck('name')->toArray();
                if (in_array($todayDateName, $weekEnds)) {
                    $totalWeekend += 1;
                } else {
                    $workDayWithoutWeekend += 1;
                }

                $holidays = Holiday::where('company_id', $user->company->id)
                    ->where('start_date', '>=', $todayDateInSqlFormat)
                    ->where('end_date', '<=', $todayDateInSqlFormat)
                    ->select('start_date', 'end_date')
                    ->first();
                if ($holidays) {
                    $totalHoliday += 1;
                }

                $leaveDate = LeaveRequest::where(['company_id' => $user->company->id, 'user_id' => $user->id, 'status_id' => 1])
                    ->where('leave_from', '<=', $todayDateInSqlFormat)
                    ->where('leave_to', '>=', $todayDateInSqlFormat)
                    ->first();

                if ($leaveDate) {
                    $totalLeave += 1;
                }

                $attendance = $this->attendance->query()->where(['user_id' => $user->id, 'date' => $todayDateInSqlFormat])->first();
                if ($attendance) {

                    $totalPresent += 1;
                    if ($attendance->check_out) {
                        $totalWorkTime += $this->totalTimeDifference($attendance->check_in, $attendance->check_out);
                    }
                    $todayInTimeStatus = $this->checkInStatus($attendance->user_id, $attendance->check_in);
                    if ($todayInTimeStatus[0] == 'OT') {
                        $totalOnTimeIn += 1;
                    } elseif ($todayInTimeStatus[0] == 'E') {
                        $totalEarlyIn += 1;
                    } elseif ($todayInTimeStatus[0] == 'L') {
                        $totalLateIn += 1;
                    } else {
                        $totalOnTimeIn += 1;
                    }

                    if ($attendance->check_out) {
                        $todayOutTimeStatus = $this->checkOutStatus($attendance->user_id, $attendance->check_out);
                        if ($todayOutTimeStatus[0] == 'LT') {
                            $totalLeftTimely += 1;
                        } elseif ($todayOutTimeStatus[0] == 'LE') {
                            $totalLeftEarly += 1;
                        } elseif ($todayOutTimeStatus[0] == 'LL') {
                            $totalLeftLater += 1;
                        } else {
                            $totalLeftTimely += 1;
                        }
                    }
                } else {
                    $totalAbsent += 1;
                }
            }
            $report_present += $totalPresent;
            $report_absent += $totalAbsent;
            $report_leave += $totalLeave;
            $report_on_time_in += $totalOnTimeIn;
            $report_early_in += $totalEarlyIn;
            $report_late_in += $totalLateIn;
            $report_left_timely += $totalLeftTimely;
            $report_left_early += $totalLeftEarly;
            $report_left_later += $totalLeftLater;

            $totalPresent = 0;
            $totalAbsent = 0;
            $totalWorkTime = 0;
            $totalLeave = 0;
            $totalOnTimeIn = 0;
            $totalEarlyIn = 0;
            $totalLateIn = 0;
            $totalLeftTimely = 0;
            $totalLeftEarly = 0;
            $totalLeftLater = 0;
            $workDayWithoutWeekend = 0;
            $totalHoliday = 0;
            $totalWeekend = 0;
        }

        $totalDayOfThisMonth = count($monthArray);
        $totalOffday = $totalWeekend + $totalHoliday;
        $totalWorkingDays = ($totalDayOfThisMonth - $totalOffday);
        $totalAbsentDays = ($totalWorkingDays - $totalPresent);
        $totalWorkTime = number_format($totalWorkTime, 2);

        // $data['users'] = "{$users->count()} Users";
        $data['working_days'] = $totalWorkingDays;
        $data['present'] = $report_present;
        $data['work_time'] = $totalWorkTime;
        $data['absent'] = $report_absent;
        $data['total_on_time_in'] = $report_on_time_in;
        $data['total_leave'] = $report_leave;
        $data['total_early_in'] = $report_early_in;
        $data['total_late_in'] = $report_late_in;
        $data['total_left_timely'] = $report_left_timely;
        $data['total_left_early'] = $report_left_early;
        $data['total_left_later'] = $report_left_later;
        return $data;
    }


    //methods for api call
    public function userAttendanceReport($user, $request)
    {
        $validator = Validator::make($request->all(), [
            'month' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->responseWithError(_trans('validation.Validation field required'), $validator->errors(), 422);
        }

        // array initiate
        $data = [];
        $thisMonthArray = [];
        $reportArray = [];
        $dayName = [];

        if ($request->month) {
            $data['attendance_summary'] = $this->singleAttendanceSummary($user, $request);
            $thisMonthArray = $this->getSelectedMonthDays($request->month);
        }

        foreach ($thisMonthArray as $key => $item) {
            $day = Carbon::parse($item);
            $arrayIndex = $day->format('d');
            $todayDateInSqlFormat = $day->format('Y-m-d');

            $attendance = $this->attendance->query()->where(['user_id' => $user->id, 'date' => $todayDateInSqlFormat])->first();
            if ($attendance) {
                $dailyReports[] = $this->attendanceVariousStatus($attendance, $day, 'Holiday');
            } else {
                $weekEnds = Weekend::where(['company_id' => $user->company->id, 'is_weekend' => 'yes'])
                    ->pluck('name')->toArray();
                $todayDateName = strtolower($day->format('l'));
                if (in_array($todayDateName, $weekEnds)) {
                    $dailyReports[] = $this->attendanceVariousStatus($attendance, $day, 'Weekend');
                } else {
                    $isHoliday = Holiday::where('company_id', $user->company->id)
                        ->where('start_date', '<=', $todayDateInSqlFormat)
                        ->where('end_date', '>=', $todayDateInSqlFormat)
                        ->select('start_date', 'end_date')
                        ->first();
                    if ($isHoliday) {
                        $dailyReports[] = $this->attendanceVariousStatus($attendance, $day, 'Holiday');
                    } else {
                        $leaveDate = LeaveRequest::where(['company_id' => $user->company->id, 'user_id' => $user->id, 'status_id' => 1])
                            ->where('leave_from', '<=', $todayDateInSqlFormat)
                            ->where('leave_to', '>=', $todayDateInSqlFormat)
                            ->first();
                        if ($leaveDate) {
                            $dailyReports[] = $this->attendanceVariousStatus($attendance, $day, 'Leave');
                        } else {
                            $dailyReports[] = $this->attendanceVariousStatus($attendance, $day, '...');
                        }
                    }
                }
            }
        }

        $data['daily_report'] = $dailyReports;

        // get attendance report

        return $this->responseWithSuccess('Monthly attendance report', $data, 200);
    }
    public function userDailyAttendanceReport($request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required',
            'user_id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->responseWithError(_trans('validation.Validation field required'), $validator->errors(), 422);
        }

        // array initiate
        $data = [];
        $thisMonthArray = [];
        $reportArray = [];
        $dayName = [];
        $dailyReports = [];

        $user = User::find($request->user_id);

        $todayDateInSqlFormat = str_replace('/', '-', $request->date);
        $todayDateInSqlFormat = Carbon::parse($todayDateInSqlFormat)->format('Y-m-d');
        $day = Carbon::parse($todayDateInSqlFormat);
        $attendances = $this->attendance->query()->where(['user_id' => $user->id, 'date' => $todayDateInSqlFormat])->get();
        foreach ($attendances as $key => $attendance) {
            $dailyReports[] = $this->attendanceVariousStatus($attendance, $day, 'Holiday', $daily_details = true);
        }


        $data['date'] = $this->dateFormatWithoutTime($todayDateInSqlFormat);
        $data['date_wise_report'] = $dailyReports;

        // get attendance report

        return $this->responseWithSuccess('Daily attendance report', $data, 200);
    }
    //methods for date summary api call
    public function dateSummaryReport($request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->responseWithError(_trans('validation.Validation field required'), $validator->errors(), 422);
        }

        $data['date'] = $this->dateFormatWithoutTime($request->date);
        $data['attendance_summary'] = $this->dateAttendanceSummary($request);

        return $this->responseWithSuccess('Datewise attendance summary', $data, 200);
    }

    public function attendanceVariousStatus($attendance, $day, $status, $daily_details = false)
    {
        if ($attendance) {
            $isCheckout = $attendance->check_out ? $attendance->remote_mode_out : '';
            if ($daily_details) {
                $last_checkout = $attendance;
            } else {
                $last_checkout = $this->lastCheckout($attendance) ?? $attendance;
            }
            $dailyReports = [
                'full_date' => $day->format('d/m/Y'),
                'week_day' => $day->format('l'),
                'date' => $day->format('d'),
                'status' => 'Present',
                'remote_mode_in' => "{$attendance->remote_mode_in}",
                'remote_mode_out' => "{$isCheckout}",
                'check_in' => $this->dateTimeInAmPm($attendance->check_in),
                'check_in_status' => $this->checkInStatus($attendance->user->id, $attendance->check_in)[0],
                'check_out_status' => $this->checkOutStatus($last_checkout->user->id, $last_checkout->check_out)[0],
                'check_in_location' => $attendance->check_in_location,
                'check_in_reason' => $attendance->lateInReason ? "{$attendance->lateInReason->reason}" : '',
                'check_out' => $last_checkout->check_out ? $this->dateTimeInAmPm($last_checkout->check_out) : '',
                'check_out_location' => $last_checkout->check_out_location,
                'check_out_reason' => $last_checkout->earlyOutReason ? "{$last_checkout->earlyOutReason->reason}" : '',
            ];
        } else {
            $dailyReports = [
                'full_date' => $day->format('d/m/Y'),
                'week_day' => $day->format('l'),
                'date' => $day->format('d'),
                'status' => $status,
                'remote_mode_in' => '',
                'remote_mode_out' => '',
                'check_in' => '',
                'check_in_status' => '',
                'check_out_status' => '',
                'check_in_location' => '',
                'check_in_reason' => '',
                'check_out' => '',
                'check_out_location' => '',
                'check_out_reason' => ''
            ];
        }
        return $dailyReports;
    }

    public function summaryToListReport($request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:late_in,on_time_in,early_in,left_timely,left_early,left_later,absent,present,leave',
            'date' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->responseWithError(_trans('validation.Validation field required'), $validator->errors(), 422);
        }

        $type_sign = "";
        $search_column = "";
        switch ($request->type) {
                //IN
            case 'late_in':
                $search_column = "in_status";
                $type_sign = "L";
                break;
            case 'on_time_in':
                $search_column = "in_status";
                $type_sign = "OT";
                break;
            case 'early_in':
                $search_column = "in_status";
                $type_sign = "EI";
                break;
                //OUT
            case 'left_timely':
                $search_column = "out_status";
                $type_sign = "LT";
                break;
            case 'left_early':
                $search_column = "out_status";
                $type_sign = "LE";
                break;
            case 'left_later':
                $search_column = "out_status";
                $type_sign = "LL";
                break;

            default:
                $search_column = "in_status";
                $type_sign = "A";
                break;
        }
        $result = [];
        $users = [];
        $today = date('Y-m-d');
        $title = str_replace("_", " ", $request->type);
        $absent_types = ['present', 'absent', 'leave'];
        if (in_array($request->type, $absent_types)) {
            switch ($request->type) {
                case 'present':
                    $data['attendance'] = $this->attendance->query()
                        ->where(['attendances.company_id' => $this->companyInformation()->id, 'date' => $request->date])
                        ->leftJoin('users', 'users.id', '=', 'attendances.user_id')
                        ->leftJoin('designations', 'designations.id', '=', 'users.designation_id')
                        ->select('users.id', 'users.name', 'designations.title', 'users.avatar_id', DB::raw("DATE_FORMAT(check_in, '%h:%i %p') as check_in"), DB::raw("DATE_FORMAT(check_out, '%h:%i %p') as check_out"))
                        ->get();
                    break;
                case 'absent':
                    $data['attendance'] = $this->attendance->query()->where(['company_id' => $this->companyInformation()->id, 'date' => $request->date])->pluck('user_id')->toArray();
                    $data['attendance'] = User::query()
                        ->where(['users.company_id' => $this->companyInformation()->id])
                        ->whereNotIn('users.id', $data['attendance'])
                        ->leftJoin('designations', 'designations.id', '=', 'users.designation_id')
                        ->select('users.id', 'users.name', 'designations.title', 'users.avatar_id')
                        ->get();
                    break;
                case 'leave':
                    $data['attendance'] = $this->attendance->query()->where(['company_id' => $this->companyInformation()->id, 'date' => $request->date])->pluck('user_id')->toArray();
                    $data['attendance'] = User::query()
                        ->where(['users.company_id' => $this->companyInformation()->id])
                        ->whereNotIn('users.id', $data['attendance'])
                        ->leftJoin('leave_requests', 'leave_requests.user_id', '=', 'users.id')
                        ->leftJoin('designations', 'designations.id', '=', 'users.designation_id')
                        ->where('leave_from', '<=', $today)
                        ->where('leave_to', '>=', $today)
                        ->where('leave_requests.status_id', 1)
                        ->select('leave_requests.*', 'users.id', 'users.name', 'designations.title', 'users.avatar_id')
                        ->get();
                    break;

                default:
                    $data['attendance'] = [];
                    break;
            }
        } else {
            $data['attendance'] = $this->attendance->query()
                ->where(['attendances.company_id' => $this->companyInformation()->id, $search_column => $type_sign, 'date' => $request->date])
                ->leftJoin('users', 'users.id', '=', 'attendances.user_id')
                ->leftJoin('designations', 'designations.id', '=', 'users.designation_id')
                ->select('users.id', 'users.name', 'designations.title', 'users.avatar_id', DB::raw("DATE_FORMAT(check_in, '%h:%i %p') as check_in"), DB::raw("DATE_FORMAT(check_out, '%h:%i %p') as check_out"))
                ->get();
        }
        foreach ($data['attendance'] as $key => $attendance) {
            $at['user_id'] = $attendance->id;
            $at['name'] = $attendance->name;
            $at['designation'] = @$attendance->title;
            $at['check_in'] = @$attendance->check_in;
            $at['check_out'] = @$attendance->check_out;
            $at['avatar'] = uploaded_asset($attendance->avatar_id);

            $users[] = $at;
        }
        $result['title'] = Str::title($title);
        $result['users'] = $users;
        return $this->responseWithSuccess('Summary To Employee List', $result, 200);
    }


    function attendanceDetails($request)
    {

        return  $this->attendance->query()
            ->with('lateInReason', 'earlyOutReason', 'lateInOutReason')
            ->where('user_id', $request->user_id)
            ->where('date', $request->date)
            ->get();
    }





    // new functions for
    function CheckInTable($data)
    {
        $checkInStatus = $this->checkInStatus($data->user_id, $data->check_in);
        
        if (is_array($checkInStatus)) {
            if ($checkInStatus[0] === 'OT') {
                $status = '';
                $status .= '<div class="d-flex badge-responsive">';
                $status .= '<span class="badge badge-success">' . $this->dateTimeInAmPm($data->check_in) . '</span>';
                $status .= '<span class="badge badge-primary ml-2" data-bs-toggle="tooltip" data-bs-placement="top" title="' . $data->check_in_location . '"> <i class="fa fa-h-square"></i> </span>';
                $status .= '</div>';
                return $status;
            } elseif ($checkInStatus[0] === 'L') {
                $status = '';
                $status .= '<div class="d-flex badge-responsive">';
                $status .= '<span class="badge badge-danger">' . $this->dateTimeInAmPm($data->check_in) . '</span>';
                $status .= '<span class="badge badge-primary ml-2" data-bs-toggle="tooltip" data-bs-placement="top" title="' . $data->check_in_location . '"> <i class="fa fa-h-square"></i> </span>';
                $status .= '<span class="badge badge-primary ml-2" data-bs-toggle="tooltip" data-bs-placement="top" title="' . @$data->lateInReason->reason . '"> <i class="fa fa-file"></i> </span>';
                $status .= '</div>';
                return $status;
            } else {
                return '';
            }
        }
    }
    function checkOutTable($data)
    {
        if ($data->check_out) {
            $checkOutStatus = $this->checkOutStatus($data->user_id, $data->check_out);
            if (is_array($checkOutStatus)) {
                if ($checkOutStatus[0] === 'LT') {
                    $status = '';
                    $status .= '<div class="d-flex badge-responsive">';
                    $status .= $data->check_out ? '<span class="badge badge-success">' . $this->dateTimeInAmPm($data->check_out) . '</span>' : '';
                    $status .= '<span class="badge badge-primary ml-2" data-bs-toggle="tooltip" data-bs-placement="top" title="' . $data->check_out_location . '"> <i class="fa fa-h-square"></i> </span>';
                    $status .= '</div>';
                    return $status;
                } elseif ($checkOutStatus[0] === 'LE') {
                    $status = '';
                    $status .= '<div class="d-flex badge-responsive">';
                    $status .= $data->check_out ? '<span class="badge badge-danger">' . $this->dateTimeInAmPm($data->check_out) . '</span>' : '';
                    $status .= '<span class="badge badge-primary ml-2" data-bs-toggle="tooltip" data-bs-placement="top" title="' . $data->check_out_location . '"> <i class="fa fa-h-square"></i> </span>';
                    $status .= '<span class="badge badge-primary ml-2" data-bs-toggle="tooltip" data-bs-placement="top" title="' . @$data->earlyOutReason->reason . '"> <i class="fa fa-file"></i> </span>';
                    $status .= '</div>';
                    return $status;
                } elseif ($checkOutStatus[0] === 'LL') {
                    return $status = '<span class="badge badge-info">' . $this->dateTimeInAmPm($data->check_out) . '</span>';
                }
            }
        }
    }
    function table($request)
    {

        
        $attendance = $this->attendance->query();
        $attendance = $attendance->where('company_id', auth()->user()->company_id);
        if (auth()->user()->role->slug == 'staff') {
            $attendance = $attendance->where('user_id', auth()->id());
        } else {
            $attendance->when(\request()->get('user_id'), function (Builder $builder) {
                return $builder->where('user_id', \request()->get('user_id'));
            });
        }
        if ($request->from && $request->to) {
            $attendance = $attendance->whereBetween('check_in', start_end_datetime($request->from, $request->to));
        }
        if ($request->search) {
            $attendance = $attendance->whereHas('user', function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%');
            });
        }
        if ($request->user_id) {
            $attendance = $attendance->where('user_id', $request->user_id);
        }
        if ($request->department) {
            $attendance->when(\request()->get('department'), function (Builder $builder) {
                return $builder->whereHas('user.department', function ($builder) {
                    return $builder->where('id', request()->get('department'));
                });
            });
        }
        $data = $attendance->paginate($request->limit ?? 2);
        return [
            'data' => $data->map(function ($data) {
                $action_button = '';
                if (hasPermission('attendance_update')) {
                    $action_button .= actionButton(_trans('common.Edit'), route('attendance.checkInEdit', $data->id), 'profile');
                }
                $button = ' <div class="dropdown dropdown-action">
                                    <button type="button" class="btn-dropdown" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <i class="fa-solid fa-ellipsis"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                    ' . $action_button . '
                                    </ul>
                                </div>';

                return [
                    'id'         => $data->id,
                    'name'       => '<a href="' . route('employeeAttendance', $data->user->id) . '" target="_blank">' . @$data->user->name . '</a>',
                    'department'       => @$data->user->department->title,
                    'totalBreak'       =>  RawTable('employee_breaks')->where(['date' => $data->date, 'user_id' => $data->user_id])->count(),
                    'breakDuration'  => breakDuration($data->date, $data->user_id),
                    'checkin'      => $this->CheckInTable($data),
                    'checkout'      => $this->checkOutTable($data) ?? '',
                    'hours'        => (@$data->check_out) ? $this->timeDifference($data->check_in, $data->check_out) : '',
                    'overtime'     => $this->overTimeCount($data) ?? '',
                    'date'       =>  $data->date,
                    'status'     => '<small class="badge badge-' . @$data->status->class . '">' . @$data->status->name . '</small>',
                    'action'      => $button,
                ];
            }),
            'pagination' => [
                'total' => $data->total(),
                'count' => $data->count(),
                'per_page' => $data->perPage(),
                'current_page' => $data->currentPage(),
                'total_pages' => $data->lastPage(),
                'pagination_html' =>  $data->links('backend.pagination.custom')->toHtml(),
            ],
        ];
    }
}
