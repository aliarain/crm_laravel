<?php

namespace Modules\Sale\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SaleDiscountPlan extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'is_active'];
    
    protected static function newFactory()
    {
        return \Modules\Sale\Database\factories\SaleDiscountPlanFactory::new();
    }

    public function customers()
    {
        return $this->belongsToMany('Modules\Sale\Entities\SaleCustomer', 'sale_discount_plan_customers', 'discount_plan_id', 'customer_id');
    }
}
