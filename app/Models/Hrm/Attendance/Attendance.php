<?php

namespace App\Models\Hrm\Attendance;

use App\Models\User;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use App\Models\Hrm\Attendance\LateInOutReason;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    use LogsActivity;

    protected $fillable = [
        'user_id',
        'company_id',
        'date',
        'check_in',
        'remote_mode_in',
        'remote_mode_out',
        'check_out',
        'stay_time',
        'late_reason',
        'late_time',
        'in_status',
        'out_status',
        'checkin_ip',
        'checkout_ip',
        'check_in_location', 
        'check_out_location',
        'check_in_latitude',
        'check_in_longitude',
        'check_out_latitude',
        'check_out_longitude',
        'city',
        'country_code',
        'country',
        'status_id'
    ];

    protected static $logAttributes = [
        'id',
        'company_id',
        'user_id',
        'date',
        'remote_mode_in',
        'remote_mode_out',
        'check_in',
        'check_out',
        'stay_time',
        'late_reason',
        'late_time',
        'status',
        'checkin_ip',
        'checkout_ip',
        'check_in_location',
        'check_out_location',
        'check_in_latitude',
        'check_in_longitude',
        'check_out_latitude',
        'check_out_longitude',
        'city',
        'country_code',
        'country',
        'status_id'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function lateInOutReason(): HasMany
    {
        return $this->hasMany(LateInOutReason::class, 'attendance_id', 'id');
    }


    public function lateInReason(): HasOne
    {
        return $this->hasOne(LateInOutReason::class, 'attendance_id', 'id')->where('type', 'in');
    }

    public function earlyOutReason(): HasOne
    {
        return $this->hasOne(LateInOutReason::class, 'attendance_id', 'id')->where('type', 'out');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }
}
