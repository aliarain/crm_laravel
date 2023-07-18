<?php

namespace App\Models\Hrm\Attendance;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeBreak extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'user_id',
        'date',
        'break_time',
        'back_time',
        'reason'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
