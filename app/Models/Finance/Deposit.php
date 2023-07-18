<?php

namespace App\Models\Finance;

use App\Models\User;
use App\Models\Upload;
use App\Models\Finance\Transaction;
use App\Models\Traits\CompanyTrait;
use App\Models\coreApp\Status\Status;
use App\Models\Expenses\PaymentMethod;
use Illuminate\Database\Eloquent\Model;
use App\Models\Expenses\IncomeExpenseCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Deposit extends Model
{
    use HasFactory,CompanyTrait;

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function category()
    {
        return $this->belongsTo(IncomeExpenseCategory::class, 'income_expense_category_id');
    }
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function payment()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }

    public function attachmentFile()
    {
        return $this->belongsTo(Upload::class, 'attachment');
    }
}
