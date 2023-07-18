<?php

namespace Modules\Sale\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SaleDiscountPlanDiscount extends Model
{
    use HasFactory;

    protected $fillable =['discount_plan_id', 'discount_id'];
    
    protected static function newFactory()
    {
        return \Modules\Sale\Database\factories\SaleDiscountPlanDiscountFactory::new();
    }
}
