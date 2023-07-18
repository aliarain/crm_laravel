<?php

namespace App\Services\Hrm;

use App\Helpers\CoreApp\Traits\CurrencyTrait;
use App\Helpers\CoreApp\Traits\DateHandler;
use App\Helpers\CoreApp\Traits\InvoiceGenerateTrait;
use App\Models\coreApp\Relationship\RelationshipTrait;
use App\Models\Expenses\ExpenseClaim;
use App\Models\Expenses\PaymentHistory;
use App\Models\Expenses\PaymentHistoryDetails;
use App\Models\Expenses\PaymentMethod;
use App\Services\Core\BaseService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class ClaimService extends BaseService
{
    use RelationshipTrait, DateHandler, InvoiceGenerateTrait, CurrencyTrait;

    public function __construct(ExpenseClaim $expenseClaim)
    {
        $this->model = $expenseClaim;
    }

    public function dataTable()
    {
        $claims = $this->model->query()->where('company_id', $this->companyInformation()->id);
        $claims->when(\request()->get('date'), function (Builder $builder) {
            $date = explode(' - ', \request()->get('date'));
            return $builder->whereBetween('claim_date', [$this->databaseFormat($date[0]), $this->databaseFormat($date[1])]);
        });
        $claims->when(\request()->get('user_ids'), function (Builder $builder) {
            return $builder->whereIn('user_id', \request()->get('user_ids'));
        });

        return datatables()->of($claims->latest()->get())
            ->addColumn('action', function ($data) use ($claims) {
                $action_button = '';
                $payNow = _trans('expense.Pay Now');
                if (hasPermission('payment_create')) {
                    if ($data->status_id == 9) {
                        $action_button .= '<a  href="javascript:void(0)" onclick="paymentModal(`' . route('expenseClaim.show', $data->id) . '`)" class="dropdown-item">'.$payNow.'</a>';
                        $button = getActionButtons($action_button);
                        return $button;
                    }
                }
            })
            ->addColumn('date', function ($data) {
                return @$data->claim_date;
            })
            ->addColumn('employee_name', function ($data) {
                return @$data->user->name;
            })
            ->addColumn('file', function ($data) {
                if ($data->attachment_file_id !=null) {
                    return '<a href="' . uploaded_asset($data->attachment_file_id) . '" download class="btn btn-white btn-sm"><i class="fas fa-download"></i></a>';
                }else{
                    return _trans('common.No File');
                }})
            ->addColumn('remarks', function ($data) {
                return @$data->remarks;
            })
            ->addColumn('amount', function ($data) {
                return $this->getCurrency() . @$data->payable_amount;
            })
            ->addColumn('status', function ($data) {
                return '<span class="badge badge-' . @$data->status->class . '">' . @$data->status->name . '</span>';
            })
            ->rawColumns(array('date', 'employee_name', 'remarks', 'amount','file', 'status', 'action'))
            ->make(true);
    }

    public function approveClaimPayment($expenseClaim)
    {
        DB::beginTransaction();
        try {
            $claim = $this->model->find($expenseClaim->id);
            $claim->status_id = 8;
            $claim->save();

            //make payment
            $payment = new PaymentHistory;
            $payment->expense_claim_id = $claim->id;
            $payment->company_id = $claim->company_id;
            $payment->code = $this->generateCode($payment, "Invoice");
            $payment->user_id = $claim->user_id;
            $payment->payment_date = date('Y-m-d');
            $payment->remarks = \request()->get('remarks');
            $payment->payable_amount = $claim->payable_amount;
            $payment->paid_amount = $claim->payable_amount;
            $payment->due_amount = 0;
            $payment->payment_status_id = 8;
            $payment->save();


            //payment history details save
            $paymentHistory = new PaymentHistoryDetails;
            $paymentHistory->payment_history_id = $payment->id;
            $paymentHistory->company_id = $payment->company_id;
            $paymentHistory->user_id = $payment->user_id;
            $paymentHistory->payment_method_id = \request()->get('payment_method');
            $paymentHistory->payment_details = $payment->remarks;
            $paymentHistory->payment_status_id = $payment->payment_status_id;
            $paymentHistory->payment_date = $payment->payment_date;
            $paymentHistory->paid_by_id = auth()->id();
            $paymentHistory->paid_amount = $payment->paid_amount;
            $paymentHistory->due_amount = 0;
            $paymentHistory->save();
            DB::commit();
            return true;
        } catch (\Throwable $throwable) {
            DB::rollBack();
            return false;
        }
    }

    public function paymentMethods()
    {
        return PaymentMethod::where(['company_id' => $this->companyInformation()->id, 'status_id' => 1])->get();
    }

}
