<?php

namespace App\Models\Finance;

use App\Models\User;
use App\Models\Upload;
use App\Models\Traits\CompanyTrait;
use App\Models\coreApp\Status\Status;
use Illuminate\Database\Eloquent\Model;
use App\Models\Expenses\IncomeExpenseCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Expense extends Model
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
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(IncomeExpenseCategory::class, 'income_expense_category_id');
    }

    public function payment()
    {
        return $this->belongsTo(Status::class, 'pay');
    }

    public function attachmentFile()
    {
        return $this->belongsTo(Upload::class, 'attachment');
    }
}
