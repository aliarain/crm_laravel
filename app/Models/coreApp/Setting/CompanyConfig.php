<?php

namespace App\Models\coreApp\Setting;

use App\Models\Traits\CompanyTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CompanyConfig extends Model
{
    use CompanyTrait;
    protected $fillable = ['key', 'value','company_id'];
}
