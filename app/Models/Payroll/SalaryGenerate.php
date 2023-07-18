<?php

namespace App\Models\Payroll;

use App\Models\User;
use App\Models\coreApp\Status\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SalaryGenerate extends Model
{
    use HasFactory;

    protected $casts = [
        'advance_details' => 'json',
        'allowance_details' => 'json',
        'deduction_details' => 'json',
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

    public function employee()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    
    public function approve()
    {
        return $this->belongsTo(User::class,'approver_id');
    }
}
