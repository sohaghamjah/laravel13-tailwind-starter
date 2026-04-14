<?php

namespace App\Models\Admin\Permissions;

use App\Models\Admin\Permissions\PermissionChecker;
use Exception;
use Illuminate\Http\Request;

class AdminPermission extends PermissionChecker
{
    public const PERMISSION_VIEW = 'admin.view';
    public const PERMISSION_CREATE = 'admin.create';
    public const PERMISSION_EDIT = 'admin.edit';
    public const PERMISSION_DELETE = 'admin.delete';
    public const PERMISSION_STATUS = 'admin.status';

    protected function getErrorMessages(string $permissionKey): string
    {
        $errorMessages = [
            static::PERMISSION_VIEW => __('You have no permission to view admin.'),
            static::PERMISSION_CREATE => __('You have no permission to create a new admin.'),
            static::PERMISSION_EDIT => __('You have no permission to edit the admin.'),
            static::PERMISSION_DELETE => __('You have no permission to delete the admin.'),
            static::PERMISSION_STATUS => __('You have no permission to update status.'),
        ];

        return $errorMessages[$permissionKey];
    }

    /**
     * @throws Exception
     */
    public function canViewAdmins(): bool
    {
        if (!$this->hasPermissions(static::PERMISSION_VIEW)) {
            $this->throwUnAuthorizedException($this->getErrorMessages(static::PERMISSION_VIEW));
        }

        return true;
    }

    /**
     * @throws Exception
     */
    public function canCreateAdmin(): bool
    {
        $hasCreatePermission = $this->hasPermissions(static::PERMISSION_CREATE);

        if (!$hasCreatePermission) {
            $this->throwUnAuthorizedException($this->getErrorMessages(static::PERMISSION_CREATE));
        }

        return true;
    }

    /**
     * @throws Exception
     */
    public function canUpdateAdmin(): bool
    {
        $hasEditPermission = $this->hasPermissions(static::PERMISSION_EDIT);

        if (!$hasEditPermission) {
            $this->throwUnAuthorizedException($this->getErrorMessages(static::PERMISSION_EDIT));
        }

        return true;
    }

    /**
     * @throws Exception
     */
    public function canDeleteAdmin(): bool
    {
        $hasDeletePermission = $this->hasPermissions(static::PERMISSION_DELETE);

        if (!$hasDeletePermission) {
            $this->throwUnAuthorizedException($this->getErrorMessages(static::PERMISSION_DELETE));
        }

        return true;
    }

     /**
     * @throws Exception
     */
    public function canStatusAdmin(): bool
    {
        $hasDeletePermission = $this->hasPermissions(static::PERMISSION_STATUS);

        if (!$hasDeletePermission) {
            $this->throwUnAuthorizedException($this->getErrorMessages(static::PERMISSION_STATUS));
        }

        return true;
    }
}
