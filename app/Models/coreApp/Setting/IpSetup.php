<?php

namespace App\Models\coreApp\Setting;

use App\Models\Traits\CompanyTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\coreApp\Traits\Relationship\StatusRelationTrait;

class IpSetup extends Model
{
    use HasFactory,CompanyTrait,StatusRelationTrait;

    protected $fillable = [
        'location',
        'ip_address',
        'status_id',
        'company_id',
    ];
}
