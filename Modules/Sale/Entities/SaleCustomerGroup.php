<?php

namespace Modules\Sale\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SaleCustomerGroup extends Model
{
    use HasFactory;

    protected $fillable =[

        "name", "percentage", "is_active"
    ];
    
    protected static function newFactory()
    {
        return \Modules\Sale\Database\factories\SaleCustomerGroupFactory::new();
    }
}
