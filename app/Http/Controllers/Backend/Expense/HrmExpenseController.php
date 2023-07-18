<?php

namespace App\Http\Controllers\Backend\Expense;

use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Http\Controllers\Controller;
use App\Models\Accounts\Category;
use App\Models\Expenses\IncomeExpenseCategory;
use App\Models\Expenses\ExpenseClaim;
use App\Models\Expenses\HrmExpense;
use App\Models\User;
use App\Repositories\Hrm\Expense\HrmExpenseRepository;
use App\Repositories\UserRepository;
use App\Services\Hrm\ClaimService;
use App\Services\Hrm\ExpenseService;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class HrmExpenseController extends Controller
{
    use ApiReturnFormatTrait;

    protected $expense;
    protected $service;
    protected $claimService;
    protected $user;

    public function __construct(HrmExpenseRepository $hrmExpenseRepository, ExpenseService $service, UserRepository $userRepository, ClaimService $claimService)
    {
        $this->user = $userRepository;
        $this->expense = $hrmExpenseRepository;
        $this->service = $service;
        $this->claimService = $claimService;
    }

    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                return $this->service->paymentTable($request);
            }
            $data['table']    = route('expense.index');
            $data['url_id']    = 'expense_payment_table_url';
            $data['class']     = 'table_class';

            $data['fields'] = $this->expense->paymentFields();

            $data['title'] = _trans('common.Payment Report');
            $data['purposes'] = $this->expense->expenseCategory();
            $data['employees'] = $this->user->getAll();
            return view('backend.expense.index', compact('data'));
        } catch (\Throwable $th) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }

    public function claimIndex()
    {
        $data['title'] = _trans('common.Claim list');
        $data['purposes'] = $this->expense->expenseCategory();
        $data['employees'] = $this->user->getAll();
        $data['payment_methods'] = $this->claimService->paymentMethods();
        return view('backend.expense.claim.index', compact('data'));
    }


 

    public function expenseList()
    {
        return $this->expense->expenseList();
    }

    public function expenseDatatable()
    {
        return $this->service->dataTable();
    }

    public function store(Request $request)
    {
        if (demoCheck()) {
            Toastr::error(_trans('message.You cannot do it for demo'), 'Error');
            return redirect()->back();
        }
        return $this->expense->store($request);
    }
    public function UserExpenseStore(Request $request)
    {
        if (demoCheck()) {
            Toastr::error(_trans('message.You cannot do it for demo'), 'Error');
            return redirect()->back();
        }
        return $this->expense->UserExpenseStore($request);
    }

    public function expenseUpdate(Request $request, $expenseId)
    {
        return $this->expense->expenseUpdate($request, $expenseId);
    }

    public function delete($expenseId)
    {
        if (demoCheck()) {
            Toastr::error(_trans('message.You cannot do it for demo'), 'Error');
            return redirect()->back();
        }
        try {
            return $this->expense->destroy($expenseId);
        } catch (\Exception $exception) {
            return $this->failResponse($exception->getMessage());
        }
    }

    public function show($id): \Illuminate\Http\JsonResponse
    {
        return $this->expense->show($id);
    }

    public function showExpenseClaim(ExpenseClaim $expenseClaim)
    {
        return $expenseClaim;
    }

    public function claimAmountPay(ExpenseClaim $expenseClaim)
    {
        $claimPayment = $this->claimService->approveClaimPayment($expenseClaim);
        if ($claimPayment) {
            Toastr::success(_trans('response.Operation successful'), 'Success');
            return redirect()->back();
        } else {
            Toastr::error('Payment is not successful', 'Error');
            return redirect()->back();
        }
    }


    public function approveOrReject(HrmExpense $expense, $status): \Illuminate\Http\RedirectResponse
    {
        if (demoCheck()) {
            Toastr::error(_trans('message.You cannot do it for demo'), 'Error');
            return redirect()->back();
        }

        $updateStatus = $this->service->approveOrReject($expense, $status);
        if ($updateStatus) {
            Toastr::success(_trans('response.Operation successful'), 'Success');
            return redirect()->back();
        } else {
            Toastr::error('Operation did not successful', 'Error');
            return redirect()->back();
        }
    }

    public function claimSend(Request $request)
    {
        return $this->expense->claimSend($request);
    }

    public function claimHistory(Request $request)
    {
        return $this->expense->claimHistory($request);
    }

    public function claimDatatable(Request $request)
    {
        return $this->claimService->dataTable($request);
    }


    public function claimDetails($id)
    {
        return $this->expense->claimDetails($id);
    }

    public function paymentHistory(Request $request)
    {
        return $this->expense->paymentHistory($request);
    }
}
