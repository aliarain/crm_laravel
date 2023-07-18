<?php

namespace App\Models;

use App\Models\Company\Company;
use App\Models\Management\Client;
use App\Models\StockProduct;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockSale extends Model
{
    use HasFactory;
    protected $fillable = [
        'company_id',
        'client_id',
        'stock_product_id',
        'stock_payment_history_id',
        'invoice',
        'date',
        'status_id',
        'payment_status_id',
        'created_by',
        'updated_by',
        'deleted_by',
        'price',
        'discount',
        'tax',
        'total'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function product()
    {
        return $this->belongsTo(StockProduct::class, 'stock_product_id');
    }

}
