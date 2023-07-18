<?php

namespace App\Models\Performance;

use App\Models\Performance\Goal;
use App\Models\coreApp\Status\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GoalType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'company_id',
    ];

    public function goals()
    {
        return $this->hasMany(Goal::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }
}
