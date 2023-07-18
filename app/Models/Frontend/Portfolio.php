<?php

namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\coreApp\Traits\Relationship\StatusRelationTrait;
use App\Models\Upload;

class Portfolio extends Model
{
    use HasFactory,StatusRelationTrait;

    public function image()
    {
        return $this->belongsTo(Upload::class, 'attachment', 'id');
    }
}
