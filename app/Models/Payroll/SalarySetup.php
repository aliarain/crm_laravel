<?php

namespace App\Models\Payroll;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SalarySetup extends Model
{
    use HasFactory;

    public function employee()
    {
        return $this->belongsTo(User::class);
    }

    public function salarySetupAdditionDetails()
    {
        return $this->hasMany(SalarySetupDetails::class)->with('commission')->whereHas('commission', function ($query) {
            $query->where('type', 1);
        });
    }

    public function salarySetupDeductionDetails()
    {
        return $this->hasMany(SalarySetupDetails::class)->with('commission')->whereHas('commission', function ($query) {
            $query->where('type', 2);
        });
    }

    public function salarySetupDetails()
    {
        return $this->hasMany(SalarySetupDetails::class);
    }
}
