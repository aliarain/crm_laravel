<?php

namespace Modules\Sale\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SaleProductQuotation extends Model
{
    use HasFactory;

    protected $fillable =[
        "quotation_id", "product_id", "product_batch_id", "variant_id", "qty", "sale_unit_id", "net_unit_price", "discount", "tax_rate", "tax", "total"
    ];
    
    protected static function newFactory()
    {
        return \Modules\Sale\Database\factories\SaleProductQuotationFactory::new();
    }
}
