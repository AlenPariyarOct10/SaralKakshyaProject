<?php

use App\Http\Controllers\Backend\Teacher\AuthController as TeacherAuthController;
use App\Http\Controllers\Backend\Admin\AuthController as AdminAuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Backend\Teacher\DashboardController as TeacherDashboardController;
use App\Http\Controllers\Backend\Student\AuthController as StudentAuthController;
use App\Http\Controllers\Backend\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Backend\Student\ProfileController as StudentProfileController;
use App\Http\Controllers\Backend\Admin\ProgramController as AdminProgramController;
use App\Http\Controllers\Backend\Admin\AttendanceController as AdminAttendanceController;
use App\Http\Controllers\Backend\Admin\AnnouncementController as AdminAnnouncementController;

Route::get('/', function () {
    return view('welcome');
});


Route::group(['prefix' => 'admin'], function () {
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
        Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login');
    });

    Route::middleware(['auth:admin'])->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, "index"])->name('admin.dashboard');
        Route::get('/attendance', [AdminAttendanceController::class, "index"])->name('admin.attendance.index');
        Route::get('/attendance-face', [AdminAttendanceController::class, "face_index"])->name('admin.attendance-face.index');
        Route::get('/announcement', [AdminAnnouncementController::class, "index"])->name('admin.announcement.index');
        Route::get('/announcement/create', [AdminAnnouncementController::class, "create"])->name('admin.announcement.create');

        Route::get('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
        Route::get('/programs', [AdminProgramController::class, 'index'])->name('admin.programs.index');

    });
});

Route::group(['prefix' => 'teacher'], function () {
    Route::middleware('guest:teacher')->group(function () {
        Route::get('/login', [TeacherAuthController::class, 'showLogin'])->name('teacher.login');
        Route::post('/login', [TeacherAuthController::class, 'login'])->name('teacher.login');
        Route::get('/register', [TeacherAuthController::class, 'showRegister'])->name('teacher.register');
        Route::post('/register', [TeacherAuthController::class, 'register'])->name('teacher.register');
    });

    Route::middleware(['auth:teacher'])->group(function () {
        Route::get('/dashboard', [TeacherDashboardController::class, "index"])->name('teacher.dashboard');
        Route::get('/logout', [TeacherAuthController::class, 'logout'])->name('teacher.logout');
    });
});

Route::group(['prefix' => 'student'], function () {
    Route::middleware('guest:student')->group(function () {
        Route::get('/login', [StudentAuthController::class, 'showLogin'])->name('student.login');
        Route::post('/login', [StudentAuthController::class, 'login'])->name('student.login');
        Route::get('/register', [StudentAuthController::class, 'showRegister'])->name('student.register');
        Route::post('/register', [StudentAuthController::class, 'register'])->name('student.register');
    });

    Route::middleware(['auth:student'])->group(function () {
        Route::get('/dashboard', [StudentDashboardController::class, "index"])->name('student.dashboard');
        Route::get('/profile', [StudentProfileController::class, "index"])->name('student.profile');
        Route::get('/logout', [StudentAuthController::class, 'logout'])->name('student.logout');
        Route::post('/update_profile_picture', [StudentProfileController::class, 'update_profile_picture'])->name('student.update_profile_picture');
    });
});
