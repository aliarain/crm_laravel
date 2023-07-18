<?php

namespace App\Models\Hrm\Attendance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Traits\LogsActivity;

    use Spatie\Activitylog\LogOptions;
class LateInOutReason extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = ['company_id', 'attendance_id', 'type', 'reason'];

    protected static $logAttributes = ['company_id', 'attendance_id', 'type', 'reason'];

    public function attendance(): BelongsTo
    {
        return $this->belongsTo(Attendance::class);
    }


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }
}
