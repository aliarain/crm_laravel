<?php

namespace App\Models\TaskManagement;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TaskFileComment extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    function childComments()
    {
        return $this->hasMany(TaskFileComment::class, 'comment_id')->with('user');
    }
}
