<?php

namespace App\Models\Hrm\Attendance;

use App\Models\Role\Role;
use App\Models\Hrm\Shift\Shift;
use App\Models\Traits\CompanyTrait;
use App\Models\coreApp\Status\Status;
use Illuminate\Database\Eloquent\Model;
use App\Models\Hrm\Department\Department;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\coreApp\Traits\Relationship\StatusRelationTrait;
    use Spatie\Activitylog\LogOptions;
class DutySchedule extends Model
{
    use HasFactory, StatusRelationTrait, LogsActivity,CompanyTrait;

    protected $fillable = [
        'company_id', 'shift_id', 'start_time', 'end_time', 'hour', 'consider_time', 'status_id'
    ];

    protected static $logAttributes = [
        'company_id', 'shift_id', 'start_time', 'end_time', 'hour', 'consider_time', 'status_id'
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class); 
    }


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }
}
