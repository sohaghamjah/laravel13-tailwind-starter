<?php

namespace App\Models\Admin\Permissions;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Access\AuthorizationException;

abstract class PermissionChecker
{
    /**
     * @throws AuthorizationException
     */
    public function checkAuthResponse(bool $isAuthorized, ?string $authorizationMessage = null): void
    {
        if (!$isAuthorized) {
            throw new AuthorizationException(
                $authorizationMessage ?: $this->getDefaultAuthorizationErrorMessage()
            );
        }
    }

    protected function getDefaultAuthorizationErrorMessage(): string
    {
        return __('You are not authorized to do this action.');
    }

    abstract protected function getErrorMessages(string $permissionKey): string;

    /**
     * Check permissions
     */
    protected function hasPermissions(string|array $permissions): bool
    {
        return $this->getAuthUser()->hasAnyPermission((array) $permissions);
    }

    /**
     * Get authenticated admin user
     */
    protected function getAuthUser(): Authenticatable
    {
        $user = Auth::guard('admin')->user(); // ✅ admin guard

        if (!$user) {
            throw new AuthorizationException(
                __('Please login as admin to perform this action.')
            );
        }

        return $user;
    }

    /**
     * Throw forbidden exception
     */
    protected function throwUnAuthorizedException(string $message): void
    {
        throw new AuthorizationException($message);
    }
}
