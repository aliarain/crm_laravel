<?php

namespace App\Models\Management;

use App\Models\User;
use App\Models\Management\Project;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProjectMembar extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function _by()
    {
        return $this->belongsTo(User::class, 'added_by');
    }
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
