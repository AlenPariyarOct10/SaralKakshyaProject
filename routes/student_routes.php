<?php

use App\Http\Controllers\Backend\Student\AssignmentSubmissionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\Student\AuthController as StudentAuthController;
use App\Http\Controllers\Backend\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Backend\Student\ProfileController as StudentProfileController;
use App\Http\Controllers\Backend\Student\AttendanceController as StudentAttendanceController;
use App\Http\Controllers\Backend\Student\AssignmentController as StudentAssignmentController;
use App\Http\Controllers\Backend\Student\SubjectController as StudentSubjectController;
use App\Http\Controllers\Backend\Student\ResourceController as StudentResourceController;

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


        Route::get('/department/{id}/programs', [StudentAuthController::class, 'getDepartmentPrograms'])->name('auth.student.department.programs');
        Route::get('/program/batches', [StudentAuthController::class, 'getProgramBatches'])->name('auth.student.program.batches');
        Route::get('/program/{programId}/sections', [StudentAuthController::class, 'getProgramSections'])->name('auth.student.program.sections');
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

        ################################# Assignment #############################################
        Route::get('/assignment/{id}', [StudentAssignmentController::class, 'show'])->name('student.assignment.show');
        Route::get('/assignments', [StudentAssignmentController::class, 'index'])->name('student.assignment.index');
        Route::POST('/assignment', [StudentAssignmentController::class, 'store'])->name('student.assignment.store');
        Route::get('/assignment/attachment/{id}/download', [StudentAssignmentController::class, 'download'])->name('student.assignment.download');
        Route::get('/assignment/attachment/{id}/view', [StudentAssignmentController::class, 'viewAttachment'])->name('student.assignment.view');
        Route::get('/assignment/{id}/edit', [StudentAssignmentController::class, 'edit'])->name('student.assignment.edit');
        Route::delete('/assignment/{id}/destroy', [StudentAssignmentController::class, 'destroy'])->name('student.assignment.destroy');

        ################################# Assignment Submission #############################################
        Route::resource('assignment-submission', AssignmentSubmissionController::class)->only([
            'index', 'create', 'store', 'show'
        ])->names([
            'index' => 'student.assignment-submission.index',
            'create' => 'student.assignment-submission.create',
            'store' => 'student.assignment-submission.store',
            'show' => 'student.assignment-submission.show',
        ]);

        Route::get('/assignment/submitted-attachment/{id}/download', [AssignmentSubmissionController::class, 'download'])->name('student.submittedassignment.download');
        Route::get('/assignment/submitted-attachment/{id}/view', [AssignmentSubmissionController::class, 'viewAttachment'])->name('student.submittedassignment.view');

        ################################# Notification #############################################
        Route::resource('notification', \App\Http\Controllers\Backend\Student\NotificationController::class)->only([
            'index', 'show'
        ])->names([
            'index' => 'student.notification.index',
            'show' => 'student.notification.show',
        ]);

        ################################# Announcement #############################################
        Route::resource('announcement', \App\Http\Controllers\Backend\Student\AnnouncementController::class)->only([
            'index', 'show'
        ])->names([
            'index' => 'student.announcement.index',
            'show' => 'student.announcement.show',
        ]);


        ################################# Subjects #############################################
        Route::get('/subjects', [StudentSubjectController::class, 'index'])->name('student.subjects.index');
        Route::get('/subject/{id}', [StudentSubjectController::class, 'show'])->name('student.subject.show');
        Route::get('/subject/{id}/resources', [StudentSubjectController::class, 'subjectResources'])->name('student.subject.resources');
        Route::get('/subject/{id}/assignments', [StudentSubjectController::class, 'subjectAssignments'])->name('student.subject.assignments');


        ################################## Resources #############################################
        Route::get('/resources', [StudentResourceController::class, 'index'])->name('student.resources.index');
        Route::get('/resource/{id}', [StudentResourceController::class, 'show'])->name('student.resource.show');
        Route::get('/resource/{id}/download', [StudentResourceController::class, 'download'])->name('student.resource.download');

    });
});
