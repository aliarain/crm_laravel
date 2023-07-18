<?php

namespace App\Models\Expenses;

use App\Models\coreApp\Status\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentHistory extends Model
{
    use HasFactory;

    public function paymentStatus()
    {
        return $this->belongsTo(Status::class, 'payment_status_id');
    }

}
