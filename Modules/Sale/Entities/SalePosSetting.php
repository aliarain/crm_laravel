<?php

namespace Modules\Sale\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SalePosSetting extends Model
{
    use HasFactory;

    protected $fillable =[
        "customer_id", "warehouse_id", "biller_id", "product_number", "stripe_public_key", "stripe_secret_key", "keybord_active"
    ];
    
    protected static function newFactory()
    {
        return \Modules\Sale\Database\factories\SalePosSettingFactory::new();
    }
}
