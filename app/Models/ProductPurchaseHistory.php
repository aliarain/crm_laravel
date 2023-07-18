<?php

namespace App\Models;

use App\Models\ProductUnit;
use App\Models\StockProduct;
use App\Models\ProductPurchase;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductPurchaseHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'product_purchase_id',
        'batch_no',
        'product_unit_id',
        'quantity',
        'price',
        'discount',
        'total',
    ];

    public function productPurchase()
    {
        return $this->belongsTo(ProductPurchase::class, 'product_purchase_id');
    }
    public function product()
    {
        return $this->belongsTo(StockProduct::class, 'stock_product_id');
    }
    public function productUnit()
    {
        return $this->belongsTo(ProductUnit::class, 'product_unit_id');
    }

}
