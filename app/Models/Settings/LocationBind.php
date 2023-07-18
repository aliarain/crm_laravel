<?php

namespace App\Models\Settings;

use App\Models\coreApp\Status\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocationBind extends Model
{
    use HasFactory;

    
    public function status()
    {
        return $this->belongsTo(Status::class);
    }
}
