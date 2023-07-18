<?php

namespace Database\Seeders\Admin;

use App\Models\Hrm\Shift\Shift;
use App\Models\User;
use App\Models\Role\Role;
use Illuminate\Support\Str;
use App\Models\Role\RoleUser;
use App\Models\Company\Company;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\ActivityLogs\AuthorInfo;
use App\Models\Hrm\Department\Department;
use App\Models\Hrm\Designation\Designation;
use App\Helpers\CoreApp\Traits\PermissionTrait;

class UserSeeder extends Seeder
{

    use PermissionTrait;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $this->createCompanyAdmin();

        
        // sample data for dummy company
        $lists = dummyEmployeeList();
        foreach ($lists as $key => $list) {
            $this->createNewUser($list);
        }
    }
}
