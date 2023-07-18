<?php

namespace App\Models\Expenses;

use App\Models\User;
use App\Models\coreApp\Status\Status;
use Illuminate\Database\Eloquent\Model;
use App\Models\Expenses\IncomeExpenseCategory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\coreApp\Traits\Relationship\StatusRelationTrait;

class HrmExpense extends Model
{
    use StatusRelationTrait;

    use HasFactory;

    protected $fillable = [
        'company_id',
        'user_id',
        'income_expense_category_id',
        'date',
        'remarks',
        'amount',
        'attachment_file_id',
        'status_id',
        'is_claimed_status_id',
        'claimed_approved_status_id',
    ];

    public function expenseCategory(): BelongsTo
    {
        return $this->belongsTo(IncomeExpenseCategory::class);
    }

    public function claimApprove(): BelongsTo
    {
        return $this->belongsTo(Status::class, 'claimed_approved_status_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function claim(): BelongsTo
    {
        return $this->belongsTo(Status::class, 'is_claimed_status_id', 'id');
    }
}
