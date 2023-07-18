<?php

namespace Modules\Sale\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SaleCategory extends Model
{
    use HasFactory;

    protected $fillable =[

        "name", 'image', "parent_id", "is_active"
    ];
    
    protected static function newFactory()
    {
        return \Modules\Sale\Database\factories\SaleCategoryFactory::new();
    }
    
    public function product()
    {
    	return $this->hasMany('Modules\Sale\Entities\SaleProduct', 'category_id', 'id');
    }

    public function parent()
    {
    	return $this->belongsTo('Modules\Sale\Entities\SaleCategory', 'parent_id', 'id');
    }
}
