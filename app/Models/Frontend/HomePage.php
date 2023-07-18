<?php

namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomePage extends Model
{
    protected $casts = [
        'contents' => 'json'
    ];
    use HasFactory;
}
