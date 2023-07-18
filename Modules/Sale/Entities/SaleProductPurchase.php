<?php

namespace Modules\Sale\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SaleProductPurchase extends Model
{
    use HasFactory;

    protected $fillable =[

        "purchase_id", "product_id", "product_batch_id", "variant_id", "imei_number", "qty", "recieved", "purchase_unit_id", "net_unit_cost", "discount", "tax_rate", "tax", "total"
    ];
    
    protected static function newFactory()
    {
        return \Modules\Sale\Database\factories\SaleProductPurchaseFactory::new();
    }
}
