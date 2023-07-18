<?php

namespace App\Models\Settings;

use App\Models\Traits\CompanyTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ApiSetup extends Model
{
    use HasFactory,CompanyTrait;
}
