<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'id', 'receiver_number', 'message'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'receiver_number', 'phone');
    }
}
