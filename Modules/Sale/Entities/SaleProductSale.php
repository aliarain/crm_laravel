<?php

namespace Modules\Sale\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SaleProductSale extends Model
{
    use HasFactory;

    protected $fillable =[
        "sale_id", "product_id", "product_batch_id", "variant_id", 'imei_number', "qty", "sale_unit_id", "net_unit_price", "discount", "tax_rate", "tax", "total"
    ];
    
    protected static function newFactory()
    {
        return \Modules\Sale\Database\factories\SaleProductSaleFactory::new();
    }

    // product 
    public function product()
    {
        return $this->belongsTo('Modules\Sale\Entities\SaleProduct');
    }
}
