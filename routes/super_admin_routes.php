<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\SuperAdmin\AuthController as SuperAdminAuthController;
use App\Http\Controllers\Backend\SuperAdmin\DashboardController as SuperAdminDashboardController;
use App\Http\Controllers\Backend\SuperAdmin\AdminManagementContoller as SuperAdminAdminManagementController;
use App\Http\Controllers\Backend\SuperAdmin\TestimonialController as SuperAdminTestimonialController;
use App\Http\Controllers\SuperAdmin\InstituteController as SuperAdminInstituteController;
use App\Http\Controllers\SuperAdmin\SettingController as SuperAdminSettingController;
use App\Http\Controllers\Backend\SuperAdmin\ProfileController as SuperAdminProfileController;

Route::group(['prefix' => 'superadmin'], function () {
    Route::middleware('guest:super_admin')->group(function () {
        Route::get('/login', [SuperAdminAuthController::class, 'showLogin'] )->name('superadmin.login');
        Route::POST('/login', [SuperAdminAuthController::class, 'login'] )->name('superadmin.login');
    });

    Route::middleware(['auth:super_admin'])->group(function () {
        Route::get('/dashboard', [SuperAdminDashboardController::class, 'index'] )->name('superadmin.index');
        Route::get('/admin-management', [SuperAdminAdminManagementController::class, 'index'] )->name('superadmin.admin-management');
        Route::get('/admins', [SuperAdminDashboardController::class, 'index'] )->name('superadmin.index');
        Route::get('/logout', [SuperAdminAuthController::class, 'logout'] )->name('superadmin.logout');

//Testimonial
        Route::get('/testimonial', [SuperAdminTestimonialController::class, "index"])->name('superadmin.testimonial.index');
        Route::post('/testimonial', [SuperAdminTestimonialController::class, "store"])->name('superadmin.testimonial.store');
        Route::delete('/testimonial/{id}', [SuperAdminTestimonialController::class, "destroy"])->name('superadmin.testimonial.destroy');
        Route::put('/testimonial', [SuperAdminTestimonialController::class, "update"])->name('superadmin.testimonial.update');
        Route::get('/testimonial/get_testimonial/{id}', [SuperAdminTestimonialController::class, "get_testimonial"])->name('superadmin.testimonial.get_testimonial');

        //Institute
        Route::get('/institute', [SuperAdminInstituteController::class, "index"])->name('superadmin.institute.index');

//        Profile
        Route::get('/profile', [SuperAdminProfileController::class, "index"])->name('superadmin.profile.index');


        //Setting
        Route::get('/setting', [SuperAdminSettingController::class, "index"])->name('superadmin.setting.index');
        Route::get('/setting/contacts', [SuperAdminSettingController::class, "contact_index"])->name('superadmin.setting.contact');
        Route::PUT('/setting/contacts', [SuperAdminSettingController::class, "store_contact"])->name('superadmin.setting.contact.update');
        Route::put('/setting', [SuperAdminSettingController::class, "store_general"])->name('superadmin.general.update');

    });
});
