<?php

namespace App\Models;

use App\Models\Bank\BankAccount;
use App\Models\Company\Company;
use App\Models\Expenses\PaymentMethod;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockPaymentHistory extends Model
{
    use HasFactory;
    protected $fillable = [
        'company_id',
        'type',
        'amount',
        'reference',
        'payment_type',
        'bank_id',
        'payment_method_id',
        'bank_reference',
        'bank_name',
        'bank_account',
        'bank_branch',
        'bank_account_holder',
        'cheque_number',
        'cheque_date',
        'cheque_bank',
        'cheque_branch',
        'cheque_account_holder',
        'email',
        'transaction_id',
        'transaction_date',
    ];
    protected $dates = [
        'transaction_date',
        'cheque_date',
    ];
    protected $casts = [
        'amount' => 'float',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function bank()
    {
        return $this->belongsTo(BankAccount::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }
}
