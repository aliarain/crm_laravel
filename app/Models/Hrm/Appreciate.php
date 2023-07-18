<?php

namespace App\Models\Hrm;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Appreciate extends Model
{
    use HasFactory;

    public function appreciateFrom()
    {
        return $this->belongsTo(User::class, 'appreciate_by','id');
    }
}
