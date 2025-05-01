<?php

use App\Events\RealtimeEvent;
use App\Models\Testimonial;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;
use PHPUnit\Event\Telemetry\System;
use App\Http\Controllers\EventCheck;

Route::get('/', function (){
    $testimonial = Testimonial::orderBy('rank','asc')->get();
    $system = \App\Models\SystemSetting::first();
    return view('welcome', compact('testimonial', 'system'));
});

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
