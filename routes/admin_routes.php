<?php

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
        Route::get('/attendance-face', [AdminAttendanceController::class, "face_index"])->name('admin.attendance-face.index');

//        Department
        Route::post('/department', [AdminDepartmentController::class, "store"])->name('admin.department.store');
        Route::delete('/department/{id}', [AdminDepartmentController::class, "destroy"])->name('admin.department.destroy');
        Route::put('/department', [AdminDepartmentController::class, "update"])->name('admin.department.update');
        Route::get('/department/get_department/{id}', [AdminDepartmentController::class, "get_department"])->name('admin.department.get_department');
        Route::get('/department/get_department_programs', [AdminDepartmentController::class, 'get_department_programs'])->name('admin.department.get_department_programs');

//        Programs
        Route::get('/programs', [AdminProgramController::class, 'index'])->name('admin.programs.index');
        Route::post('/programs', [AdminProgramController::class, 'store'])->name('admin.programs.store');
        Route::delete('/programs/{id}', [AdminProgramController::class, 'destroy'])->name('admin.programs.destroy');
        Route::get('/department/get_program_semesters', [AdminProgramController::class, 'get_program_semesters'])->name('admin.department.get_program_semesters');




        Route::get('/testimonial', [AdminTestimonialController::class, "index"])->name('admin.testimonial.index');
        Route::post('/testimonial', [AdminTestimonialController::class, "store"])->name('admin.testimonial.store');
        Route::delete('/testimonial/{id}', [AdminTestimonialController::class, "destroy"])->name('admin.testimonial.destroy');
        Route::put('/testimonial', [AdminTestimonialController::class, "update"])->name('admin.testimonial.update');
        Route::get('/testimonial/get_testimonial/{id}', [AdminTestimonialController::class, "get_testimonial"])->name('admin.testimonial.get_testimonial');

        Route::get('/announcement', [AdminAnnouncementController::class, "index"])->name('admin.announcement.index');
        Route::get('/announcement/create', [AdminAnnouncementController::class, "create"])->name('admin.announcement.create');

        Route::get('/student', [AdminStudentController::class, "index"])->name('admin.student.index');
        Route::get('/teacher', [AdminTeacherController::class, "index"])->name('admin.teacher.index');

        Route::get('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

    });
});
