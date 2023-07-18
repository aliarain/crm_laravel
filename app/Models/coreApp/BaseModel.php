<?php

namespace App\Models\coreApp;

use App\Filters\BaseFilter;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class BaseModel extends Model
{
    use LogsActivity;



    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }
}
