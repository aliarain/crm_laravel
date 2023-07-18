<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Company\Company;
use Illuminate\Database\Seeder;
use App\Models\Hrm\Notice\Notice;
use App\Models\Hrm\Notice\NoticeViewLog;
use App\Models\Hrm\Department\Department;

use Faker\Factory as Faker;
class NoticeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $fake = Faker::create();
    
        $users = User::all();
        $departments = Department::all();
        foreach ($departments as $department) {

 


            for ($i = 0; $i < 10; $i++) {
                $notice = new Notice();
                $notice->created_by = 1;
                $notice->company_id = $department->company_id; 
                $notice->subject = $fake->sentence;
                $notice->description = $fake->paragraph;
                $notice->department_id = $department->id;
                $notice->date = date('Y-m-d');
                $notice->save(); 
            }
        }
        foreach ($users as $user) {
            $notices = Notice::where('company_id', $user->company_id)
                ->where('department_id', $user->department_id)->get();
            foreach ($notices as $notice) {
                NoticeViewLog::create([
                    'company_id' => $user->company_id,
                    'user_id' => $user->id,
                    'notice_id' => $notice->id,
                    'is_view' => 0,
                ]);
            }
        }
    }
}
