<?php

namespace App\Models;

use App\Models\coreApp\Status\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadType extends Model
{
    use HasFactory;

    public function status()
    {
        return $this->belongsTo(Status::class,'status_id','id');
    }
}
