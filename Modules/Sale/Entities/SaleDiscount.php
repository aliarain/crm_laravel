<?php

namespace Modules\Sale\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SaleDiscount extends Model
{
    use HasFactory;

    protected $fillable= ['name', 'applicable_for', 'product_list', 'valid_from', 'valid_till', 'type', 'value', 'minimum_qty', 'maximum_qty', 'days', 'is_active'];
    
    protected static function newFactory()
    {
        return \Modules\Sale\Database\factories\SaleDiscountFactory::new();
    }

    public function discountPlans()
    {
        return $this->belongsToMany('Modules\Sale\Entities\SaleDiscountPlan', 'sale_discount_plan_discounts', 'discount_id', 'discount_plan_id');
    }
}
