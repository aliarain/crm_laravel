<?php

namespace App\Models\Leads;

use App\Models\User;
use App\Models\LeadType;
use App\Models\Leads\LeadSource;
use App\Models\Leads\LeadStatus;
use App\Models\Hrm\Country\Country;
use App\Models\coreApp\Status\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Lead extends Model
{
    use HasFactory;




    protected  $fillable = [
        'name',
        'company',
        'title',
        'description',
        'country',
        'state',
        'city',
        'zip',
        'address',
        'email',
        'phone',
        'website',
        'date',
        'next_follow_up',
        'company_id',
        'status_id',
        'lead_type_id',
        'lead_source_id',
        'lead_status_id',
        'created_by',
        'attachments',
        'emails',
        'calls',
        'activities',
        'notes',
        'tasks',
        'reminders',
        'tags',
        'deals',
    ];

    public function status()
    {
        return $this->belongsTo(Status::class,'status_id','id');
    }
    public function source()
    {
        return $this->belongsTo(LeadSource::class,'status_id','id');
    }

    // LeadType 
    public function type()
    {
        return $this->belongsTo(LeadType::class,'lead_type_id','id');
    }

    // LeadStatus
    public function lead_status()
    {
        return $this->belongsTo(LeadStatus::class,'lead_status_id','id');
    }

    // LeadStatus
    public function author()
    {
        return $this->belongsTo(User::class,'created_by','id');
    }

    // countryInfo 
    public function countryInfo()
    {
        return $this->belongsTo(Country::class,'country','id');
    }
    // function for activities 
    public static function CreateActivity($lead, $request, $message = null)
    {

        $input = [ 
            'index' => @$request->index??100,
            'date' => dateFormatInPlainText(date('Y-m-d h:i:sa')),
            'status' => 'New',
            'author' => authorInfo(),
            'message' => $message,
        ];

        $list = json_decode($lead->activities, true) ?? [];
        array_push($list, $input);

        $lead->activities = json_encode($list); 
        $lead->save();
    }
}
