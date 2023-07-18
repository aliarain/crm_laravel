<?php

namespace App\Http\Controllers\Backend\Attendance;

use Illuminate\Http\Request;
use App\Models\Hrm\Shift\Shift;
use App\Http\Requests\ShiftReqeust;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\DesignationReqeust;
use App\Models\Hrm\Designation\Designation;
use App\Repositories\Hrm\Shift\ShiftRepository;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Models\coreApp\Relationship\RelationshipTrait;


class ShiftController extends Controller
{
    use RelationshipTrait, ApiReturnFormatTrait;

    protected ShiftRepository $shift;
    protected $model;

    public function __construct(ShiftRepository $shift, Shift $model)
    {
        $this->shift = $shift;
        $this->model = $model;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->shift->table($request);
        }
        $data['table']    = route('shift.index');
        $data['url_id']    = 'shift_table_url';
        $data['class']     = 'table_class';
        $data['delete_url'] =  route('shift.delete_data');
        $data['status_url'] =  route('shift.statusUpdate');
        $data['checkbox'] = true;
        $data['fields'] = $this->shift->fields();

        $data['title'] = _trans('attendance.Duty Shifts');
        return view('backend.shift.index', compact('data'));
    }


    public function create()
    {
        try {
            $data['title']     = _trans('attendance.Create Shift');
            $data['url']       = route('shift.store');
            $data['attributes'] = $this->shift->createAttributes();
            @$data['button']   = _trans('common.Save');
            return view('backend.modal.create', compact('data'));
        } catch (\Throwable $th) {
            return response()->json('fail');
        }
        $data['title'] = _trans('attendance.Add Shift');
        return view('backend.shift.create', compact('data'));
    }


    public function store(ShiftReqeust $request)
    {
        try {
            if (!$request->ajax()) {
                Toastr::error(_trans('response.Please click on button!'), 'Error');
                return redirect()->back();
            }
            if (demoCheck()) {
                return $this->responseWithError(_trans('message.You cannot do it for demo'), [], 400);
            }
            return $this->shift->newStore($request);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }


    public function dataTable(Request $request)
    {
        return  $this->shift->dataTable($request);
    }


    public function show(Shift $shift): Shift
    {
        return $shift;
    }

    public function edit(Shift $shift)
    {
        try {
            $data['title'] = _trans('common.Edit Shift');
            $data['url']          = route('shift.update', $shift->id);
            $data['attributes'] = $this->shift->editAttributes($shift);
            @$data['button']   = _trans('common.Update');
            return view('backend.modal.create', compact('data'));
        } catch (\Throwable $th) {
            return response()->json('fail');
        }
    }

    public function update(ShiftReqeust $request, $id)
    {
        try {
            if (!$request->ajax()) {
                Toastr::error(_trans('response.Please click on button!'), 'Error');
                return redirect()->back();
            }
            if (demoCheck()) {
                return $this->responseWithError(_trans('message.You cannot do it for demo'), [], 400);
            }
            return $this->shift->newUpdate($request, $id);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }

    public function delete(Shift $shift)
    {
        if (demoCheck()) {
            Toastr::error(_trans('message.You cannot do it for demo'), 'Error');
            return redirect()->back();
        }
        return $this->shift->destroy($shift);
    }

    // status change
    public function statusUpdate(Request $request)
    {
        if (demoCheck()) {
            return $this->responseWithError(_trans('message.You cannot do it for demo'), [], 400);
        }
        return $this->shift->statusUpdate($request);
    }

    // destroy all selected data

    public function deleteData(Request $request)
    {
        if (demoCheck()) {
            return $this->responseWithError(_trans('message.You cannot delete for demo'), [], 400);
        }
        return $this->shift->destroyAll($request);
    }
}
