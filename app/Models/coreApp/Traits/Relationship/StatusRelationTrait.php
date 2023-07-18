<?php

namespace App\Models\coreApp\Traits\Relationship;

use App\Models\coreApp\Status\Status;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait StatusRelationTrait
{
    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class, 'status_id', 'id');
    }
}