<?php

namespace App\Models\Award;

use App\Models\User;
use App\Models\Award\AwardType;
use App\Models\coreApp\Status\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Award extends Model
{
    use HasFactory;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function added()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function type()
    {
        return $this->belongsTo(AwardType::class, 'award_type_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function goal()
    {
        return $this->belongsTo(\App\Models\Performance\Goal::class);
    }

    public function Employee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    
}
