<?php

namespace App\Models\Travel;

use App\Models\User;
use App\Models\Travel\TravelType;
use App\Models\coreApp\Status\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Travel extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function type()
    {
        return $this->belongsTo(TravelType::class, 'travel_type_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function goal()
    {
        return $this->belongsTo(\App\Models\Performance\Goal::class);
    }
}
