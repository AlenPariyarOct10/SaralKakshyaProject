<?php

namespace App\Http\Controllers\Backend\Student;

use App\Http\Controllers\Controller;
use App\Models\ClassRoutine;
use App\Models\Student;
use App\Models\SubjectTeacherMapping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class RoutineController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Load student with batch and related data
        $student = Student::with(['batch.program', 'batch.subjects'])
            ->where('id', $user->id)
            ->firstOrFail();

        if (!$student->batch) {
            return view('backend.student.routine', [
                'user' => $user,
                'routine' => collect(),
                'timeSlots' => [],
                'todayClasses' => collect(),
                'nextClass' => null,
                'routineStats' => [
                    'total_classes' => 0,
                    'today_classes' => 0,
                    'total_subjects' => 0
                ],
                'subjectSummary' => []
            ]);
        }

        // Get class routines for the student's batch
        $routines = $this->getStudentRoutines($student->batch->id);

        // Process routine data
        $processedRoutine = $this->processRoutineData($routines);

        // Get time slots
        $timeSlots = $this->generateTimeSlots($routines);

        // Get today's classes
        $todayClasses = $this->getTodayClasses($routines);

        // Get next class
        $nextClass = $this->getNextClass($todayClasses);

        // Calculate statistics
        $routineStats = $this->calculateRoutineStats($routines, $todayClasses, $student->batch);

        // Get subject-wise summary
        $subjectSummary = $this->getSubjectSummary($routines);

        return view('backend.student.routine.index', compact(
            'user',
            'timeSlots',
            'todayClasses',
            'nextClass',
            'routineStats',
            'subjectSummary'
        ))->with('routine', $processedRoutine);
    }

    private function getStudentRoutines($batchId)
    {
        // First get the batch to access its semester
        $batch = DB::table('batches')->where('id', $batchId)->first();

        if (!$batch) {
            return collect();
        }

        return DB::table('class_routines')
            ->join('subject_teacher_mappings', 'class_routines.subject_teacher_mappings_id', '=', 'subject_teacher_mappings.id')
            ->join('subjects', 'subject_teacher_mappings.subject_id', '=', 'subjects.id')
            ->join('teachers', 'subject_teacher_mappings.teacher_id', '=', 'teachers.id')
            ->join('batches', 'subjects.semester', '=', 'batches.semester')
            ->where('subjects.semester', $batch->semester) // Filter by semester
            ->where('batches.semester', $batch->semester) // Ensure batch semester matches
            ->select(
                'class_routines.*',
                'subjects.name as subject_name',
                'subjects.code as subject_code',
                'subjects.semester as subject_semester',
                'teachers.fname as teacher_fname',
                'teachers.lname as teacher_lname',
                'batches.semester as batch_semester',
                DB::raw("CONCAT(teachers.fname, ' ', teachers.lname) as teacher_name")
            )
            ->orderBy('class_routines.day')
            ->orderBy('class_routines.start_time')
            ->get();
    }

    private function processRoutineData($routines)
    {
        return $routines->map(function ($routine) {
            $startTime = Carbon::parse($routine->start_time);
            $endTime = Carbon::parse($routine->end_time);

            return (object) [
                'id' => $routine->id,
                'day' => $routine->day,
                'start_time' => $routine->start_time,
                'end_time' => $routine->end_time,
                'time_slot' => $startTime->format('g:i A') . ' - ' . $endTime->format('g:i A'),
                'subject_name' => $routine->subject_name,
                'subject_code' => $routine->subject_code,
                'teacher_name' => $routine->teacher_name,
                'notes' => $routine->notes,
                'duration' => $startTime->diffInMinutes($endTime)
            ];
        });
    }

    private function generateTimeSlots($routines)
    {
        $timeSlots = [];

        foreach ($routines as $routine) {
            $startTime = Carbon::parse($routine->start_time);
            $endTime = Carbon::parse($routine->end_time);
            $timeSlot = $startTime->format('g:i A') . ' - ' . $endTime->format('g:i A');

            if (!in_array($timeSlot, $timeSlots)) {
                $timeSlots[] = $timeSlot;
            }
        }

        // Sort time slots
        usort($timeSlots, function($a, $b) {
            $timeA = Carbon::parse(explode(' - ', $a)[0]);
            $timeB = Carbon::parse(explode(' - ', $b)[0]);
            return $timeA->timestamp - $timeB->timestamp;
        });

        return $timeSlots;
    }

    private function getTodayClasses($routines)
    {
        $today = now()->format('l'); // Full day name (e.g., Monday)

        return $routines->filter(function ($routine) use ($today) {
            return $routine->day === $today;
        })->sortBy('start_time');
    }

    private function getNextClass($todayClasses)
    {
        $currentTime = now()->format('H:i');

        foreach ($todayClasses as $class) {
            $classStart = Carbon::parse($class->start_time)->format('H:i');

            if ($currentTime < $classStart) {
                return [
                    'time' => Carbon::parse($class->start_time)->format('g:i A'),
                    'subject' => $class->subject_name,
                    'teacher' => $class->teacher_name
                ];
            }
        }

        return null;
    }

    private function calculateRoutineStats($routines, $todayClasses, $batch)
    {
        $totalSubjects = $batch->subjects()->count();

        return [
            'total_classes' => $routines->count(),
            'today_classes' => $todayClasses->count(),
            'total_subjects' => $totalSubjects
        ];
    }

    private function getSubjectSummary($routines)
    {
        $summary = [];

        $groupedBySubject = $routines->groupBy('subject_name');

        foreach ($groupedBySubject as $subjectName => $subjectRoutines) {
            $totalMinutes = $subjectRoutines->sum(function ($routine) {
                return Carbon::parse($routine->start_time)->diffInMinutes(Carbon::parse($routine->end_time));
            });

            $days = $subjectRoutines->pluck('day')->unique()->values()->toArray();

            $summary[] = [
                'name' => $subjectName,
                'teacher' => $subjectRoutines->first()->teacher_name,
                'total_classes' => $subjectRoutines->count(),
                'total_hours' => round($totalMinutes / 60, 1),
                'days' => $days
            ];
        }

        return $summary;
    }

    public function downloadPdf()
    {
        $user = Auth::user();

        $student = Student::with(['batch.program'])->where('id', $user->id)->firstOrFail();

        if (!$student->batch) {
            return redirect()->back()->with('error', 'No batch assigned to the student.');
        }

        $routines = $this->getStudentRoutines($student->batch->id);
        $processedRoutine = $this->processRoutineData($routines);

        $pdf = Pdf::loadView('backend.student.routine.pdf', [
            'student' => $student,
            'routine' => $processedRoutine
        ]);

        $filename = 'Routine_' . $student->batch->program->name . '.pdf';

        return $pdf->download($filename);
    }

    public function getRoutineData(Request $request)
    {
        $user = Auth::user();
        $student = Student::with('batch')->find($user->id);

        if (!$student->batch) {
            return response()->json([
                'success' => false,
                'message' => 'No batch assigned'
            ]);
        }

        $routines = $this->getStudentRoutines($student->batch->id);
        $processedRoutine = $this->processRoutineData($routines);

        return response()->json([
            'success' => true,
            'routine' => $processedRoutine,
            'stats' => $this->calculateRoutineStats($routines, $this->getTodayClasses($routines), $student->batch)
        ]);
    }
}
