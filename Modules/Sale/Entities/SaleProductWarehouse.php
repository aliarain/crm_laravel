<?php

namespace Modules\Sale\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SaleProductWarehouse extends Model
{
    use HasFactory;

    protected $fillable =[

        "product_id", "product_batch_id", "varinat_id", "imei_number", "warehouse_id", "qty", "price"
    ];

    
    
    protected static function newFactory()
    {
        return \Modules\Sale\Database\factories\SaleProductWarehouseFactory::new();
    }

    public function scopeFindProductWithVariant($query, $product_id, $variant_id, $warehouse_id)
    {
    	return $query->where([
            ['product_id', $product_id],
            ['variant_id', $variant_id],
            ['warehouse_id', $warehouse_id]
        ]);
    }

    public function scopeFindProductWithoutVariant($query, $product_id, $warehouse_id)
    {
    	return $query->where([
            ['product_id', $product_id],
            ['warehouse_id', $warehouse_id]
        ]);
    }


    // warehouse 
    public function warehouse()
    {
    	return $this->belongsTo('Modules\Sale\Entities\SaleWarehouse');
    }

    // SaleProductVariant 
    public function productVariant()
    {
    	return $this->belongsTo('Modules\Sale\Entities\SaleProductVariant');
    }
}
