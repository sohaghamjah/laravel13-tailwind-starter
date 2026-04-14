<?php

namespace Database\Seeders\Admin;

use App\Helpers\Permissions;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = Permissions::getAllPermissionsWithGrouping();

        for ($i = 0; $i < count($permissions); $i++) {
            $permissionGroup = $permissions[$i]['group_name'];

            for ($j = 0; $j < count($permissions[$i]['permissions']); $j++) {
                Permission::updateOrCreate(['name' => $permissions[$i]['permissions'][$j], 'group_name' => $permissionGroup],[
                    'name' => $permissions[$i]['permissions'][$j],
                    'group_name' => $permissionGroup
                ]);
            }
        }
    }
}
