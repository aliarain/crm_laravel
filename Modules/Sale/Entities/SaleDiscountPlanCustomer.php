<?php

namespace Modules\Sale\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SaleDiscountPlanCustomer extends Model
{
    use HasFactory;

    protected $fillable = ['discount_plan_id', 'customer_id'];
    
    protected static function newFactory()
    {
        return \Modules\Sale\Database\factories\SaleDiscountPlanCustomerFactory::new();
    }
}
