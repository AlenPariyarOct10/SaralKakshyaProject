<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\Student\AuthController as StudentAuthController;
use App\Http\Controllers\Backend\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Backend\Student\ProfileController as StudentProfileController;

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
