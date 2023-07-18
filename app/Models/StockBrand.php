<?php

namespace App\Models;

use App\Models\User;
use App\Models\StockProduct;
use App\Models\coreApp\Status\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockBrand extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'name',
        'slug',
        'status_id',
        'author_info_id',
        'avatar_id',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_info_id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class, 'status_id');
    }
    public function products(): HasMany
    {
        return $this->hasMany(StockProduct::class, 'stock_brand_id');
    }
}
