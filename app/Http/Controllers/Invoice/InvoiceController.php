<?php

namespace App\Http\Controllers\Invoice;

use App\Http\Controllers\Controller;
use App\Models\Payments\DriverPayment;
use App\Repositories\InvoiceRepositories;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    protected $invoice;

    public function __construct(InvoiceRepositories $invoiceRepositories)
    {
        $this->invoice = $invoiceRepositories;
    }

    public function payment($paymentId)
    {
        $data['title'] = _trans('common.Payment invoice');
        $payment =  $this->invoice->paymentDetails($paymentId);
        $data['payment'] = $payment;

        return view('backend.payment.payment_history', compact('data'));
    }

    public function driverInvoice($paymentId)
    {
        try {
            return $this->invoice->driverInvoice($paymentId);
        } catch (\Exception $ex) {
        }
    }
}
