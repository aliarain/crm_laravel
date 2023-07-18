<?php

namespace Modules\Sale\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sale extends Model
{
    use HasFactory;

    protected $fillable =[
        "reference_no", "user_id", "cash_register_id", "customer_id", "warehouse_id", "biller_id", "item", "total_qty", "total_discount", "total_tax", "total_price", "order_tax_rate", "order_tax", "order_discount_type", "order_discount_value", "order_discount", "coupon_id", "coupon_discount", "shipping_cost", "grand_total", "sale_status", "payment_status", "paid_amount", "document", "sale_note", "staff_note", "created_at"
    ];
    
    protected static function newFactory()
    {
        return \Modules\Sale\Database\factories\SaleFactory::new();
    }

    public function biller()
    {
    	return $this->belongsTo('Modules\Sale\Entities\SaleBiller');
    }

    public function customer()
    {
    	return $this->belongsTo('Modules\Sale\Entities\SaleCustomer');
    }

    public function supplier()
    {
    	return $this->belongsTo('Modules\Sale\Entities\SaleSupplier');
    }

    public function user()
    {
    	return $this->belongsTo('App\Models\User');
    }

    public function warehouse()
    {
        return $this->belongsTo('Modules\Sale\Entities\SaleWarehouse');
    }

    // sale product sale
    public function saleProducts()
    {
        return $this->hasMany('Modules\Sale\Entities\SaleProductSale');
    }

    public function saleSingleProduct()
    {
        return $this->belongsTo('Modules\Sale\Entities\SaleProductSale', 'id', 'sale_id');
    }

    
}
