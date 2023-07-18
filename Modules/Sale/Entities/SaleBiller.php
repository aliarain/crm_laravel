<?php

namespace Modules\Sale\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SaleBiller extends Model
{
    use HasFactory;

    protected $fillable =[
        "name", "image", "company_name", "vat_number",
        "email", "phone_number", "address", "city",
        "state", "postal_code", "country", "is_active"
    ];
    
    protected static function newFactory()
    {
        return \Modules\Sale\Database\factories\SaleBillerFactory::new();
    }

    public function sale()
    {
    	return $this->hasMany('Modules\Sale\Entities\Sale');
    }
}
