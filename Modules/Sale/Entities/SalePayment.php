<?php

namespace Modules\Sale\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SalePayment extends Model
{
    use HasFactory;

    protected $fillable =[
        "purchase_id", "user_id", "sale_id", "cash_register_id", "account_id", "payment_reference", "amount", "used_points", "change", "paying_method", "payment_note"
    ];
    
    protected static function newFactory()
    {
        return \Modules\Sale\Database\factories\SalePaymentFactory::new();
    }

    // account 
    public function account()
    {
        return $this->belongsTo('Modules\Sale\Entities\SaleAccount');
    }
    
}
