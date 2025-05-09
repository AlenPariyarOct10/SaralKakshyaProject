<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\Teacher\AuthController as TeacherAuthController;
use App\Http\Controllers\Backend\Teacher\DashboardController as TeacherDashboardController;
use App\Http\Controllers\Backend\Teacher\AttendanceController as TeacherAttendanceController;
use App\Http\Controllers\Backend\Teacher\AssignmentController as TeacherAssignmentController;
use App\Http\Controllers\Teacher\AnnouncementController as TeacherAnnouncementController;
use App\Http\Controllers\Teacher\ProfileController as TeacherProfileController;
use App\Http\Controllers\Teacher\SettingController as TeacherSettingController;
use App\Http\Controllers\Backend\Teacher\PersonalRoutineController as TeacherPersonalRoutineController;

Route::group(['prefix' => 'teacher'], function () {
    Route::middleware('guest:teacher')->group(function () {

        Route::get('/login', [TeacherAuthController::class, 'showLogin'])->name('teacher.login');
        Route::post('/login', [TeacherAuthController::class, 'login'])->name('teacher.login');
        Route::get('/register', [TeacherAuthController::class, 'showRegister'])->name('teacher.register');
        Route::post('/register', [TeacherAuthController::class, 'register'])->name('teacher.register');

        Route::get('teacher/register/step-2', [TeacherAuthController::class, 'showRegisterStep2Form'])->name('teacher.register.step2');
        Route::post('teacher/register/step-2', [TeacherAuthController::class, 'registerStep2']);
    });

    Route::middleware(['auth:teacher'])->group(function () {
        Route::get('/dashboard', [TeacherDashboardController::class, "index"])->name('teacher.dashboard');

        Route::get('/attendance', [TeacherAttendanceController::class, "index"])->name('teacher.attendance.index');

        //Announcement
        Route::get('/announcements', [TeacherAnnouncementController::class, "index"])->name('teacher.announcement.index');

        //Profile
        Route::get('/profile', [TeacherProfileController::class, "index"])->name('teacher.profile.index');
        Route::PUT('/profile/{id}', [TeacherProfileController::class, "store"])->name('teacher.profile.update');
        Route::get('/profile/show', [TeacherProfileController::class, "show"])->name('teacher.profile.show');
        Route::get('/profile/routine', [TeacherPersonalRoutineController::class, "index"])->name('teacher.profile.routine.show');

        //Setting
        Route::get('/setting', [TeacherSettingController::class, "index"])->name('teacher.setting.index');

        Route::get('/assignment', [TeacherAssignmentController::class, "index"])->name('teacher.assignment.index');
        Route::get('/logout', [TeacherAuthController::class, 'logout'])->name('teacher.logout');

    });
});
