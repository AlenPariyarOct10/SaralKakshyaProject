<?php

namespace App\Http\Controllers\Backend\Teacher;

use App\Http\Controllers\Controller;
use App\Models\TeacherAvailability;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;

class TeacherAvailabilityController extends Controller
{
    /**
     * Display the availability schedule page.
     */
    public function index()
    {
        $teacher = Teacher::where('id', Auth::id())->firstOrFail();

        // Fetch all availability data for the teacher
        $availabilities = $teacher->availabilities()->get();

        // Group availabilities by day
        $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        $availabilityByDay = [];
        foreach ($days as $day) {
            $availabilityByDay[$day] = $availabilities->where('day_of_week', $day)->values();
        }

        // Pass the grouped data to the view
        return view('backend.teacher.availability.index', compact('teacher', 'availabilityByDay'));
    }
    /**
     * Store or update teacher's availability.
     */
    public function store(Request $request)
    {
        $teacher = Teacher::where('id', Auth::id())->firstOrFail();

        if (!$teacher) {
            return response()->json([
                'status' => 'error',
                'message' => 'Teacher profile not found. Please complete your profile.'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'teacher_id' => 'required|exists:teachers,id',
            'enabled_days' => 'nullable|array',
            'enabled_days.*' => 'in:Sunday,Monday,Tuesday,Wednesday,Thursday,Friday,Saturday',
            'availability' => 'nullable|array',
            'availability.*.*.start_time' => 'required|date_format:H:i',
            'availability.*.*.end_time' => 'required|date_format:H:i|after:availability.*.*.start_time',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Delete existing availabilities for the teacher
        TeacherAvailability::where('teacher_id', $teacher->id)->delete();

        // Process new availabilities only for enabled days
        if ($request->has('enabled_days') && $request->has('availability')) {
            foreach ($request->enabled_days as $day) {
                if (isset($request->availability[$day])) {
                    foreach ($request->availability[$day] as $slot) {
                        TeacherAvailability::create([
                            'teacher_id' => $teacher->id,
                            'institute_id' => Session::get('institute_id'),
                            'day_of_week' => ucfirst($day),
                            'start_time' => $slot['start_time'],
                            'end_time' => $slot['end_time'],
                            'is_available' => true,
                        ]);
                    }
                }
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Availability updated successfully'
        ], 200);
    }

    /**
     * Update a specific availability slot.
     */
    public function update(Request $request, $id)
    {
        $teacher = Teacher::where('id', Auth::id())->firstOrFail();

        if (!$teacher) {
            return response()->json([
                'status' => 'error',
                'message' => 'Teacher profile not found. Please complete your profile.'
            ], 403);
        }

        $availability = TeacherAvailability::findOrFail($id);

        if ($availability->teacher_id !== $teacher->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'day_of_week' => 'required|in:Sunday,Monday,Tuesday,Wednesday,Thursday,Friday,Saturday',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $availability->update([
            'day_of_week' => $request->day_of_week,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'is_available' => true,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Availability slot updated successfully'
        ], 200);
    }

    /**
     * Remove a specific availability slot.
     */
    public function destroy($id)
    {
        $teacher = Teacher::where('id', Auth::id())->firstOrFail();
        if (!$teacher) {
            return response()->json([
                'status' => 'error',
                'message' => 'Teacher profile not found. Please complete your profile.'
            ], 403);
        }

        $availability = TeacherAvailability::findOrFail($id);

        if ($availability->teacher_id !== $teacher->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 403);
        }

        $availability->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Availability slot deleted successfully'
        ], 200);
    }
}
