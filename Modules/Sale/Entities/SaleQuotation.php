<?php

namespace Modules\Sale\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SaleQuotation extends Model
{
    use HasFactory;

    protected $fillable =[

        "reference_no", "user_id", "biller_id", "supplier_id", "customer_id", "warehouse_id", "item", "total_qty", "total_discount", "total_tax", "total_price", "order_tax_rate", "order_tax", "order_discount", "shipping_cost", "grand_total", "quotation_status","document", "note"
    ];

    protected static function newFactory()
    {
        return \Modules\Sale\Database\factories\SaleQuotationFactory::new();
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
    
    
}
