<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use HasFactory;

    public function getIsRtlAttribute()
    {
        if ($this->rtl == 1) {
            return '<i class="fa fa-check text-success"></i>';
        } else {
            return '<i class="fa fa-times text-danger"></i>';
        }

    }
    public function getIsActiveAttribute()
    {
        if ($this->status == 1) {
            return '<small class="badge badge-success">'._trans('common.Active').'</small>';
        } else {
            return '<small class="badge badge-danger">'._trans('common.Inactive').'</small>';
        }

    }
}
