<?php

namespace Database\Seeders;

use App\Models\Company\Company;
use Illuminate\Database\Seeder;
use App\Models\Hrm\Leave\LeaveType;
use App\Models\Hrm\Leave\AssignLeave;
use App\Models\Hrm\Department\Department;

class LeaveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $leave_types = [
            'Casual Leave',
            'Sick Leave',
            'Maternity Leave',
            'Paternity Leave',
            'Leave Without Pay'
        ];

        $companies = Company::all();
        foreach ($companies as $company) {
            foreach ($leave_types as $leave_type) {
                $s = new LeaveType();
                $s->company_id = $company->id;
                $s->name = $leave_type;
                $s->status_id =    1;
                $s->save();
            }
        }



        foreach ($companies as $company) {
            $leave_types = LeaveType::where('company_id', $company->id)->get();
            foreach ($leave_types as $leave_type) {
                $departments = Department::where('company_id', $company->id)->get();
                foreach ($departments as $department) {
                    $s = new AssignLeave();
                    $s->company_id = $company->id;
                    $s->type_id = $leave_type->id;
                    $s->department_id = $department->id;
                    $s->status_id =    1;
                    $s->days = 10 + rand(0, 10);
                    $s->save();
                }
            }
        }
    }
}
