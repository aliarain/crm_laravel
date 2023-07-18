<?php

namespace App\Http\Controllers\Backend\Finance;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Repositories\Company\CompanyRepository;
use App\Http\Requests\Payroll\AdvanceTypeRequest;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Repositories\Hrm\Finance\DepositCategoryRepository;

class CategoryController extends Controller
{

    use ApiReturnFormatTrait;

    protected $DepositCategoryRepository;
    protected $companyRepository;

    public function __construct(
        DepositCategoryRepository $DepositCategoryRepository,
        CompanyRepository $companyRepository
    ) {
        $this->DepositCategoryRepository = $DepositCategoryRepository;
        $this->companyRepository = $companyRepository;
    }

    public function expense(Request $request)
    {
        $data['create']     = route('hrm.deposit_category.create', 'create=expense');
        $data['title']      = _trans('account.Expense Categories');
        $data['fields']     = $this->DepositCategoryRepository->fields();


        if ($request->ajax()) {
            return $this->DepositCategoryRepository->deposit_table($request);
        }
        $data['checkbox'] = true;
        $data['class'] = 'deposit_cat_datatable';

        $data['is_income']  = 0;
        $data['status_url'] = route('hrm.deposit_category.statusUpdate');
        $data['delete_url'] = route('hrm.deposit_category.deleteData');


        return view('backend.finance.category.index', compact('data'));
    }
    public function deposit(Request $request)
    {
        if ($request->ajax()) {
            return $this->DepositCategoryRepository->deposit_table($request);
        }
        $data['checkbox'] = true;
        $data['class'] = 'deposit_cat_datatable';

        $data['create']     = route('hrm.deposit_category.create', 'create=deposit');
        $data['title']      = _trans('account.Deposit Categories');
        $data['fields']     = $this->DepositCategoryRepository->fields();
        $data['is_income']  = 1;
        $data['status_url'] = route('hrm.deposit_category.statusUpdate');
        $data['delete_url'] = route('hrm.deposit_category.deleteData');


        return view('backend.finance.category.index', compact('data'));
    }

    public function datatable($type)
    {
        if ($type == 'deposit') {
            return $this->DepositCategoryRepository->datatable('deposit');
        } elseif ($type == 'expense') {
            return $this->DepositCategoryRepository->datatable('expense');
        }
    }

    public function create(Request $request)
    {
        try {
            if (@$request->create == 'deposit') {
                $data['title'] = _trans('account.Add Deposit Category');
                $data['url'] = route('hrm.deposit_category.store');
                $data['attributes'] = $this->DepositCategoryRepository->createAttributes(1);
            } else {
                $data['title'] = _trans('account.Add Expense Category');
                $data['url'] = route('hrm.deposit_category.store');
                $data['attributes'] = $this->DepositCategoryRepository->createAttributes('0');
            }
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
            return $this->DepositCategoryRepository->store($request);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }

    public function edit(Request $request, $id)
    {
        try {
            $categoryModel = $this->DepositCategoryRepository->model([
                'id' => $id,
                'company_id' => $this->companyRepository->company()->id,
            ])->first();
            if (@$request->type == 1) {
                $data['title'] = _trans('account.Edit Deposit Category');
                $data['url'] = route('hrm.deposit_category.update', $id);
                $data['attributes'] = $this->DepositCategoryRepository->editAttributes($categoryModel,1);
            } else {
                $data['title'] = _trans('account.Edit Expense Category');
                $data['url'] = route('hrm.deposit_category.update', $id);
                $data['attributes'] = $this->DepositCategoryRepository->editAttributes($categoryModel,0);
            }
            @$data['button']   = _trans('common.Update');
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
            return $this->DepositCategoryRepository->update($request, $id, $this->companyRepository->company()->id);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }

    public function delete(Request $request, $id)
    {
        try {
            $result = $this->DepositCategoryRepository->delete($id, $this->companyRepository->company()->id);
            if ($result->original['result']) {
                Toastr::success($result->original['message'], 'Success');
                if (@$request->type == 1) {
                    $list_url = 'hrm.deposit_category.deposit';
                } else {
                    $list_url = 'hrm.deposit_category.expense';
                }
                return redirect()->route($list_url);
            } else {
                Toastr::error($result->original['message'], 'Error');
                return redirect()->route('hrm.accounts.index');
            }
        } catch (\Throwable $th) {
            Toastr::error(_trans('validation.Something went wrong!'), 'Error');
            if (@$request->type == 1) {
                $list_url = 'hrm.deposit_category.deposit';
            } else {
                $list_url = 'hrm.deposit_category.expense';
            }
            return redirect()->route($list_url);
        }
    }


    // status change
    public function statusUpdate(Request $request)
    {
        if (demoCheck()) {
            return $this->responseWithError(_trans('message.You cannot do it for demo'), [], 400);
        }
        return $this->DepositCategoryRepository->statusUpdate($request);
    }

    // destroy all selected data

    public function deleteData(Request $request)
    {
        if (demoCheck()) {
            return $this->responseWithError(_trans('message.You cannot delete for demo'), [], 400);
        }
        return $this->DepositCategoryRepository->destroyAll($request);
    }
}
