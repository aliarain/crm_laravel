<?php

namespace App\Models\coreApp\Status;

use App\Models\coreApp\BaseModel;
use App\Models\coreApp\Traits\Translate\TranslatedNameTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Status extends BaseModel
{
    use HasFactory, TranslatedNameTrait;

    protected $fillable = ['name', 'type', 'class'];

    protected $appends = ['translated_name'];


    public static function findByNameAndType($name, $type = 'user')
    {
        return self::query()
            ->where('name', $name)
            ->where('type', $type)
            ->first();
    }
}
