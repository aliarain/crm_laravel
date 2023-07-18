<?php

namespace App\Models\Accounts;

use App\Models\coreApp\Status\Status;
use App\Models\coreApp\Traits\Relationship\StatusRelationTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory, SoftDeletes,StatusRelationTrait;

      // status query belongsTo relationship with status table
      public function status(): BelongsTo
      {
          return $this->belongsTo(Status::class);
      }
}
