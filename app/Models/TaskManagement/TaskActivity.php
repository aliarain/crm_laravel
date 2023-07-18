<?php

namespace App\Models\TaskManagement;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TaskActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'description',
        'task_id',
        'user_id',
    ];


    public static function CreateActivityLog($company_id, $task_id, $user_id, $description = null)
    {
        return new TaskActivity([
            'company_id' => $company_id,
            'description' => $description,
            'task_id' => $task_id,
            'user_id' => $user_id,
        ]);
    }

     // user
     public function user()
     {
         return $this->belongsTo(User::class);
     } 
}
