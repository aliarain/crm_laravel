<?php

namespace App\Models\Management;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscussionLike extends Model
{
    use HasFactory;

    public function discussion()
    {
        return $this->belongsTo(Discussion::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
