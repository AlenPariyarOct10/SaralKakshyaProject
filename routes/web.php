<?php

use App\Models\Testimonial;
use Illuminate\Support\Facades\Route;
use PHPUnit\Event\Telemetry\System;

Route::get('/', function (){
    $testimonial = Testimonial::orderBy('rank','asc')->get();
    $system = \App\Models\SystemSetting::first();
    return view('welcome', compact('testimonial', 'system'));
});

require __DIR__ . '/admin_routes.php';
require __DIR__ . '/student_routes.php';
require __DIR__ . '/teacher_routes.php';
require __DIR__ . '/super_admin_routes.php';


