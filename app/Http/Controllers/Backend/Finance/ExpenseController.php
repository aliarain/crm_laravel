<?php

namespace App\Http\Controllers\Backend\Finance;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Repositories\Company\CompanyRepository;
use App\Http\Requests\Finance\CreateExpenseRequest;
use App\Http\Requests\Finance\UpdateExpenseRequest;
use App\Repositories\Hrm\Finance\AccountRepository;
use App\Repositories\Hrm\Finance\ExpenseRepository;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Models\coreApp\Relationship\RelationshipTrait;
use App\Repositories\Hrm\Finance\TransactionRepository;
use App\Repositories\Hrm\Expense\ExpenseCategoryRepository;

class ExpenseController extends Controller
{
    use ApiReturnFormatTrait, RelationshipTrait;

    protected $expenseRepository;
    protected $accountRepository;
    protected $companyRepository;
    protected $transactionRepository;
    protected $incomeExpenseCategoryRepository;

    public function __construct(
        AccountRepository $accountRepository,
        TransactionRepository $transactionRepository,
        ExpenseCategoryRepository $incomeExpenseCategoryRepository,
        ExpenseRepository $expenseRepository,
        CompanyRepository $companyRepository
    ) {
        $this->accountRepository = $accountRepository;
        $this->expenseRepository = $expenseRepository;
        $this->companyRepository = $companyRepository;
        $this->transactionRepository = $transactionRepository;
        $this->incomeExpenseCategoryRepository = $incomeExpenseCategoryRepository;
    }

