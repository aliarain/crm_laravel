<?php

namespace App\Models\Accounts;

use App\Models\User;
use App\Models\Accounts\Category;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class IncomeExpense extends Model
{
    use HasFactory, SoftDeletes,LogsActivity;

    protected $fillable = [
        'category_id',
        'user_id',
        'date',
        'amount',
        'due_date',
        'paid_amount',
        'note',
        'payment_type_id',
        'status_id',
        'author_info_id'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // user
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }
}
