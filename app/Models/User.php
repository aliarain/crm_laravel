<?php

namespace App\Models;

use App\Models\Skill;
use App\Models\Role\Role;
use App\Models\Notification;
use App\Models\Role\RoleUser;
use Laravel\Cashier\Billable;
use App\Models\Hrm\Appreciate;
use App\Models\Company\Company;
use App\Models\Hrm\Shift\Shift;
use App\Models\Feedback\Feedback;
use App\Models\Hrm\Notice\Notice;
use App\Models\Track\LocationLog;
use App\Models\Management\Project;
use Spatie\Activitylog\LogOptions;
use App\Models\Hrm\Country\Country;
use App\Models\Payroll\SalarySetup;
use App\Models\TaskManagement\Task;
use App\Models\Traits\CompanyTrait;
use Illuminate\Support\Facades\Log;
use App\Models\Hrm\Leave\AssignLeave;
use App\Models\ActivityLogs\AuthorInfo;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use App\Models\Hrm\Attendance\Attendance;
use App\Models\Hrm\Department\Department;
use App\Models\Hrm\Support\SupportTicket;
use App\Models\Management\DiscussionLike;
use App\Models\Payroll\SalarySetupDetails;
use App\Models\Hrm\Designation\Designation;
use Spatie\Activitylog\Traits\LogsActivity;
use App\Models\coreApp\Social\SocialIdentity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\coreApp\Traits\Relationship\StatusRelationTrait;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory,
        Notifiable,
        SoftDeletes,
        StatusRelationTrait,
        LogsActivity,
        Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id', 'name', 'phone', 'email', 'role_id', 'department_id', 'shift_id', 'designation_id', 'permissions', 'verification_code',
        'email_verify_token', 'is_email_verified', 'email_verified_at', 'phone_verify_token', 'is_phone_verified', 'phone_verified_at', 'password',
        'password_hints', 'avatar_id', 'status_id', 'last_login_at', 'last_logout_at', 'last_login_ip', 'device_token', 'login_access', 'address',
        'gender', 'birth_date', 'religion', 'blood_group', 'joining_date', 'basic_salary', 'marital_status', 'social_id', 'social_type', 'employee_id',
        'nationality', 'nid_card_number', 'nid_card_id', 'facebook_link', 'linkedin_link', 'instagram_link', 'passport_number', 'passport_file_id', 'tin', 'bank_name', 'bank_account', 'emergency_name', 'emergency_mobile_number', 'emergency_mobile_relationship',
        'manager_id', 'employee_type', 'grade', 'country_id', 'remember_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token'
    ];
    protected $appends = array();
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'permissions' => 'array',
        'company_id' => 'integer',
    ];

    protected static $logAttributes = [
        'company_id', 'name', 'phone', 'email', 'role_id', 'department_id', 'designation_id', 'permissions', 'verification_code',
        'email_verify_token', 'is_email_verified', 'email_verified_at', 'phone_verify_token', 'is_phone_verified', 'phone_verified_at', 'password',
        'password_hints', 'avatar_id', 'status_id', 'last_login_at', 'last_logout_at', 'last_login_ip', 'device_token', 'login_access', 'address',
        'gender', 'birth_date', 'religion', 'blood_group', 'joining_date', 'basic_salary', 'marital_status', 'social_id', 'social_type', 'employee_id',
        'nationality', 'nid_card_number', 'nid_card_id', 'facebook_link', 'linkedin_link', 'instagram_link', 'passport_number', 'passport_file_id', 'tin', 'bank_name', 'bank_account', 'emergency_name', 'emergency_mobile_number', 'emergency_mobile_relationship',
        'manager_id', 'employee_type', 'grade', 'remember_token'
    ];


    //pre request for jwt auth
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }


    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id', 'id')->withDefault();
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id')->withDefault();
    }

    public function identities(): HasMany
    {
        return $this->hasMany(SocialIdentity::class);
    }


    public function uploads(): HasMany
    {
        return $this->hasMany(Upload::class);
    }

    //can be common relationship make it trait
    public function author(): BelongsTo
    {
        return $this->belongsTo(AuthorInfo::class, 'id', 'authorable_id');
    }

    public function allAuthor(): HasMany
    {
        return $this->hasMany(AuthorInfo::class, 'created_by', 'id');
    }

    public function roleUser(): HasOne
    {
        return $this->hasOne(RoleUser::class, 'user_id', 'id');
    }

    public function userRole(): BelongsTo
    {
        return $this->belongsTo(RoleUser::class, 'user_id', 'id');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class)->withDefault();
    }

    public function designation(): BelongsTo
    {
        return $this->belongsTo(Designation::class)->withDefault();
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function assignLeave(): HasMany
    {
        return $this->hasMany(AssignLeave::class, 'user_id', 'id');
    }

    public function Leave(): HasMany
    {
        return $this->hasMany(AssignLeave::class, 'department_id', 'department_id');
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'user_id', 'id');
    }

    public function notices(): HasMany
    {
        return $this->hasMany(Notice::class, 'department_id', 'id');
    }

    public function appreciates(): HasMany
    {
        return $this->hasMany(Appreciate::class, 'user_id', 'id');
    }

    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class, 'shift_id', 'id');
    }
    public function myHr()
    {
        $hr_info = $this->where('is_hr', 1)->first();
        if ($hr_info) {
            return $hr_info;
        } else {
            return null;
        }
    }
    public function allNotifications()
    {
        return $this->hasMany(Notification::class, 'notifiable_id', 'id');
    }

    public function salary_setup()
    {
        return $this->hasOne(SalarySetup::class, 'user_id', 'id');
    }
    public function staffCommissions()
    {
        return $this->hasMany(SalarySetupDetails::class, 'user_id', 'id');
    }

    public function location_log()
    {
        return $this->hasOne(LocationLog::class, 'user_id', 'id')->orderBy('id', 'desc');
    }
    public function skills()
    {
        return $this->hasMany(Skill::class, 'user_id', 'id');
    }
    public function supports()
    {
        return $this->hasMany(SupportTicket::class, 'user_id', 'id');
    }

    protected static function boot()
    {
        parent::boot();
        static::created(function ($model) {
            $model->employee_id = 'EMP-' . $model->id;
            $model->permissions = $model->role->permissions;
            $model->is_hr=   $model->role->slug=='hr' ? 1 : 0;
            $model->save();
        });

    }

    public function totalProject()
    {
        return $this->hasMany(Project::class, 'created_by', 'id')->whereHas('members', function ($q) {
            $q->where('user_id', $this->id);
        })->count();
    }
    public function totalTask()
    {
        return $this->hasMany(Task::class, 'created_by', 'id')->whereHas('members', function ($q) {
            $q->where('user_id', $this->id);
        })->count();
    }


    public function getActivitylogOptions(): LogOptions{
        $logOptions = LogOptions::defaults(); 

        return $logOptions;
    }
}
