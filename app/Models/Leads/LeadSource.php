<?php

namespace App\Models\Leads;

use App\Models\Leads\Lead;
use App\Models\coreApp\Status\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LeadSource extends Model
{
    use HasFactory;


    public function leads()
    {
        return $this->hasMany(Lead::class);
    }
    public function status()
    {
        return $this->belongsTo(Status::class,'status_id','id');
    }
}
