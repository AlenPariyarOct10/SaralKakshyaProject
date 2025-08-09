<?php

use App\Events\TestEvent;
use App\Http\Controllers\Backend\Admin\Api\SectionController as AdminSectionControllerApi;
use App\Http\Controllers\Backend\Admin\ChapterController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Backend\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Backend\Admin\ProgramController as AdminProgramController;
use App\Http\Controllers\Backend\Admin\AttendanceController as AdminAttendanceController;
use App\Http\Controllers\Backend\Admin\AnnouncementController as AdminAnnouncementController;
use App\Http\Controllers\Backend\Admin\DepartmentController as AdminDepartmentController;
use App\Http\Controllers\Backend\Admin\StudentController as AdminStudentController;
use App\Http\Controllers\Backend\Admin\TeacherController as AdminTeacherController;
use App\Http\Controllers\Backend\Admin\TestimonialController as AdminTestimonialController;
use App\Http\Controllers\Backend\Admin\AcademicInstituteController as AdminAcademicInstituteController;
use App\Http\Controllers\Backend\Admin\SubjectController as AdminSubjectController;
use App\Http\Controllers\Backend\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Backend\Admin\SubjectTeacherController as AdminSubjectTeacherController;
use App\Http\Controllers\Backend\Admin\BatchController as AdminBatchController;
use App\Http\Controllers\Backend\Admin\SubjectTeacherMappingController as AdminSubjectTeacherMappingController;
use App\Http\Controllers\Backend\Admin\ClassRoutineController as AdminClassRoutineController;
use App\Http\Controllers\Backend\Admin\ForgotPasswordController as AdminForgotPasswordController;
use App\Http\Controllers\Backend\Admin\ResetPasswordController as AdminResetPasswordController;
use App\Http\Controllers\Backend\Admin\InstituteSessionController as AdminInstituteSessionController;
use App\Http\Controllers\Backend\Admin\EvaluationController as AdminEvaluationController;


Route::group(['prefix' => 'admin'], function () {
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
        Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login');
        Route::get('/register', [AdminAuthController::class, 'showRegister'])->name('admin.register');
        Route::post('/register', [AdminAuthController::class, 'register'])->name('admin.register');
        Route::get('/register/institute/{token}', [AdminAcademicInstituteController::class, 'create'])
            ->name('admin.register.institute.create');
        Route::get('/register/institute', function (){return redirect()->route('admin.register');})->name('abackend.admin.login');

        Route::post('/register/institute', [AdminAcademicInstituteController::class, 'store'])
            ->name('admin.register.institute.store');

        Route::get('/forgot-password', [AdminForgotPasswordController::class, 'create'])
            ->name('admin.forgot-password');

        Route::post('/forgot-password', [AdminForgotPasswordController::class, 'store'])
            ->name('admin.password.email');

        Route::get('/reset-password/{token}', [AdminResetPasswordController::class, 'create'])
            ->name('password.reset');

        Route::post('/reset-password', [AdminResetPasswordController::class, 'store'])
            ->name('password.update');


    });

    Route::middleware(['auth:admin','admin.approved'])->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, "index"])->name('admin.dashboard');
        Route::get('/attendance', [AdminAttendanceController::class, "index"])->name('admin.attendance.index');
        Route::get('/department', [AdminDepartmentController::class, "index"])->name('admin.department.index');

        //Department
        Route::post('/department', [AdminDepartmentController::class, "store"])->name('admin.department.store');
        Route::delete('/department/{id}', [AdminDepartmentController::class, "destroy"])->name('admin.department.destroy');
        Route::put('/department', [AdminDepartmentController::class, "update"])->name('admin.department.update');
        Route::get('/department/get_department/{id}', [AdminDepartmentController::class, "get_department"])->name('admin.department.get_department');
        Route::get('/department/get_department_programs', [AdminDepartmentController::class, 'get_department_programs'])->name('admin.department.get_department_programs');
        Route::get('/department/getAllDepartments', [AdminDepartmentController::class, 'getAllDepartments']);
        Route::get('/department/getSubjects', [AdminDepartmentController::class, 'getSubjects']);
        Route::post('/department/section', [AdminDepartmentController::class, 'storeSection'])->name('admin.section.store');
        Route::get('/department/{id}/mappings', [AdminDepartmentController::class, 'getTeacherMappings'])->name('admin.department.mappings.index');

        //Section
        Route::get('/sections/getBySubject/{id}', [AdminDepartmentController::class, 'getBySubject'])->name('admin.section.getBySubject');
        Route::get('/sections/{id}/edit', [AdminSectionControllerApi::class, 'edit'])->name('admin.section.edit');
        Route::PUT('/sections/{id}/', [AdminSectionControllerApi::class, 'update'])->name('admin.section.update');
        Route::delete('/sections/{id}/', [AdminSectionControllerApi::class, 'destroy'])->name('admin.section.destroy');

        //Subjects
        Route::get('/subjects', [AdminSubjectController::class, 'index'])->name('admin.subjects.index');
        Route::get('/subjects/getAll', [AdminSubjectController::class, 'getAll'])->name('admin.subjects.getAll');
        Route::get('/subject/create', [AdminSubjectController::class, 'create'])->name('admin.subjects.create');
        Route::post('/subject', [AdminSubjectController::class, 'store'])->name('admin.subjects.store');
        Route::get('/subject/{id}/edit', [AdminSubjectController::class, 'edit'])->name('admin.subjects.edit');
        Route::get('/subject/{id}/evaluations', [AdminSubjectController::class, 'getEvaluationFormats']);
        Route::put('/subject/{id}/edit', [AdminSubjectController::class, 'update'])->name('admin.subjects.update');
        Route::delete('/subject/{id}', [AdminSubjectController::class, 'destroy'])->name('admin.subjects.destroy');

        //SubjectChapters
        Route::post('/subjects/{subject}/chapters', [ChapterController::class, 'store'])
            ->name('admin.subjects.chapters.store');
