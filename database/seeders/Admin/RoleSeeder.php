<?php

namespace Database\Seeders\Admin;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name'       => 'Super Admin',
                'code'       => 'super_admin',
                'deletable'  => 0,
                'guard_name' => 'api',
            ],
            [
                'name'       => 'Sales Executive',
                'code'       => 'sales_executive',
                'deletable'  => 1,
                'guard_name' => 'api',
            ]
        ];

        Role::insert($roles);
    }
}
