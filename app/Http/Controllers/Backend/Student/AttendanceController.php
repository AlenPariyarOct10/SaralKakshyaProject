<?php

namespace App\Http\Controllers\Backend\Student;

use App\Exports\Admin\StudentExport;
use App\Http\Controllers\Controller;
use App\Models\Attachment;
use App\Models\Attendance;
use App\Models\Institute;
use App\Models\InstituteSession;
use App\Models\InstituteStudent;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $studentId = $user->id;

        // Get student's institute information
        $instituteStudent = InstituteStudent::where('student_id', $studentId)
            ->where('is_approved', true)
            ->first();

        if (!$instituteStudent) {
            return view('backend.student.attendance.index', [
                'attendancePercentage' => 0,
                'daysPresent' => 0,
                'daysAbsent' => 0,
                'daysLate' => 0,
                'totalClassDays' => 0,
                'attendanceRecords' => [],
                'holidays' => [],
                'classSessions' => [],
                'currentMonth' => Carbon::now()->format('F Y'),
                'calendarData' => [],
                'monthlyTrend' => [],
                'startDate' => null,
                'endDate' => null,
            ]);
        }

        $instituteId = $instituteStudent->institute_id;

        // Get date range (default to current month, but allow filtering)
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth());

        if (is_string($startDate)) {
            $startDate = Carbon::parse($startDate);
        }
        if (is_string($endDate)) {
            $endDate = Carbon::parse($endDate);
        }

        $today = Carbon::today();

        // Get class sessions from InstituteSession where type = 'class'
        $classSessions = InstituteSession::where('institute_id', $instituteId)
            ->where('status', 'class')
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date', 'asc')
            ->get();

        // Filter class sessions only up to today for attendance calculation
        $classSessionsUpToToday = $classSessions->filter(function ($session) use ($today) {
            return Carbon::parse($session->date)->lte($today);
        });

        // Get holidays from InstituteSession where type = 'holiday'
        $holidays = InstituteSession::where('institute_id', $instituteId)
            ->where('status', 'holiday')
            ->whereBetween('date', [$startDate, $endDate])
            ->get();

        // Get attendance records for the student
        $attendanceRecords = Attendance::where('attendee_id', $studentId)
            ->where('attendee_type', 'student')
            ->where('institute_id', $instituteId)
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date', 'desc')
            ->get();

        // Create a comprehensive attendance analysis (only up to today)
        $attendanceAnalysis = $this->analyzeAttendance($classSessionsUpToToday, $attendanceRecords, $holidays);

        // Prepare calendar data (can include full month)
        $calendarData = $this->prepareCalendarData($classSessions, $attendanceRecords, $holidays, $startDate);

        // Get monthly trend data (last 6 months)
        $monthlyTrend = $this->getMonthlyTrend($studentId, $instituteId);

        return view('backend.student.attendance.index', array_merge($attendanceAnalysis, [
            'attendanceRecords' => $attendanceRecords,
            'holidays' => $holidays,
            'classSessions' => $classSessions,
            'calendarData' => $calendarData,
            'monthlyTrend' => $monthlyTrend,
            'startDate' => $startDate,
            'endDate' => $endDate
        ]));
    }

    /**
     * Analyze attendance based on class sessions and actual attendance records
     */
    private function analyzeAttendance($classSessions, $attendanceRecords, $holidays)
    {
        $totalClassDays = $classSessions->count();
        $attendanceByDate = $attendanceRecords->keyBy(function($item) {
            return Carbon::parse($item->date)->format('Y-m-d');
        });

        $daysPresent = 0;
        $daysAbsent = 0;
        $daysLate = 0;
        $daysExcused = 0;

        foreach ($classSessions as $session) {
            $sessionDate = Carbon::parse($session->date)->format('Y-m-d');

            if (isset($attendanceByDate[$sessionDate])) {
                $attendance = $attendanceByDate[$sessionDate];
                switch ($attendance->status) {
                    case 'present':
                        $daysPresent++;
                        break;
                    case 'late':
                        $daysLate++;
                        break;
                    case 'excused':
                        $daysExcused++;
                        break;
                    case 'absent':
                    default:
                        $daysAbsent++;
                        break;
                }
            } else {
                // No attendance record for this class session = absent
                $daysAbsent++;
            }
        }

        // Calculate attendance percentage (present + late days / total class days)
        $attendancePercentage = $totalClassDays > 0 ?
            round((($daysPresent + $daysLate) / $totalClassDays) * 100, 1) : 0;

        return [
            'attendancePercentage' => $attendancePercentage,
            'daysPresent' => $daysPresent,
            'daysAbsent' => $daysAbsent,
            'daysLate' => $daysLate,
            'daysExcused' => $daysExcused,
            'totalClassDays' => $totalClassDays
        ];
    }

    /**
     * Get attendance data for a specific month (AJAX)
     */
    public function getMonthlyAttendance(Request $request)
    {
        $user = Auth::user();
        $studentId = $user->id;
        $month = $request->get('month', Carbon::now()->month);
        $year = $request->get('year', Carbon::now()->year);

        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();

        $instituteStudent = InstituteStudent::where('student_id', $studentId)
            ->where('is_approved', true)
            ->first();

        if (!$instituteStudent) {
            return response()->json(['error' => 'Student not enrolled in any institute'], 404);
        }

        $instituteId = $instituteStudent->institute_id;

        // Get class sessions
        $classSessions = InstituteSession::where('institute_id', $instituteId)
            ->where('status', 'class')
            ->whereBetween('date', [$startDate, $endDate])
            ->get();

        // Get attendance records
        $attendanceRecords = Attendance::where('attendee_id', $studentId)
            ->where('attendee_type', 'student')
            ->where('institute_id', $instituteId)
            ->whereBetween('date', [$startDate, $endDate])
            ->get();

        // Get holidays
        $holidays = InstituteSession::where('institute_id', $instituteId)
            ->where('status', 'holiday')
            ->whereBetween('date', [$startDate, $endDate])
            ->get();

        $calendarData = $this->prepareCalendarData($classSessions, $attendanceRecords, $holidays, $startDate);
        $attendanceAnalysis = $this->analyzeAttendance($classSessions, $attendanceRecords, $holidays);

        return response()->json([
            'calendarData' => $calendarData,
            'attendanceAnalysis' => $attendanceAnalysis,
            'monthName' => $startDate->format('F Y'),
            'daysInMonth' => $startDate->daysInMonth,
            'firstDayOfWeek' => $startDate->dayOfWeek
        ]);
    }

    /**
     * Prepare calendar data for frontend
     */
    private function prepareCalendarData($classSessions, $attendanceRecords, $holidays, $startDate)
    {
        $calendarData = [];
        $daysInMonth = $startDate->daysInMonth;

        // Create array for each day of the month
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $currentDate = $startDate->copy()->day($day)->format('Y-m-d');
            $calendarData[$day] = [
                'date' => $currentDate,
                'status' => null,
                'type' => 'no_class',
                'hasClass' => false,
                'isHoliday' => false
            ];
        }

        // Mark holidays first
        foreach ($holidays as $holiday) {
            $day = Carbon::parse($holiday->date)->day;
            if (isset($calendarData[$day])) {
                $calendarData[$day]['status'] = 'holiday';
                $calendarData[$day]['type'] = 'holiday';
                $calendarData[$day]['isHoliday'] = true;
                $calendarData[$day]['notes'] = $holiday->notes;
            }
        }

        // Mark class sessions
        $classSessionsByDate = $classSessions->keyBy(function($item) {
            return Carbon::parse($item->date)->format('Y-m-d');
        });

        foreach ($classSessions as $session) {
            $day = Carbon::parse($session->date)->day;
            if (isset($calendarData[$day]) && !$calendarData[$day]['isHoliday']) {
                $calendarData[$day]['hasClass'] = true;
                $calendarData[$day]['type'] = 'class';
                $calendarData[$day]['session_notes'] = $session->notes;
                $calendarData[$day]['start_time'] = $session->start_time;
                $calendarData[$day]['end_time'] = $session->end_time;
            }
        }

        // Mark attendance records
        foreach ($attendanceRecords as $record) {
            $day = Carbon::parse($record->date)->day;
            $recordDate = Carbon::parse($record->date)->format('Y-m-d');

            if (isset($calendarData[$day])) {
                $calendarData[$day]['status'] = $record->status;
                $calendarData[$day]['type'] = 'attendance';
                $calendarData[$day]['attended_at'] = $record->attended_at;
                $calendarData[$day]['remarks'] = $record->remarks;
                $calendarData[$day]['method'] = $record->method;
            }
        }

        // Mark absent for class days without attendance records
        foreach ($classSessions as $session) {
            $day = Carbon::parse($session->date)->day;
            $sessionDate = Carbon::parse($session->date)->format('Y-m-d');

            if (isset($calendarData[$day]) &&
                !$calendarData[$day]['isHoliday'] &&
                $calendarData[$day]['hasClass'] &&
                $calendarData[$day]['status'] === null) {

                $calendarData[$day]['status'] = 'absent';
                $calendarData[$day]['type'] = 'auto_absent';
                $calendarData[$day]['remarks'] = 'Auto-marked absent (no attendance record)';
            }
        }

        return $calendarData;
    }

    /**
     * Get monthly attendance trend for the last 6 months
     */
    private function getMonthlyTrend($studentId, $instituteId)
    {
        $months = [];
        $attendanceData = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $startDate = $date->copy()->startOfMonth();
            $endDate = $date->copy()->endOfMonth();

            // Get class sessions for this month
            $classSessions = InstituteSession::where('institute_id', $instituteId)
                ->where('status', 'class')
                ->whereBetween('date', [$startDate, $endDate])
                ->get();

            // Get attendance records for this month
            $attendanceRecords = Attendance::where('attendee_id', $studentId)
                ->where('attendee_type', 'student')
                ->where('institute_id', $instituteId)
                ->whereBetween('date', [$startDate, $endDate])
                ->get();

            $analysis = $this->analyzeAttendance($classSessions, $attendanceRecords, collect());

            $months[] = $date->format('M');
            $attendanceData[] = $analysis['attendancePercentage'];
        }

        return [
            'months' => $months,
            'data' => $attendanceData
        ];
    }

    // ... (keep all other existing methods unchanged)

    public function user_info_for_face_recognition(Request $request)
    {
        $uid = $request->id;
        $institute_id = $request->institute_id;

        try {
            $user = Student::findOrFail($uid);
            $institute = Institute::findOrFail($institute_id);

            $user->institute = $institute;

            return response()->json([
                'success' => true,
                'student' => $user,
                'institute' => $institute,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'User not found or error occurred.',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function updateFacePhotos(Request $request)
    {
        $photos = $request->only(['photo_1', 'photo_2', 'photo_3', 'photo_4', 'photo_5']);

        if (count($photos) !== 5) {
            return response()->json(['success' => false, 'message' => 'Please upload exactly 5 valid photos.'], 422);
        }

        $user = auth()->user();
        $parentId = $user->id;
        $base64Images = [];

        foreach ($photos as $photo) {
            if ($photo && $photo->isValid()) {
                $extension = $photo->getClientOriginalExtension();
                $filename = uniqid('face_') . '.' . $extension;
                $path = $photo->storeAs('uploads/faces', $filename, 'public');

                Attachment::create([
                    'title'       => 'Face Photo ' . $parentId,
                    'file_type'   => $extension,
                    'parent_type' => get_class($user),
                    'parent_id'   => $parentId,
                    'path'        => $path,
                ]);

                $imageContent = Storage::disk('public')->get($path);
                $base64Images[] = base64_encode($imageContent);
            }
        }

        $response = Http::post('http://127.0.0.1:5000/update-face', [
            'student_id' => $parentId,
            'institute_id' => $parentId,
            'images'     => $base64Images,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Face photos saved and sent to Python.',
            'flask_response' => $response->json()
        ]);
    }

    public function saveFacePhotos(Request $request)
    {
        $photos = $request->only(['photo_1', 'photo_2', 'photo_3', 'photo_4', 'photo_5']);

        if (count($photos) !== 5) {
            return response()->json(['success' => false, 'message' => 'Please upload exactly 5 valid photos.'], 422);
        }

        $user = auth()->user();
        $parentId = $user->id;
        $base64Images = [];

        foreach ($photos as $photo) {
            if ($photo && $photo->isValid()) {
                $extension = $photo->getClientOriginalExtension();
                $filename = uniqid('face_') . '.' . $extension;
                $path = $photo->storeAs('uploads/faces', $filename, 'public');

                Attachment::create([
                    'title'       => 'Face Photo ' . $parentId,
                    'file_type'   => $extension,
                    'parent_type' => get_class($user),
                    'parent_id'   => $parentId,
                    'path'        => $path,
                ]);

                $imageContent = Storage::disk('public')->get($path);
                $base64Images[] = base64_encode($imageContent);
            }
        }

        $response = Http::post('http://127.0.0.1:5000/register-face', [
            'student_id' => $parentId,
            'institute_id' => $parentId,
            'images'     => $base64Images,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Face photos saved and sent to Python.',
            'flask_response' => $response->json()
        ]);
    }

    public function setup_index()
    {
        $user = Auth::user();
        return view('backend.student.attendance.setup-face', compact('user'));
    }

    public function mark_attendance(Request $request)
    {
        $student_id = $request->student_id;
        $institute_id = $request->institute_id;
        $timestamp = $request->timestamp;

        Attendance::create([
            "attendee_type"=>"student",
            "attendee_id" => $student_id,
            "institute_id" => $institute_id,
            "date" => now(),
            "attended_at" => now(),
            "subject_id" => null,
            "status"=>"present",
            "method"=>"face",
            "creator_id"=>$student_id,
            "creator_type"=>"student",
            "is_verified"=>0,
            "remarks"=>null,
        ]);
    }

    public function create() { }
    public function store(Request $request) { }
    public function show(string $id) { }
    public function edit(string $id) { }
    public function update(Request $request, string $id) { }
    public function destroy(string $id) { }
}
