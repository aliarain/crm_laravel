<?php

namespace App\Models\TaskManagement;

use App\Models\User;
use App\Models\coreApp\Status\Status;
use Illuminate\Database\Eloquent\Model;
use App\Models\Management\DiscussionLike;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\TaskManagement\TaskDiscussionComment;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TaskDiscussion extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'task_id',
        'subject',
        'description',
        'user_id',
        'show_to_customer',
        'last_activity'
    ];

    public function comments(): HasMany
    {
        return $this->hasMany(TaskDiscussionComment::class)->with('childComments','user');
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
    public function likes(): HasMany
    {
        return $this->hasMany(DiscussionLike::class, 'discussion_id');
    }
}
