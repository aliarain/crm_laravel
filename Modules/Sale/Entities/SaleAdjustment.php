<?php

namespace Modules\Sale\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SaleAdjustment extends Model
{
    use HasFactory;

    protected $fillable =[
        "reference_no", "warehouse_id", "document", "total_qty", "item", "note"   
    ];
    
    protected static function newFactory()
    {
        return \Modules\Sale\Database\factories\SaleAdjustmentFactory::new();
    }
}
