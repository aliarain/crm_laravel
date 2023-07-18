<?php

namespace Modules\Sale\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SaleUnit extends Model
{
    use HasFactory;

    protected $fillable =[

        "unit_code", "unit_name", "base_unit", "operator", "operation_value", "is_active"
    ];

    
    
    protected static function newFactory()
    {
        return \Modules\Sale\Database\factories\SaleUnitFactory::new();
    }
    public function product()
    {
    	return $this->hasMany('Modules\Sale\Entities\SaleProduct');
    	
    }
}
