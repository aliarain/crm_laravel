<?php

namespace App\Http\Controllers\Backend\Leave;

use Illuminate\Http\Request;
use App\Models\Hrm\Leave\LeaveType;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\Hrm\Leave\LeaveRequest;
use App\Http\Requests\Hrm\Leave\LeaveTypeRequest;
use App\Repositories\Hrm\Leave\LeaveTypeRepository;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Models\coreApp\Relationship\RelationshipTrait;

class LeaveTypeController extends Controller
{
    use RelationshipTrait, ApiReturnFormatTrait;

    protected LeaveTypeRepository $leaveType;
    protected $model;

    public function __construct(LeaveTypeRepository $leaveTypeRepository, LeaveType $leaveType)
    {
        $this->leaveType = $leaveTypeRepository;
        $this->model = $leaveType;
    }

    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                return $this->leaveType->table($request);
            }
            $data['class']  = 'leave_type_table';
            $data['fields'] = $this->leaveType->fields();
            $data['checkbox'] = true;
            $data['title'] = _trans('leave.Leave type');
            return view('backend.leave.type.index', compact('data'));
        } catch (\Exception $e) {
            Toastr::error(_trans('response.Something went wrong!'), 'Error');
            return redirect()->back();
        }
    }

    public function create()
    {
        $data['title'] = _trans('leave.Add leave type');
        return view('backend.leave.type.create', compact('data'));
    }


    public function dataTable(Request $request)
    {
        try {
            return $this->leaveType->dataTable($request, $id = null);
        } catch (\Exception $e) {
            Toastr::error(_trans('response.Something went wrong!'), 'Error');
            return redirect()->back();
        }
    }

    public function show(LeaveType $leaveType)
    {
        return $this->leaveType->show($leaveType->id);
    }

    public function edit(LeaveType $leaveType)
    {
        $data['title'] = _trans('leave.Edit leave type');
        $data['show'] = $this->leaveType->show($leaveType->id);
        return view('backend.leave.type.edit', compact('data'));
    }

 

    public function delete($id): \Illuminate\Http\RedirectResponse
    {
        if (demoCheck()) {
            Toastr::error(_trans('message.You cannot do it for demo'), 'Error');
            return redirect()->back();
        }
        return $this->leaveType->destroy($id);
    }

    // status change
    public function statusUpdate(Request $request)
    {
        if (demoCheck()) {
            return $this->responseWithError(_trans('message.You cannot do it for demo'), [], 400);
        }
        return $this->leaveType->statusUpdate($request);
    }

    // destroy all selected data

    public function deleteData(Request $request)
    {
        if (demoCheck()) {
            return $this->responseWithError(_trans('message.You cannot delete for demo'), [], 400);
        }
        return $this->leaveType->destroyAll($request);
    }

    // new functions for

    public function createModal()
    {
        try {
            $data['title']     = _trans('common.Create Leave Type');
            $data['url']       = route('leave.store');
            $data['attributes'] = $this->leaveType->createAttributes();
            @$data['button']   = _trans('common.Save');
            return view('backend.modal.create', compact('data'));
        } catch (\Throwable $th) {
            return response()->json('fail');
        }
    }

    public function store(LeaveTypeRequest $request)
    {

        try {
            if (!$request->ajax()) {
                Toastr::error(_trans('response.Please click on button!'), 'Error');
                return redirect()->back();
            }
            if (demoCheck()) {
                return $this->responseWithError(_trans('message.You cannot do it for demo'), [], 400);
            }
            return $this->leaveType->newStore($request);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }

    public function editModal($id)
    {
        try {
            $leaveModel = $this->model->find($id);
            $data['title'] = _trans('common.Edit Leave Type');
            $data['url']          = route('leave.update', $leaveModel->id);
            $data['attributes'] = $this->leaveType->editAttributes($leaveModel);
            @$data['button']   = _trans('common.Update');
            return view('backend.modal.create', compact('data'));
        } catch (\Throwable $th) {
            return response()->json('fail');
        }
    }

    public function update(LeaveTypeRequest $request, $id)
    {
        try {
            if (!$request->ajax()) {
                Toastr::error(_trans('response.Please click on button!'), 'Error');
                return redirect()->back();
            }
            if (demoCheck()) {
                return $this->responseWithError(_trans('message.You cannot do it for demo'), [], 400);
            }
            return $this->leaveType->newUpdate($request, $id);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }
}
