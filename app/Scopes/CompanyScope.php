<?php
  
namespace App\Scopes;
  
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Builder;
  
class CompanyScope implements Scope
{

    public function apply(Builder $builder, Model $model)
    {
        if (Auth::check()) {
            $builder->where($model->getTable().'.company_id', '=', auth()->user()->company->id);   
        }
    }
}