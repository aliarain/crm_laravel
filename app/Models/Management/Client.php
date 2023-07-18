<?php

namespace App\Models\Management;

use App\Models\Visit\VisitImage;
use App\Models\Management\Project;
use Spatie\Activitylog\LogOptions;
use App\Models\Hrm\Country\Country;
use App\Models\Traits\CompanyTrait;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\coreApp\Traits\Relationship\StatusRelationTrait;

class Client extends Model
{
    use HasFactory, StatusRelationTrait, SoftDeletes, CompanyTrait;

    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->setDescriptionForEvent(fn(string $eventName) => "{$eventName} a Client");
    }

    public function getModelProperties(): array
    {
        return array_merge($this->attributesToArray(), [
            'ip_address' => Request::ip(),
        ]);
    }

    public function avater()
    {
        return $this->morphOne(VisitImage::class, 'imageable');
    }

    public function countryInfo()
    {
        return $this->belongsTo(Country::class, 'country');
    }
    public function projects()
    {
        return $this->hasMany(Project::class, 'client_id')->orderBy('id', 'desc');
    }
    public function projects_short_list()
    {
        return $this->hasMany(Project::class, 'client_id')->orderBy('id', 'desc')->limit(5);
    }
}
