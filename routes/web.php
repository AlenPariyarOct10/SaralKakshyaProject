<?php

use App\Events\RealtimeEvent;
use App\Models\Institute;
use App\Models\Testimonial;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;
use PHPUnit\Event\Telemetry\System;
use App\Http\Controllers\EventCheck;

Route::get('/', function (){
    $testimonial = Testimonial::orderBy('rank','asc')->get();
    $system = \App\Models\SystemSetting::first();
    return view('welcome', compact('testimonial', 'system'));
})->name('welcome');

Route::get('/attendance', function (){

    $system = \App\Models\SystemSetting::first();
    $institutes = Institute::all();
    return view('frontend.face-attendance', compact( 'system', 'institutes'));
})->name('face.attendance');

Route::get('login/{role?}', function ($role = null) {
    // Default to 'guest' or another role if none is passed in the URL
    $role = $role ?? 'guest';

    // Redirect to specific login page based on role
    switch ($role) {
        case 'teacher':
            return redirect()->route('teacher.login');
        case 'admin':
            return redirect()->route('admin.login');
        case 'student':
            return redirect()->route('student.login');
        default:
            return redirect()->route('welcome');
    }
})->name('login');

// Reverb test routes
Route::get('/fire-test', [EventCheck::class, 'fireTest']);
Route::get('/reverb-status', [EventCheck::class, 'checkStatus']);
Route::view('/reverb-test', 'pusher-view.test');

Route::get('/test-event', function() {
    broadcast(new \App\Events\RealtimeEvent('Test message from route'));
    return "Event fired!";
});

require __DIR__ . '/admin_routes.php';
require __DIR__ . '/student_routes.php';
require __DIR__ . '/teacher_routes.php';
require __DIR__ . '/super_admin_routes.php';

require __DIR__ . '/api/student_routes.php';
