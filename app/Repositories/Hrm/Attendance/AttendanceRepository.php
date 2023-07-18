<?php

namespace App\Repositories\Hrm\Attendance;

use Validator;
use Carbon\Carbon;
use App\Models\User;
use App\Enums\AttendanceStatus;
use App\Models\Track\LocationLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use App\Models\Settings\LocationBind;
use App\Models\coreApp\Setting\IpSetup;
use App\Models\Hrm\Attendance\Attendance;
use App\Helpers\CoreApp\Traits\DateHandler;
use App\Models\Hrm\Attendance\DutySchedule;
use App\Models\coreApp\Setting\CompanyConfig;
use App\Models\Hrm\Attendance\LateInOutReason;
use App\Helpers\CoreApp\Traits\GeoLocationTrait;
use App\Helpers\CoreApp\Traits\TimeDurationTrait;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Models\coreApp\Relationship\RelationshipTrait;
use App\Repositories\Hrm\Leave\LeaveRequestRepository;
use App\Repositories\Settings\CompanyConfigRepository;

class AttendanceRepository
{
    use ApiReturnFormatTrait, RelationshipTrait, TimeDurationTrait, GeoLocationTrait, DateHandler;

    protected $attendance;
    protected $user;
    protected $leave_request_repo;
    protected $config_repo;

    public function __construct(
        Attendance $attendance,
        User $user,
        LeaveRequestRepository $leave_request_repo,
        CompanyConfigRepository $companyConfigRepo
    ) {
        $this->attendance = $attendance;
        $this->user = $user;
        $this->leave_request_repo = $leave_request_repo;
        $this->config_repo = $companyConfigRepo;
    }


    public function companySetup()
    {
        $configs = $this->config_repo->getConfigs();
        $config_array = [];
        foreach ($configs as $key => $config) {
            $config_array[$config->key] = $config->value;
        }
        $data = $config_array;
        return $data;
    }

    public function getCheckInCheckoutStatus($userId)
    {
        $user = $this->user->query()->find($userId);
        if ($user) {
            if (@$this->companySetup()['multi_checkin']) {
                $where = ['user_id' => $userId, 'check_out' => null];
            } else {
                $where = ['user_id' => $userId];
            }
            $attendance = $this->attendance->query()->orderByDesc('id')->where($where)->first();
            if ($attendance) {
                if ($attendance->check_out) {

                    //Check if max working hours is crossed or not
                    $check_in_time  =   $attendance->check_in;
                    $current_time   =   date('Y-m-d H:i:s');
                    $time_diff      =   intval($this->timeDifferenceHour($check_in_time, $current_time));
                    $max_work_hours =   intval(settings('max_work_hours') ? settings('max_work_hours') : 16);
                    $checkin_status =   $time_diff > $max_work_hours ? false : true;
                    $checkout_status =   $time_diff > $max_work_hours ? false : true;
                    $in_time        =   $time_diff > $max_work_hours ? null : $this->dateTimeInAmPm(@$attendance->check_in);
                    $out_time       =   $time_diff > $max_work_hours ? null : $this->dateTimeInAmPm(@$attendance->check_out);
                    $stay_time      =   $time_diff > $max_work_hours ? null : $this->timeDifference($attendance->check_in, $attendance->check_out);

                    return $this->responseWithSuccess('Already checked out', [
                        'id' => $attendance->id,
                        'checkin' => $checkin_status,
                        'checkout' => $checkout_status,
                        'in_time' => $in_time,
                        'out_time' =>  $out_time,
                        'stay_time' => $stay_time
                    ], 200);
                } else {
                    return $this->responseWithSuccess('You are checked in please leave from office in due time', [
                        'id' => $attendance->id,
                        'checkin' => true,
                        'checkout' => false,
                        'in_time' => $this->dateTimeInAmPm(@$attendance->check_in),
                        'out_time' => null,
                        'stay_time' => null
                    ], 200);
                }
            } else {
                return $this->responseWithSuccess('Please check in now', [
                    'checkin' => false,
                    'checkout' => false,
                    'in_time' => null,
                    'out_time' => null,
                    'stay_time' => null

                ], 200);
            }
        } else {
            return $this->responseWithError('No user found', [], 200);
        }
    }

    public function getAll()
    {
        return $this->attendance::get();
    }

    public function index()
    {
        $attendance = $this->attendance->query()->where('company_id', $this->companyInformation()->id)->orderBy('date', 'DESC')->get();
        return $attendance;
    }