// In your web.php routes file
        Route::get('/programs/{program}/semesters', [AdminSubjectController::class, 'getSemesters'])->name('admin.programs.semesters');
//        Programs
        Route::get('/programs', [AdminProgramController::class, 'index'])->name('admin.programs.index');
        Route::get('/programs/{id}/edit', [AdminProgramController::class, 'edit'])->name('admin.programs.edit');
        Route::get('/programs/{id}/', [AdminProgramController::class, 'show'])->name('admin.programs.show');
        Route::put('/programs/{id}', [AdminProgramController::class, 'update'])->name('admin.programs.update');
        Route::post('/programs', [AdminProgramController::class, 'store'])->name('admin.programs.store');
        Route::delete('/programs/{id}', [AdminProgramController::class, 'destroy'])->name('admin.programs.destroy');
        Route::get('/department/get_program_semesters', [AdminProgramController::class, 'get_program_semesters'])->name('admin.department.get_program_semesters');
        Route::get('/programs/{id}/semesters', [AdminProgramController::class, 'getSemesters']);
        Route::get('/programs/{id}/subjects', [AdminProgramController::class, 'getSubjects']);

        //Program Batch Controller
        Route::POST('/program/batch', [AdminBatchController::class, 'store'])->name('admin.program.batch');
        Route::PUT('/program/{id}', [AdminProgramController::class, 'update'])->name('admin.program.put');
        Route::get('/program/batch/create', [AdminBatchController::class, 'create'])->name('admin.program.batch.create');


        //Academic Institute
        Route::get('/program/section/create', [AdminBatchController::class, 'create'])->name('admin.program.section.create');


        //Testimonial
        Route::get('/testimonial', [AdminTestimonialController::class, "index"])->name('admin.testimonial.index');
        Route::post('/testimonial', [AdminTestimonialController::class, "store"])->name('admin.testimonial.store');
        Route::delete('/testimonial/{id}', [AdminTestimonialController::class, "destroy"])->name('admin.testimonial.destroy');
        Route::put('/testimonial/{id}', [AdminTestimonialController::class, "update"])->name('admin.testimonial.update');
        Route::get('/testimonial/get_testimonial/{id}', [AdminTestimonialController::class, "get_testimonial"])->name('admin.testimonial.get_testimonial');

        //Announcement
        Route::get('/announcement', [AdminAnnouncementController::class, "index"])->name('admin.announcement.index');
        Route::get('/announcement/new', [AdminAnnouncementController::class, "create"])->name('admin.announcement.create');
        Route::get('/announcement/{id}', [AdminAnnouncementController::class, "show"])->name('admin.announcement.show');
        Route::PUT('/announcement/{id}', [AdminAnnouncementController::class, "update"])->name('admin.announcement.update');
        Route::delete('/announcement/{id}', [AdminAnnouncementController::class, "destroy"])->name('admin.announcement.destroy');
        Route::put('/announcement/pin/{id}/', [AdminAnnouncementController::class, "setPin"])->name('admin.announcement.pin');
        Route::get('/announcement/{id}/edit', [AdminAnnouncementController::class, "edit"])->name('admin.announcement.edit');
        Route::post('/announcement/store', [AdminAnnouncementController::class, "store"])->name('admin.announcement.store');
        Route::get('/announcement/attachment/{id}', [AdminAnnouncementController::class, "deleteAttachment"])->name('admin.announcement.deleteAttachment');

        Route::get("/test-email", [AdminAnnouncementController::class, "email"]);

        Route::get('/student', [AdminStudentController::class, "index"])->name('admin.student.index');
        Route::get('/student/{id}', [AdminStudentController::class, "show"])->name('admin.student.show');
        Route::get('/students/unapproved-students', [AdminStudentController::class, "index_pending_students"])->name('admin.student.unapproved.index');

        Route::put('/student/approve/{id}', [AdminStudentController::class, "approve_student"])->name('admin.student.approve');
        Route::put('/student/unapprove/{id}', [AdminStudentController::class, "unapprove_student"])->name('admin.student.unapprove');
        Route::POST('/student/status/{id}', [AdminStudentController::class, "toggle_status"])->name('admin.student.status');
        Route::get('/student/download/excel', [AdminStudentController::class, "generatePDF"])->name('admin.student.download.excel');

        //Teacher
        Route::get('/teacher', [AdminTeacherController::class, "index"])->name('admin.teacher.index');
        Route::get('/teacher/{id}', [AdminTeacherController::class, "show"])->name('admin.teacher.show');
        Route::get('/teachers/getAll', [AdminTeacherController::class, "getAll"])->name('admin.teacher.getAll');
        Route::get('/teacher/unapprovedd', [AdminTeacherController::class, "index_pending_teachers"])->name('admin.teacher.unapproved.index');
        Route::put('/teacher/approve/{id}', [AdminTeacherController::class, "approve_teacher"])->name('admin.teacher.approve');
        Route::put('/teacher/unapprove/{id}', [AdminTeacherController::class, "unapprove_teacher"])->name('admin.teacher.unapprove');
        Route::POST('/teacher/status/{id}', [AdminTeacherController::class, "toggle_status"])->name('admin.teacher.status');
        Route::get('/teacher/download/excel', [AdminTeacherController::class, "generatePDF"])->name('admin.teacher.download.excel');
        Route::get('/teacher/{id}/getTiming', [AdminTeacherController::class, "getTiming"])->name('admin.subject-teacher.getTiming');

        //SubjectTeacher
        Route::get('/subject-teacher', [AdminSubjectTeacherController::class, "index"])->name('admin.subject-teacher.index');
        Route::POST('/subject-teacher', [AdminSubjectTeacherMappingController::class, "store"])->name('admin.subject-teacher.store');
        Route::PUT('/subject-teacher/{id}', [AdminSubjectTeacherMappingController::class, "update"])->name('admin.subject-teacher.update');
        Route::GET('/subject-teacher/mapping', [AdminSubjectTeacherMappingController::class, "index"])->name('admin.subject-teacher.mapping.index');
        Route::delete('/subject-teacher/{id}/mapping', [AdminSubjectTeacherMappingController::class, "destroy"])->name('admin.subject-teacher.mapping.destroy');

        //SubjectTeacher Mapping
        Route::get('/mapping/{selectedMappingId}/timing', [AdminSubjectTeacherMappingController::class, "getTiming"])->name('admin.subject-teacher.mapping.getTiming');

        //RoutinePlanner
        Route::get('/routine-planner', [AdminClassRoutineController::class, "index"])->name('admin.routine-planner.index');
        Route::POST('/routine-planner', [AdminClassRoutineController::class, "store"])->name('admin.routine-planner.store');

        //Routine
        Route::get('/routines', [AdminClassRoutineController::class, "getRoutines"])->name('admin.routines.index');
        Route::post('/routines', [AdminClassRoutineController::class, "store"])->name('admin.routines.store');
        Route::put('/routines/{id}', [AdminClassRoutineController::class, "update"])->name('admin.routines.update');
        Route::delete('/routines/{id}', [AdminClassRoutineController::class, "destroy"])->name('admin.routines.destroy');
        Route::get('/routines/{id}', [AdminClassRoutineController::class, "show"])->name('admin.routines.show');

        Route::get('/profile', [AdminProfileController::class, "index"])->name("admin.profile.index");
        Route::PUT('/profile/change-password', [AdminProfileController::class, "changePassword"])->name("admin.profile.changePassword");
        Route::PUT('/profile/update', [AdminProfileController::class, "store"])->name("admin.profile.update");
        Route::get('/setting', [AdminProfileController::class, "index"])->name("admin.settings");
        Route::get('/notifications', [AdminProfileController::class, "index"])->name("admin.notifications");

        Route::get('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

        Route::get('/trigger', function () {
            broadcast(new \App\Events\TestEvent);
            return 'Event has been broadcast!';
        });


        ############################################# StudentPassPredictionController #############################################
        Route::get('/prediction', [App\Http\Controllers\Algorithm\StudentPassPredictionController::class, 'index'])->name('admin.prediction.index');
        Route::post('/prediction/train', [App\Http\Controllers\Algorithm\StudentPassPredictionController::class, 'train'])->name('admin.prediction.train');
        Route::post('/prediction/predict', [App\Http\Controllers\Algorithm\StudentPassPredictionController::class, 'predict'])->name('admin.prediction.predict');


        ############################################# Institute Session #############################################
        Route::get('/session', [AdminInstituteSessionController::class, 'index'])->name('admin.session.index');
        Route::POST('/sessions/bulk-create', [AdminInstituteSessionController::class, 'bulkCreate'])->name('admin.session.bulkCreate');
        Route::PUT('/sessions/{id}', [AdminInstituteSessionController::class, 'update'])->name('admin.session.update');
        Route::delete('/sessions/{session}', [AdminInstituteSessionController::class, 'destroy'])
            ->name('admin.sessions.destroy');


        // Attendance routes
        Route::get('/attendance', [AdminAttendanceController::class, "index"])->name('admin.attendance.index');

        Route::get('/attendance/location', [AdminAttendanceController::class, "showLocationSetup"])->name('admin.attendance.location.index');
        Route::post('/attendance/location', [AdminAttendanceController::class, "updateLocation"])->name('admin.attendance.location.update');
        Route::delete('/attendance/location', [AdminAttendanceController::class, "deleteLocation"])->name('admin.attendance.location.delete');
        Route::get('/attendance/create', [AdminAttendanceController::class, "create"])->name('admin.attendance.create');
        Route::post('/attendance', [AdminAttendanceController::class, "store"])->name('admin.attendance.store');
        Route::get('/attendance/{id}', [AdminAttendanceController::class, "show"])->name('admin.attendance.show');
        Route::get('/attendance/{id}/edit', [AdminAttendanceController::class, "edit"])->name('admin.attendance.edit');
        Route::put('/attendance/{id}', [AdminAttendanceController::class, "update"])->name('admin.attendance.update');
        Route::delete('/attendance/{id}', [AdminAttendanceController::class, "destroy"])->name('admin.attendance.destroy');
        Route::get('/attendance/export/csv', [AdminAttendanceController::class, "export"])->name('admin.attendance.export');
        Route::post('/attendance/bulk', [AdminAttendanceController::class, "bulkStore"])->name('admin.attendance.bulk');


        ########################################### Evaluations #############################################
        Route::get('/evaluations', [AdminEvaluationController::class, 'index'])->name('admin.evaluations.index');
        Route::get('/evaluations/results', [AdminEvaluationController::class, 'evaluation'])->name('admin.evaluations.evaluation');
        Route::get('/evaluations/download-pdf', [AdminEvaluationController::class, 'downloadResultsPdf'])->name('admin.evaluations.download-pdf');

        // Batch Management Routes
        Route::get('/batches', [AdminBatchController::class, 'index'])->name('admin.batches.index');
        Route::get('/batches/{id}', [AdminBatchController::class, 'show'])->name('admin.batches.show');
        Route::get('/batches/{id}/edit', [AdminBatchController::class, 'edit'])->name('admin.batches.edit');
        Route::put('/batches/{id}', [AdminBatchController::class, 'update'])->name('admin.batches.update');
        Route::delete('/batches/{id}', [AdminBatchController::class, 'destroy'])->name('admin.batches.destroy');
        Route::get('/batches/{id}/subjects', [AdminBatchController::class, 'getSubjects'])->name('admin.batches.subjects');
        Route::get('/batches/get-batches', [AdminBatchController::class, 'getBatches'])->name('admin.batches.getBatches');

        /*
         * API
         *
         * */

        //Teacher Profile
        Route::get('/api/teacher/{id}', [AdminTeacherController::class, "getProfile"])->name('teacher.assignment.index');

    });
});
