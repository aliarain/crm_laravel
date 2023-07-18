<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';

    public function receiver()
    {
        return $this->belongsTo(User::class, 'notifiable_id');
    }
    public static function sender($sender_id)
    {
        return User::find($sender_id);
    }
}
