<?php

namespace App\Models\Hrm\Team;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\coreApp\Traits\Relationship\StatusRelationTrait;

class Team extends Model
{
    use HasFactory, StatusRelationTrait;

    protected $fillable = [
        'name',
        'team_lead_id',
        'status_id',
        'user_id',
        'company_id',
    ];

    public function teams()
    {
        return $this->hasMany(TeamMember::class);
    }

    // belongs to
    public function teamLead()
    {
        return $this->belongsTo(User::class, 'team_lead_id');
    }

}
