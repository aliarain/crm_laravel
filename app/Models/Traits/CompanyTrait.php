<?php
namespace App\Models\Traits;

use App\Scopes\CompanyScope;
use App\Models\Company\Company;
use Illuminate\Support\Facades\Auth;

trait CompanyTrait{
    
    protected static function bootCompanyTrait()
    {
        static::addGlobalScope(new CompanyScope);
       if (Auth::check()) {
            static::creating(function($model){
                $model->company_id = auth()->user()->company->id;
            });
       } 
    }

    public function company(){
        return $this->belongsTo(Company::class,'company_id','id');
    }
}