<?php

namespace Database\Seeders;

use App\Models\Hrm\Attendance\DutySchedule;
use App\Models\Hrm\Department\Department;
use App\Models\Hrm\Designation\Designation;
use App\Models\Hrm\Leave\AssignLeave;
use App\Models\Hrm\Leave\LeaveType;
use App\Models\Role\Role;
use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $departments = ['IT', 'HR', 'Business Development', 'Production Manager', 'Call Center','Business Analysis'];

        foreach ($departments as $department) {
            Department::create([
                'title' => $department,
                'status_id' => 1,
                'company_id' => 2
            ]);
        }

        $designations = ['Web Developer', 'HR Manager', 'Business Analyst', 'Inventory Manager', 'Call Center','Business Analyst'];

        foreach ($designations as $designation) {
            Designation::create([
                'title' => $designation,
                'status_id' => 1,
                'company_id' => 2
            ]);
        }

        DutySchedule::create([
            'department_id' => 2,
            'start_time' => '10:00:00',
            'end_time' => '18:00:00',
            'consider_time' => 10,
            'hour' => 8,
            'status_id' => 1,
            'company_id' => 2
        ]);

        DutySchedule::create([
            'department_id' => 3,
            'start_time' => '11:00:00',
            'end_time' => '19:00:00',
            'consider_time' => 5,
            'hour' => 8,
            'status_id' => 1,
            'company_id' => 2
        ]);

        LeaveType::create([
            'name' => 'Casual',
            'status_id' => 1,
            'company_id' => 2
        ]);

        LeaveType::create([
            'name' => 'Sick',
            'status_id' => 1,
            'company_id' => 2
        ]);

        AssignLeave::create([
            'company_id' => 2,
            'type_id' => 1,
            'days' => 20,
            'department_id' => 3,
            'status_id' => 1,
        ]);

        AssignLeave::create([
            'company_id' => 2,
            'type_id' => 2,
            'days' => 15,
            'department_id' => 3,
            'status_id' => 1,
        ]);

    }
}
