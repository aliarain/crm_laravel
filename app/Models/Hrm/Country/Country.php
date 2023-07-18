<?php

namespace App\Models\Hrm\Country;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;
    protected $fillable = ['country_code', 'name', 'time_zone', 'currency_code', 'currency_symbol', 'currency_name', 'currency_symbol_placement'];

}
