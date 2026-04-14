<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Auth\RegisteredAdminController;
use App\Http\Controllers\Admin\RolePermissionController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;


Route::prefix('admin')->as('admin.')->group(function () {

    Route::get('register', [RegisteredAdminController::class, 'create'])
        ->name('register');

    Route::post('register', [RegisteredAdminController::class, 'store'])->name('register.store');

    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.store');



    Route::middleware('auth:admin')->group(function () {

        Route::get('/dashboard', function () {
            return view('admin.pages.dashboard');
        })->name('dashboard');

        Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

        Route::resource('role-permissions', RolePermissionController::class);

        Route::prefix('/hrm')->as('hrm.')->group(function () {
            // Admins
            Route::resource('admins', AdminController::class);
            Route::post('admins/update-status/{id}', [AdminController::class, 'updateStatus'])->name('admins.status');
            // Users

            Route::resource('users', UserController::class);
            Route::post('users/update-status/{id}', [UserController::class, 'updateStatus'])->name('admins.status');
        });

    });
});

