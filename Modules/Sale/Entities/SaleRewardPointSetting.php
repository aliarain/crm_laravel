<?php

namespace Modules\Sale\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SaleRewardPointSetting extends Model
{
    use HasFactory;

    protected $fillable = ["per_point_amount", "minimum_amount", "duration", "type", "is_active"];
    
    protected static function newFactory()
    {
        return \Modules\Sale\Database\factories\SaleRewardPointSettingFactory::new();
    }
}
