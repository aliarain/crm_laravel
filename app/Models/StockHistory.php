<?php

namespace App\Models;

use App\Models\ProductUnit;
use App\Models\StockProduct;
use App\Models\ProductPurchase;
use Illuminate\Database\Eloquent\Model;
use App\Models\coreApp\Status\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockHistory extends Model
{
    use HasFactory;
    protected $fillable = [
        'company_id',
        'stock_product_id',
        'product_purchase_id',
        'invoice_no',
        'batch_no',
        'expiry_date',
        'quantity',
        'product_unit_id',
        'unit_price',
        'total',
        'discount_type',
        'discount',
        'grand_total',
        'status_id',
    ];

    public function product()
    {
        return $this->belongsTo(StockProduct::class, 'stock_product_id');
    }
    public function productPurchase()
    {
        return $this->belongsTo(ProductPurchase::class, 'product_purchase_id');
    }
    public function productUnit()
    {
        return $this->belongsTo(ProductUnit::class, 'product_unit_id');
    }
    
    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }
}
