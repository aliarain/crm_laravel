<?php

namespace Modules\Sale\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SaleGiftCard extends Model
{
    use HasFactory;

    protected $fillable =[
        "card_no", "amount", "expense", "customer_id", "user_id", "expired_date", "created_by", "is_active"  
    ];
    
    protected static function newFactory()
    {
        return \Modules\Sale\Database\factories\SaleGiftCardFactory::new();
    }

    public function user()
    {
    	return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
    public function createdBy()
    {
    	return $this->belongsTo('App\Models\User', 'created_by', 'id');
    }

    public function customer()
    {
    	return $this->belongsTo('Modules\Sale\Entities\SaleCustomer');
    }
}
