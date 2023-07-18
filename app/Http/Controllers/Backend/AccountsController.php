<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Repositories\AccountRepository;

class AccountsController extends Controller
{
    public function __construct(AccountRepository $accounts)
    {
        $this->accounts = $accounts;
    }
    // make payment request
    public function makePayment(){
        try {
            $data['title'] = _trans('common.Make Payment');   
            $data['users'] = $this->accounts->getAllActive();
            return view('backend.accounts.make_payment', compact('data'));
        } catch (\Throwable $th) {
            Toastr::error(_trans('validation.Something went wrong!'), 'Error');
            return redirect()->back();
        }
    }
    public function driverPayments()
    {   
        try {
            $data['title'] = _trans('common.Driver Payment List');   
            $data['url']   = route('accounts.payment_datatable');
            $data['drivers'] = $this->driver->getAllActive();
            $data['filed'] = _trans('common.Driver');
            return view('backend.accounts.payment_list', compact('data'));
        } catch (\Throwable $th) {
            Toastr::error(_trans('validation.Something went wrong!'), 'Error');
            return redirect()->back();
        }
    }

    public function clientPayments()
    {   
        try {
            $data['title'] = _trans('common.Client Payment List');   
            $data['url']   = route('accounts.client_payment_datatable');
            $data['drivers'] = $this->driver->getAllClientActive();
            $data['filed'] = _trans('common.Client');
            return view('backend.accounts.payment_list', compact('data'));
        } catch (\Throwable $th) {
            Toastr::error(_trans('validation.Something went wrong!'), 'Error');
            return redirect()->back();
        }
    }

      // driver data table payment list
      function datatablePaymentList(Request $request) {
        try {
            $data = $this->accounts->paymentDatatable($request,null,0);
            return $data;
        } catch (\Throwable $th) {
            Toastr::error(_trans('validation.Something went wrong!'), 'Error');
            return redirect()->back();
        }
    }    
    
    
    // Client data table payment list
      function datatableClientPaymentList(Request $request) {
        try {
            $data = $this->accounts->paymentDatatable($request,null,1);
            return $data;
        } catch (\Throwable $th) {
            Toastr::error(_trans('validation.Something went wrong!'), 'Error');
            return redirect()->back();
        }
    }
}
