<?php

namespace App\Models\Hrm\Appoinment;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AppoinmentParticipant extends Model
{
    use HasFactory;
    public function participantInfo():BelongsTo
    {
        return $this->belongsTo(User::class, 'participant_id');
    }
}
