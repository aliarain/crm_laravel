<?php

namespace App\Models;

use App\Models\User;
use App\Models\Slider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Upload extends Model
{
    use HasFactory;


    protected $fillable = ['file_name', 'img_path'];

    public function sliders(): HasOne
    {
        return $this->hasOne(Slider::class);
    }

    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pages(): HasOne
    {
        return $this->hasOne(Page::class);
    }

    public function advertise(): HasOne
    {
        return $this->hasOne(Advertise::class);
    }
}
