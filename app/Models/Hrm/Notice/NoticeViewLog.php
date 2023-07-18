<?php

namespace App\Models\Hrm\Notice;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoticeViewLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'user_id',
        'notice_id',
        'is_view',
    ];
}
