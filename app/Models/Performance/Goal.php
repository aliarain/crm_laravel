<?php

namespace App\Models\Performance;

use App\Models\Performance\GoalType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Goal extends Model
{
    use HasFactory;

    public function goalType()
    {
        return $this->belongsTo(GoalType::class);
    }
}
