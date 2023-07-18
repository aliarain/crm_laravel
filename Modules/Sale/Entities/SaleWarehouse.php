<?php

namespace Modules\Sale\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SaleWarehouse extends Model
{
    use HasFactory;

    protected $fillable = ["name", "phone", "email", "address", "is_active"];
    
    protected static function newFactory()
    {
        return \Modules\Sale\Database\factories\SaleWarehouseFactory::new();
    }
}
