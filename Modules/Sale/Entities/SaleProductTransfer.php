<?php

namespace Modules\Sale\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Sale\Entities\SaleTransfer;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SaleProductTransfer extends Model
{
    use HasFactory;

    protected $fillable =[

        "transfer_id", "product_id", "product_batch_id", "variant_id", "imei_number", "qty", "purchase_unit_id", "net_unit_cost", "tax_rate", "tax", "total"
    ];
    
    protected static function newFactory()
    {
        return \Modules\Sale\Database\factories\SaleProductTransferFactory::new();
    }

    // sale transfer
    public function saleTransfer()
    {
        return $this->belongsTo(SaleTransfer::class);
    } 
}
