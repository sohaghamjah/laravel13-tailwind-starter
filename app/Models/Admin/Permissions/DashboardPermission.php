<?php

namespace App\Models\Admin\Permissions;

use App\Models\Admin\Permissions\PermissionChecker;
use Exception;

class DashboardPermission extends PermissionChecker
{
    public const PERMISSION_VIEW = 'dashboard.view';

    protected function getErrorMessages(string $permissionKey): string
    {
        $errorMessages = [
            static::PERMISSION_VIEW => __('You have no permission to view the dashboard.'),
        ];

        return $errorMessages[$permissionKey];
    }

    /**
     * @throws Exception
     */
    public function canViewDashboard(): bool
    {
        if (!$this->hasPermissions(static::PERMISSION_VIEW)) {
            $this->throwUnAuthorizedException($this->getErrorMessages(static::PERMISSION_VIEW));
        }

        return true;
    }
}
