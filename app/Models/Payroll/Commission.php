<?php

namespace App\Models\Payroll;

use App\Models\User;
use App\Models\Traits\CompanyTrait;
use App\Models\coreApp\Status\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Commission extends Model
{
    use HasFactory,CompanyTrait;

    protected $fillable=[
        'company_id',
        'name',
        'type'
    ];
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }
    public function status()
    {
        return $this->belongsTo(Status::class);
    }
}
