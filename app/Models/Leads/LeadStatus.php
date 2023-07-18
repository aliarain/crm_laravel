<?php

namespace App\Models\Leads;

use App\Models\coreApp\Status\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadStatus extends Model
{
    use HasFactory;

    public function status()
    {
        return $this->belongsTo(Status::class,'status_id','id');
    }
}
