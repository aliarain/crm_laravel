<?php

namespace Modules\Sale\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SaleVariant extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    
    
    protected static function newFactory()
    {
        return \Modules\Sale\Database\factories\SaleVariantFactory::new();
    }

    public function product()
    {
    	return $this->belongsToMany('Modules\Sale\Entities\SaleProduct', 'sale_product_variant')->withPivot('id', 'item_code', 'additional_cost', 'additional_price');
    }
}
