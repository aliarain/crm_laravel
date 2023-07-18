<?php

namespace App\Models\Traits;

use Illuminate\Support\Str;

trait SlugTrait
{
    public static function boot()
    {
        parent::boot();
        if (!app()->runningInConsole()) {
            static::creating(function ($model) {
                return $model->fill([
                    'slug' => Str::slug($model->name, '-')
                ]);
            });
        }
    }
}
