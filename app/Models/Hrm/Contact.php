<?php

namespace App\Models\Hrm;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    //Get attribute
    public function getContactForAttribute($key)
    {
        $value = parent::getAttribute($key);
        if ($key == 1) {
            return 'Support';
        } elseif ($key == 0) {
            return 'Query';
        }
        return $key;
    }
    public function getContactStatusAttribute($key)
    {
        $value = parent::getAttribute($key);
        if ($value == 0) {
            return 'Unread';
        }
        if ($value == 1) {
            return 'Read';
        }
        return $value;
    }

    public function getIsActiveAttribute()
    {
        if ($this->status == 1) {
            return '<small class="badge badge-success">'._trans('common.Read').'</small>';
        } else {
            return '<small class="badge badge-danger">'._trans('common.Unread').'</small>';
        }

    }
}
