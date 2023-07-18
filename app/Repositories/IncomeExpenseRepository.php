<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Supplier;
use App\Models\Accounts\Category;
use Illuminate\Support\Facades\DB;
use App\Models\Payments\PaymentType;
use Illuminate\Support\Facades\Hash;
use App\Models\Accounts\IncomeExpense;
use App\Helpers\CoreApp\Traits\SmsHandler;
use App\Helpers\CoreApp\Traits\FileHandler;
use App\Repositories\Interfaces\IncomeExpenseInterface;

class IncomeExpenseRepository
{

    public function getById($id)
    {
        return IncomeExpense::find($id);
    }

    public function create($request)
    {
            $user = User::find($request->supplier_id);
            $data = new IncomeExpense();
            $data->user_id = $request->supplier_id;
            $data->category_id = $request->category_id;
            $data->amount = $request->amount;
            $data->date = $request->expense_date;
            $data->payment_type_id = $request->payment_type_id;
            $data->type = $request->type;
            $data->status_id = 1;
            $data->author_info_id = $user->author->id;
            $data->save();
            return 1;
    }

    public function data_table($request, $type = null)
    {
        $income_expense = IncomeExpense::select('*');
        if (@$type) {
            $income_expense = $income_expense->where('type', $type); // 1 for income
        }
        if ($request->from && $request->to) {
            $income_expense = $income_expense->whereBetween('created_at', start_end_datetime($request->from, $request->to));
        }
        if (@$request->driver_id) {
            $income_expense = $income_expense->where('user_id', $request->driver_id);
        }
        return datatables()->of($income_expense->latest()->get())
            ->addColumn('action', function ($data) {
                $button = '<div class="flex-nowrap">
                    <div class="dropdown">
                        <button class="btn btn-white dropdown-toggle align-text-top" data-boundary="viewport" data-toggle="dropdown"> ' . _trans('common.Action') . '</button>
                        <div class="dropdown-menu dropdown-menu-right">
                        ' . actionButton('Income-Expense Edit', route('income_expense.edit', encrypt($data->id))) . '
                        ' . actionButton('Income-Expense Delete', '__globalDelete(' . $data->id . ',`dashboard/income-expense/delete/`)', 'delete') . '
                        </div>
                    </div>
                </div>';
                return $button;
            })
            ->addColumn('name', function ($data) {
                return $data->user->name;
            })
            ->addColumn('type', function ($data) {
                return $data->type == 1 ? 'Income' : 'Expense';
            })
            ->addColumn('category', function ($data) {
                return $data->category->name;
            })
            ->addColumn('amount', function ($data) {
                return $data->amount . 'Tk';
            })
            ->rawColumns(array('action', 'category', 'name', 'type', 'amount'))
            ->make(true);
    }

    public function update($request, $id)
    {
        try {
            $data = IncomeExpense::find($id);
            $data->user_id = $request->supplier_id;
            $data->category_id = $request->category_id;
            $data->amount = $request->amount;
            $data->date = $request->expense_date;
            $data->payment_type_id = $request->payment_type_id;
            $data->type = $request->type;
            $data->save();
            return 1;
        } catch (\Exception $e) {
            return 0;
        }

    }

    public function delete($id)
    {
        $data = IncomeExpense::find($id);
        $data->delete($data);
        return $data;
    }


}