    function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->expenseRepository->table($request);
        }
        $data['checkbox'] = true;
        $data['fields'] = $this->expenseRepository->fields();
        $data['class'] = 'expense_datatable';

        $data['title'] = _trans('account.Expense List');
        $data['create'] = (hasPermission('expense_create')) ? route('hrm.expenses.create') : '';
        $data['category'] = $this->incomeExpenseCategoryRepository->model(
            [
                'status_id' => 1,
                'company_id' => $this->companyRepository->company()->id
            ]
        )->get();
        return view('backend.finance.expense.index', compact('data'));
    }

    function datatable(Request $request)
    {
        return $this->expenseRepository->datatable($request);
    }

    public function create()
    {
        $data['title']         = _trans('account.Create Expense');
        $data['url']           = (hasPermission('expense_store')) ? route('hrm.expenses.store') : '';
        $data['category'] = $this->incomeExpenseCategoryRepository->model(
            [
                'is_income' => 0,
                'status_id' => 1,
                'company_id' => $this->companyRepository->company()->id
            ]
        )->get();
        $data['list_url']      = (hasPermission('expense_list')) ? route('hrm.expenses.index') : '';

        return view('backend.finance.expense.create', compact('data'));
    }
    public function UserExpenseList(Request $request)
    {
        try {
            return $this->expenseRepository->UserExpenseList($request);
        } catch (\Throwable $th) {
            return $this->responseWithError(_trans('response.Something went wrong'), '', 500);
        }
    }
    public function UserExpenseView(Request $request, $expense_id)
    {
        $request['expense_id'] = $expense_id;

        $validator = Validator::make($request->all(), [
            'expense_id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->responseWithError(_trans('validation.Validation field required'), $validator->errors(), 422);
        }
        try {
            return $this->expenseRepository->UserExpenseView($request, $expense_id);
        } catch (\Throwable $th) {
            return $this->responseWithError(_trans('response.Something went wrong'), '', 500);
        }
    }

    public function store(CreateExpenseRequest $request)
    {
        try {
            $result = $this->expenseRepository->store($request);
            if ($result->original['result']) {
                Toastr::success($result->original['message'], 'Success');
                return redirect()->route('hrm.expenses.index');
            } else {
                Toastr::error($result->original['message'], 'Error');
                return redirect()->back();
            }
        } catch (\Throwable $th) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }
    public function CategoryList()
    {
        try {
            $data['categories'] = $this->incomeExpenseCategoryRepository->model(
                [
                    'status_id' => 1,
                    'company_id' => $this->companyRepository->company()->id
                ]
            )->select('id', 'name')->get();
            return $this->responseWithSuccess('Expense Category List', $data, 200);
        } catch (\Throwable $th) {
            return $this->responseWithError(_trans('response.Something went wrong'), '', 500);
        }
    }
    public function UserExpenseStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category' => 'required:max:191',
            'date' => 'required|max:191',
            'amount' => 'required|max:191',
            'ref' => 'nullable|max:191',
            'description' => 'required|max:391',
            'attachment'  => 'required|mimes:jpeg,png,jpg,pdf,doc|max:2048',
        ]);

        if ($validator->fails()) {
            return $this->responseWithError(_trans('validation.Validation field required'), $validator->errors(), 422);
        }
        try {
            $result = $this->expenseRepository->store($request);
            if ($result->original['result']) {
                return $this->responseWithSuccess(_trans('response.User expense created successfully'), []);
            } else {
                return $this->responseWithError(_trans('response.Something went wrong'), $validator->errors(), 500);
            }
        } catch (\Throwable $th) {
            return $this->responseWithError(_trans('response.Something went wrong'), '', 500);
        }
    }

    public function show($id)
    {
        try {
            $data['title']        =  _trans('account.Details Expense');

            $data['show']  = $this->expenseRepository->model([
                'id' => $id,
                'company_id' => $this->companyRepository->company()->id,
            ])->first();
            $data['category'] = $this->incomeExpenseCategoryRepository->model(
                [
                    'is_income' => 0,
                    'status_id' => 1,
                    'company_id' => $this->companyRepository->company()->id
                ]
            )->get();
            $data['list_url']      = (hasPermission('expense_list')) ? route('hrm.expenses.index') : '';
            return view('backend.finance.expense.details', compact('data'));
        } catch (\Throwable $th) {
            Toastr::error(_trans('validation.Something went wrong!'), 'Error');
            return redirect()->back();
        }
    }
    public function edit($id)
    {
        try {
            $data['title']        =  _trans('account.Edit Expense');

            $data['edit']  = $this->expenseRepository->model([
                'id' => $id,
                'company_id' => $this->companyRepository->company()->id,
            ])->first();
            $data['url']          = (hasPermission('expense_update')) ? route('hrm.expenses.update', [$data['edit']->id, $data['edit']->company_id]) : '';
            $data['is_permission']  = (hasPermission('expense_update')) ? true : false;
            $data['category'] = $this->incomeExpenseCategoryRepository->model(
                [
                    'is_income' => 0,
                    'status_id' => 1,
                    'company_id' => $this->companyRepository->company()->id
                ]
            )->get();
            $data['list_url']      = (hasPermission('expense_list')) ? route('hrm.expenses.index') : '';
            return view('backend.finance.expense.create', compact('data'));
        } catch (\Throwable $th) {
            Toastr::error(_trans('validation.Something went wrong!'), 'Error');
            return redirect()->back();
        }
    }

    public function update(UpdateExpenseRequest $request, $id, $company_id)
    {

        try {
            $params                = [
                'id' => $id,
                'company_id' => $company_id,
            ];
            if (auth()->user()->role->slug == 'staff') {
                $params['user_id'] = auth()->user()->id;
            }
            $expense       = $this->expenseRepository->model($params)->first();
            if (!$expense) {
                Toastr::error('Expense not found!', 'Error');
                return redirect()->route('hrm.expenses.index');
            }

            $result = $this->expenseRepository->update($request, $expense);
            if ($result->original['result']) {
                Toastr::success($result->original['message'], 'Success');
                return redirect()->route('hrm.expenses.index');
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
            $params                = [
                'id' => $id,
                'company_id' => $this->companyRepository->company()->id,
            ];
            if (auth()->user()->role->slug == 'staff') {
                $params['user_id'] = auth()->user()->id;
            }
            $expense       = $this->expenseRepository->model($params)->first();
            if (!$expense) {
                Toastr::error('Expense not found!', 'Error');
                return redirect()->route('hrm.expenses.index');
            }

            $result = $this->expenseRepository->delete($expense);
            if ($result->original['result']) {
                Toastr::success($result->original['message'], 'Success');
                return redirect()->route('hrm.expenses.index');
            } else {
                Toastr::error($result->original['message'], 'Error');
                return redirect()->route('hrm.expenses.index');
            }
        } catch (\Throwable $th) {
            Toastr::error(_trans('validation.Something went wrong!'), 'Error');
            return redirect()->route('hrm.expenses.index');
        }
    }

    public function approveModal($id)
    {
        try {
            $data['title']         = _trans('payroll.Expense Approve');
            $params                = [
                'id' => $id,
                'company_id' => $this->companyRepository->company()->id,
            ];
            if (auth()->user()->role->slug == 'staff') {
                $params['user_id'] = auth()->user()->id;
            }
            $data['expense']       = $this->expenseRepository->model($params)->first();
            $data['url']           = (hasPermission('expense_approve')) ? route('hrm.expenses.approve', $id) : '';
            if (auth()->user()->role->slug == 'staff' && $data['expense']->status_id != 2) {
                $data['url'] = '';
            }
            $data['button']        = _trans('common.Approve');
            return view('backend.finance.expense.approve_modal', compact('data'));
        } catch (\Throwable $e) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }

    function approve(Request $request, $id)
    {
        if (!$request->amount) {
            Toastr::error('Please select approve amount!', 'Error');
            return redirect()->back();
        }
        if (!$request->status) {
            Toastr::error('Please select status!', 'Error');
            return redirect()->back();
        }
        try {
            $params                = [
                'id' => $id,
                'company_id' => $this->companyRepository->company()->id,
            ];
            if (auth()->user()->role->slug == 'staff') {
                $params['user_id'] = auth()->user()->id;
            }
            $expense       = $this->expenseRepository->model($params)->first();
            if (!$expense) {
                Toastr::error(_trans('message.Expense not found.'), 'Error');
                return redirect()->route('hrm.expenses.index');
            }
            $result = $this->expenseRepository->approve($request, $expense);
            if ($result->original['result']) {
                Toastr::success($result->original['message'], 'Success');
                return redirect()->route('hrm.expenses.index');
            } else {
                Toastr::error($result->original['message'], 'Error');
                return redirect()->back();
            }
        } catch (\Throwable $th) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }
    public function pay($id)
    {
        try {
            $data['title']         = _trans('payroll.Make Payment');
            $params                = [
                'id' => $id,
                'company_id' => $this->companyRepository->company()->id,
            ];
            if (auth()->user()->role->slug == 'staff') {
                $params['user_id'] = auth()->user()->id;
            }
            $data['expense']       = $this->expenseRepository->model($params)->first();
            $data['payment_method'] = DB::table('payment_methods')->where(
                [
                    'company_id' => $this->companyRepository->company()->id
                ]
            )->get();
            $data['url']           = (hasPermission('expense_pay')) ? route('hrm.expenses.pay_store', $id) : '';
            if (auth()->user()->role->slug == 'staff' && $data['expense']->status_id != 2) {
                $data['url'] = '';
            }
            $data['accounts']      = $this->accountRepository->model(
                [
                    'company_id' => $this->companyRepository->company()->id,
                    'status_id' => 1,
                ]
            )->get();
            $data['button']        =   _trans('common.Payment');
            return view('backend.finance.expense.payment_modal', compact('data'));
        } catch (\Throwable $e) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }

    function payStore(Request $request, $id)
    {
        if (!$request->payment_method) {
            Toastr::error('Please select payment method!', 'Error');
            return redirect()->back();
        }
        if (!$request->account) {
            Toastr::error('Please select account!', 'Error');
            return redirect()->back();
        }
        try {
            $params                = [
                'id' => $id,
                'company_id' => $this->companyRepository->company()->id,
            ];
            if (auth()->user()->role->slug == 'staff') {
                $params['user_id'] = auth()->user()->id;
            }
            $expense       = $this->expenseRepository->model($params)->first();
            if (!$expense) {
                Toastr::error('Expense not found!', 'Error');
                return redirect()->route('hrm.expenses.index');
            }
            $result = $this->expenseRepository->pay($request, $expense);
            if ($result->original['result']) {
                Toastr::success($result->original['message'], 'Success');
                return redirect()->route('hrm.expenses.index');
            } else {
                Toastr::error($result->original['message'], 'Error');
                return redirect()->back();
            }
        } catch (\Throwable $e) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }

    // destroy all selected data

    public function deleteData(Request $request)
    {
        if (demoCheck()) {
            return $this->responseWithError(_trans('message.You cannot delete for demo'), [], 400);
        }
        return $this->expenseRepository->destroyAll($request);
    }
}
