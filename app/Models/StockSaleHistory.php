<?php

namespace App\Models;

use App\Models\Company\Company;
use App\Models\StockProduct;
use App\Models\StockSale;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockSaleHistory extends Model
{
    use HasFactory;
    protected $fillable = [
        'company_id',
        'sotck_sale_id',
        'stock_product_id',
        'quantity',
        'price',
        'discount',
        'tax',
        'total',
    ];
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    protected $casts = [
        'company_id' => 'integer',
        'sotck_sale_id' => 'integer',
        'stock_product_id' => 'integer',
        'quantity' => 'integer',
        'price' => 'float',
        'discount' => 'float',
        'tax' => 'float',
        'total' => 'float',
    ];
    public function product()
    {
        return $this->belongsTo(StockProduct::class, 'stock_product_id');
    }
    public function stockSale()
    {
        return $this->belongsTo(StockSale::class, 'sotck_sale_id');
    }
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

}
