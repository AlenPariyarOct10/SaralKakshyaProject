<?php

use App\Models\Testimonial;
use Illuminate\Support\Facades\Route;

Route::get('/', function (){
    $testimonial = Testimonial::orderBy('rank','asc')->get();
    return view('welcome', compact('testimonial'));
});

require __DIR__ . '/admin_routes.php';
require __DIR__ . '/student_routes.php';
require __DIR__ . '/teacher_routes.php';
require __DIR__ . '/super_admin_routes.php';


