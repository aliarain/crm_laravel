<?php

namespace App\Models\Award;

use App\Models\Award\Award;
use App\Models\coreApp\Status\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AwardType extends Model
{
    use HasFactory;

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }

    //awards 
    public function awards()
    {
        return $this->hasMany(Award::class);
    }
}
