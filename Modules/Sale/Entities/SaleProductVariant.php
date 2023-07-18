<?php

namespace Modules\Sale\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SaleProductVariant extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'variant_id', 'position', 'item_code', 'additional_cost', 'additional_price', 'qty'];
    
    protected static function newFactory()
    {
        return \Modules\Sale\Database\factories\SaleProductVariantFactory::new();
    }

    public function scopeFindExactProduct($query, $product_id, $variant_id)
    {
    	return $query->where([
            ['product_id', $product_id],
            ['variant_id', $variant_id]
        ]);
    }

    public function scopeFindExactProductWithCode($query, $product_id, $item_code)
    {
    	return $query->where([
            ['product_id', $product_id],
            ['item_code', $item_code],
        ]);
    }

    // variant 
    public function variant()
    {
        return $this->belongsTo('Modules\Sale\Entities\SaleVariant');
    }
}
