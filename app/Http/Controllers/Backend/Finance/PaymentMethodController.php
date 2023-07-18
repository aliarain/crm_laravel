<?php

namespace App\Http\Controllers\Backend\Finance;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Repositories\Company\CompanyRepository;
use App\Http\Requests\Payroll\AdvanceTypeRequest;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Repositories\Hrm\Finance\PaymentMethodsRepository;

class PaymentMethodController extends Controller
{
    use ApiReturnFormatTrait;
    protected $PaymentMethodsRepository;
    protected $companyRepository;

    public function __construct(
        PaymentMethodsRepository $PaymentMethodsRepository,
        CompanyRepository $companyRepository
    ) {
        $this->PaymentMethodsRepository = $PaymentMethodsRepository;
        $this->companyRepository = $companyRepository;
    }

    public function index(Request $request)
    {
        $data['create']     = route('hrm.payment_method.create');
        $data['title']      = _trans('account.Payment Methods');
        $data['fields']     = $this->PaymentMethodsRepository->fields();
        if ($request->ajax()) {
            return $this->PaymentMethodsRepository->table($request);
        }
        $data['checkbox'] = true;
        $data['class'] = 'payment_methods_datatable';

        $data['status_url'] = route('hrm.payment_method.statusUpdate');
        $data['delete_url'] = route('hrm.payment_method.deleteData');

        return view('backend.finance.payment_methods.index', compact('data'));
    }

    public function datatable()
    {
        return $this->PaymentMethodsRepository->datatable();
    }

    public function create()
    {
        try {
            $data['title']     = _trans('common.Create Payment Method');
            $data['url']       = route('hrm.payment_method.store');
            $data['attributes'] = $this->PaymentMethodsRepository->createAttributes();
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
            return $this->PaymentMethodsRepository->store($request);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }


    public function edit($id)
    {
        try {
            $payment_methodModel = $this->PaymentMethodsRepository->edit($id);
            $data['title']     = _trans('common.Edit Payment Method');
            $data['url'] = route('hrm.payment_method.update', $id);
            $data['attributes'] = $this->PaymentMethodsRepository->editAttributes($payment_methodModel);
            @$data['button']   = _trans('common.Save');
            return view('backend.modal.create', compact('data'));
        } catch (\Throwable $th) {
            return response()->json('fail');
        }
    }


    public function update(AdvanceTypeRequest $request, $id)
    {
        try {
            if (!$request->ajax()) {
                Toastr::error(_trans('response.Please click on button!'), 'Error');
                return redirect()->back();
            }
            if (demoCheck()) {
                return $this->responseWithError(_trans('message.You cannot do it for demo'), [], 400);
            }
            return $this->PaymentMethodsRepository->update($request, $id);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }

    public function delete($id)
    {
        try {
            $result = $this->PaymentMethodsRepository->destroy($id, $this->companyRepository->company()->id);
            if ($result->original['result']) {
                Toastr::success($result->original['message'], 'Success');
                return redirect()->route('hrm.payment_method.index');
            } else {
                Toastr::error($result->original['message'], 'Error');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }


    // status change
    public function statusUpdate(Request $request)
    {
        if (demoCheck()) {
            return $this->responseWithError(_trans('message.You cannot do it for demo'), [], 400);
        }
        return $this->PaymentMethodsRepository->statusUpdate($request);
    }

    // destroy all selected data

    public function deleteData(Request $request)
    {
        if (demoCheck()) {
            return $this->responseWithError(_trans('message.You cannot delete for demo'), [], 400);
        }
        return $this->PaymentMethodsRepository->destroyAll($request);
    }
}
