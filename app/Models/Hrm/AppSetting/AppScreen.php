<?php

namespace App\Models\Hrm\AppSetting;

use App\Models\coreApp\Traits\Relationship\StatusRelationTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppScreen extends Model
{
    use HasFactory, StatusRelationTrait;

    protected $fillable = [
        'name', 'slug', 'position', 'icon', 'status_id'
    ];
}
