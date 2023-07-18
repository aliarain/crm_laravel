<?php

namespace App\Http\Controllers\Backend\Payroll;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Http\Requests\CommissionReqeust;
use App\Repositories\Company\CompanyRepository;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Repositories\Hrm\Payroll\CommissionRepository;

class CommissionController extends Controller
{
    use ApiReturnFormatTrait;
    protected $view_path = 'backend.payroll.commission';
    protected $commissionRepository;
    protected $companyRepository;

    public function __construct(CommissionRepository $commissionRepository, CompanyRepository $companyRepository)
    {
        $this->commissionRepository = $commissionRepository;
        $this->companyRepository = $companyRepository;
    }

    public function index(Request $request)
    {
        try {

            $data['title']          =  _trans('payroll.Commissions');

            if ($request->ajax()) {
                return $this->commissionRepository->table($request);
            }
            $data['fields'] = $this->commissionRepository->fields();
            $data['checkbox'] = true;
            $data['delete_url']    = route('payroll_items.delete_data');
            $data['status_url']    = route('payroll_items.statusUpdate');

            $data['table']     = route('hrm.payroll_items.index');
            $data['url_id']    = 'payroll_item_table_url';
            $data['class']     = 'table_class';

            return view('backend.payroll.commission.index', compact('data'));
            return view($this->view_path . '.index', compact('data'));
        } catch (\Throwable $th) {
            Toastr::error(_trans('validation.Something went wrong!'), 'Error');
            return redirect()->back();
        }
    }
    public function datatable()
    {
        try {
            return $this->commissionRepository->datatable();
        } catch (\Throwable $th) {
            return $this->responseExceptionError($th->getMessage(), [], 400);
        }
    }



    public function delete($id)
    {
        try {
            $result = $this->commissionRepository->delete($id, $this->companyRepository->company()->id);
            if ($result->original['result']) {
                Toastr::success($result->original['message'], 'Success');
                return redirect()->route('hrm.payroll_items.index');
            } else {
                Toastr::error($result->original['message'], 'Error');
                return redirect()->route('hrm.payroll_items.index');
            }
        } catch (\Throwable $th) {
            Toastr::error(_trans('validation.Something went wrong!'), 'Error');
            return redirect()->route('hrm.payroll_items.index');
        }
    }


    // new functions

    public function create()
    {
        try {
            $data['title']     = _trans('common.Create Commission');
            $data['url']       = route('hrm.payroll_items.store');
            $data['attributes'] = $this->commissionRepository->createAttributes();
            @$data['button']   = _trans('common.Save');
            return view('backend.modal.create', compact('data'));
        } catch (\Throwable $th) {
            return response()->json('fail');
        }
    }

    public function store(CommissionReqeust $request)
    {
        try {
            if (!$request->ajax()) {
                Toastr::error(_trans('response.Please click on button!'), 'Error');
                return redirect()->back();
            }
            if (demoCheck()) {
                return $this->responseWithError(_trans('message.You cannot do it for demo'), [], 400);
            }
            return $this->commissionRepository->store($request);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }

    public function edit($id)
    {
        try {
            $commissionModel = $this->commissionRepository->model([
                'id' => $id,
                'company_id' => $this->companyRepository->company()->id,
            ]);
            $data['title'] = _trans('common.Edit Commission');
            $data['url']          = route('hrm.payroll_items.update', [$commissionModel->id, $this->companyRepository->company()->id]);
            $data['attributes'] = $this->commissionRepository->editAttributes($commissionModel);
            @$data['button']   = _trans('common.Update');
            return view('backend.modal.create', compact('data'));
        } catch (\Throwable $th) {
            return response()->json('fail');
        }
    }

    public function update(CommissionReqeust $request, $id, $company_id)
    {
        try {
            if (!$request->ajax()) {
                Toastr::error(_trans('response.Please click on button!'), 'Error');
                return redirect()->back();
            }
            if (demoCheck()) {
                return $this->responseWithError(_trans('message.You cannot do it for demo'), [], 400);
            }
            return $this->commissionRepository->update($request, $id, $company_id);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }

    // status change
    public function statusUpdate(Request $request)
    {
        if (demoCheck()) {
            return $this->responseWithError(_trans('message.You cannot do it for demo'), [], 400);
        }
        return $this->commissionRepository->statusUpdate($request);
    }

    // destroy all selected data

    public function deleteData(Request $request)
    {
        if (demoCheck()) {
            return $this->responseWithError(_trans('message.You cannot delete for demo'), [], 400);
        }
        return $this->commissionRepository->destroyAll($request);
    }


}
