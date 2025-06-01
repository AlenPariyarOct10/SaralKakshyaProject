<?php

namespace App\Http\Controllers\Backend\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Student;
use App\Models\Subject;
use App\Models\SubjectTeacherMapping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /**
     * Display the attendance management page
     */
    public function index()
    {
        $teacher = Auth::guard('teacher')->user();

        // Get subjects assigned to this teacher
        $subjects = Subject::whereHas('subjectTeacherMappings', function($query) use ($teacher) {
            $query->where('teacher_id', $teacher->id);
        })->with(['batch', 'program'])->get();

        return view('backend.teacher.attendance', compact('subjects'));
    }

    /**
     * Get students for a specific subject
     */
    public function getStudents($subjectId, Request $request)
    {
        try {
            $teacher = Auth::guard('teacher')->user();
            $date = $request->get('date', now()->format('Y-m-d'));

            // Verify teacher has access to this subject
            $subject = Subject::whereHas('subjectTeacherMappings', function($query) use ($teacher) {
                $query->where('teacher_id', $teacher->id);
            })->with(['batch', 'program'])->findOrFail($subjectId);

            // Get students from the same batch as the subject
            $students = Student::where('batch_id', $subject->batch_id)
                ->where('status', 'active')
                ->select('id', 'fname', 'lname', 'email', 'roll_number')
                ->get()
                ->map(function($student) {
                    $student->full_name = $student->fname . ' ' . $student->lname;
                    return $student;
                });

            // Get existing attendance for this date and subject
            $existingAttendance = Attendance::where('subject_id', $subjectId)
                ->where('date', $date)
                ->where('attendee_type', 'student')
                ->get()
                ->keyBy('attendee_id')
                ->toArray();

            return response()->json([
                'success' => true,
                'students' => $students,
                'subject' => $subject,
                'existingAttendance' => $existingAttendance
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load students: ' . $e->getMessage()
            ], 500);
        }
    }



    public function getAllStudents(Request $request)
    {
        $teacher = Auth::guard('teacher')->user();

        $attendances = Attendance::where('institute_id', session()
            ->get('institute_id'))
            ->with('student')
            ->where('attendee_type', 'student')
            ->get();

        return response()->json([
            'success' => true,
            'attendances' => $attendances,
        ]);
    }

    /**
     * Store attendance records
     */
    public function store(Request $request)
    {
        try {
            $teacher = Auth::guard('teacher')->user();

            $request->validate([
                'subject_id' => 'required|exists:subjects,id',
                'date' => 'required|date',
                'attendance' => 'required|array',
                'attendance.*.student_id' => 'required|exists:students,id',
                'attendance.*.status' => 'required|in:present,absent,late,excused',
                'attendance.*.remarks' => 'nullable|string|max:255'
            ]);

            // Verify teacher has access to this subject
            $subject = Subject::whereHas('subjectTeacherMappings', function($query) use ($teacher) {
                $query->where('teacher_id', $teacher->id);
            })->findOrFail($request->subject_id);

            DB::beginTransaction();

            // Delete existing attendance for this date and subject
            Attendance::where('subject_id', $request->subject_id)
                ->where('date', $request->date)
                ->where('attendee_type', 'student')
                ->delete();

            // Create new attendance records
            foreach ($request->attendance as $attendanceData) {
                Attendance::create([
                    'attendee_type' => 'student',
                    'institute_id' => $teacher->institute_id ?? 1, // Assuming teacher has institute_id
                    'attendee_id' => $attendanceData['student_id'],
                    'subject_id' => $request->subject_id,
                    'date' => $request->date,
                    'attended_at' => now(),
                    'status' => $attendanceData['status'],
                    'method' => 'manual',
                    'creator_type' => 'teacher',
                    'creator_id' => $teacher->id,
                    'is_verified' => true,
                    'remarks' => $attendanceData['remarks'] ?? null
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Attendance saved successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to save attendance: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get attendance history
     */
    public function getHistory(Request $request)
    {
        try {
            $teacher = Auth::guard('teacher')->user();
            $subjectId = $request->get('subject_id');

            $query = DB::table('attendances')
                ->join('subjects', 'attendances.subject_id', '=', 'subjects.id')
                ->join('subject_teacher_mappings', 'subjects.id', '=', 'subject_teacher_mappings.subject_id')
                ->where('subject_teacher_mappings.teacher_id', $teacher->id)
                ->where('attendances.attendee_type', 'student')
                ->select(
                    'attendances.date',
                    'attendances.subject_id',
                    'subjects.name as subject_name',
                    DB::raw('COUNT(attendances.id) as total_students'),
                    DB::raw('SUM(CASE WHEN attendances.status = "present" THEN 1 ELSE 0 END) as present_count'),
                    DB::raw('SUM(CASE WHEN attendances.status = "absent" THEN 1 ELSE 0 END) as absent_count'),
                    DB::raw('ROUND((SUM(CASE WHEN attendances.status = "present" THEN 1 ELSE 0 END) / COUNT(attendances.id)) * 100, 2) as attendance_rate')
                )
                ->groupBy('attendances.date', 'attendances.subject_id', 'subjects.name')
                ->orderBy('attendances.date', 'desc');

            if ($subjectId) {
                $query->where('attendances.subject_id', $subjectId);
            }

            $history = $query->get();

            return response()->json([
                'success' => true,
                'history' => $history
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load attendance history: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get detailed attendance for a specific date and subject
     */
    public function getDetails(Request $request)
    {
        try {
            $teacher = Auth::guard('teacher')->user();
            $date = $request->get('date');
            $subjectId = $request->get('subject_id');

            // Verify teacher has access to this subject
            $subject = Subject::whereHas('subjectTeacherMappings', function($query) use ($teacher) {
                $query->where('teacher_id', $teacher->id);
            })->with(['batch', 'program'])->findOrFail($subjectId);

            $attendanceDetails = DB::table('attendances')
                ->join('students', 'attendances.attendee_id', '=', 'students.id')
                ->where('attendances.subject_id', $subjectId)
                ->where('attendances.date', $date)
                ->where('attendances.attendee_type', 'student')
                ->select(
                    'students.fname',
                    'students.lname',
                    'students.roll_number',
                    'students.email',
                    'attendances.status',
                    'attendances.remarks',
                    'attendances.attended_at'
                )
                ->orderBy('students.fname')
                ->get();

            return view('backend.teacher.attendance-details', compact('attendanceDetails', 'subject', 'date'));

        } catch (\Exception $e) {
            abort(404, 'Attendance details not found');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
