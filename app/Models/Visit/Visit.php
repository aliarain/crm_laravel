<?php

namespace App\Models\Visit;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Visit\VisitNote;
use App\Models\Visit\VisitImage;
use App\Models\Traits\CompanyTrait;
use App\Models\Visit\VisitSchedule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Visit extends Model
{
    use HasFactory,CompanyTrait;
    protected $fillable=['date','title','description','user_id'];

    public function visitImages()
    {
        return $this->morphMany(VisitImage::class, 'imageable');
    }
    public function notes(): HasMany
    {
        return $this->hasMany(VisitNote::class)->orderBy('id', 'desc');
    }
    public function schedules(): HasMany
    {
        return $this->hasMany(VisitSchedule::class)->orderBy('id', 'desc');
    }
    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getNextStatus($visit)
    {
        $status = $visit->status;
        if ($visit->date == date('Y-m-d')) {
            if ($status == 'created') {
                return [
                     'status' => 'started',
                     'status_text' => 'Start',
                   ];
             } elseif ($status == 'started') {
                 return [
                     'status' => 'reached',
                     'status_text' => 'Reached',
                   ];
             } elseif ($status == 'reached') {
                 return [
                     'status' => 'completed',
                     'status_text' => 'End',
                   ];
             }elseif ($status == 'completed') {
                 return [
                     'status' => '',
                     'status_text' => '',
                   ];
             }else{
                 return [
                     'status' => '',
                     'status_text' => '',
                   ];
             }
        } else {
            return [
                'status' => '',
                'status_text' => '',
              ];
        }
        
 
    }
    public function startReached($visit)
    {
        if ($visit->schedules->count() > 0) {
        
        
            $started= $visit->schedules->where('started_at','!=',null)->pluck('started_at');
            $reached= $visit->schedules->where('reached_at','!=',null)->pluck('reached_at');


            $startTime = Carbon::parse($started[0]);
            $endTime = Carbon::parse($reached[count($reached)-1]);
            $duration =  $startTime->diff($endTime)->format('%H:%I')." h";
            $startTime = date('h:i a', strtotime($started[0]));
            $endTime = date('h:i a', strtotime($reached[count($reached)-1]));
            $visit_from_to=[
                'started'=>strtoupper($startTime),
                'reached'=>strtoupper($endTime),
                'duration'=>$duration,
            ];  
        } else {
            $visit_from_to=[];
        }
        return $visit_from_to;
    }
}
