<?php

namespace App\Models\Hrm\Appoinment;

use App\Models\User;
use App\Models\Visit\VisitImage;
use App\Models\Traits\CompanyTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Hrm\Appoinment\AppoinmentParticipant;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\coreApp\Traits\Relationship\StatusRelationTrait;

class Appoinment extends Model
{
    use HasFactory,CompanyTrait, StatusRelationTrait;

    public function visitImages()
    {
        return $this->morphMany(VisitImage::class, 'imageable');
    }
    public function createdBy():BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function appoinmentWith():BelongsTo
    {
        return $this->belongsTo(User::class, 'appoinment_with');
    }
    public function participants():HasMany
    {
        return $this->hasMany(AppoinmentParticipant::class);
    }
    public function otherParticipant()
    {
        return $this->belongsTo(AppoinmentParticipant::class,'appoinment_with','participant_id');
    }

    public static function boot() {
        parent::boot();

        static::deleting(function($appoinment) { 
             $appoinment->participants()->delete();
        });
    }
}
