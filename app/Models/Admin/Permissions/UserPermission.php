<?php

namespace App\Models\Admin\Permissions;

use App\Models\Admin\Permissions\PermissionChecker;
use Exception;

class UserPermission extends PermissionChecker
{
    public const PERMISSION_VIEW = 'user.view';
    public const PERMISSION_CREATE = 'user.create';
    public const PERMISSION_EDIT = 'user.edit';
    public const PERMISSION_DELETE = 'user.delete';
    public const PERMISSION_STATUS = 'user.status';

    protected function getErrorMessages(string $permissionKey): string
    {
        $errorMessages = [
            static::PERMISSION_VIEW => __('You have no permission to view user.'),
            static::PERMISSION_CREATE => __('You have no permission to create a new user.'),
            static::PERMISSION_EDIT => __('You have no permission to edit the user.'),
            static::PERMISSION_DELETE => __('You have no permission to delete the user.'),
            static::PERMISSION_STATUS => __('You have no permission to update status.'),
        ];

        return $errorMessages[$permissionKey];
    }

    /**
     * @throws Exception
     */
    public function canViewUser(): bool
    {
        if (!$this->hasPermissions(static::PERMISSION_VIEW)) {
            $this->throwUnAuthorizedException($this->getErrorMessages(static::PERMISSION_VIEW));
        }

        return true;
    }

    /**
     * @throws Exception
     */
    public function canCreateUser(): bool
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
    public function canUpdateUser(): bool
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
    public function canDeleteUser(): bool
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
    public function canStatusUser(): bool
    {
        $hasDeletePermission = $this->hasPermissions(static::PERMISSION_STATUS);

        if (!$hasDeletePermission) {
            $this->throwUnAuthorizedException($this->getErrorMessages(static::PERMISSION_STATUS));
        }

        return true;
    }
}
