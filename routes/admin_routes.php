<?php

use App\Events\TestEvent;
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

        //Subjects
        Route::get('/subjects', [AdminSubjectController::class, 'index'])->name('admin.subjects.index');
        Route::get('/subject/create', [AdminSubjectController::class, 'create'])->name('admin.subjects.create');
        Route::post('/subject', [AdminSubjectController::class, 'store'])->name('admin.subjects.store');
        Route::get('/subject/{id}/edit', [AdminSubjectController::class, 'edit'])->name('admin.subjects.edit');
        Route::get('/subject/{id}/evaluations', [AdminSubjectController::class, 'getEvaluationFormats']);
        Route::put('/subject/{id}/edit', [AdminSubjectController::class, 'update'])->name('admin.subjects.update');
        Route::delete('/subject/{id}', [AdminSubjectController::class, 'destroy'])->name('admin.subjects.destroy');

//        Programs
        Route::get('/programs', [AdminProgramController::class, 'index'])->name('admin.programs.index');
        Route::get('/programs/{id}/edit', [AdminProgramController::class, 'edit'])->name('admin.programs.edit');
        Route::put('/programs/{id}', [AdminProgramController::class, 'update'])->name('admin.programs.update');
        Route::post('/programs', [AdminProgramController::class, 'store'])->name('admin.programs.store');
        Route::delete('/programs/{id}', [AdminProgramController::class, 'destroy'])->name('admin.programs.destroy');
        Route::get('/department/get_program_semesters', [AdminProgramController::class, 'get_program_semesters'])->name('admin.department.get_program_semesters');
        Route::get('/programs/{id}/semesters', [AdminProgramController::class, 'getSemesters']);
        Route::get('/programs/{id}/subjects', [AdminProgramController::class, 'getSubjects']);

        //Program Bacth Controller
        Route::POST('/program/batch', [AdminBatchController::class, 'store'])->name('admin.program.batch');

        //Testimonial
        Route::get('/testimonial', [AdminTestimonialController::class, "index"])->name('admin.testimonial.index');
        Route::post('/testimonial', [AdminTestimonialController::class, "store"])->name('admin.testimonial.store');
        Route::delete('/testimonial/{id}', [AdminTestimonialController::class, "destroy"])->name('admin.testimonial.destroy');
        Route::put('/testimonial/{id}', [AdminTestimonialController::class, "update"])->name('admin.testimonial.update');
        Route::get('/testimonial/get_testimonial/{id}', [AdminTestimonialController::class, "get_testimonial"])->name('admin.testimonial.get_testimonial');

        //Announcement
        Route::get('/announcement', [AdminAnnouncementController::class, "index"])->name('admin.announcement.index');
        Route::get('/announcement/{id}', [AdminAnnouncementController::class, "show"])->name('admin.announcement.show');
        Route::delete('/announcement/{id}', [AdminAnnouncementController::class, "destroy"])->name('admin.announcement.destroy');
        Route::put('/announcement/pin/{id}/', [AdminAnnouncementController::class, "setPin"])->name('admin.announcement.pin');
        Route::get('/announcement/{id}/edit', [AdminAnnouncementController::class, "edit"])->name('admin.announcement.edit');
        Route::get('/announcement/create', [AdminAnnouncementController::class, "create"])->name('admin.announcement.create');
        Route::post('/announcement/store', [AdminAnnouncementController::class, "store"])->name('admin.announcement.store');

        Route::get("/test-email", [AdminAnnouncementController::class, "email"]);

        Route::get('/student', [AdminStudentController::class, "index"])->name('admin.student.index');
        Route::get('/student/unapproved', [AdminStudentController::class, "index_pending_students"])->name('admin.student.unapproved.index');
        Route::put('/student/approve/{id}', [AdminStudentController::class, "approve_student"])->name('admin.student.approve');
        Route::POST('/student/status/{id}', [AdminStudentController::class, "toggle_status"])->name('admin.student.status');
        Route::get('/student/download/excel', [AdminStudentController::class, "generatePDF"])->name('admin.student.download.excel');
        //Teacher
        Route::get('/teacher', [AdminTeacherController::class, "index"])->name('admin.teacher.index');
        Route::get('/teacher/unapproved', [AdminTeacherController::class, "index_pending_teachers"])->name('admin.teacher.unapproved.index');
        Route::put('/teacher/approve/{id}', [AdminTeacherController::class, "approve_teacher"])->name('admin.teacher.approve');
        Route::POST('/teacher/status/{id}', [AdminTeacherController::class, "toggle_status"])->name('admin.teacher.status');
        Route::get('/teacher/download/excel', [AdminTeacherController::class, "generatePDF"])->name('admin.teacher.download.excel');

        //SubjectTeacher
        Route::get('/subject-teacher', [AdminSubjectTeacherController::class, "index"])->name('admin.subject-teacher.index');



        Route::get('/profile', [AdminProfileController::class, "index"])->name("admin.profile.index");
        Route::get('/setting', [AdminProfileController::class, "index"])->name("admin.settings");
        Route::get('/notifications', [AdminProfileController::class, "index"])->name("admin.notifications");

        Route::get('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

        Route::get('/trigger', function () {
            broadcast(new \App\Events\TestEvent);
            return 'Event has been broadcast!';
        });
    });
});
