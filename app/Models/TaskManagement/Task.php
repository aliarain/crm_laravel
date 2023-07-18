<?php

namespace App\Models\TaskManagement;

use App\Models\Management\Client;
use App\Models\Management\Project;
use App\Models\Traits\CompanyTrait;
use App\Models\coreApp\Status\Status;
use App\Models\TaskManagement\TaskFile;
use Illuminate\Database\Eloquent\Model;
use App\Models\TaskManagement\TaskMember;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory,CompanyTrait;

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }

    public function priorityStatus()
    {
        return $this->belongsTo(Status::class, 'priority');
    }
    
    public function goal()
    {
        return $this->belongsTo(\App\Models\Performance\Goal::class);
    }
    public function createdBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    public function members(): HasMany
    {
        return $this->hasMany(TaskMember::class)->with('user');
    }
    public function members_short(): HasMany
    {
        return $this->hasMany(TaskMember::class)->with('user')->take(3);
    }

    public function files(): HasMany
    {
        return $this->hasMany(TaskFile::class);
    }

    public function discussions(): HasMany
    {
        return $this->hasMany(TaskDiscussion::class);
    }

    public function notes(): HasMany
    {
        return $this->hasMany(TaskNote::class);
    }
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
  
}
