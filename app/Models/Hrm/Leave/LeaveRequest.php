<?php

namespace App\Models\Hrm\Leave;

use App\Models\ActivityLogs\AuthorInfo;
use App\Models\coreApp\Traits\Relationship\StatusRelationTrait;
use App\Models\coreApp\Traits\Relationship\UserRelationTrait;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class LeaveRequest extends Model
{

    use HasFactory, StatusRelationTrait,UserRelationTrait;

    protected $fillable = [
        'assign_leave_id',
        'user_id',
        'apply_date',
        'leave_from',
        'leave_to',
        'reason',
        'substitute_id',
        'attachment_file_id',
        'status_id',
        'author_info_id',
    ];


    public function assignLeave(): BelongsTo
    {
        return $this->belongsTo(AssignLeave::class);
    }

    public function substitute(): BelongsTo
    {
        return $this->belongsTo(User::class, 'substitute_id', 'id');
    }

    public function referredBy():HasOne
    {
        return $this->hasOne(AuthorInfo::class, 'referred_by','id');
    }
    public function approvedBy():HasOne
    {
        return $this->hasOne(AuthorInfo::class, 'approved_by', 'id');
    }


}
