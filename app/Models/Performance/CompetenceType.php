<?php

namespace App\Models\Performance;

use App\Models\coreApp\Status\Status;
use App\Models\Performance\Competence;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CompetenceType extends Model
{
    use HasFactory;

    public function competencies()
    {
        return $this->hasMany(Competence::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }
}
