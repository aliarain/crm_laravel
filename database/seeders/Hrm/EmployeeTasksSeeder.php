<?php

namespace Database\Seeders\Hrm;

use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use App\Models\Hrm\Task\EmployeeTask;

class EmployeeTasksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $users=User::get();
        $user_ids=$users->pluck('id')->toArray();
        foreach ($users as $key => $user) {
            $employee_task=new EmployeeTask();
            $employee_task->assigned_id=$user->id;
            $employee_task->created_by=$user_ids[array_rand($user_ids, 1)];
            $employee_task->due_date=date('Y-m-') . rand(1,28);
            $employee_task->title	 = $faker->sentence;
            $employee_task->description = $faker->text;
            $employee_task->save();
        }
    }
}
