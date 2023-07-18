<?php

namespace App\Models;

use App\Models\User;
use App\Models\StockBrand;
use App\Models\StockHistory;
use App\Models\StockCategory;
use App\Models\Company\Company;
use App\Models\coreApp\Status\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'serial',
        'name',
        'status_id',
        'author_id',
        'stock_brand_id',
        'stock_category_id',
        'avatar_id',
        'tags',
        'description',
        'total_quantity',
        'published',
    ];

    public function brand(): BelongsTo
    {
        return $this->belongsTo(StockBrand::class, 'stock_brand_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(StockCategory::class, 'stock_category_id');
    }
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }
    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class, 'status_id');
    }
    public function stockHistory()
    {
        return $this->hasMany(StockHistory::class, 'stock_product_id');
    }
    public function productPrice()
    {
        return $this->belongsTo(StockHistory::class, 'stock_product_id');
    }

}
