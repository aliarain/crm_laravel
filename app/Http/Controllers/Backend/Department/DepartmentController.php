<?php

namespace App\Http\Controllers\Backend\Department;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Http\Requests\DepartmentReqeust;
use App\Models\Hrm\Department\Department;
use App\Helpers\CoreApp\Traits\AuthorInfoTrait;
use App\Models\coreApp\Relationship\RelationshipTrait;
use App\Repositories\Hrm\Department\DepartmentRepository;

class DepartmentController extends Controller
{

    use AuthorInfoTrait, RelationshipTrait;

    protected DepartmentRepository $department;
    protected $model;

    public function __construct(DepartmentRepository $department, Department $model)
    {
        $this->department = $department;
        $this->model = $model;
    }


    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->department->table($request);
        }
        $data['class']  = 'department_table';
        $data['fields'] = $this->department->fields();
        $data['title'] = _trans('common.Departments');
        $data['checkbox'] = true;
        return view('backend.department.index', compact('data'));
    }


    public function create()
    {
        $data['title'] = _trans('common.Add Department');
        return view('backend.department.create', compact('data'));
    }



    public function dataTable(Request $request)
    {
        return $this->department->dataTable($request);
    }

    public function show(Department $department): Department
    {
        return $department;
    }

    public function edit(Department $department)
    {
        $data['title'] = _trans('common.Edit department');
        $data['department'] = $department;
        return view('backend.department.edit', compact('data'));
    }



    public function delete(Department $department): \Illuminate\Http\RedirectResponse
    {
        
        if (demoCheck()) {
            Toastr::error(_trans('message.You cannot do it for demo'), 'Error');
            return redirect()->back();
        }
        return  $this->department->destroy($department);
    }

    // status change
    public function statusUpdate(Request $request)
    {
        if (demoCheck()) {
            return $this->responseWithError(_trans('message.You cannot do it for demo'), [], 400);
        }
        return $this->department->statusUpdate($request);
    }

    // destroy all selected data

    public function deleteData(Request $request)
    {
        if (demoCheck()) {
            return $this->responseWithError(_trans('message.You cannot delete for demo'), [], 400);
        }
        return $this->department->destroyAll($request);
    }

    // new functions for

    public function createModal()
    {
        try {
            $data['title']     = _trans('common.Create Department');
            $data['url']       = route('department.store');
            $data['attributes'] = $this->department->createAttributes();
            @$data['button']   = _trans('common.Save');
            return view('backend.modal.create', compact('data'));
        } catch (\Throwable $th) {
            return response()->json('fail');
        }
    }

    public function store(DepartmentReqeust $request)
    {

        try {
            if (!$request->ajax()) {
                Toastr::error(_trans('response.Please click on button!'), 'Error');
                return redirect()->back();
            }
            if (demoCheck()) {
                return $this->responseWithError(_trans('message.You cannot do it for demo'), [], 400);
            }
            return $this->department->newStore($request);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }

    public function editModal($id)
    {
        try {
            $departmentModel = $this->model->find($id);
            $data['title'] = _trans('common.Edit department');
            $data['department'] = $departmentModel;
            $data['url']          = route('department.update', $departmentModel->id);
            $data['attributes'] = $this->department->editAttributes($departmentModel);
            @$data['button']   = _trans('common.Update');
            return view('backend.modal.create', compact('data'));
        } catch (\Throwable $th) {
            return response()->json('fail');
        }
    }

    public function update(DepartmentReqeust $request, $id)
    {
        try {
            if (!$request->ajax()) {
                Toastr::error(_trans('response.Please click on button!'), 'Error');
                return redirect()->back();
            }
            if (demoCheck()) {
                return $this->responseWithError(_trans('message.You cannot do it for demo'), [], 400);
            }
            return $this->department->newUpdate($request, $id);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }
}
