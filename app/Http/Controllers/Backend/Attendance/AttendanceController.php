<?php

namespace App\Http\Controllers\Backend\Attendance;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Config;
use App\Models\Hrm\Attendance\Attendance;
use App\Services\Hrm\EmployeeBreakService;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Models\coreApp\Relationship\RelationshipTrait;
use App\Http\Requests\Hrm\Attendance\AttendanceRequest;
use App\Repositories\Report\AttendanceReportRepository;
use App\Repositories\Hrm\Attendance\AttendanceRepository;
use App\Repositories\Hrm\Department\DepartmentRepository;

class AttendanceController extends Controller
{
    use RelationshipTrait, ApiReturnFormatTrait;

    protected $attendance_repo;
    protected $departmentRepository;
    protected $userRepository;
    protected $breakBackService;
    protected $attendanceReportRepository;

    public function __construct(
        AttendanceRepository $attendance_repo,
        DepartmentRepository $departmentRepository,
        UserRepository $userRepository,
        AttendanceReportRepository $attendanceReportRepository,
        EmployeeBreakService $breakBackService
    ) {
        $this->attendance_repo = $attendance_repo;
        $this->departmentRepository = $departmentRepository;
        $this->userRepository = $userRepository;
        $this->breakBackService = $breakBackService;
        $this->attendanceReportRepository = $attendanceReportRepository;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->attendanceReportRepository->table($request);
        }
        $data['class']  = 'attendance_table';
        $data['fields'] = $this->attendance_repo->fields();
        $data['table']     = route('attendance.index');
        $data['url_id']    = 'attendance_table_url';
        $data['checkbox'] = true;
        $data['title'] = _trans('attendance.Attendance History');
        $data['departments'] = $this->departmentRepository->getAll();
        return view('backend.attendance.attendance.index', compact('data'));
    }

    public function checkIn()
    {
        $data['title'] = _trans('attendance.Add Attendance');
        $data['users'] = $this->attendance_repo->checkInUsers();
        return view('backend.attendance.attendance.check_in', compact('data'));
    }

    public function checkInEdit(Attendance $attendance)
    {
        
        $data['title'] = _trans('attendance.Edit Attendance');
        $data['show'] = $attendance->load('lateInReason');
        $data['users'] = $this->attendance_repo->checkInUsers();
        return view('backend.attendance.attendance.check_out', compact('data'));
    }



    public function show($attendance_id)
    {
        $data = $this->attendance_repo->show($attendance_id);
        return view('backend.attendance.attendance.edit', compact('data'));
    }

    public function store(Request $request)
    {


        if (demoCheck()) {
            Toastr::error(_trans('message.You cannot do it for demo'), 'Error');
            return redirect()->back();
        }

        try {
            $request['remote_mode_in'] = 0;
            if ($request->in_from_web) {
                $request['company_id'] = $this->userRepository->getById($request->user_id)->company->id;
            } else {
                $request['company_id'] = $this->companyInformation()->id;
            }

            $request['latitude'] = $request->check_in_latitude;
            $request['longitude'] = $request->check_in_longitude;

            $store = $this->attendance_repo->store($request);
            if ($store->original['result']) {
                if ($request->check_out) {
                    $request['remote_mode_out'] = 0;
                    $this->attendance_repo->update($request, $store->original['data']->id);
                }

                Toastr::success(_trans('attendance.Attendance has been created'), 'Success');
                return redirect()->route('attendance.index');
            } else {
                Toastr::error($store->original['message'], 'Error');
                return redirect()->back();
            }
        } catch (\Throwable $th) {
            Toastr::error(_trans('response.Something went wrong'), 'Error');
            return redirect()->back();
        }
    }

    public function update(Request $request, Attendance $attendance_id)
    {
        if (demoCheck()) {
            Toastr::error(_trans('message.You cannot do it for demo'), 'Error');
            return redirect()->back();
        }

        try {
            $request['remote_mode_out'] = 0;
            $request['user_id'] = $attendance_id->user_id;
            $checkout = $this->attendance_repo->update($request, $attendance_id->id);
            if ($checkout->original['result']) {
                Toastr::success(_trans('attendance.Attendance has been updated'), 'Success');
                return redirect()->route('attendance.index');
            } else {
                Toastr::error(_trans('attendance.Attendance did not checkout'), 'Error');
                return redirect()->back();
            }
        } catch (\Throwable $th) {
            Toastr::error(_trans('response.Something went wrong'), 'Error');
            return redirect()->back();
        }
    }

    public function checkOut(Request $request, $attendance_id)
    {
        try {
            $checkout = $this->attendance_repo->checkOutFromAdmin($request, $attendance_id);
            if ($checkout->original['result']) {
                Toastr::success(_trans('attendance.Checkout successfully'), 'Success');
                return redirect()->route('attendance.index');
            } else {
                Toastr::error(_trans('attendance.Did not checkout'), 'Error');
                return redirect()->back();
            }
        } catch (\Throwable $th) {
            Toastr::error(_trans('response.Something went wrong'), 'Error');
            return redirect()->back();
        }
    }
    public function dashboardCheckin(Request $request)
    {

        try {
            $request['user_id'] = auth()->user()->id;
            $request['check_in'] = date('H:i');
            $request['date'] = date('Y-m-d');
            $request['check_in_latitude'] = $request->latitude;
            $request['check_in_longitude'] = $request->longitude;
            $request['remote_mode_in'] = 0;
            $request['company_id'] = $this->userRepository->getById($request->user_id)->company->id;
            $store = $this->attendance_repo->store($request);
            if ($store->original['result']) {
                if ($request->check_out) {
                    $request['remote_mode_out'] = 0;
                    $this->attendance_repo->update($request, $store->original['data']->id);
                }
                Toastr::success(_trans('attendance.Attendance has been created'), 'Success');
                return redirect()->back();
            } else {
                Toastr::error($store->original['message'], 'Error');
                return redirect()->route('attendance.check-in');
            }
        } catch (\Throwable $th) {
            Toastr::error(_trans('response.Something went wrong'), 'Error');
            return redirect()->back();
        }
    }
    public function dashboardAjaxCheckin(Request $request)
    {

        try {
            $request['user_id'] = auth()->user()->id;
            $request['check_in'] = date('H:i');
            $request['date'] = date('Y-m-d');
            $request['check_in_latitude'] = $request->latitude;
            $request['check_in_longitude'] = $request->longitude;
            $request['remote_mode_in'] = 0;
            $request['company_id'] = $this->userRepository->getById($request->user_id)->company->id;
            $store = $this->attendance_repo->store($request);
            if ($store->original['result']) {
                if ($request->check_out) {
                    $request['remote_mode_out'] = 0;
                    $this->attendance_repo->update($request, $store->original['data']->id);
                }
                return $this->responseWithSuccess($store->original['message'], route('attendance.index'), 200);
            } else {
                return $this->responseWithError($store->original['message'], route('attendance.check-in'), 422);
            }
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage());
        }
    }
    public function dashboardCheckOut(Request $request)
    {
        try {
            $request['user_id'] = auth()->user()->id;
            $request['check_out'] = date('H:i');
            $request['date'] = date('Y-m-d');

            $request['remote_mode_out'] = 0;
            $attendance = Attendance::where('user_id', $request->user_id)->where('date', $request->date)->where('check_in', '!=', null)->where('check_out', '=', null)->first();
            $time1 = strtotime($attendance->check_in);
            $request['check_in'] = date('h:i', $time1);
            $request['user_id'] = $attendance->user_id;
            $checkout = $this->attendance_repo->update($request, $attendance->id);
            if ($checkout->original['result']) {
                Toastr::success(_trans('attendance.Attendance has been updated'), 'Success');
                return redirect()->back();
            } else {
                Toastr::error(_trans('attendance.Attendance did not checkout'), 'Error');
                return redirect()->back();
            }
        } catch (\Throwable $th) {
            Toastr::error(_trans('response.Something went wrong'), 'Error');
            return redirect()->back();
        }
    }
    public function ajaxDashboardCheckOut(Request $request)
    {
        try {
            $request['user_id'] = auth()->user()->id;
            $request['check_out'] = date('H:i');
            $request['date'] = date('Y-m-d');
            $request['check_in_latitude'] = $request->latitude;
            $request['check_in_longitude'] = $request->longitude;
            $request['remote_mode_out'] = 0;
            $attendance = Attendance::where('user_id', $request->user_id)->where('date', $request->date)->where('check_in', '!=', null)->where('check_out', '=', null)->first();
            if (!$attendance) {
                return $this->responseWithError('Attendance Data Not Found', false);
            }
            $time1 = strtotime($attendance->check_in);
            $request['check_in'] = date('h:i', $time1);
            $request['user_id'] = $attendance->user_id;
            $checkout = $this->attendance_repo->update($request, $attendance->id);
            if ($checkout->original['result']) {
                return $this->responseWithSuccess($checkout->original['message'], route('attendance.index'), 200);
            } else {
                return $this->responseWithError($checkout->original['message'], false);
            }
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage());
        }
    }
    public function dashboardBreakBack(Request $request, $slug)
    {

        try {
            $request['time'] = date('H:i:s');
            $data = $this->breakBackService->breakStartEndWeb($request, $slug);
            if (!$data) {
                return response()->json('fail');
            } else {
                return view('backend.modal.break_back', compact('data'));
            }
        } catch (\Throwable $th) {
            return response()->json('fail');
        }
    }

    public function dashboardBreakStart(Request $request)
    {
        try {
            $request['time'] = date('H:i:s');
            $slug = _trans('common.start');
            $this->breakBackService->breakStartEnd($request, 'start');
            Toastr::success(_trans('Break has been ') . $slug, 'Success');
            return redirect()->back();
        } catch (\Throwable $th) {
            Toastr::error(_trans('response.Something went wrong'), 'Error');
            return redirect()->back();
        }
    }

    public function userProfileTable(Request $request)
    {
        if ($request->ajax()) {
            return $this->attendanceReportRepository->table($request);
        }
    }

    // new functions for
    function newDashboardBreakBack(Request $request)
    {
        try {
            $request['time'] = date('H:i:s');
            $data = $this->breakBackService->breakStartEndWeb($request, 'break_back');
            if (!$data) {
                return $this->responseWithError('fail', false);
            } else {
                return $this->responseWithSuccess('success', $data, 200);
            }
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage());
        }
    }
}
