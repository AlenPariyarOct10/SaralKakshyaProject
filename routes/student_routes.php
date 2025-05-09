<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\Student\AuthController as StudentAuthController;
use App\Http\Controllers\Backend\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Backend\Student\ProfileController as StudentProfileController;
use App\Http\Controllers\Backend\Student\AttendanceController as StudentAttendanceController;

Route::group(['prefix' => 'student'], function () {

    Route::POST('/attendance/getuser', [StudentAttendanceController::class, 'user_info_for_face_recognition'])->name('student.getInfo');
    Route::POST('/attendance/face/mark', [StudentAttendanceController::class, 'mark_attendance'])->name('student.face.mark');


    Route::middleware('guest:student')->group(function () {
        Route::get('/login', [StudentAuthController::class, 'showLogin'])->name('student.login');
        Route::post('/login', [StudentAuthController::class, 'login'])->name('student.login');
        Route::get('/register', [StudentAuthController::class, 'showRegister'])->name('student.register');
        Route::POST('/register', [StudentAuthController::class, 'register'])->name('student.register');
        Route::get('/complete-profile', [StudentAuthController::class, 'showCompleteProfile'])->name('student.complete-profile');
        Route::post('/complete-profile', [StudentAuthController::class, 'completeProfile'])->name('student.complete-profile.post');
    });

    Route::middleware(['auth:student'])->group(function () {
        Route::get('/dashboard', [StudentDashboardController::class, "index"])->name('student.dashboard');
        Route::get('/profile', [StudentProfileController::class, "index"])->name('student.profile');
        Route::get('/logout', [StudentAuthController::class, 'logout'])->name('student.logout');
        Route::post('/update_profile_picture', [StudentProfileController::class, 'update_profile_picture'])->name('student.update_profile_picture');
        Route::get('/attendance', [StudentAttendanceController::class, 'index'])->name('student.attendance.index');
        Route::get('/attendance/setup', [StudentAttendanceController::class, 'setup_index'])->name('student.attendance.setup.index');
        Route::post('/attendance/setup', [StudentAttendanceController::class, 'saveFacePhotos'])->name('student.saveFacePhotos');
        Route::post('/attendance/setup/update', [StudentAttendanceController::class, 'updateFacePhotos'])->name('student.updateFacePhotos');
    });
});
