<?php

namespace App\Models\Hrm\Notice;

use Illuminate\Database\Eloquent\Model;
use App\Models\Hrm\Department\Department;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NoticeDepartment extends Model
{
    use HasFactory;
    protected $fillable=['noticeable_id','noticeable_type','department_id'];

    public function noticeable()
    {
        return $this->morphTo();
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

  

}
