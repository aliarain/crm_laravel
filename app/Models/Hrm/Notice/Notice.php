<?php

namespace App\Models\Hrm\Notice;

use App\Models\User;
use App\Models\Traits\CompanyTrait;
use Illuminate\Database\Eloquent\Model;
use App\Models\Hrm\Department\Department;
use App\Models\Hrm\Notice\NoticeDepartment;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notice extends Model
{
    use HasFactory,CompanyTrait;

    protected $fillable = [
        'company_id',
        'department_id',
        'attachment_file_id',
        'subject',
        'date',
        'description',
        'status_id',
        'created_by',
        'created_at'
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function userNoticeLogs(): HasMany
    {
        return $this->hasMany(NoticeViewLog::class)->where('user_id', auth()->id());
    }

    public function departmentNotices()
    {
        return $this->morphMany(NoticeDepartment::class, 'noticeable');
    }

    public function noticeDepartments()
    {
        return $this->hasMany(NoticeDepartment::class,'noticeable_id','id');
    }

    public function departmentFor()
    {
        return $this->noticeDepartments->pluck('department_id')->toArray();
    }
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            $model->noticeDepartments()->delete();
        });
    }



}
