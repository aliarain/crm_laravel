<?php

namespace Modules\Sale\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SalePurchase extends Model
{
    use HasFactory;

    protected $fillable =[

        "reference_no", "user_id", "warehouse_id", "supplier_id", "item", "total_qty", "total_discount", "total_tax", "total_cost", "order_tax_rate", "order_tax", "order_discount", "shipping_cost", "grand_total","paid_amount", "status", "payment_status", "document", "note", "created_at"
    ];
    
    protected static function newFactory()
    {
        return \Modules\Sale\Database\factories\SalePurchaseFactory::new();
    }

    public function supplier()
    {
    	return $this->belongsTo('Modules\Sale\Entities\SaleSupplier');
    }

    public function warehouse()
    {
        return $this->belongsTo('Modules\Sale\Entities\SaleWarehouse');
    }

    public function purchaseSingleProduct()
    {
        return $this->belongsTo('Modules\Sale\Entities\SaleProductPurchase', 'id', 'purchase_id');
    }
}
