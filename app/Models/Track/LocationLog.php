<?php

namespace App\Models\Track;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LocationLog extends Model
{
    use HasFactory;

    protected $fillable = ['company_id', 'user_id', 'date', 'location', 'latitude', 'longitude','speed','heading','city','address','countryCode','country','distance'];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
