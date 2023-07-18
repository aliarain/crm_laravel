<?php

namespace App\Models\Visit;

use App\Models\Upload;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VisitImage extends Model
{
    use HasFactory;

    public function imageable()
    {
        return $this->morphTo();
    }

    public function file()
    {
        return $this->belongsTo(Upload::class, 'file_id');
    }
}
