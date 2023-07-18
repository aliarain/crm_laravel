<?php

namespace App\Models\Management;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DiscussionComment extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    function childComments()
    {
        return $this->hasMany(DiscussionComment::class, 'comment_id')->with('user');
    }
}
