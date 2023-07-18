<?php

namespace App\Models\coreApp\User;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    protected $fillable = ['user_id', 'gender', 'phone_number', 'address', 'date_of_birth'];

}
