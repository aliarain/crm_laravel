<?php

namespace Database\Seeders;

use App\Models\Company\Company;
use App\Models\Hrm\Department\Department;
use App\Models\Hrm\Designation\Designation;
use Illuminate\Database\Seeder;

class DesignationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $designations = [
            'Chairman',
            'MD',
            'CEO',
            'CIO',
            'HR Manager',
            'Staff',
        ];
        $departments = [
            'Management',
            'HR',
            'IT',
        ];
        $companies = Company::all();
        foreach ($companies as $company) {
            foreach ($designations as $designation) {
                $s = new Designation();
                $s->company_id = $company->id;
                $s->title = $designation;
                $s->status_id = 1;
                $s->save();
            }
            foreach ($departments as $department) {
                $s = new Department();
                $s->company_id = $company->id;
                $s->title = $department;
                $s->status_id = 1;
                $s->save();
            }
        }

    }
}
