<?php

namespace App\Http\Controllers\Backend\Payroll;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Repositories\Company\CompanyRepository;
use App\Http\Requests\Payroll\AdvanceTypeRequest;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Repositories\Hrm\Payroll\AdvanceTypeRepository;

class AdvanceTypeController extends Controller
{
    use ApiReturnFormatTrait;
    protected $view_path = 'backend.payroll.advance_type';
    protected $advanceTypeRepository;
    protected $companyRepository;

    public function __construct(AdvanceTypeRepository $advanceTypeRepository, CompanyRepository $companyRepository)
    {
        $this->advanceTypeRepository = $advanceTypeRepository;
        $this->companyRepository = $companyRepository;
    }

    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                return $this->advanceTypeRepository->table($request);
            }
            $data['table']    = route('hrm.payroll_advance_type.index');
            $data['url_id']    = 'advance_type_table_url';
            $data['class']     = 'table_class';
            $data['delete_url'] =  route('payroll_advance_type.delete_data');
            $data['status_url'] =  route('payroll_advance_type.statusUpdate');
            $data['checkbox'] = true;
            $data['fields'] = $this->advanceTypeRepository->fields();

            $data['title']          =  _trans('payroll.Advance Type');
            return view('backend.payroll.advance_type.index', compact('data'));
        } catch (\Throwable $th) {
            Toastr::error(_trans('validation.Something went wrong!'), 'Error');
            return redirect()->back();
        }
    }

    public function create()
    {
        try {
            $data['title']     = _trans('common.Create Advance Type');
            $data['url']       = route('hrm.payroll_advance_type.store');
            $data['attributes'] = $this->advanceTypeRepository->createAttributes();
            @$data['button']   = _trans('common.Save');
            return view('backend.modal.create', compact('data'));
        } catch (\Throwable $th) {
            return response()->json('fail');
        }
    }

    public function store(AdvanceTypeRequest $request)
    {
        try {
            if (!$request->ajax()) {
                Toastr::error(_trans('response.Please click on button!'), 'Error');
                return redirect()->back();
            }
            if (demoCheck()) {
                return $this->responseWithError(_trans('message.You cannot do it for demo'), [], 400);
            }
            return $this->advanceTypeRepository->store($request);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }
    public function datatable()
    {
        try {
            return $this->advanceTypeRepository->datatable();
        } catch (\Throwable $th) {
            return $this->responseExceptionError($th->getMessage(), [], 400);
        }
    }

    public function edit($id)
    {
        try {
            $advance_typeModel = $this->advanceTypeRepository->model([
                'id' => $id,
                'company_id' => $this->companyRepository->company()->id,
            ]);
            $data['title'] = _trans('common.Edit Advance Type');
            $data['url']          = route('hrm.payroll_advance_type.update', [$advance_typeModel->id, $advance_typeModel->company_id]);
            $data['attributes'] = $this->advanceTypeRepository->editAttributes($advance_typeModel);
            @$data['button']   = _trans('common.Update');
            return view('backend.modal.create', compact('data'));
        } catch (\Throwable $th) {
            return response()->json('fail');
        }
    }
    public function update(AdvanceTypeRequest $request, $id, $company_id)
    {
        try {
            if (!$request->ajax()) {
                Toastr::error(_trans('response.Please click on button!'), 'Error');
                return redirect()->back();
            }
            if (demoCheck()) {
                return $this->responseWithError(_trans('message.You cannot do it for demo'), [], 400);
            }
            return $this->advanceTypeRepository->update($request, $id, $company_id);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }

    public function delete($id)
    {
        try {
            $result = $this->advanceTypeRepository->delete($id, $this->companyRepository->company()->id);
            if ($result->original['result']) {
                Toastr::success($result->original['message'], 'Success');
                return redirect()->route('hrm.payroll_advance_type.index');
            } else {
                Toastr::error($result->original['message'], 'Error');
                return redirect()->route('hrm.payroll_advance_type.index');
            }
        } catch (\Throwable $th) {
            Toastr::error(_trans('validation.Something went wrong!'), 'Error');
            return redirect()->route('hrm.payroll_advance_type.index');
        }
    }

    // status change
    public function statusUpdate(Request $request)
    {
        if (demoCheck()) {
            return $this->responseWithError(_trans('message.You cannot do it for demo'), [], 400);
        }
        return $this->advanceTypeRepository->statusUpdate($request);
    }

    // destroy all selected data

    public function deleteData(Request $request)
    {
        if (demoCheck()) {
            return $this->responseWithError(_trans('message.You cannot delete for demo'), [], 400);
        }
        return $this->advanceTypeRepository->destroyAll($request);
    }
}
