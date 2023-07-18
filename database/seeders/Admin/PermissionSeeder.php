<?php

namespace Database\Seeders\Admin;

use App\Helpers\CoreApp\Traits\PermissionTrait;
use App\Models\Permission\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    use PermissionTrait;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $attributes = $this->adminRolePermissions();
        foreach ($attributes as $key => $attribute) {
            $permission = new Permission;
            $permission->attribute = $key;
            $permission->keywords = $attribute;
            $permission->save();
        }
    }
}
