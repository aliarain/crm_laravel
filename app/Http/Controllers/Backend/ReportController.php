<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Repositories\AccountRepository;
use App\Repositories\IncomeExpenseRepository;

class ReportController extends Controller
{
    protected $accounts;
    protected $incomeExpense;

    public function __construct(AccountRepository $accounts,  IncomeExpenseRepository $incomeExpense)
    {
        $this->accounts = $accounts;
        $this->incomeExpense = $incomeExpense;
    }

    // income report
    public function reportIncome()
    {
        try {
            $data['title'] = _trans('common.Income Report List');
            $data['url'] = route('report.income_datatable');
            return view('backend.report.income', compact('data'));
        } catch (\Exception $e) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }

    public function incomeDatatableList(Request $request)
    {
        try {
            $data = $this->incomeExpense->data_table($request, 1);
            return $data;
        } catch (\Exception $e) {
            Toastr::error(_trans('validation.Something went wrong!'), 'Error');
            return redirect()->back();
        }
    }

    // expense report
    public function reportExpense()
    {
        try {
            $data['title'] = _trans('common.Expense Report List');
            $data['url'] = route('report.expense_datatable');
            return view('backend.report.expense', compact('data'));
        } catch (\Exception $e) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }

    public function expenseDatatableList(Request $request)
    {
        try {
            $data = $this->incomeExpense->data_table($request, 2);
            return $data;
        } catch (\Exception $e) {
            Toastr::error(_trans('validation.Something went wrong!'), 'Error');
            return redirect()->back();
        }
    }

    public function accountStatement(Request $request)
    {
        try {
            $data['title'] = _trans('common.Account Statement Report');
            return view('backend.report.accounts_statement_report', compact('data'));
        } catch (\Throwable $th) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }

    public function ajaxAccountStatement(Request $request)
    {
        try {
            $data = $this->accounts->accountStatement($request);
            return view('backend.partials.statement', compact('data'));
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()]);
        }
    }
}
