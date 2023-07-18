<?php

namespace App\Models\coreApp\Traits\Relationship;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait UserRelationTrait
{
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}