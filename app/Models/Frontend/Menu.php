<?php

namespace App\Models\Frontend;

use App\Models\Content\AllContent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\coreApp\Traits\Relationship\StatusRelationTrait;

class Menu extends Model
{

    use HasFactory,StatusRelationTrait;


    public function page()
    {
        return $this->belongsTo(AllContent::class, 'all_content_id', 'id');
    }
}
