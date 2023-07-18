<?php

namespace App\Models\Management;

use App\Models\User;
use App\Models\coreApp\Status\Status;
use Illuminate\Database\Eloquent\Model;
use App\Models\Management\DiscussionComment;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Discussion extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'project_id',
        'subject',
        'description',
        'user_id',
        'show_to_customer',
        'last_activity'
    ];

    public function comments(): HasMany
    {
        return $this->hasMany(DiscussionComment::class)->with('user','childComments');
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
