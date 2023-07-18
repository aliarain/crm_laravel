<?php

namespace App\Models\Notification;

use App\Models\Upload;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NotificationType extends Model
{
    use HasFactory;


    public function uploads(): HasMany
    {
        return $this->hasMany(Upload::class);
    }
}
