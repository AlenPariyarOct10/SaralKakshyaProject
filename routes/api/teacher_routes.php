<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\Teacher\Api\SubjectController as TeacherSubjectController;

use App\Http\Controllers\Backend\Student\Api\NotificationController as ApiNotificationController;

Route::group(['prefix' => 'api/teacher'], function () {
    Route::middleware(['auth:teacher'])->group(function () {
        Route::get('/subject/{id}/chapters', [TeacherSubjectController::class, "getChapters"])->name('api.teacher.subject.chapters');
        Route::get('/chapters/{id}/sub-chapters', [TeacherSubjectController::class, "getSubChapters"])->name('api.teacher.subject.chapters');
    });
});
