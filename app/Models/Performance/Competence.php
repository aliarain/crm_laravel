<?php

namespace App\Models\Performance;

use App\Models\coreApp\Status\Status;
use Illuminate\Database\Eloquent\Model;
use App\Models\Performance\CompetenceType;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Competence extends Model
{
    use HasFactory;

    public function competenceType()
    {
        return $this->belongsTo(CompetenceType::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }
}
