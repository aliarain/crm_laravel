<?php

namespace App\Models\ActivityLogs;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuthorInfo extends Model
{
    use HasFactory;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function approveUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by','id');
    }
    public function rejectedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rejected_by','id');
    }
    public function cancelledUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cancelled_by','id');
    }
    public function referredUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referred_by','id');
    }
}
