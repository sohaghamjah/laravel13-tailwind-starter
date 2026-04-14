<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Permission directive for admin guard
        Blade::if('admincan', function ($permission) {
            return auth()->guard('admin')->user()?->can($permission) ?? false;
        });

        // Permission directive for multiple permissions (any)
        Blade::if('admincanany', function ($permissions) {
            return auth()->guard('admin')->user()?->canAny($permissions) ?? false;
        });

        // Role directive
        Blade::if('adminrole', function ($role) {
            return auth()->guard('admin')->user()?->hasRole($role) ?? false;
        });
    }
}
