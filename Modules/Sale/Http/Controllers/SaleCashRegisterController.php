<?php

namespace Modules\Sale\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Sale\Entities\SaleCashRegister;
use Illuminate\Contracts\Support\Renderable;

class SaleCashRegisterController extends Controller
{
    public function index()
	{
		if(Auth::user()->role_id <= 2) {
			$ot_crm_cash_register_all = SaleCashRegister::with('user', 'warehouse')->get();
			return view('backend.cash_register.index', compact('ot_crm_cash_register_all'));
		}
		else
			return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
	}
	public function store(Request $request)
	{
		$data = $request->all();
		$data['status'] = true;
		$data['user_id'] = Auth::id();
		SaleCashRegister::create($data);
		return redirect()->back()->with('message', 'Cash register created successfully');
	}

	public function getDetails($id)
	{
		$cash_register_data = SaleCashRegister::find($id);

		$data['cash_in_hand'] = $cash_register_data->cash_in_hand;
		$data['total_sale_amount'] = Sale::where([
										['cash_register_id', $cash_register_data->id],
										['sale_status', 1]
									])->sum('grand_total');
		$data['total_payment'] = Payment::where('cash_register_id', $cash_register_data->id)->sum('amount');
		$data['cash_payment'] = Payment::where([
									['cash_register_id', $cash_register_data->id],
									['paying_method', 'Cash']
								])->sum('amount');
		$data['credit_card_payment'] = Payment::where([
									['cash_register_id', $cash_register_data->id],
									['paying_method', 'Credit Card']
								])->sum('amount');
		$data['gift_card_payment'] = Payment::where([
									['cash_register_id', $cash_register_data->id],
									['paying_method', 'Gift Card']
								])->sum('amount');
		$data['deposit_payment'] = Payment::where([
									['cash_register_id', $cash_register_data->id],
									['paying_method', 'Deposit']
								])->sum('amount');
		$data['cheque_payment'] = Payment::where([
									['cash_register_id', $cash_register_data->id],
									['paying_method', 'Cheque']
								])->sum('amount');
		$data['paypal_payment'] = Payment::where([
									['cash_register_id', $cash_register_data->id],
									['paying_method', 'Paypal']
								])->sum('amount');
		$data['total_sale_return'] = Returns::where('cash_register_id', $cash_register_data->id)->sum('grand_total');
		$data['total_expense'] = Expense::where('cash_register_id', $cash_register_data->id)->sum('amount');
		$data['total_cash'] = $data['cash_in_hand'] + $data['total_payment'] - ($data['total_sale_return'] + $data['total_expense']);
		$data['status'] = $cash_register_data->status;
		return $data;
	}

	public function showDetails($warehouse_id)
	{
		$cash_register_data = SaleCashRegister::where([
					    		['user_id', Auth::id()],
					    		['warehouse_id', $warehouse_id],
					    		['status', true]
					    	])->first();

		$data['cash_in_hand'] = $cash_register_data->cash_in_hand;
		$data['total_sale_amount'] = Sale::where([
										['cash_register_id', $cash_register_data->id],
										['sale_status', 1]
									])->sum('grand_total');
		$data['total_payment'] = Payment::where('cash_register_id', $cash_register_data->id)->sum('amount');
		$data['cash_payment'] = Payment::where([
									['cash_register_id', $cash_register_data->id],
									['paying_method', 'Cash']
								])->sum('amount');
		$data['credit_card_payment'] = Payment::where([
									['cash_register_id', $cash_register_data->id],
									['paying_method', 'Credit Card']
								])->sum('amount');
		$data['gift_card_payment'] = Payment::where([
									['cash_register_id', $cash_register_data->id],
									['paying_method', 'Gift Card']
								])->sum('amount');
		$data['deposit_payment'] = Payment::where([
									['cash_register_id', $cash_register_data->id],
									['paying_method', 'Deposit']
								])->sum('amount');
		$data['cheque_payment'] = Payment::where([
									['cash_register_id', $cash_register_data->id],
									['paying_method', 'Cheque']
								])->sum('amount');
		$data['paypal_payment'] = Payment::where([
									['cash_register_id', $cash_register_data->id],
									['paying_method', 'Paypal']
								])->sum('amount');
		$data['total_sale_return'] = Returns::where('cash_register_id', $cash_register_data->id)->sum('grand_total');
		$data['total_expense'] = Expense::where('cash_register_id', $cash_register_data->id)->sum('amount');
		$data['total_cash'] = $data['cash_in_hand'] + $data['total_payment'] - ($data['total_sale_return'] + $data['total_expense']);
		$data['id'] = $cash_register_data->id;
		return $data;
	}

	public function close(Request $request)
	{
		$cash_register_data = SaleCashRegister::find($request->cash_register_id);
		$cash_register_data->status = 0;
		$cash_register_data->save();
		return redirect()->back()->with('message', 'Cash register closed successfully');
	}

    public function checkAvailability($warehouse_id)
    {
    	$open_register_number = SaleCashRegister::where([
						    		['user_id', Auth::id()],
						    		['warehouse_id', $warehouse_id],
						    		['status', true]
						    	])->count();
    	if($open_register_number)
    		return 'true';
    	else
    		return 'false';
    }
}
