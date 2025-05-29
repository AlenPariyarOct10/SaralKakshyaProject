<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Program;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::guard('admin')->user();
        $instituteId = session()->get('institute_id');

        // Get programs for filter dropdown
        $programs = Program::where('institute_id', $instituteId)->get();

        // Build query with filters
        $query = Attendance::with(['student.program'])
            ->where('institute_id', $instituteId);

        // Apply filters
        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        if ($request->filled('program_id')) {
            $query->whereHas('student', function($q) use ($request) {
                $q->where('program_id', $request->program_id);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('student', function($q) use ($search) {
                $q->where(DB::raw("CONCAT(fname, ' ', lname)"), 'LIKE', "%{$search}%")
                    ->orWhere('roll_number', 'LIKE', "%{$search}%");
            });
        }

        // Get paginated results
        $attendances = $query->orderBy('date', 'desc')
            ->orderBy('attended_at', 'desc')
            ->paginate(15);

        // Calculate statistics
        $stats = $this->getAttendanceStats($instituteId);

        return view('backend.admin.attendance', compact('user', 'attendances', 'programs', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::guard('admin')->user();
        $instituteId = session()->get('institute_id');

        $programs = Program::where('institute_id', $instituteId)->get();
        $students = Student::whereHas('institutes', function($q) use ($instituteId) {
            $q->where('institutes.id', $instituteId);
        })->get();

        return view('backend.admin.attendance-create', compact('user', 'programs', 'students'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:students,id',
            'date' => 'required|date',
            'status' => 'required|in:present,absent,late,excused',
            'attended_at' => 'nullable|date_format:H:i',
            'remarks' => 'nullable|string|max:500'
        ]);

        $instituteId = session()->get('institute_id');
        $creatorId = Auth::guard('admin')->id();

        foreach ($request->student_ids as $studentId) {
            Attendance::updateOrCreate(
                [
                    'attendee_id' => $studentId,
                    'attendee_type' => 'student',
                    'institute_id' => $instituteId,
                    'date' => $request->date,
                ],
                [
                    'status' => $request->status,
                    'attended_at' => $request->attended_at ?
                        Carbon::parse($request->date . ' ' . $request->attended_at) : null,
                    'remarks' => $request->remarks,
                    'creator_type' => 'admin',
                    'creator_id' => $creatorId,
                    'method' => 'manual'
                ]
            );
        }

        return redirect()->route('admin.attendance.index')
            ->with('success', 'Attendance marked successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $attendance = Attendance::with(['student', 'institute'])
            ->where('institute_id', session()->get('institute_id'))
            ->findOrFail($id);

        return response()->json($attendance);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = Auth::guard('admin')->user();
        $attendance = Attendance::with(['student'])
            ->where('institute_id', session()->get('institute_id'))
            ->findOrFail($id);

        return view('backend.admin.attendance-edit', compact('user', 'attendance'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'status' => 'required|in:present,absent,late,excused',
            'attended_at' => 'nullable|date_format:H:i',
            'remarks' => 'nullable|string|max:500'
        ]);

        $attendance = Attendance::where('institute_id', session()->get('institute_id'))
            ->findOrFail($id);

        $attendance->update([
            'status' => $request->status,
            'attended_at' => $request->attended_at ?
                Carbon::parse($attendance->date . ' ' . $request->attended_at) : null,
            'remarks' => $request->remarks,
        ]);

        return redirect()->route('admin.attendance.index')
            ->with('success', 'Attendance updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $attendance = Attendance::where('institute_id', session()->get('institute_id'))
            ->findOrFail($id);

        $attendance->delete();

        return response()->json(['success' => true, 'message' => 'Attendance deleted successfully!']);
    }

    /**
     * Export attendance records
     */
    public function export(Request $request)
    {
        $instituteId = session()->get('institute_id');

        $query = Attendance::with(['student.program'])
            ->where('institute_id', $instituteId);

        // Apply same filters as index
        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        if ($request->filled('program_id')) {
            $query->whereHas('student', function($q) use ($request) {
                $q->where('program_id', $request->program_id);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $attendances = $query->orderBy('date', 'desc')->get();

        $filename = 'attendance_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($attendances) {
            $file = fopen('php://output', 'w');

            // Add CSV headers
            fputcsv($file, ['Date', 'Student Name', 'Roll Number', 'Program', 'Status', 'Time', 'Remarks']);

            foreach ($attendances as $attendance) {
                fputcsv($file, [
                    $attendance->date,
                    $attendance->student->full_name ?? 'N/A',
                    $attendance->student->roll_number ?? 'N/A',
                    $attendance->student->program->name ?? 'N/A',
                    ucfirst($attendance->status),
                    $attendance->attended_at ? Carbon::parse($attendance->attended_at)->format('h:i A') : '',
                    $attendance->remarks ?? ''
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get attendance statistics
     */
    private function getAttendanceStats($instituteId)
    {
        $today = Carbon::today();

        $todayAttendance = Attendance::where('institute_id', $instituteId)
            ->whereDate('date', $today)
            ->get();

        $presentToday = $todayAttendance->where('status', 'present')->count();
        $absentToday = $todayAttendance->where('status', 'absent')->count();
        $lateToday = $todayAttendance->where('status', 'late')->count();

        $totalToday = $todayAttendance->count();
        $attendanceRate = $totalToday > 0 ? round(($presentToday / $totalToday) * 100, 1) : 0;

        return [
            'present_today' => $presentToday,
            'absent_today' => $absentToday,
            'late_today' => $lateToday,
            'attendance_rate' => $attendanceRate
        ];
    }

    /**
     * Bulk mark attendance
     */
    public function bulkStore(Request $request)
    {
        $request->validate([
            'attendances' => 'required|array',
            'attendances.*.student_id' => 'required|exists:students,id',
            'attendances.*.status' => 'required|in:present,absent,late,excused',
            'date' => 'required|date',
        ]);

        $instituteId = session()->get('institute_id');
        $creatorId = Auth::guard('admin')->id();

        foreach ($request->attendances as $attendanceData) {
            Attendance::updateOrCreate(
                [
                    'attendee_id' => $attendanceData['student_id'],
                    'attendee_type' => 'student',
                    'institute_id' => $instituteId,
                    'date' => $request->date,
                ],
                [
                    'status' => $attendanceData['status'],
                    'attended_at' => isset($attendanceData['attended_at']) ?
                        Carbon::parse($request->date . ' ' . $attendanceData['attended_at']) : null,
                    'remarks' => $attendanceData['remarks'] ?? null,
                    'creator_type' => 'admin',
                    'creator_id' => $creatorId,
                    'method' => 'bulk'
                ]
            );
        }

        return response()->json(['success' => true, 'message' => 'Bulk attendance marked successfully!']);
    }
}
