<?php

namespace Modules\Sale\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Modules\Sale\Entities\SaleAccount;
use Modules\Sale\Entities\SaleExpense;
use Modules\Sale\Entities\SaleWarehouse;
use Modules\Sale\Entities\SaleCashRegister;
use Illuminate\Contracts\Support\Renderable;
use Modules\Sale\Entities\SaleExpenseCategory;

class SaleExpenseController extends Controller
{
    public function index(Request $request)
    {
        $entries = isset($_GET['entries']) !== false ? $_GET['entries'] : 10;
        $search = isset($_GET['search']) !== false ? $_GET['search'] : '';
        $data['entries'] = $entries;
        $data['search'] = $search;

        $warehouses = SaleWarehouse::where('is_active', true)->get();
        $categories = SaleExpenseCategory::where('is_active', true)->get();
        $accounts = SaleAccount::where('is_active', true)->get();
        $data['title'] = 'Expense List';
        $data['expenses'] = SaleExpense::with('warehouse','expenseCategory');
        $data['search'] = '';
        if ($search != "") {
            $data['expenses'] = $data['expenses']->where('reference_no', 'like', '%' . $search . '%');
        }
        $data['expenses'] = $data['expenses']->latest()->paginate($entries);

        return view('sale::expense.index', compact('accounts', 'warehouses','data','categories'));

    }

    public function store(Request $request)
    {
        $data = $request->all();
        if(isset($data['created_at']))
            $data['created_at'] = date("Y-m-d H:i:s", strtotime($data['created_at']));
        else
            $data['created_at'] = date("Y-m-d H:i:s");
        $data['reference_no'] = 'er-' . date("Ymd") . '-'. date("his");
        $data['user_id'] = Auth::id();
        $cash_register_data = SaleCashRegister::where([
            ['user_id', $data['user_id']],
            ['warehouse_id', $data['warehouse_id']],
            ['status', true]
        ])->first();
        if($cash_register_data)
            $data['cash_register_id'] = $cash_register_data->id;
        SaleExpense::create($data);
        Toastr::success(_trans('message.Operation Successful'), 'Success');
        return redirect()->route('saleExpense.index');

    }


    public function edit($id)
    {
            $expense = SaleExpense::find($id);
            $expense->date = date('d-m-Y', strtotime($expense->created_at->toDateString()));
            return $expense;
    }

    public function update(Request $request)
    {
        $data = $request->all();
        $ot_crm_expense_data = SaleExpense::find($data['expense_id']);
        $data['created_at'] = date("Y-m-d H:i:s", strtotime($data['created_at']));
        $ot_crm_expense_data->update($data);
        Toastr::success(_trans('message.Operation Successful'), 'Success');
        return redirect()->route('saleExpense.index');
    }

    public function deleteBySelection(Request $request)
    {
        $expense_id = $request['expenseIdArray'];
        foreach ($expense_id as $id) {
            $ot_crm_expense_data = Expense::find($id);
            $ot_crm_expense_data->delete();
        }
        return 'Expense deleted successfully!';
    }

    public function destroy($id)
    {
        $ot_crm_expense_data = SaleExpense::find($id);
        $ot_crm_expense_data->delete();
        Toastr::success(_trans('response.Expense deleted successfully'), 'Success');
        return response()->json(['status' => 'success']);
    }
}
