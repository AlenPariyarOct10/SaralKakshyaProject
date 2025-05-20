<?php

use App\Http\Controllers\Backend\Teacher\AssignmentController;
use App\Http\Controllers\Backend\Teacher\DepartmentController;
use App\Http\Controllers\Backend\Teacher\TeacherAvailabilityController;
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

        Route::get('/register/step-2', [TeacherAuthController::class, 'showRegisterStep2Form'])->name('teacher.register.step2');
        Route::post('/register/step-2', [TeacherAuthController::class, 'registerStep2']);
    });

    Route::middleware(['auth:teacher'])->group(function () {
        Route::get('/dashboard', [TeacherDashboardController::class, "index"])->name('teacher.dashboard');

        Route::get('/attendance', [TeacherAttendanceController::class, "index"])->name('teacher.attendance.index');

        // Announcement
        Route::get('/announcements', [TeacherAnnouncementController::class, "index"])->name('teacher.announcement.index');

        //Subject
        Route::get('/subject/{id}/chapters', [TeacherAssignmentController::class, "getChapters"])->name('teacher.subject.chapters');
        Route::get('/chapter/{chapterId}/sub-chapters', [TeacherAssignmentController::class, "getSubChapters"])->name('teacher.subject.subchapters');



        // Profile
        Route::get('/profile', [TeacherProfileController::class, "index"])->name('teacher.profile.index');
        Route::put('/profile/{id}', [TeacherProfileController::class, "store"])->name('teacher.profile.update');
        Route::get('/profile/show', [TeacherProfileController::class, "show"])->name('teacher.profile.show');
        Route::get('/profile/routine', [TeacherPersonalRoutineController::class, "index"])->name('teacher.profile.routine.show');

        // Availability
        Route::get('/availability', [TeacherAvailabilityController::class, "index"])->name('teacher.availability.index');
        Route::post('/availability', [TeacherAvailabilityController::class, "store"])->name('teacher.availability.store');
        Route::put('/availability/{id}', [TeacherAvailabilityController::class, "update"])->name('teacher.availability.update');
        Route::delete('/availability/{id}', [TeacherAvailabilityController::class, "destroy"])->name('teacher.availability.destroy');

        // Setting
        Route::get('/setting', [TeacherSettingController::class, "index"])->name('teacher.setting.index');

        //Assignment
        Route::get('/assignments', [AssignmentController::class, 'filterAssignments']);
        Route::get('/assignment', [TeacherAssignmentController::class, "index"])->name('teacher.assignment.index');
        Route::get('/assignment/create', [TeacherAssignmentController::class, "create"])->name('teacher.assignment.create');
        Route::POST('/assignment', [TeacherAssignmentController::class, "store"])->name('teacher.assignment.store');
        Route::get('/assignment/{assignment}', [TeacherAssignmentController::class, "show"])->name('teacher.assignment.show');

        Route::get('/assignment/{assignment}/edit', [TeacherAssignmentController::class, "edit"])->name('teacher.assignment.edit');
        Route::PUT('/assignment/{assignment}', [TeacherAssignmentController::class, "update"])->name('teacher.assignment.update');

        Route::delete('/assignment/{assignment}', [TeacherAssignmentController::class, "destroy"])->name('teacher.assignment.destroy');
        Route::get('/assignment/draft', [TeacherAssignmentController::class, "draft"])->name('teacher.assignment.draft');
        Route::get('/assignment/{id}/duplicate', [TeacherAssignmentController::class, "duplicate"])->name('teacher.assignment.duplicate');
        Route::get('assignment/{assignment}/download/{attachment}', [AssignmentController::class, 'downloadAttachment'])->name('teacher.assignment.download');
        Route::get('assignment/{assignment}/view/{attachment}', [AssignmentController::class, 'viewAttachment'])->name('teacher.assignment.view');

        //Teacher Departments
        Route::get('/departments', [DepartmentController::class, 'index'])
            ->name('teacher.department.index');

        //Department Programs
        Route::get('/departments/{id}/programs', [DepartmentController::class, 'getPrograms'])
            ->name('teacher.department.programs');

        //Department Subjects
        Route::get('/programs/{id}/subjects', [DepartmentController::class, 'getSubjects'])
            ->name('teacher.department.subjects');

        //Assignment Submission
        Route::get('/assignment/{assignment}/submissions', [TeacherAssignmentController::class, 'submissions'])
            ->name('teacher.assignment.submissions');

        //Assignment API
        Route::get('/api/assignments', [TeacherAssignmentController::class, 'myAssignments'])
            ->name('api.teacher.assignment.index');


        Route::get('subject/{id}/chapters', [AssignmentController::class, 'getChapters'])
            ->name('teacher.subject.chapters');
        Route::get('chapter/{id}/sub-chapters', [AssignmentController::class, 'getSubChapters'])
            ->name('teacher.chapter.sub-chapters');

        Route::get('/logout', [TeacherAuthController::class, 'logout'])->name('teacher.logout');
    });
});
