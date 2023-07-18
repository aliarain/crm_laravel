<?php

namespace Modules\Sale\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SaleTransfer extends Model
{
    use HasFactory;

    protected $fillable =[

        "reference_no", "user_id", "status", "from_warehouse_id", "to_warehouse_id", "item", "total_qty", "total_tax", "total_cost", "shipping_cost", "grand_total", "document", "note", "created_at"
    ];
    
    protected static function newFactory()
    {
        return \Modules\Sale\Database\factories\SaleTransferFactory::new();
    }
    public function user()
    {
    	return $this->belongsTo('App\Models\User');
    }

    public function fromWarehouse()
    {
        return $this->belongsTo('Modules\Sale\Entities\SaleWarehouse', 'from_warehouse_id');
    }
    public function toWarehouse()
    {
        return $this->belongsTo('Modules\Sale\Entities\SaleWarehouse', 'to_warehouse_id');
    }
}
