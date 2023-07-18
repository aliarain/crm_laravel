<?php

namespace Modules\Sale\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        "name","slug", "code", "type", "barcode_symbology", "brand_id", "category_id", "unit_id", "purchase_unit_id", "sale_unit_id", "cost", "price", "qty", "alert_quantity", "daily_sale_objective", "promotion", "promotion_price", "starting_date", "last_date", "tax_id", "tax_method", "image", "file", "is_embeded", "is_batch", "is_variant", "is_diffPrice", "is_imei", "featured", "product_list", "variant_list", "qty_list", "price_list", "product_details", "variant_option", "variant_value", "is_active",
    ];

    protected static function newFactory()
    {
        return \Modules\Sale\Database\factories\SaleProductFactory::new ();
    }

    public function category()
    {
        return $this->belongsTo('Modules\Sale\Entities\SaleCategory');
    }

    public function brand()
    {
        return $this->belongsTo('Modules\Sale\Entities\SaleBrand', 'brand_id', 'id');
    }
    public function tax()
    {
        return $this->belongsTo('Modules\Sale\Entities\SaleTax', 'tax_id', 'id');
    }

    public function unit()
    {
        return $this->belongsTo('Modules\Sale\Entities\SaleUnit', 'unit_id', 'id');
    }

    public function variant()
    {
        return $this->belongsToMany('Modules\Sale\Entities\SaleVariant', 'sale_product_variants', 'product_id', 'variant_id');
    }

    public function scopeActiveStandard($query)
    {
        return $query->where([
            ['is_active', true],
            ['type', 'standard'],
        ]);
    }

    public function scopeActiveFeatured($query)
    {
        return $query->where([
            ['is_active', true],
            ['featured', 1],
        ]);
    }

}
