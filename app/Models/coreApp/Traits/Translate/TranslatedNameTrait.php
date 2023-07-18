<?php


namespace App\Models\coreApp\Traits\Translate;


trait TranslatedNameTrait
{
    public function getTranslatedNameAttribute()
    {
        return trans("default.{$this->attributes['name']}");
    }
}
