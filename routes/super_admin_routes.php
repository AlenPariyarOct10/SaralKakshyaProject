<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'superadmin'], function () {
    Route::middleware('guest:super_admin')->group(function () {
        Route::get('login', 'LoginController@showLoginForm')->name('superadmin.login');
    });

    Route::middleware(['auth:super_admin'])->group(function () {

    });
});
