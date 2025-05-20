<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\Student\DashboardController as StudentDashboardController;

use App\Http\Controllers\Backend\Student\Api\NotificationController as ApiNotificationController;

Route::group(['prefix' => 'api/student'], function () {
    Route::middleware(['auth:student'])->group(function () {
        Route::get('/dashboard', [StudentDashboardController::class, "index"])->name('student.dashboard');
        ################################# Notification #############################################
        Route::resource('notification', ApiNotificationController::class);

    });
});
