<?php

namespace App\Http\Controllers\Backend\Employee;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\Hrm\Leave\LeaveRequest;
use App\Repositories\Hrm\Employee\AppoinmentRepository;
use App\Http\Requests\Appointment\CreateAppointmentRequest;

class AppointmentController extends Controller
{

    protected $appointRepo;
    public function __construct(AppoinmentRepository $appointRepo)
    {
        $this->appointRepo = $appointRepo;
    }
    public function index()
    {
        try {
            $data['title'] = _trans('leave.Leave Request');
            $data['departments'] = resolve(DepartmentRepository::class)->getAll();
            $data['url'] = route('leaveRequest.dataTable');
            return view('backend.leave.leaveRequest.index', compact('data'));
        } catch (\Exception $exception) {
            Toastr::error(_trans('response.Something went wrong!'), 'Error');
            return redirect()->back();
        }
    }
    public function list()
    {
        try {
            $data['title'] = _trans('appointment.Appointment List');
            $data['id'] = auth()->user()->id;

            $data['checkbox'] = true;
            $data['fields']    = $this->appointRepo->fields();
            $data['table']    = route('appointment.table');
            $data['url_id']    = 'appointment_table_url';
            $data['class']     = 'table_class';

            return view('backend.appointment.index', compact('data'));
        } catch (\Exception $exception) {
            Toastr::error(_trans('response.Something went wrong!'), 'Error');
            return redirect()->back();
        }
    }

    public function table(Request $request)
    {
        if ($request->ajax()) {
            return $this->appointRepo->table($request);
        }
    }


    public function create()
    {
        try {
            $data['title'] = _trans('common.Appointment');
            return view('backend.appointment.create', compact('data'));
        } catch (\Exception $exception) {
            Toastr::error(_trans('response.Something went wrong!'), 'Error');
            return redirect()->back();
        }
    }

    public function store(CreateAppointmentRequest $request)
    {
        if (demoCheck()) {
            Toastr::error(_trans('message.You cannot do it for demo'), 'Error');
            return redirect()->back();
        }

        try {
            $data = $this->appointRepo->store($request);
            if ($data->original['result']) {
                Toastr::success(_trans('response.Appointment created successfully'), 'Success');
                return redirect()->route('appointment.index');
            } else {
                Toastr::error('Appointment is not available for you', 'Error');
            }
            return redirect()->back();
        } catch (\Exception $exception) {
            Toastr::error(_trans('response.Something went wrong!'), 'Error');
            return redirect()->back();
        }
    }


    public function dataTable(Request $request)
    {
        return $this->appointRepo->dataTable($request);
    }

    public function profileDataTable(Request $request)
    {
        return $this->appointRepo->profileDataTable($request, $request->id);
    }


    public function delete(LeaveRequest $leaveRequest): \Illuminate\Http\RedirectResponse
    {

        if (demoCheck()) {
            Toastr::error(_trans('message.You cannot do it for demo'), 'Error');
            return redirect()->back();
        }

        try {
            $data = $this->appointRepo->destroy($leaveRequest->id);
            if ($data) {
                Toastr::success(_trans('response.Operation successful'), 'Success');                
                return redirect()->route('appointment.index');
            } else {
                Toastr::error('Operation is not successful', 'Error');                
                return redirect()->route('appointment.index');
            }
        } catch (\Exception $exception) {
            Toastr::error(_trans('response.Something went wrong!'), 'Error');
            return redirect()->back();
        }
    }

    public function userProfileTable(Request $request)
    {
        if ($request->ajax()) {
            return $this->appointRepo->table($request);
        }
    }
}
