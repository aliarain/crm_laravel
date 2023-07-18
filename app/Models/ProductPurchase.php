<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPurchase extends Model
{
    use HasFactory;

    // `company_id`, `stock_payment_history_id`, `client_id`, `invoice_no`, `date`, `batch_no`, `total`, `tax`, `grand_total`
    protected $fillable = [
        'company_id',
        'stock_payment_history_id',
        'client_id',
        'invoice_no',
        'date',
        'batch_no',
        'total',
        'tax',
        'grand_total',
    ];
    
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
