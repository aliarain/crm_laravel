<?php

namespace Modules\Sale\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SaleBrand extends Model
{
    use HasFactory;

    protected $fillable =[

        "title", "image", "is_active"
    ];
    
    protected static function newFactory()
    {
        return \Modules\Sale\Database\factories\SaleBrandFactory::new();
    }
    public function product()
    {
    	return $this->hasMany('Modules\Sale\Entities\SaleProduct', 'brand_id', 'id');
    	
    }
}
