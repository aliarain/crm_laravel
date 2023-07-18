<?php

namespace App\Models\Payroll;

use App\Models\Payroll\Commission;
use App\Models\coreApp\Status\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SalarySetupDetails extends Model
{
    use HasFactory;

    function commission()
    {
        return $this->belongsTo(Commission::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }
}
