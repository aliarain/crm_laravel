<?php

namespace App\Http\Controllers\Backend\Leave;

use App\Models\Role\Role;
use Illuminate\Http\Request;
use App\Models\Hrm\Leave\LeaveType;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\Hrm\Leave\AssignLeave;
use App\Repositories\Admin\RoleRepository;
use App\Http\Requests\Hrm\Leave\AssignLeaveRequest;
use App\Repositories\Hrm\Leave\LeaveTypeRepository;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Repositories\Hrm\Leave\AssignLeaveRepository;
use App\Models\coreApp\Relationship\RelationshipTrait;
use App\Repositories\Hrm\Department\DepartmentRepository;

class AssignLeaveController extends Controller
{
    use RelationshipTrait, ApiReturnFormatTrait;

    protected AssignLeaveRepository $assignLeave;
    protected RoleRepository $role;
    protected LeaveTypeRepository $leaveType;
    protected DepartmentRepository $departmentRepository;
    protected $model;

    public function __construct(AssignLeaveRepository $assignLeaveRepository, RoleRepository $role, LeaveTypeRepository $leaveType, AssignLeave $model, DepartmentRepository $departmentRepository)
    {
        $this->assignLeave = $assignLeaveRepository;
        $this->role = $role;
        $this->leaveType = $leaveType;
        $this->model = $model;
        $this->departmentRepository = $departmentRepository;
    }

    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                return $this->assignLeave->table($request);
            }
            $data['class']  = 'leave_assign_table';
            $data['fields'] = $this->assignLeave->fields();
            $data['checkbox'] = true;
            $data['title'] = _trans('leave.Assign leave');
            $data['departments'] = $this->departmentRepository->getAll();

            return view('backend.leave.assign.index', compact('data'));
        } catch (\Exception $exception) {
            Toastr::error(_trans('response.Something went wrong!'), 'Error');
            return redirect()->back();
        }
    }

    public function create()
    {
        try {
            $data['title']     = _trans('leave.Create Assign Leave');
            $data['url']       = route('assignLeave.store');
            $data['attributes'] = $this->assignLeave->createAttributes();
            @$data['button']   = _trans('common.Save');
            return view('backend.modal.create', compact('data'));
        } catch (\Throwable $th) {
            return response()->json('fail');
        }
    }


    public function dataTable(Request $request)
    {
        try {
            return $this->assignLeave->dataTable($request, $id = null);
        } catch (\Exception $e) {
            Toastr::error(_trans('response.Something went wrong!'), 'Error');
            return redirect()->back();
        }
    }

    public function store(AssignLeaveRequest $request)
    {
        try {
            if (!$request->ajax()) {
                Toastr::error(_trans('response.Please click on button!'), 'Error');
                return redirect()->back();
            }
            if (demoCheck()) {
                return $this->responseWithError(_trans('message.You cannot do it for demo'), [], 400);
            }
            return $this->assignLeave->store($request);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }

    public function show($id)
    {
        return $this->assignLeave->show($id);
    }

    public function edit(AssignLeave $assignLeave)
    {
        try {
            $data['title'] = _trans('common.Edit Assign Leave');
            $data['url']          = route('assignLeave.update', $assignLeave->id);
            $data['attributes'] = $this->assignLeave->editAttributes($assignLeave);
            @$data['button']   = _trans('common.Update');
            return view('backend.modal.create', compact('data'));
        } catch (\Throwable $th) {
            return response()->json('fail');
        }
    }

    public function update(AssignLeaveRequest $request, $id)
    {
        try {
            if (!$request->ajax()) {
                Toastr::error(_trans('response.Please click on button!'), 'Error');
                return redirect()->back();
            }
            if (demoCheck()) {
                return $this->responseWithError(_trans('message.You cannot do it for demo'), [], 400);
            }
            return $this->assignLeave->update($request, $id);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }

    public function isAlreadyAssigned($request)
    {
        $exists = AssignLeave::where([
            'type_id' => $request->type_id,
            'role_id' => $request->role_id,
        ])->first();
        if (!$exists) {
            return true;
        } else {
            return false;
        }
    }

    public function delete(AssignLeave $assignLeave)
    {
        if (demoCheck()) {
            Toastr::error(_trans('message.You cannot do it for demo'), 'Error');
            return redirect()->back();
        }
        return $this->assignLeave->destroy($assignLeave->id);
    }

    // status change
    public function statusUpdate(Request $request)
    {
        if (demoCheck()) {
            return $this->responseWithError(_trans('message.You cannot do it for demo'), [], 400);
        }
        return $this->assignLeave->statusUpdate($request);
    }

    // destroy all selected data

    public function deleteData(Request $request)
    {
        if (demoCheck()) {
            return $this->responseWithError(_trans('message.You cannot delete for demo'), [], 400);
        }
        return $this->assignLeave->destroyAll($request);
    }
}
