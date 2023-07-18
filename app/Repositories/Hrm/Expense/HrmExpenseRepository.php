<?php

namespace App\Repositories\Hrm\Expense;

use Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Expenses\HrmExpense;
use App\Services\Hrm\ExpenseService;
use App\Models\Expenses\ExpenseClaim;
use App\Models\Expenses\PaymentHistory;
use App\Models\Expenses\ExpenseCategory;
use App\Helpers\CoreApp\Traits\DateHandler;
use App\Helpers\CoreApp\Traits\FileHandler;
use App\Models\Expenses\ExpenseClaimDetails;
use App\Helpers\CoreApp\Traits\CurrencyTrait;
use App\Http\Resources\Hrm\ExpenseCollection;
use App\Http\Resources\Hrm\PaymentCollection;
use App\Models\Expenses\IncomeExpenseCategory;
use App\Http\Resources\Hrm\ClaimHistoryCollection;
use App\Http\Resources\Hrm\ExpenseClaimCollection;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Models\coreApp\Relationship\RelationshipTrait;

class HrmExpenseRepository
{
    use RelationshipTrait, ApiReturnFormatTrait, FileHandler, DateHandler, CurrencyTrait;

    protected $expenseClaim;

    public function __construct(ExpenseClaim $expenseClaim, ExpenseService $service)
    {
        $this->expenseClaim = $expenseClaim;
        $this->service = $service;
    }

    public function expenseCategory()
    {
        return IncomeExpenseCategory::where('company_id', auth()->user()->company_id)->where('is_income', 0)->get();
    }

    public function expenseList(): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make(\request()->all(), [
            'month' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->responseWithError(_trans('validation.Validation field required'), $validator->errors(), 422);
        }

        $month = $this->onlyMonth(\request()->get('month'));
        $expenses = $this->service->model->where(['company_id' => $this->companyInformation()->id, 'is_claimed_status_id' => 11, 'user_id' => auth()->id()])->whereMonth('created_at', $month)->paginate(15);
        $data['total_amount'] = $this->getCurrency() . "{$expenses->sum('amount')}";
        $data['expense_list'] = new ExpenseCollection($expenses);
        return $this->responseWithSuccess('Expense list with total amount', $data, 200);
    }

    public function show($id): \Illuminate\Http\JsonResponse
    {
        $expense = $this->service->model->query()->find($id);
        if ($expense) {
            $data['month'] = $this->onlyMonthString($expense->date);
            $data['date'] = $this->dateFormatInPlainText($expense->date);
            $data['category'] = @$expense->expenseCategory->name;
            $data['amount'] = $this->getCurrency() . $expense->amount;
            $data['remarks'] = $expense->remarks;
            $data['attachment_file'] = uploaded_asset($expense->attachment_file_id);
            return $this->responseWithSuccess('Expense details', $data, 200);
        } else {
            return $this->responseWithError('No data found', [], 400);
        }
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $expense = $this->service->query()->find($id);
        if ($expense) {
            $expense->delete();
            return $this->responseWithSuccess('Expense deleted successfully', [], 200);
        } else {
            return $this->responseWithError('No data found', [], 400);
        }
    }