    public function checkInUsers()
    {
        return User::query()->where('role_id', '!=', 1)->where('company_id', $this->companyInformation()->id)->select('id', 'name')->get();
    }

    public function show($attendance_id)
    {
        $data['title'] = 'Attendance Check In Edit';
        $data['attendance_data'] = $this->attendance::find($attendance_id);
        $data['users'] = User::where('role_id', '!=', 1)->select('id', 'name')->get();

        return $data;
    }

    public function getAttendanceStatus($user_id, $check_in_time)
    {
        $check_in_time = $check_in_time . ':00';

        $user_info = User::find($user_id);
        $schedule = DutySchedule::where('role_id', $user_info->role_id)->where('status_id', 1)->first();
        if ($schedule) {
            $check_in_time_diff = timeDiff($schedule->start_time, $check_in_time, 'all', $start_date = null, $end_date = null);
            $consider_time = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d') . ' ' . $schedule->start_time);
            $consider_time = Carbon::parse($consider_time)->addMinutes($schedule->consider_time);
            $startTime = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d') . ' ' . $schedule->start_time);
            $endTime = $consider_time;

            $check = Carbon::now()->between($startTime, $endTime, true);
            $office_start_time = Carbon::now()->subMinutes(5);
            $in_time = Carbon::now();
            $result = $startTime->gt($in_time);
            $status = "";
            if ($result) {
                $status = 'OT';
            } else {
                //IF USER CHECK-IN AFTER START TIME
                if ($check) {
                    $status = 'OT';
                } else {
                    $status = 'L';
                }
            }
            return $status;
        } else {
            return "OT";
        }
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
            $check_in_time = strtotime($check_in_time . ':00');
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
            return array();
        }
    }

    public function attendanceFromDevice($request)
    {
        $validator = Validator::make($request->all(), [
            'userID' => 'required',
            'date_time' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->responseWithError(_trans('validation.Validation field required'), $validator->errors(), 422);
        }

        try {
            $request['remote_mode_in'] = 1;
            $request['check_in_location'] = 'Device';
            $request['check_in_latitude'] = '';
            $request['check_in_longitude'] = '';
            $request['city'] = '';
            $request['country_code'] = '';
            $request['country'] = '';
            $request['checkin_ip'] = '';
            $request['check_in'] = $this->timeFormatFromTimestamp($request['date_time']);
            $request['date'] = $this->databaseFormat($request['date_time']);
            $user = $this->user->query()->where('userID', $request->userID)->first();
            if (!$user) {
                return $this->responseWithError('User not found', [], 200);
            }

            auth()->login($user);


            $request['user_id'] = $user->id;
            return $this->userAttendance($user, $request);
        } catch (\Throwable $th) {
            return $this->responseWithError(_trans('response.Something went wrong.'), [$th->getMessage()], 400);
        }
    }


    public function locationCheck($request)
    {
        $locationInfo = false;
        foreach (DB::table('location_binds')->where('company_id', $this->companyInformation()->id)->where('status_id', 1)->get() as $location) {
            $distance = distanceCalculate($request->latitude, $request->longitude, $location->latitude, $location->longitude);
            if ($distance <= $location->distance) {
                $locationInfo = true;
            }
        }
        return $locationInfo;
    }
    public function store($request)
    {

        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'check_in' => 'required',
            'date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return $this->responseWithError(_trans('validation.Validation field required'), $validator->errors(), 422);
        }

        //Start Checking QR Code
        if (settings('attendance_method') == 'QR') {
            $validator = Validator::make($request->all(), [
                'qr_scan' => 'required',
            ]);

            if ($validator->fails()) {
                return $this->responseWithError(_trans('validation.Validation field required'), $validator->errors(), 422);
            }
            try {
                $company_id = decrypt($request->qr_scan);
                if (auth()->user()->company_id != $company_id) {
                    return $this->responseWithError('Invalid QR Code', [], 400);
                }
            } catch (\Throwable $th) {
                return $this->responseWithError(_trans('response.Something went wrong.'), [$th->getMessage()], 400);
            }
        }
        //End QR Checking

        try {
            if (auth()->user()->role->slug == 'staff' && $request->user_id != auth()->id()) {
                return $this->responseWithError('You are doing a shit thing so go away from here!!', [], 400);
            }


            $user = $this->user->query()->find($request->user_id);

            return $this->userAttendance($user, $request);
        } catch (\Throwable $th) {
            return $this->responseWithError(_trans('response.Something went wrong.'), [$th->getMessage()], 400);
        }
    }

    public function userAttendance($user, $request)
    {
        if ($user) {
            $attendance = $this->attendance->where(['user_id' => $user->id, 'date' => $request->date])->first();
            if ($attendance && !settings('multi_checkin')) {
                return $this->responseWithError('Attendance already exists', [], 400);
            }
            if (settings('location_check') && !$this->locationCheck($request)) {
                return $this->responseWithError('Your location is not valid', [], 400);
            }
            $isIpRestricted = $this->isIpRestricted();
            if ($isIpRestricted) {
                $request['checkin_ip'] = \request()->ip();
                $attendance_status = $this->checkInStatus($request->user_id, $request->check_in);
                if (count($attendance_status) > 0) {

                    if ($attendance_status[0] == AttendanceStatus::LATE && $request['check_in_location'] != 'Device') {
                        $validator = Validator::make($request->all(), [
                            'reason' => 'required',
                        ]);

                        if ($validator->fails()) {
                            $data = [
                                'reason_status' => 'L'
                            ];
                            return $this->responseWithError(_trans('validation.Reason is required'), $data, 400);
                        }
                    }
                    $current_date_time = date('Y-m-d H:i:s');
                    $checkinTime = $this->getDateTime($request->check_in);
                    $check_in = new $this->attendance;
                    $check_in->company_id = $user->company->id;
                    $check_in->user_id = $request->user_id;
                    $check_in->remote_mode_in = $request->remote_mode_in;
                    $check_in->date = $request->date;

                    if ($request->attendance_from == 'web') {
                        $check_in->check_in = $checkinTime;
                    } else {
                        $check_in->check_in = $current_date_time;
                    }

                    $check_in->in_status = $attendance_status[0];
                    $check_in->checkin_ip = $request->checkin_ip;
                    $check_in->late_time = $attendance_status[1];
                    $check_in->check_in_location = $request->check_in_location;
                    $check_in->check_in_latitude = $request->check_in_latitude;
                    $check_in->check_in_longitude = $request->check_in_longitude;
                    $check_in->check_in_city = $request->city;
                    $check_in->check_in_country_code = $request->country_code;
                    $check_in->check_in_country = $request->country;
                    $check_in->save();

                    if ($request->reason) {
                        LateInOutReason::create([
                            'attendance_id' => $check_in->id,
                            'user_id' => $check_in->user_id,
                            'company_id' => $check_in->user->company->id,
                            'type' => 'in',
                            'reason' => $request->reason
                        ]);
                    }

                    return $this->responseWithSuccess('Check in successfully', $check_in, 200);
                } else {
                    return $this->responseWithError('No Schedule found', [], 400);
                }
            } else {
                return $this->responseWithError('You your ip address is not valid', [], 400);
            }
            //            }

        } else {
            return $this->responseWithError('No user found', [], 400);
        }
    }

    public function getTodayAttendance($date)
    {
        // $date='2022-05-10';
        // $date=date('Y-m-d');
        $attendance = $this->attendance->where('company_id', auth()->user()->company_id)
            ->where('date', $date)
            ->select('user_id', 'date')
            ->groupBy('user_id', 'date')
            ->get()
            ->count();
        $total_employee = $this->user->where('company_id', auth()->user()->company_id)->where('status_id', 1)->count();
        $today_leave = $this->leave_request_repo->dateWiseLeaveCount($date);
        $data = [
            'Present' => $attendance,
            'total_employee' => $total_employee,
            'Absent' => $total_employee - $attendance - intval($today_leave),
            'Leave' => $today_leave,
        ];
        return $data;
        if ($attendance) {
            return $this->responseWithSuccess('Attendance found', $attendance, 200);
        } else {
            return $this->responseWithError('No attendance found', [], 400);
        }
    }

    public function isIpRestricted(): bool
    {
        $companyId = $this->companyInformation()->id;
        $isIpEnabled = CompanyConfig::where([
            'company_id' => $this->companyInformation()->id,
            'key' => 'ip_check',
            'value' => 1
        ])->first();



        //if IP restriction is enabled the meet the condition and go for IP check otherwise this will return true
        if ($isIpEnabled) {
            // if (\request()->get('remote_mode_in') === 0 || \request()->get('remote_mode_out') === 0) {
            $getIps = IpSetup::where('company_id', $companyId)->where('status_id', 1)->whereIn('ip_address', [\request()->ip()])->get();
            if ($getIps->count() > 0) {
                return true;
            } else {
                return false;
            }
            // } else {
            //     return true;
            // }
        } else {
            return true;
        }
    }

    public function lateInOutReason($request, $attendance_id): \Illuminate\Http\JsonResponse
    {
        $attendance = $this->attendance->query()->find($attendance_id);
        if ($attendance) {
            $request['company_id'] = $this->companyInformation()->id;
            $attendance->lateInOutReason()->create($request->all());
            return $this->responseWithSuccess('Reason added successfully', [], 200);
        } else {
            return $this->responseWithError('No data found', $attendance, 400);
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
            $endTime = strtotime($schedule->end_time);
            $formate = [
                'check_out_time' => $check_out_time,
                'endTime' => $schedule->end_time
            ];
            $check_out_time = strtotime($formate['check_out_time']);
            $endTime = strtotime($formate['endTime']);
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
            return array();
        }
    }

    public function checkOut($request, $id)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'check_out' => 'required',
            'date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return $this->responseWithError(_trans('validation.Validation field required'), $validator->errors(), 422);
        }


        try {

            if (settings('location_check') && !$this->locationCheck($request)) {
                return $this->responseWithError('Your location is not valid', [], 400);
            }
            if (settings('ip_check') && !$this->isIpRestricted()) {
                return $this->responseWithError('You your ip address is not valid', [], 400);
            }
            $user = $this->user->query()->find($request->user_id);
            if ($user) {
                $isIpRestricted = $this->isIpRestricted();
                if ($isIpRestricted) {
                    $request['checkin_ip'] = \request()->ip();
                    $attendance_status = $this->checkOutStatus($request->user_id, $request->check_out);
                    if (count($attendance_status) > 0) {
                        if ($attendance_status[0] == AttendanceStatus::LEFT_EARLY) {
                            $validator = Validator::make($request->all(), [
                                'reason' => 'required',
                            ]);

                            if ($validator->fails()) {
                                $data = [
                                    'reason_status' => 'LE'
                                ];
                                return $this->responseWithError(_trans('validation.Reason is required'), $data, 422);
                            }
                        }


                        $checkOutTime = $this->getDateTime($request->check_out);
                        $time_zone = @settings('timezone') ?? config('app.timezone');
                        date_default_timezone_set($time_zone);
                        $current_date_time = date('Y-m-d H:i:s');
                        $check_in = $this->attendance->query()->find($id);
                        if ($check_in) {
                            $check_in->user_id = $request->user_id;
                            $check_in->remote_mode_out = $request->remote_mode_out;
                            $check_in->date = $request->date;
                            $check_in->check_out = $current_date_time;
                            $check_in->out_status = $attendance_status[0];
                            $check_in->checkout_ip = $request->checkin_ip;
                            $check_in->late_time = $attendance_status[1];
                            $check_in->check_out_location = $request->check_out_location;
                            $check_in->check_out_latitude = $request->check_out_latitude;
                            $check_in->check_out_longitude = $request->check_out_longitude;
                            $check_in->check_out_city = $request->city;
                            $check_in->check_out_country_code = $request->country_code;
                            $check_in->check_out_country = $request->country;
                            $check_in->save();
                            if ($request->reason) {
                                LateInOutReason::create([
                                    'attendance_id' => $check_in->id,
                                    'user_id' => $check_in->user_id,
                                    'company_id' => $check_in->user->company->id,
                                    'type' => 'out',
                                    'reason' => $request->reason
                                ]);
                            }
                            return $this->responseWithSuccess('Check out successfully', $check_in, 200);
                        } else {
                            return $this->responseWithError('No data found', [], 400);
                        }
                    } else {
                        return $this->responseWithError('No Schedule found', [], 400);
                    }
                } else {
                    return $this->responseWithError('You your ip address is not valid', [], 400);
                }
            } else {
                return $this->responseWithError('No user found', [], 400);
            }
        } catch (\Throwable $th) {
            return $this->responseWithError(_trans('response.Something went wrong.'), [], 400);
        }
    }
    public function webCheckOut($request, $id)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'check_out' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->responseWithError(_trans('validation.Validation field required'), $validator->errors(), 422);
        }


        try {

            if (settings('location_check') && !$this->locationCheck($request)) {
                return $this->responseWithError('Your location is not valid', [], 400);
            }
            if (settings('ip_check') && !$this->isIpRestricted()) {
                return $this->responseWithError('You your ip address is not valid', [], 400);
            }
            $user = $this->user->query()->find($request->user_id);
            if ($user) {
                $isIpRestricted = $this->isIpRestricted();
                if ($isIpRestricted) {
                    $request['checkin_ip'] = \request()->ip();
                    $attendance_status = $this->checkOutStatus($request->user_id, $request->check_out);
                    if (count($attendance_status) > 0) {
                        if ($attendance_status[0] == AttendanceStatus::LEFT_EARLY) {
                            $validator = Validator::make($request->all(), [
                                'reason' => 'required',
                            ]);

                            if ($validator->fails()) {
                                $data = [
                                    'reason_status' => 'LE'
                                ];
                                return $this->responseWithError(_trans('validation.Reason is required'), $data, 422);
                            }
                        }


                        $checkOutTime = $this->getDateTime($request->check_out);
                        $check_in = $this->attendance->query()->find($id);
                        if ($check_in) {
                            $check_in->user_id = $request->user_id;
                            $check_in->remote_mode_out = $request->remote_mode_out;
                            $check_in->date = $request->date;
                            $check_in->check_out = $checkOutTime;
                            $check_in->out_status = $attendance_status[0];
                            $check_in->checkout_ip = $request->checkin_ip;
                            $check_in->late_time = $attendance_status[1];
                            $check_in->check_out_location = $request->check_out_location;
                            $check_in->check_out_latitude = $request->check_out_latitude;
                            $check_in->check_out_longitude = $request->check_out_longitude;
                            $check_in->check_out_city = $request->city;
                            $check_in->check_out_country_code = $request->country_code;
                            $check_in->check_out_country = $request->country;
                            $check_in->save();
                            if ($request->reason) {
                                LateInOutReason::create([
                                    'attendance_id' => $check_in->id,
                                    'user_id' => $check_in->user_id,
                                    'company_id' => $check_in->user->company->id,
                                    'type' => 'out',
                                    'reason' => $request->reason
                                ]);
                            }
                            return $this->responseWithSuccess('Check out successfully', $check_in, 200);
                        } else {
                            return $this->responseWithError('No data found', [], 400);
                        }
                    } else {
                        return $this->responseWithError('No Schedule found', [], 400);
                    }
                } else {
                    return $this->responseWithError('You your ip address is not valid', [], 400);
                }
            } else {
                return $this->responseWithError('No user found', [], 400);
            }
        } catch (\Throwable $th) {
            return $this->responseWithError(_trans('response.Something went wrong.'), [], 400);
        }
    }

    public function checkOutFromAdmin($request, $attendance_id)
    {
        try {
            $check_out = Carbon::now()->toDateTimeString();
            $attendance = $this->attendance->query()->find($attendance_id);
            $check_in = date("H:i:s", strtotime($attendance->check_in));
            $check_out_time = date("H:i:s", strtotime($check_out));
            $stay_time = timeDiff($check_in, $check_out_time, 'all', $start_date = null, $end_date = null);

            $attendance->check_out = $check_out;
            $attendance->checkout_ip = \request()->ip();
            $attendance->stay_time = $stay_time;
            $attendance->save();

            return $this->responseWithSuccess('Check out successfully', $check_in, 200);
        } catch (\Throwable $th) {
            return $this->responseWithError(_trans('response.Something went wrong.'), [], 400);
        }
    }

    function resetCheckOutDate($data,$request){
        $check_in=$data->check_in;
        $check_in_date=date('Y-m-d',strtotime($data->check_in));
        $check_out=$data->check_out;
        $checkOutoutDate = intval($this->dateDiff($check_out,$check_in));

        if($checkOutoutDate >= 0){
            $check_out=Carbon::parse($request->date)->addDays($checkOutoutDate)->format('Y-m-d').' '.$request->check_out;
        }
        return $check_out;
    }

    public function update($request, $id)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'date' => 'required',
            'check_out' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->responseWithError(_trans('validation.Validation field required'), $validator->errors(), 422);
        }

        try {
            $user = $this->user->query()->find($request->user_id);
            if ($user) {
                $request['checkout_ip'] = \request()->ip();
                $attendance_status = $this->checkOutStatus($request->user_id, $request->check_out);
                if (count($attendance_status) > 0) {

                    if (settings('location_check') && !$this->locationCheck($request)) {
                        return $this->responseWithError('Your location is not valid', [], 400);
                    }
                    if (settings('ip_check') && !$this->isIpRestricted()) {
                        return $this->responseWithError('You your ip address is not valid', [], 400);
                    }

                    $checkInTime = Carbon::createFromFormat('Y-m-d H:i:s', $request->date . ' ' . $request->check_in . ':00');
                    $checkOutTime = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d') . ' ' . $request->check_out . ':00');
                    $check_out = $this->attendance->query()->find($id);
                    $check_out->user_id = $request->user_id;
                    $check_out->check_in_location = $request->check_in_location;
                    $check_out->check_in = $checkInTime;
                    $check_out->date = $request->date ? $request->date : $check_out->date;
                    $check_out->check_out = $this->resetCheckOutDate($check_out,$request);
                    $check_out->check_out_location = $request->check_out_location;
                    $check_out->out_status = $attendance_status[0];
                    $check_out->late_time = $attendance_status[1];
                    $check_out->save();

                    if ($request->late_in_reason) {
                        LateInOutReason::updateOrCreate([
                            'attendance_id' => $check_out->id,
                            'company_id' => $check_out->user->company->id,
                            'type' => 'in',
                        ], [
                            'reason' => $request->late_in_reason
                        ]);
                    }
                    if ($request->early_leave_reason) {
                        LateInOutReason::updateOrCreate([
                            'attendance_id' => $check_out->id,
                            'company_id' => $check_out->user->company->id,
                            'type' => 'out',
                        ], [
                            'reason' => $request->early_leave_reason
                        ]);
                    }


                    return $this->responseWithSuccess('Check out successfully', $check_out, 200);
                } else {
                    return $this->responseWithError('No Schedule found', [], 400);
                }
            } else {
                return $this->responseWithError('No user found', [], 400);
            }
        } catch (\Throwable $th) {
            return $this->responseWithError(_trans('response.Something went wrong.'), [], 400);
        }
    }

    public function liveLocationStore($request)
    {
        try {

            foreach ($request->locations as $key => $location) {
                $locationLog = new LocationLog();
                $locationLog->date = $request->date;
                $locationLog->latitude = $location['latitude'];
                $locationLog->longitude = $location['longitude'];
                $locationLog->speed = $location['speed'];
                $locationLog->heading = $location['heading'];
                $locationLog->city = $location['city'];
                $locationLog->country = $location['country'];
                $locationLog->distance = $location['distance'];
                $locationLog->address = $location['address'];
                $locationLog->countryCode = $location['countryCode'];
                $locationLog->user_id = auth()->id();
                $locationLog->company_id = auth()->user()->company_id;
                $locationLog->save();
            }



            return $this->responseWithSuccess('Live data stored successfully');
        } catch (\Throwable $exception) {
            return $this->responseWithError($exception->getMessage(), [], 400);
        }
    }


    // new functions

    function fields()
    {
        return [
            _trans('common.ID'),
            _trans('common.Name'),
            _trans('common.Date'),
            _trans('common.Department'),
            _trans('common.Break'),
            _trans('common.Break Duration'),
            _trans('common.Check In'),
            _trans('common.Check Out'),
            _trans('common.Hours'),
            _trans('common.Overtime'),
            _trans('common.Action')

        ];
    }
    function report_fields()
    {
        return [
            _trans('common.ID'),
            _trans('common.Name'),
            _trans('common.Date'),
            _trans('common.Department'),
            _trans('common.Break'),
            _trans('common.Break Duration'),
            _trans('common.Check In'),
            _trans('common.Check Out'),
            _trans('common.Hours'),
            _trans('common.Overtime'),

        ];
    }


    // new functions for
    public function getTodayAttendanceDashboard($date)
    {
        $attendance = $this->attendance->where('company_id', auth()->user()->company_id)
            ->where('date', $date)
            ->select('user_id', 'date')
            ->groupBy('user_id', 'date')
            ->get()
            ->count();
        $total_employee = $this->user->where('company_id', auth()->user()->company_id)->where('status_id', 1)->count();
        $today_leave = $this->leave_request_repo->dateWiseLeaveCount($date);
        $data = [
            [
                'value' => $attendance,
                'name' => _trans('common.Present'),
            ],
            [
                'value' => $total_employee - $attendance - intval($today_leave),
                'name' => _trans('common.Absent'),
            ],
            [
                'value' => $today_leave,
                'name' => _trans('common.Leave'),
            ]
        ];
        return $data;
    }
}
