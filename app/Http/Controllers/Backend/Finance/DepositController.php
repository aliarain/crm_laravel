<?php

namespace App\Http\Controllers\Backend\Finance;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Repositories\Company\CompanyRepository;
use App\Http\Requests\Finance\CreateDepositRequest;
use App\Http\Requests\Finance\UpdateDepositRequest;
use App\Repositories\Hrm\Finance\AccountRepository;
use App\Repositories\Hrm\Finance\DepositRepository;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Repositories\Hrm\Finance\TransactionRepository;
use App\Repositories\Hrm\Expense\ExpenseCategoryRepository;

class DepositController extends Controller
{
    use ApiReturnFormatTrait;

    protected $depositRepository;
    protected $accountRepository;
    protected $companyRepository;
    protected $transactionRepository;
    protected $incomeExpenseCategoryRepository;

    public function __construct(
        AccountRepository $accountRepository,
        TransactionRepository $transactionRepository,
        ExpenseCategoryRepository $incomeExpenseCategoryRepository,
        DepositRepository $depositRepository,
        CompanyRepository $companyRepository
    ) {
        $this->accountRepository = $accountRepository;
        $this->depositRepository = $depositRepository;
        $this->companyRepository = $companyRepository;
        $this->transactionRepository = $transactionRepository;
        $this->incomeExpenseCategoryRepository = $incomeExpenseCategoryRepository;
    }

    function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->depositRepository->table($request);
        }
        $data['checkbox'] = true;
        $data['fields'] = $this->depositRepository->fields();

        $data['title'] = _trans('common.Deposit List');
        $data['class'] = 'deposit_datatable';
        $data['create'] = (hasPermission('deposit_create')) ? route('hrm.deposits.create') : '';

        $data['accounts']      = $this->accountRepository->model(
            [
                'company_id' => auth()->user()->company_id,
            ]
        )->get();
        return view('backend.finance.deposit.index', compact('data'));
    }

    function datatable(Request $request)
    {
        return $this->depositRepository->datatable($request);
    }

    public function create()
    {
        $data['title']         = 'Create Deposit';
        $data['url']           = (hasPermission('deposit_store')) ? route('hrm.deposits.store') : '';
        $data['accounts']      = $this->accountRepository->model(
            [
                'company_id' => $this->companyRepository->company()->id,
                'status_id' => 1,
            ]
        )->get();
        $data['category'] = $this->incomeExpenseCategoryRepository->model(
            [
                'is_income' => 1,
                'status_id' => 1,
                'company_id' => $this->companyRepository->company()->id
            ]
        )->get();
        $data['payment_method'] = DB::table('payment_methods')->where(
            [
                'company_id' => $this->companyRepository->company()->id
            ]
        )->get();
        $data['list_url']      = (hasPermission('deposit_list')) ? route('hrm.deposits.index') : '';

        return view('backend.finance.transaction.create', compact('data'));
    }

    public function store(CreateDepositRequest $request)
    {

        try {
            $result = $this->depositRepository->store($request);
            if ($result->original['result']) {
                Toastr::success($result->original['message'], 'Success');
                return redirect()->route('hrm.deposits.index');
            } else {
                Toastr::error($result->original['message'], 'Error');
                return redirect()->back();
            }
        } catch (\Throwable $th) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        try {
            $data['edit']  = $this->depositRepository->model([
                'id' => $id,
                'company_id' => $this->companyRepository->company()->id,
            ])->first();
            $data['accounts']      = $this->accountRepository->model(
                [
                    'company_id' => $this->companyRepository->company()->id,
                    'status_id' => 1,
                ]
            )->get();
            $data['title']        =  _trans('payroll.Edit Deposit');
            $data['url']          = (hasPermission('deposit_update')) ? route('hrm.deposits.update', [$data['edit']->id, $data['edit']->company_id]) : '';
            $data['is_permission']  = (hasPermission('deposit_update')) ? true : false;
            $data['category'] = $this->incomeExpenseCategoryRepository->model(
                [
                    'is_income' => 1,
                    'status_id' => 1,
                    'company_id' => $this->companyRepository->company()->id
                ]
            )->get();
            $data['payment_method'] = DB::table('payment_methods')->where(
                [
                    'company_id' => $this->companyRepository->company()->id
                ]
            )->get();
            $data['list_url']      = (hasPermission('deposit_list')) ? route('hrm.deposits.index') : '';
            return view('backend.finance.transaction.create', compact('data'));
        } catch (\Throwable $th) {
            Toastr::error(_trans('validation.Something went wrong!'), 'Error');
            return redirect()->back();
        }
    }

    public function update(UpdateDepositRequest $request, $id, $company_id)
    {

        try {
            $result = $this->depositRepository->update($request, $id, $company_id);
            if ($result->original['result']) {
                Toastr::success($result->original['message'], 'Success');
                return redirect()->route('hrm.deposits.index');
            } else {
                Toastr::error($result->original['message'], 'Error');
                return redirect()->back();
            }
        } catch (\Throwable $th) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }

    public function delete($id)
    {
        try {
            $result = $this->depositRepository->delete($id, $this->companyRepository->company()->id);
            if ($result->original['result']) {
                Toastr::success($result->original['message'], 'Success');
                return redirect()->route('hrm.deposits.index');
            } else {
                Toastr::error($result->original['message'], 'Error');
                return redirect()->route('hrm.deposits.index');
            }
        } catch (\Throwable $th) {
            Toastr::error(_trans('validation.Something went wrong!'), 'Error');
            return redirect()->route('hrm.deposits.index');
        }
    }

    // destroy all selected data

    public function deleteData(Request $request)
    {
        if (demoCheck()) {
            return $this->responseWithError(_trans('message.You cannot delete for demo'), [], 400);
        }
        return $this->depositRepository->destroyAll($request);
    }
}
