<?php

namespace App\Helpers;

use App\Models\Admin\Permissions\DashboardPermission;
use App\Models\Admin\Permissions\RolePermission;

class Permissions
{
    public static function getAllPermissions(array $excludes = []): array
    {
        $groupPermissions = self::getAllPermissionsWithGrouping();
        $permissions = [];

        for ($i = 0; $i < count($groupPermissions); $i++) {
            $permissionGroup = $groupPermissions[$i]['group_name'];

            for ($j = 0; $j < count($groupPermissions[$i]['permissions']); $j++) {
                $permissionName = $groupPermissions[$i]['permissions'][$j];

                if (!in_array($permissionName, $excludes, true)) {
                    $permissions[] = $permissionName;
                }
            }
        }

        return $permissions;
    }

    public static function getAllPermissionsWithGrouping(): array
    {
        return [
            [
                'group_name' => 'dashboard',
                'permissions' => [
                    DashboardPermission::PERMISSION_VIEW,
                ]
            ],
            [
                'group_name' => 'role',
                'permissions' => [
                    RolePermission::PERMISSION_CREATE,
                    RolePermission::PERMISSION_VIEW,
                    RolePermission::PERMISSION_EDIT,
                    RolePermission::PERMISSION_DELETE,
                    RolePermission::PERMISSION_SUPER_ADMIN,
                ],
            ],
        ];
    }

    public static function getAllPermissionsIncluding(array $includes = []): array
    {
        $groupPermissions = self::getAllPermissionsWithGrouping();
        $permissions = [];

        for ($i = 0; $i < count($groupPermissions); $i++) {
            $permissionGroup = $groupPermissions[$i]['group_name'];

            for ($j = 0; $j < count($groupPermissions[$i]['permissions']); $j++) {
                $permissionName = $groupPermissions[$i]['permissions'][$j];

                if (in_array($permissionName, $includes, true)) {
                    $permissions[] = $permissionName;
                }
            }
        }

        return $permissions;
    }

}
