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
    // Get all institutes with location data for initial load
    $institutes = Institute::whereNotNull('latitude')
        ->whereNotNull('longitude')
        ->whereNotNull('threshold')
        ->get();

    return view('frontend.face-attendance', compact('system', 'institutes'));
})->name('face.attendance');


Route::post('/attendance/nearby-institutes', function (\Illuminate\Http\Request $request) {
    try {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric'
        ]);

        $userLat = $request->latitude;
        $userLng = $request->longitude;

        // Log the incoming request for debugging
        \Log::info('Location request received', [
            'latitude' => $userLat,
            'longitude' => $userLng
        ]);

        // Get institutes with location data
        $institutes = Institute::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->whereNotNull('threshold')
            ->get();

        // Log institutes found
        \Log::info('Institutes with location data', [
            'count' => $institutes->count(),
            'institutes' => $institutes->pluck('name', 'id')->toArray()
        ]);

        $nearbyInstitutes = [];

        foreach ($institutes as $institute) {
            // Calculate distance using Haversine formula
            $distance = calculateDistance(
                $userLat,
                $userLng,
                $institute->latitude,
                $institute->longitude
            );

            // 10 meter
            if (round($distance, 2) <= $institute->threshold)
            {
                $nearbyInstitutes[] = [
                    'id' => $institute->id,
                    'name' => $institute->name,
                    'distance' => round($distance, 2),
                    'threshold' => $institute->threshold
                ];
            }


        }

        // Sort by distance (closest first)
        usort($nearbyInstitutes, function($a, $b) {
            return $a['distance'] <=> $b['distance'];
        });

        \Log::info('Nearby institutes found', [
            'count' => count($nearbyInstitutes),
            'institutes' => $nearbyInstitutes
        ]);

        return response()->json([
            'success' => true,
            'institutes' => $nearbyInstitutes,
            'user_location' => [
                'latitude' => $userLat,
                'longitude' => $userLng
            ],
            'debug' => [
                'total_institutes_checked' => $institutes->count(),
                'institutes_found' => count($nearbyInstitutes)
            ]
        ]);

    } catch (\Exception $e) {
        \Log::error('Error in nearby-institutes route', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Error processing location request',
            'error' => $e->getMessage()
        ], 500);
    }
})->name('attendance.nearby-institutes');

//Haversine Formula
function calculateDistance($lat1, $lng1, $lat2, $lng2) {
    // Validate inputs
    if (!is_numeric($lat1) || !is_numeric($lng1) || !is_numeric($lat2) || !is_numeric($lng2)) {
        return PHP_INT_MAX; // Return very large distance for invalid coordinates
    }

    $earthRadius = 6371000; // Earth's radius in meters

    $dLat = deg2rad($lat2 - $lat1);
    $dLng = deg2rad($lng2 - $lng1);

    $a = sin($dLat / 2) * sin($dLat / 2) +
        cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
        sin($dLng / 2) * sin($dLng / 2);

    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

    $distance = $earthRadius * $c; // Distance in meters

    return $distance;
}

Route::get('login/{role?}', function ($role = null) {
    // Default to 'guest' or another role if none is passed in the URL
    $role = $role ?? 'guest';

    // Redirect to specific login page based on role
    switch ($role) {
        case 'super_admin':
            return redirect()->route('superadmin.login');
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
require __DIR__ . '/api/teacher_routes.php';
