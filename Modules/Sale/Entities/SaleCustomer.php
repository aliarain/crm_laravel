<?php

namespace Modules\Sale\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SaleCustomer extends Model
{
    use HasFactory;

    protected $fillable =[
        "customer_group_id", "user_id", "name", "company_name",
        "email", "phone_number", "tax_no", "address", "city",
        "state", "postal_code", "country", "points", "deposit", "expense", "is_active"
    ];

    public function customerGroup()
    {
        return $this->belongsTo('Modules\Sale\Entities\CustomerGroup');
    }

    public function user()
    {
    	return $this->belongsTo('App\User');
    }

    public function discountPlans()
    {
        return $this->belongsToMany('App\DiscountPlan', 'discount_plan_customers');
    }
    
    protected static function newFactory()
    {
        return \Modules\Sale\Database\factories\SaleCustomerFactory::new();
    }
}
