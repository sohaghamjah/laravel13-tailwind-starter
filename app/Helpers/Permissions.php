<?php

namespace App\Helpers;

use App\Models\Admin\Permissions\AdminPermission;
use App\Models\Admin\Permissions\DashboardPermission;
use App\Models\Admin\Permissions\RolePermission;
use App\Models\Admin\Permissions\UserPermission;

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
            [
                'group_name' => 'admin',
                'permissions' => [
                    AdminPermission::PERMISSION_CREATE,
                    AdminPermission::PERMISSION_VIEW,
                    AdminPermission::PERMISSION_EDIT,
                    AdminPermission::PERMISSION_DELETE,
                    AdminPermission::PERMISSION_STATUS,
                ],
            ],
            [
                'group_name' => 'user',
                'permissions' => [
                    UserPermission::PERMISSION_CREATE,
                    UserPermission::PERMISSION_VIEW,
                    UserPermission::PERMISSION_EDIT,
                    UserPermission::PERMISSION_DELETE,
                    UserPermission::PERMISSION_STATUS,
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
