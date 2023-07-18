<?php

namespace App\Http\Controllers\Backend\Attendance;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\Hrm\Attendance\Attendance;
use App\Services\Hrm\EmployeeBreakService;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Models\coreApp\Relationship\RelationshipTrait;
use App\Repositories\Hrm\Attendance\AttendanceRepository;
use App\Repositories\Hrm\Department\DepartmentRepository;

class CheckInController extends Controller
{
    use RelationshipTrait, ApiReturnFormatTrait;

    protected $attendance_repo;
    protected $departmentRepository;
    protected $userRepository;
    protected $breakBackService;

    public function __construct(AttendanceRepository $attendance_repo, DepartmentRepository $departmentRepository, UserRepository $userRepository, EmployeeBreakService $breakBackService)
    {
        $this->attendance_repo = $attendance_repo;
        $this->departmentRepository = $departmentRepository;
        $this->userRepository = $userRepository;
        $this->breakBackService = $breakBackService;
    }

    public function dashboardAjaxCheckinModal(Request $request)
    {

        try {
            $data['title']    = _trans('common.Check In');
            $data['url']      = route('admin.ajaxDashboardCheckin');
            $data['button']   = _trans('common.Check In');
            $data['type']     = 'checkin';
            $data['reason']   = $this->attendance_repo->checkInStatus(auth()->user()->id, date('H:i'));
            return view('backend.attendance.attendance.check_in_modal', compact('data'));
        } catch (\Throwable $th) {
            return response()->json('fail');
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
            // $request['remote_mode_in'] = 0;
            if (!$request->has('remote_mode_in')) {
                return $this->responseWithError(_trans('messages.Remote mode is not selected'));
            }
            $request['company_id'] = $this->userRepository->getById($request->user_id)->company->id;
            $store = $this->attendance_repo->store($request);
            if ($store->original['result']) {
                if ($request->check_out) {
                    $request['remote_mode_out'] = 0;
                    $this->attendance_repo->update($request, $store->original['data']->id);
                }
                return $this->responseWithSuccess($store->original['message'], route('attendance.index'), 200);
            } else {
                return $this->responseWithError($store->original['message']);
            }
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage());
        }
    }

    public function ajaxDashboardCheckOutModal(Request $request)
    {

        try {
            $data['attendance'] = Attendance::where('user_id', auth()->user()->id)->where('date', date('Y-m-d'))->where('check_in', '!=', null)->where('check_out', '=', null)->first();
            if (!$data['attendance']) {
                return response()->json('fail');
            }
            $data['title']    = _trans('common.Check Out');
            $data['url']      = route('admin.ajaxDashboardCheckOut');
            $data['button']   = _trans('common.Check Out');
            $data['type']     = 'checkin';
            $data['reason']   = $this->attendance_repo->checkOutStatus(auth()->user()->id, date('H:i'));
            return view('backend.attendance.attendance.check_out_modal', compact('data'));
        } catch (\Throwable $th) {
            return response()->json('fail');
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

            $checkout = $this->attendance_repo->webCheckOut($request, $attendance->id);
            if ($checkout->original['result']) {
                return $this->responseWithSuccess($checkout->original['message'], route('attendance.index'), 200);
            } else {
                return $this->responseWithError($checkout->original['message'], false);
            }
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage());
        }
    }


    // tea break

    public function dashboardAjaxBreakModal(Request $request)
    {

        try {
            $data['title']    = _trans('common.Take Break');
            $data['url']      = route('admin.ajaxDashboardBreak');
            return view('backend.attendance.attendance.break', compact('data'));
        } catch (\Throwable $th) {
            return response()->json('fail');
        }
    }

    public function dashboardAjaxBreak(Request $request)
    {
        try {
            $request['time'] = date('H:i:s');
            $break = $this->breakBackService->breakStartEnd($request, 'start');
            if ($break->original['result']) {
                $route = [route('admin.ajaxDashboardBreakModal_Back'), route('admin.ajaxDashboardBreakModal')];
                return $this->responseWithSuccess($break->original['message'] ,$route, 200);
            } else {
                return $this->responseWithError($break->original['message'], false);
            }
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage());
        }
    }

    public function ajaxDashboardBreakModalBack(Request $request,$slug='back')
    {

        try {
            $request['time']=date('H:i:s');
            $data['title']  = _trans('common.Back Break time');
            $data = $this->breakBackService->breakStartEndWeb($request, $slug);  
            if (!$data) {
                return response()->json('fail');
            }else {
                return view('backend.modal.break_back',compact('data'));
            }     
        } catch (\Throwable $th) {
            return response()->json('fail');
        }
    }
}
