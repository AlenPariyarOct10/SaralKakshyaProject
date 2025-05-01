<?php

use App\Events\Admin\NewAnnouncement;
use App\Events\TestEvent;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\Teacher\AuthController as TeacherAuthController;
use App\Http\Controllers\Backend\Teacher\DashboardController as TeacherDashboardController;
use App\Http\Controllers\Backend\Teacher\AttendanceController as TeacherAttendanceController;
use App\Http\Controllers\Backend\Teacher\AssignmentController as TeacherAssignmentController;

Route::group(['prefix' => 'teacher'], function () {
    Route::middleware('guest:teacher')->group(function () {

        Route::get('/login', [TeacherAuthController::class, 'showLogin'])->name('teacher.login');
        Route::post('/login', [TeacherAuthController::class, 'login'])->name('teacher.login');
        Route::get('/register', [TeacherAuthController::class, 'showRegister'])->name('teacher.register');
        Route::post('/register', [TeacherAuthController::class, 'register'])->name('teacher.register');
    });

    Route::middleware(['auth:teacher'])->group(function () {
        Route::get('/dashboard', [TeacherDashboardController::class, "index"])->name('teacher.dashboard');

        Route::get('/attendance', [TeacherAttendanceController::class, "index"])->name('teacher.attendance.index');

        Route::get('/assignment', [TeacherAssignmentController::class, "index"])->name('teacher.assignment.index');
        Route::get('/logout', [TeacherAuthController::class, 'logout'])->name('teacher.logout');
    });
});
