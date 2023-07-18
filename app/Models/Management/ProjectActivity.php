<?php

namespace App\Models\Management;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProjectActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'description',
        'project_id',
        'user_id',
    ];


    public static function CreateActivityLog($company_id, $project_id, $user_id, $description = null)
    {
        return new ProjectActivity([
            'company_id' => $company_id,
            'description' => $description,
            'project_id' => $project_id,
            'user_id' => $user_id,
        ]);
    }

     // user
     public function user()
     {
         return $this->belongsTo(User::class);
     } 
}