    public function store($request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'expense_category_id' => 'required',
            'attachment_file' => 'sometimes|max:2048',
            'amount' => 'required|numeric',
            'remarks' => 'sometimes|max:255',
        ]);

        if ($validator->fails()) {
            return $this->responseWithError(_trans('validation.Validation field required'), $validator->errors(), 422);
        }

        try {
            $request['company_id'] = $this->companyInformation()->id;
            $request['user_id'] = auth()->id();
            $request['status_id'] = 1;
            $request['date'] = date('Y-m-d');
            $request['is_claimed_status_id'] = 11;
            $request['claimed_approved_status_id'] = 2;
            if ($request->hasFile('attachment_file')) {
                $request['attachment_file_id'] = $this->uploadImage($request->attachment_file, 'expense')->id;
            }
            $this->service->model->query()->create($request->all());
            return $this->responseWithSuccess('Expense stored successfully', [], 200);
        } catch (\Throwable $throwable) {
            return $this->responseWithError('Server error', [], 500);
        }
    }

    public function expenseUpdate($request, $id): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'expense_category_id' => 'required',
            'attachment_file' => 'sometimes|max:2048',
            'amount' => 'required|numeric',
            'remarks' => 'sometimes|max:255',
        ]);

        if ($validator->fails()) {
            return $this->responseWithError(_trans('validation.Validation field required'), $validator->errors(), 422);
        }

        try {
            $data = $this->service->model->query()->find($id);
            if ($data) {
                if ($data->is_claimed_status_id == 11) {

                    $data->income_expense_category_id = $request->expense_category_id;
                    $data->remarks = $request->remarks;
                    $data->amount = $request->amount;
                    if ($request->hasFile('attachment_file')) {
                        $request['attachment_file_id'] = $this->uploadImage($request->attachment_file, 'expense')->id;
                        $data->attachment_file_id = $request->attachment_file_id;
                    }
                    $data->save();
                    return $this->responseWithSuccess('Expense updated successfully', [], 200);
                } else {
                    return $this->responseWithError('Already claimed this expense', [], 400);
                }
            } else {
                return $this->responseWithError('No data found', [], 400);
            }
        } catch (\Throwable $throwable) {
            return $this->responseWithError('Server error', [], 500);
        }
    }

    public function claimSend($request)
    {
        $validator = Validator::make($request->all(), [
            'remarks' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return $this->responseWithError(_trans('validation.Validation field required'), $validator->errors(), 422);
        }
        //is_claimed_status_id = 10 claimed
        //is_claimed_status_id = 11 not claimed yet
        //status_id = 9 unpaid
        DB::beginTransaction();
        try {
            $nonClaimedExpenses = $this->service->model->query()
                ->where(['company_id' => $this->companyInformation()->id, 'is_claimed_status_id' => 11, 'user_id' => auth()->id()])
                ->get();
            if ($nonClaimedExpenses->count() > 0) {
                $claim = new ExpenseClaim;
                $claim->company_id = $this->companyInformation()->id;
                $claim->user_id = auth()->id();
                $claim->invoice_number = 'HRM-' . rand(10000, 90000);
                $claim->claim_date = date('Y-m-d');
                $claim->remarks = $request->remarks;
                $claim->payable_amount = $nonClaimedExpenses->sum('amount');
                $claim->due_amount = $nonClaimedExpenses->sum('amount');
                $claim->status_id = 9;
                $claim->save();
                if ($claim) {
                    foreach ($nonClaimedExpenses as $expense) {
                        $claimDetails = new ExpenseClaimDetails;
                        $claimDetails->hrm_expense_id = $expense->id;
                        $claimDetails->expense_claim_id = $claim->id;
                        $claimDetails->company_id = $claim->company_id;
                        $claimDetails->user_id = $claim->user_id;
                        $claimDetails->amount = $expense->amount;
                        $claimDetails->save();

                        $e = HrmExpense::find($expense->id);
                        $e->is_claimed_status_id = 10;
                        $e->save();
                    }
                }
            } else {
                return $this->responseWithSuccess('No claimed created', [], 200);
            }
            DB::commit();
            return $this->responseWithSuccess('Claimed successfully', [], 200);
        } catch (\Throwable $throwable) {
            DB::rollBack();
        }
    }

    public function claimHistory($request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'month' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->responseWithError(_trans('validation.Validation field required'), $validator->errors(), 422);
        }
        $month = Carbon::parse($request->month)->format('m');
        $year = Carbon::parse($request->month)->format('Y');
        $claimHistory = ExpenseClaim::whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->where('company_id', $this->companyInformation()->id)
            ->where('user_id', auth()->id())
            ->paginate(15);
        $data = new ClaimHistoryCollection($claimHistory);
        return $this->responseWithSuccess('Claim history', $data, 200);
    }

    public function paymentHistory($request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'month' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->responseWithError(_trans('validation.Validation field required'), $validator->errors(), 422);
        }

        $month = Carbon::parse($request->month)->format('m');
        $year = Carbon::parse($request->month)->format('Y');
        $paymentHistory = PaymentHistory::whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->where('company_id', $this->companyInformation()->id)
            ->where('user_id', auth()->id())
            ->paginate(15);
        $data = new PaymentCollection($paymentHistory);
        return $this->responseWithSuccess('Claim history', $data, 200);
    }

    public function claimDetails($id): \Illuminate\Http\JsonResponse
    {
        $expenseClaim = ExpenseClaim::find($id);
        if ($expenseClaim) {
            $claims = ExpenseClaimDetails::with(['hrmExpense', 'expenseClaim'])->where('expense_claim_id', $id)->get();
            $claimCollection = new ExpenseClaimCollection($claims);
            $data['month'] = $this->onlyMonthString($expenseClaim->created_at);
            $data['total_amount'] = $this->getCurrency() . "{$claims->sum('amount')}";
            $data['claim_data'] = $claimCollection;
            return $this->responseWithSuccess('Claim details list', $data, 200);
        } else {
            return $this->responseWithError('No data found', [], 400);
        }
    }
    public function getMonthlyExpense()
    {

        $month = $this->onlyMonth(\request()->get('month'));

        $thisMonthArray = $this->getSelectedMonthDays(\request()->get('month'));
        $date_array = [];
        $date_expense_array = [];
        $category_expense_array = [];
        $categories = $this->expenseCategory();
        foreach ($categories as $key => $category) {
            $date_expense_array = [];
            foreach ($thisMonthArray as $key => $item) {
                $day = Carbon::parse($item);
                $arrayIndex = $day->format('d');
                $todayDateInSqlFormat = $day->format('Y-m-d');
                $date_array[$key] = $day->format('d');

                $category_expense_array[$category->name][$day->format('d')] = $this->service->model->where(['hrm_expenses.company_id' => $this->companyInformation()->id, 'is_claimed_status_id' => 11])
                    ->where('date', $todayDateInSqlFormat)
                    ->where('income_expense_category_id', $category->id)
                    ->sum('amount');
            }
        }
        foreach ($category_expense_array as $key => $expense) {
            $category_expense_array[$key] = array_values($expense);
        }
        $data['thisMonthArray'] = $date_array;
        $data['categories'] = $categories->pluck('name');
        $data['expenses'] = $category_expense_array;
        return  $this->responseWithSuccess('Expense list', $data, 200);
    }

    // new functions

    function paymentFields()
    {
        return [
            _trans('common.ID'),
            _trans('common.Date'),
            _trans('common.Employee name'),
            _trans('common.Amount'),
            _trans('common.File'),
            _trans('common.Status'),
        ];
    }

   
}
