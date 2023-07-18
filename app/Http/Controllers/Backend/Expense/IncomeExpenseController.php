<?php

namespace App\Http\Controllers\Backend\Expense;

use App\Http\Controllers\Controller;
use App\Http\Requests\IncomeExpenseRequest;
use App\Repositories\IncomeExpenseRepository;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use function __translate;
use function _translate;
use function back;
use function decrypt;
use function redirect;
use function route;
use function view;

class IncomeExpenseController extends Controller
{
    protected $incomeExpense;

    public function __construct(IncomeExpenseRepository $incomeExpense)
    {
        $this->incomeExpense = $incomeExpense;
    }
    public function index()
    {
        try {
            $data['title'] = _trans('common.Income/Expense List');
            $data['url'] = route('income_expense.data_table');
            return view('backend.income_expense.index', compact('data'));
        } catch (\Exception $e) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }

    // data table methods categories user get all data
    public function data_table(Request $request)
    {
        try {
            $data = $this->incomeExpense->data_table($request);
            return $data;
        } catch (\Exception $e) {
            Toastr::error(_trans('validation.Something went wrong!'), 'Error');
            return redirect()->back();
        }
    }

    // create method income expense
    public function create()
    {
        try {
            $data['title'] = _trans('common.Create Income/Expense');
            $data['suppliers'] = $this->incomeExpense->suppliers();
            $data['url'] = route('income_expense.store');
            return view('backend.income_expense.create', compact('data'));
        } catch (\Exception $e) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }

    public function store(IncomeExpenseRequest $request)
    {
        try {
            $data = $this->incomeExpense->create($request);
            if ($data) {
                Toastr::success(_trans('validation.Income-Expense created successfully.'), 'Success');
                return redirect()->route('income_expense.index');
            } else {
                Toastr::error(_trans('validation.Something went wrong!'), 'Error');
                return redirect()->back();
            }
            Toastr::error(_trans('validation.Something went wrong!'), 'Error');
            return back();
        } catch (\Exception $e) {
            Toastr::error(_trans('validation.Something went wrong!'), 'Error');
            return back();
        }
    }

    // edit method income expense
    public function edit($id)
    {
        try {
            $data['title'] = _trans('common.Create Income/Expense');
            $data['suppliers'] = $this->incomeExpense->suppliers();
            $data['edit'] = $this->incomeExpense->getById(decrypt($id));
            $data['url'] = route('income_expense.update', decrypt($id));
            return view('backend.income_expense.create', compact('data'));
        } catch (\Exception $e) {
            Toastr::error(_trans('response.Something went wrong.'), 'Error');
            return redirect()->back();
        }
    }

    public function update(IncomeExpenseRequest $request, $id)
    {
        try {
            $data = $this->incomeExpense->update($request, $id);
            if ($data) {
                Toastr::success(_trans('validation.Category has been updated successfully.'), 'Success');
                return redirect()->route('income_expense.index');
            } else {
                Toastr::error(_trans('validation.Something went wrong!'), 'Error');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            Toastr::error(_trans('validation.Something went wrong!'), 'Error');
            return back();
        }
    }

    // destroy method income expense
    public function destroy($id)
    {
        try {
            $data = $this->incomeExpense->delete($id);
            if ($data) {
                Toastr::success(_trans('validation.Income-Expense has been deleted successfully.'), 'Success');
                return redirect()->route('income_expense.index');
            } else {
                Toastr::error(_trans('validation.Something went wrong!'), 'Error');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            Toastr::error(_trans('validation.Something went wrong!'), 'Error');
            return redirect()->back();
        }
    }
}
