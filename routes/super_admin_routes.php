<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\SuperAdmin\AuthController as SuperAdminAuthController;
use App\Http\Controllers\Backend\SuperAdmin\DashboardController as SuperAdminDashboardController;

Route::group(['prefix' => 'superadmin'], function () {
    Route::middleware('guest:super_admin')->group(function () {
        Route::get('/login', [SuperAdminAuthController::class, 'showLogin'] )->name('superadmin.login');
        Route::POST('/login', [SuperAdminAuthController::class, 'login'] )->name('superadmin.login');
    });

    Route::middleware(['auth:super_admin'])->group(function () {
        Route::get('/dashboard', [SuperAdminDashboardController::class, 'index'] )->name('superadmin.index');
        Route::get('/admins', [SuperAdminDashboardController::class, 'index'] )->name('superadmin.index');
        Route::get('/logout', [SuperAdminAuthController::class, 'logout'] )->name('superadmin.logout');
    });
});
