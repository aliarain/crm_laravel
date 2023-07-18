<?php

namespace App\Models\Payroll;

use App\Models\User;
use App\Models\Payroll\AdvanceType;
use App\Models\coreApp\Status\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdvanceSalary extends Model
{
    use HasFactory;

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

    public function returnPayment()
    {
        return $this->belongsTo(Status::class, 'return_status');
    }
    public function payment()
    {
        return $this->belongsTo(Status::class, 'pay');
    }

    public function employee()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    
    public function approve()
    {
        return $this->belongsTo(User::class,'approver_id');
    }

    public function advance_type()
    {
        return $this->belongsTo(AdvanceType::class);
    }

}
