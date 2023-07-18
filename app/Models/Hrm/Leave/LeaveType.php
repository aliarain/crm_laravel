<?php

namespace App\Models\Hrm\Leave;

use App\Models\Traits\CompanyTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\coreApp\Traits\Relationship\StatusRelationTrait;

class LeaveType extends Model
{
    use HasFactory, StatusRelationTrait,CompanyTrait, SoftDeletes;

    protected $fillable = [
        'company_id', 'name', 'status_id'
    ];
}
