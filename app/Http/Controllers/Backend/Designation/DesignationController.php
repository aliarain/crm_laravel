<?php

namespace App\Http\Controllers\Backend\Designation;

use Illuminate\Http\Request;
use App\Models\Company\Company;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\DesignationReqeust;
use App\Models\Hrm\Designation\Designation;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Models\coreApp\Relationship\RelationshipTrait;
use App\Repositories\Hrm\Designation\DesignationRepository;

class DesignationController extends Controller
{

    use RelationshipTrait,
        ApiReturnFormatTrait;

    protected DesignationRepository $designation;
    protected $model;

    public function __construct(DesignationRepository $designation, Designation $model)
    {
        $this->designation = $designation;
        $this->model = $model;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->designation->table($request);
        }
        $data['class'] = 'designation_table';
        $data['fields'] = $this->designation->fields();
        $data['checkbox'] = true;
        $data['title'] = _trans('common.Designations');
        return view('backend.designation.index', compact('data'));
    }

    public function create()
    {
        $data['title'] = _trans('common.Add Designation');
        return view('backend.designation.create', compact('data'));
    }

    public function dataTable(Request $request)
    {
        return $this->designation->dataTable($request);
    }

    public function show(Designation $designation): Designation
    {
        return $designation;
    }

    public function edit(Designation $designation)
    {
        $data['title'] = _trans('common.Edit designation');
        $data['designation'] = $designation;
        return view('backend.designation.edit', compact('data'));
    }

    public function delete(Designation $designation)
    {
        if (demoCheck()) {
            Toastr::error(_trans('message.You cannot do it for demo'), 'Error');
            return redirect()->back();
        }
        return $this->designation->destroy($designation);
    }

    // status change
    public function statusUpdate(Request $request)
    {
        if (demoCheck()) {
            return $this->responseWithError(_trans('message.You cannot do it for demo'), [], 400);
        }
        return $this->designation->statusUpdate($request);
    }

    // destroy all selected data

    public function deleteData(Request $request)
    {
        if (demoCheck()) {
            return $this->responseWithError(_trans('message.You cannot delete for demo'), [], 400);
        }
        return $this->designation->destroyAll($request);
    }

    // new functions for

    public function createModal()
    {
        try {
            $data['title'] = _trans('common.Create Designation');
            $data['url'] = route('designation.store');
            $data['attributes'] = $this->designation->createAttributes();
            @$data['button'] = _trans('common.Save');
            return view('backend.modal.create', compact('data'));
        } catch (\Throwable $th) {
            return response()->json('fail');
        }
    }

    public function store(DesignationReqeust $request)
    {


        try {
            if (!$request->ajax()) {
                Toastr::error(_trans('response.Please click on button!'), 'Error');
                return redirect()->back();
            }
            if (demoCheck()) {
                return $this->responseWithError(_trans('message.You cannot do it for demo'), [], 400);
            }
            return $this->designation->newStore($request);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }

    public function editModal($id)
    {
        try {
            $designationModel = $this->model->find($id);
            $data['title'] = _trans('common.Edit designation');
            $data['designation'] = $designationModel;
            $data['url'] = route('designation.update', $designationModel->id);
            $data['attributes'] = $this->designation->editAttributes($designationModel);
            @$data['button'] = _trans('common.Update');
            return view('backend.modal.create', compact('data'));
        } catch (\Throwable $th) {
            return response()->json('fail');
        }
    }

    public function update(DesignationReqeust $request, $id)
    {
        try {
            if (!$request->ajax()) {
                Toastr::error(_trans('response.Please click on button!'), 'Error');
                return redirect()->back();
            }
            if (demoCheck()) {
                return $this->responseWithError(_trans('message.You cannot do it for demo'), [], 400);
            }
            return $this->designation->newUpdate($request, $id);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }
}
