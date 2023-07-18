<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetaInformation extends Model
{
    use HasFactory;


    protected $fillable = ['type', 'meta_title', 'meta_image', 'meta_description', 'meta_keywords', 'created_by', 'updated_by'];

    protected static $logAttributes = ['type', 'meta_title', 'meta_image', 'meta_description', 'meta_keywords', 'created_by', 'updated_by'];


}
