<?php

namespace App\Models\Management;

use App\Models\User;
use App\Models\coreApp\Status\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notes extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'project_id',
        'description',
        'user_id',
        'show_to_customer',
        'last_activity'
    ];

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
