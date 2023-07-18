<?php

namespace Modules\Sale\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SaleProductAdjustment extends Model
{
    use HasFactory;

    protected $fillable =[
        "adjustment_id", "product_id", "variant_id", "qty", "action"
    ];
    
    protected static function newFactory()
    {
        return \Modules\Sale\Database\factories\SaleProductAdjustmentFactory::new();
    }
}
