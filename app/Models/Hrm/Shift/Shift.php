<?php

namespace App\Models\Hrm\Shift;

use App\Models\Traits\CompanyTrait;
use Illuminate\Database\Eloquent\Model;
use App\Models\Hrm\Attendance\DutySchedule;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\coreApp\Traits\Relationship\StatusRelationTrait;
    use Spatie\Activitylog\LogOptions;

class Shift extends Model
{
    use HasFactory, StatusRelationTrait, LogsActivity,CompanyTrait;
    protected $fillable = ['id', 'company_id', 'name', 'status_id'];

    public function dutySchedule(): HasOne
    {
        return $this->hasOne(DutySchedule::class, 'shift_id', 'id');
    }

    protected static $logAttributes = [
       'company_id', 'id','title', 'status_id'
    ];


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }

}
