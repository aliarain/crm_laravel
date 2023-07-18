<?php

namespace Modules\Sale\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SaleExpenseCategory extends Model
{
    use HasFactory;

    protected $fillable =[
        "code", "name", "is_active"  
    ];
    
    protected static function newFactory()
    {
        return \Modules\Sale\Database\factories\SaleExpenseCategoryFactory::new();
    }
    

    public function expense() {
    	return $this->hasMany('Modules\Sale\Entities\SaleExpense');
    }
}
