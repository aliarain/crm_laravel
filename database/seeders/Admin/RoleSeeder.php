<?php

namespace Database\Seeders\Admin;

use App\Models\Role\Role;
use App\Models\Company\Company;
use Illuminate\Database\Seeder;
use App\Helpers\CoreApp\Traits\PermissionTrait;

class RoleSeeder extends Seeder
{
    use PermissionTrait;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            'superadmin',
            'admin',
            'manager',
            'staff',
            'client',
        ];
        $companies = Company::all();
        foreach ($companies as $company) {
            foreach ($roles as $role) {
                if($company->is_main_company== "no" && $role == "superadmin"){
                    continue;
                }
                $role = Role::create([
                    'name' => $role,
                    'slug' => $role,
                    'company_id' => $company->id,
                    'permissions' => $this->customPermissions($role),
                    'status_id' => 1,
                ]);
            }
        }
    }


}
