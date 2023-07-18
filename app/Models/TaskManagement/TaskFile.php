<?php

namespace App\Models\TaskManagement;

use App\Models\User;
use App\Models\coreApp\Status\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TaskFile extends Model
{
    use HasFactory;

    public function comments(): HasMany
    {
        return $this->hasMany(TaskFileComment::class)->with('user:id,avatar_id,name','childComments');
    }

    //show_to_customer
    public function visitCustomer()
    {
        return $this->belongsTo(Status::class, 'show_to_customer');
    }

    // user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
