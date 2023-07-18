<?php

namespace App\Models\Hrm\Leave;

use Illuminate\Database\Eloquent\Model;
use App\Models\Hrm\Department\Department;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\coreApp\Traits\Relationship\StatusRelationTrait;
use Illuminate\Support\Facades\Log;
    use Spatie\Activitylog\LogOptions;

class AssignLeave extends Model
{
    use HasFactory, StatusRelationTrait, LogsActivity, SoftDeletes;

    protected $fillable = [
        'company_id', 'department_id', 'days', 'type_id', 'status_id'
    ];

    protected static $logAttributes = [
        'company_id', 'department_id', 'days', 'type_id', 'status_id'
    ];


    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(LeaveType::class, 'type_id');
    }  
    

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }
}
