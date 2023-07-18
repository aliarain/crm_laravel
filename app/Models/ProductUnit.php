<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\coreApp\Status\Status;
use Illuminate\Database\Eloquent\Model;

class ProductUnit extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'name',
        'attachment_file_id',
        'status_id',
    ];
    protected $casts = [
        'attachment_file_id' => 'integer',
        'status_id' => 'integer',
    ];


    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }


    
}
