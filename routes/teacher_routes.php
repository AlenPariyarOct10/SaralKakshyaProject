<?php

use App\Http\Controllers\Backend\Teacher\AssignmentController;
use App\Http\Controllers\Backend\Teacher\DepartmentController;
use App\Http\Controllers\Backend\Teacher\TeacherAvailabilityController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\Teacher\AuthController as TeacherAuthController;
use App\Http\Controllers\Backend\Teacher\DashboardController as TeacherDashboardController;
use App\Http\Controllers\Backend\Teacher\AttendanceController as TeacherAttendanceController;
use App\Http\Controllers\Backend\Teacher\AssignmentController as TeacherAssignmentController;
use App\Http\Controllers\Backend\Teacher\AnnouncementController as TeacherAnnouncementController;

use App\Http\Controllers\Teacher\ProfileController as TeacherProfileController;
use App\Http\Controllers\Teacher\SettingController as TeacherSettingController;
use App\Http\Controllers\Backend\Teacher\PersonalRoutineController as TeacherPersonalRoutineController;
use App\Http\Controllers\Backend\Teacher\ResourceController as TeacherResourceController;
use App\Http\Controllers\Backend\Teacher\AssignmentSubmissionController as AssignmentSubmissionController;

use App\Http\Controllers\Backend\Teacher\EvaluationController as TeacherEvaluationController;
use App\Http\Controllers\Backend\Teacher\EvaluationReportController as TeacherEvaluationReportController;
use App\Http\Controllers\Backend\Teacher\EvaluationApiController as TeacherEvaluationApiController;

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

        // Attendance routes
        Route::get('/attendance', [TeacherAttendanceController::class, "index"])->name('teacher.attendance.index');
        Route::get('/attendance/students/{subjectId}', [TeacherAttendanceController::class, "getStudents"])->name('teacher.attendance.students');
        Route::get('/attendance/students/', [TeacherAttendanceController::class, "getAllStudents"])->name('teacher.attendance.allstudents');
        Route::post('/attendance/store', [TeacherAttendanceController::class, "store"])->name('teacher.attendance.store');
        Route::get('/attendance/history', [TeacherAttendanceController::class, "getHistory"])->name('teacher.attendance.history');
        Route::get('/attendance/details', [TeacherAttendanceController::class, "getDetails"])->name('teacher.attendance.details');

        // Announcement
        Route::get('/announcements', [TeacherAnnouncementController::class, "index"])->name('teacher.announcement.index');
        Route::get('/announcements/create', [TeacherAnnouncementController::class, "create"])->name('teacher.announcement.create');
        Route::get('/announcement/{id}', [TeacherAnnouncementController::class, "show"])->name('teacher.announcement.show');
        Route::get('/announcement/{id}/edit', [TeacherAnnouncementController::class, "edit"])->name('teacher.announcement.edit');
        Route::delete('/announcement/{id}/edit', [TeacherAnnouncementController::class, "destroy"])->name('teacher.announcement.destroy');
        Route::put('/announcement/{id}/', [TeacherAnnouncementController::class, "update"])->name('teacher.announcement.update');
        Route::put('/announcement/{id}/pin', [TeacherAnnouncementController::class, "setPin"])->name('teacher.announcement.pin');
        Route::put('/announcement/{id}/unpin', [TeacherAnnouncementController::class, "setUnpin"])->name('teacher.announcement.unpin');
        Route::POST('/announcements', [TeacherAnnouncementController::class, "store"])->name('teacher.announcements.store');
        Route::get('/announcements/attachment/{id}/delete', [TeacherAnnouncementController::class, "deleteAttachment"])->name('teacher.announcements.deleteAttachment');

        //Subject
        Route::get('/subject/{id}/chapters', [TeacherAssignmentController::class, "getChapters"])->name('teacher.subject.chapters');
        Route::get('/chapter/{chapterId}/sub-chapters', [TeacherAssignmentController::class, "getSubChapters"])->name('teacher.subject.subchapters');

        //Resource
        Route::resource('resources', TeacherResourceController::class)->names([
            'index' => 'teacher.resources.index',
            'create' => 'teacher.resources.create',
            'store' => 'teacher.resources.store',
            'show' => 'teacher.resources.show',
            'edit' => 'teacher.resources.edit',
            'update' => 'teacher.resources.update',
            'destroy' => 'teacher.resources.destroy',
        ]);

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
        Route::get('/assignment/{assignment}/submissions', [AssignmentSubmissionController::class, "index"])->name('teacher.assignment.submission.index');
        Route::get('/assignment/submission/{id}', [AssignmentSubmissionController::class, "show"])->name('teacher.assignment.submission.show');
        Route::get('/assignment/submission/{id}/edit', [AssignmentSubmissionController::class, "edit"])->name('teacher.assignment.submission.edit');
        Route::get('/assignment/submission/{id}/download', [AssignmentSubmissionController::class, "download"])->name('teacher.assignment.submission.download');
        Route::get('/assignment/submission/{id}/view', [AssignmentSubmissionController::class, "viewAttachment"])->name('teacher.assignment.submission.view');
        Route::put('/assignment/submission/{id}/', [AssignmentSubmissionController::class, "gradeAssignment"])->name('teacher.assignment.submission.grade');

        Route::get('/assignment/{assignment}/edit', [TeacherAssignmentController::class, "edit"])->name('teacher.assignment.edit');
        Route::PUT('/assignment/{assignment}', [TeacherAssignmentController::class, "update"])->name('teacher.assignment.update');

        Route::delete('/assignments', [TeacherAssignmentController::class, "index"])->name('teacher.assignments.index');
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

        // Teacher Evaluation Routes
        Route::get('/evaluation', [TeacherEvaluationController::class, 'index'])
            ->name('teacher.evaluation.index');

        // FIXED: Corrected API route for evaluations list
        Route::get('/api/evaluations', [TeacherEvaluationController::class, 'getEvaluations'])
            ->name('teacher.evaluation.api.list');

        Route::get('/evaluation/create', [TeacherEvaluationController::class, 'create'])
            ->name('teacher.evaluation.create');
        Route::post('/evaluation', [TeacherEvaluationController::class, 'store'])
            ->name('teacher.evaluation.store');
        Route::get('/evaluation/{id}', [TeacherEvaluationController::class, 'show'])
            ->name('teacher.evaluation.show');
        Route::get('/evaluation/{id}/edit', [TeacherEvaluationController::class, 'edit'])
            ->name('teacher.evaluation.edit');
        Route::put('/evaluation/{id}', [TeacherEvaluationController::class, 'update'])
            ->name('teacher.evaluation.update');
        Route::post('/evaluation/{id}/finalize', [TeacherEvaluationController::class, 'finalize'])
            ->name('teacher.evaluation.finalize');
        Route::delete('/evaluation/{id}', [TeacherEvaluationController::class, 'destroy'])
            ->name('teacher.evaluation.destroy');
        Route::get('/evaluation/{id}/export', [TeacherEvaluationController::class, 'export'])
            ->name('teacher.evaluation.export');

        // Batch Evaluation Routes
        Route::get('/evaluation/batch/create', [TeacherEvaluationController::class, 'batchEvaluation'])
            ->name('teacher.evaluation.batch.create');
        Route::post('/evaluation/batch', [TeacherEvaluationController::class, 'storeBatchEvaluation'])
            ->name('teacher.evaluation.batch.store');

        // Evaluation API Routes
        Route::get('/api/batch/{batchId}/subjects', [TeacherEvaluationApiController::class, 'getBatchSubjects'])
            ->name('teacher.batch.subjects');
        Route::get('/api/subject/{subjectId}/evaluation-formats', [TeacherEvaluationApiController::class, 'getSubjectEvaluationFormats'])
            ->name('teacher.subject.evaluation-formats');

        Route::delete('/api/evaluation/{id}', [TeacherEvaluationApiController::class, 'destoryStudentEvaluation'])
            ->name('api.teacher.evaluation.delete');

        Route::get('/api/batch/{batchId}/students', [TeacherEvaluationApiController::class, 'getBatchStudents'])
            ->name('teacher.batch.students');

        // Evaluation Report Routes
        Route::get('/evaluation/reports', [TeacherEvaluationReportController::class, 'index'])
            ->name('teacher.evaluation.reports.index');
        Route::get('/evaluation/reports/batch-performance', [TeacherEvaluationReportController::class, 'batchPerformance'])
            ->name('teacher.evaluation.reports.batch-performance');
        Route::get('/evaluation/reports/student-performance', [TeacherEvaluationReportController::class, 'studentPerformance'])
            ->name('teacher.evaluation.reports.student-performance');
        Route::get('/evaluation/reports/format-comparison', [TeacherEvaluationReportController::class, 'formatComparison'])
            ->name('teacher.evaluation.reports.format-comparison');

        // Evaluation API Routes for Reports and Charts
        Route::get('/api/evaluation/departments', [TeacherEvaluationApiController::class, 'getDepartments'])
            ->name('api.teacher.evaluation.departments');
        Route::get('/api/evaluation/department/{departmentId}/programs', [TeacherEvaluationApiController::class, 'getDepartmentPrograms'])
            ->name('api.teacher.evaluation.department.programs');
        Route::get('/api/evaluation/program/{programId}/subjects', [TeacherEvaluationApiController::class, 'getProgramSubjects'])
            ->name('api.teacher.evaluation.program.subjects');
        Route::get('/api/evaluation/batch/statistics', [TeacherEvaluationApiController::class, 'getBatchStatistics'])
            ->name('api.teacher.evaluation.batch.statistics');
        Route::get('/api/evaluation/student/{studentId}/performance', [TeacherEvaluationApiController::class, 'getStudentPerformance'])
            ->name('api.teacher.evaluation.student.performance');

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
