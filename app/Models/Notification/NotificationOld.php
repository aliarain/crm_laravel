<?php

namespace App\Models\Notification;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationOld extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id', 'receiver_id', 'type', 'title', 'message', 'image_id', 'read_at'
    ];

      // sender name belongsTo
      public function sender(): \Illuminate\Database\Eloquent\Relations\BelongsTo
      {
          return $this->belongsTo(User::class, 'sender_id');
      }
      public function receiver(): \Illuminate\Database\Eloquent\Relations\BelongsTo
      {
          return $this->belongsTo(User::class, 'receiver_id');
      }
}
