<?php

namespace App\Models\Management;

use App\Models\User;
use App\Models\Management\Notes;
use App\Models\Management\Client;
use App\Models\TaskManagement\Task;
use App\Models\Traits\CompanyTrait;
use App\Models\coreApp\Status\Status;
use App\Models\Management\Discussion;
use App\Models\Management\ProjectFile;
use Illuminate\Database\Eloquent\Model;
use App\Models\Management\ProjectMembar;
use App\Models\Management\ProjectPayment;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\coreApp\Traits\Relationship\StatusRelationTrait;

class Project extends Model
{
    use HasFactory,CompanyTrait,SoftDeletes;

    public function client() : BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id','id');
    }

    public function priorityStatus()
    {
        return $this->belongsTo(Status::class, 'priority');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }

    public function members(): HasMany
    {
        return $this->hasMany(ProjectMembar::class)->with('user');
    }
    public function members_short_list(): HasMany
    {
        return $this->hasMany(ProjectMembar::class)->take(3)->with('user');
    }

    public function files(): HasMany
    {
        return $this->hasMany(ProjectFile::class);
    }

    public function discussions(): HasMany
    {
        return $this->hasMany(Discussion::class)->orderBy('id', 'desc');
    }

    public function notes(): HasMany
    {
        return $this->hasMany(Notes::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(ProjectPayment::class)->with('payment_method');
    }

    public function goal()
    {
        return $this->belongsTo(\App\Models\Performance\Goal::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class)->orderBy('id', 'desc');
    }

}
