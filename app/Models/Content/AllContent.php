<?php

namespace App\Models\Content;

use App\Models\coreApp\Status\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AllContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'title',
        'slug',
        'content',
        'meta_title',
        'meta_description',
        'keywords',
        'meta_image',
        'created_by',
        'updated_by',
    ];


    public function status()
    {
        return $this->belongsTo(Status::class);
    }
}
