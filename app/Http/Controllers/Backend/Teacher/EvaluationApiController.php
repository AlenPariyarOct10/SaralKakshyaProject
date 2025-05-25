<?php

namespace App\Http\Controllers\Backend\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Attendance;
use App\Models\InstituteSession;
use App\Models\Student;
use App\Models\StudentEvaluationDetail;
use App\Models\Subject;
use App\Models\SubjectEvaluationFormat;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EvaluationApiController extends Controller
{
    /**
     * Get subjects for a batch.
     */
    public function getBatchSubjects(string $batchId): JsonResponse
    {
        $teacherId = Auth::guard('teacher')->id();

        $subjects = Subject::select('subjects.*')
            ->join('batches', function($join) {
                $join->on('subjects.program_id', '=', 'batches.program_id')
                    ->whereColumn('subjects.semester', '=', 'batches.semester');
            })
            ->join('subject_teacher_mappings', 'subject_teacher_mappings.subject_id', '=', 'subjects.id')
            ->where('batches.id', $batchId)
            ->where('subject_teacher_mappings.teacher_id', $teacherId)
            ->distinct()
            ->get();

        return response()->json($subjects);
    }

    /**
     * Get evaluation formats for a subject.
     */
    /**
     * Get evaluation formats for a subject, excluding formats that already have evaluations.
     */
    public function getSubjectEvaluationFormats(string $subjectId): JsonResponse
    {
        $teacherId = Auth::guard('teacher')->id();

        // Get all formats for the subject
        $formats = SubjectEvaluationFormat::where('subject_id', $subjectId)
            ->get();

        // Get formats that already have evaluations for this teacher and subject
        $evaluatedFormats = StudentEvaluationDetail::where('subject_id', $subjectId)
            ->where('evaluated_by', $teacherId)
            ->pluck('evaluation_format_id')
            ->unique()
            ->toArray();

        // Filter out formats that have been evaluated
        $filteredFormats = $formats->reject(function ($format) use ($evaluatedFormats) {
            return in_array($format->id, $evaluatedFormats);
        });

        return response()->json($filteredFormats);
    }

    /**
     * Get students for a batch with enhanced data handling.
     */
    public function getBatchStudents(string $batchId, Request $request): JsonResponse
    {
        $students = Student::where('batch_id', $batchId)
            ->where('status', '1')
            ->select('id', 'fname', 'lname', 'roll_number', 'profile_picture')
            ->get();

        // Add full_name attribute for consistency
        $students->each(function($student) {
            $student->full_name = trim($student->fname . ' ' . $student->lname);
        });

        // Handle assignment source request
        if ($request->has('source') && $request->source === 'assignment' && $request->has('subject_id')) {
            $subjectId = $request->subject_id;
            $totalAssignments = Assignment::where('subject_id', $subjectId)
                ->where('batch_id', $batchId)
                ->count();

            $students->each(function($student) use ($subjectId, $totalAssignments) {
                $submittedCount = AssignmentSubmission::where('student_id', $student->id)
                    ->whereHas('assignment', function($query) use ($subjectId) {
                        $query->where('subject_id', $subjectId);
                    })
                    ->count();

                $student->total_assignments = $totalAssignments;
                $student->submitted_assignments = $submittedCount;
                $student->submission_rate = $totalAssignments > 0
                    ? round(($submittedCount / $totalAssignments) * 100, 2)
                    : 0;
            });
        }
        // Handle attendance source request
        elseif ($request->has('source') && $request->source === 'attendance' && $request->has('subject_id')) {
            $subjectId = $request->subject_id;
            $startDate = $request->date_from ?? null;
            $endDate = $request->date_to ?? null;

            $students->each(function($student) use ($subjectId, $startDate, $endDate) {
                $query = Attendance::where('attendee_id', $student->id)
                    ->where('attendee_type', 'student')
                    ->where('status', 'present');

                if ($startDate && $endDate) {
                    $query->whereBetween('date', [$startDate, $endDate]);
                } elseif ($startDate) {
                    $query->where('date', '>=', $startDate);
                } elseif ($endDate) {
                    $query->where('date', '<=', $endDate);
                }

                $attendedDays = $query->count();

                // Count total possible attendance days
                $totalQuery = Attendance::where('attendee_id', $student->id)
                    ->where('attendee_type', 'student');

                if ($startDate && $endDate) {
                    $totalQuery->whereBetween('date', [$startDate, $endDate]);
                } elseif ($startDate) {
                    $totalQuery->where('date', '>=', $startDate);
                } elseif ($endDate) {
                    $totalQuery->where('date', '<=', $endDate);
                }

                $totalDays = $totalQuery->distinct('date')->count();

                $student->attended_days = $attendedDays;
                $student->total_days = $totalDays;
                $student->attendance_rate = $totalDays > 0
                    ? round(($attendedDays / $totalDays) * 100, 2)
                    : 0;
            });
        }

        // Handle evaluation check
        elseif ($request->has('subject_id') && $request->has('format_id')) {
            $subjectId = $request->subject_id;
            $formatId = $request->format_id;
            $teacherId = Auth::guard('teacher')->id();

            $students->each(function($student) use ($subjectId, $formatId, $teacherId) {
                $evaluation = StudentEvaluationDetail::where('student_id', $student->id)
                    ->where('subject_id', $subjectId)
                    ->where('evaluation_format_id', $formatId)
                    ->where('evaluated_by', $teacherId)
                    ->first();

                $student->has_evaluation = (bool) $evaluation;
                $student->evaluation_id = $evaluation ? $evaluation->id : null;
            });
        }

        return response()->json($students);
    }

    /**
     * Get evaluation statistics for a batch and subject.
     */
    public function getBatchStatistics(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'batch_id' => 'required|exists:batches,id',
            'subject_id' => 'required|exists:subjects,id',
        ]);

        $teacherId = Auth::guard('teacher')->id();

        // Get all evaluation formats for this subject
        $formats = SubjectEvaluationFormat::where('subject_id', $validated['subject_id'])
            ->get();

        // Get evaluations for each format
        $formatStats = [];

        foreach ($formats as $format) {
            $evaluations = StudentEvaluationDetail::where('batch_id', $validated['batch_id'])
                ->where('subject_id', $validated['subject_id'])
                ->where('evaluation_format_id', $format->id)
                ->where('evaluated_by', $teacherId)
                ->where('is_finalized', true)
                ->get();

            if ($evaluations->isNotEmpty()) {
                $formatStats[$format->id] = [
                    'format_name' => $format->name,
                    'weight' => $format->marks_weight,
                    'full_marks' => $format->full_marks,
                    'avg_marks' => $evaluations->avg('obtained_marks'),
                    'max_marks' => $evaluations->max('obtained_marks'),
                    'min_marks' => $evaluations->min('obtained_marks'),
                    'count' => $evaluations->count(),
                    'distribution' => [
                        '0-20%' => $evaluations->filter(function($eval) use ($format) {
                            $percentage = ($eval->obtained_marks / $format->full_marks) * 100;
                            return $percentage >= 0 && $percentage < 20;
                        })->count(),
                        '20-40%' => $evaluations->filter(function($eval) use ($format) {
                            $percentage = ($eval->obtained_marks / $format->full_marks) * 100;
                            return $percentage >= 20 && $percentage < 40;
                        })->count(),
                        '40-60%' => $evaluations->filter(function($eval) use ($format) {
                            $percentage = ($eval->obtained_marks / $format->full_marks) * 100;
                            return $percentage >= 40 && $percentage < 60;
                        })->count(),
                        '60-80%' => $evaluations->filter(function($eval) use ($format) {
                            $percentage = ($eval->obtained_marks / $format->full_marks) * 100;
                            return $percentage >= 60 && $percentage < 80;
                        })->count(),
                        '80-100%' => $evaluations->filter(function($eval) use ($format) {
                            $percentage = ($eval->obtained_marks / $format->full_marks) * 100;
                            return $percentage >= 80 && $percentage <= 100;
                        })->count(),
                    ],
                ];
            }
        }

        // Get overall statistics
        $allEvaluations = StudentEvaluationDetail::where('batch_id', $validated['batch_id'])
            ->where('subject_id', $validated['subject_id'])
            ->where('evaluated_by', $teacherId)
            ->where('is_finalized', true)
            ->get();

        $overallStats = [
            'total_evaluations' => $allEvaluations->count(),
            'total_students' => Student::where('batch_id', $validated['batch_id'])->count(),
            'formats_evaluated' => count($formatStats),
            'total_formats' => $formats->count(),
        ];

        return response()->json([
            'format_stats' => $formatStats,
            'overall_stats' => $overallStats,
        ]);
    }

    /**
     * Get student performance across evaluations.
     */
    public function getStudentPerformance(string $studentId): JsonResponse
    {
        $teacherId = Auth::guard('teacher')->id();

        $student = Student::findOrFail($studentId);

        // Get all evaluations for this student by this teacher
        $evaluations = StudentEvaluationDetail::where('student_id', $studentId)
            ->where('evaluated_by', $teacherId)
            ->where('is_finalized', true)
            ->with(['subject', 'evaluationFormat'])
            ->get();

        // Group evaluations by subject
        $subjectPerformance = [];

        foreach ($evaluations as $evaluation) {
            $subjectId = $evaluation->subject_id;

            if (!isset($subjectPerformance[$subjectId])) {
                $subjectPerformance[$subjectId] = [
                    'subject_name' => $evaluation->subject->name,
                    'subject_code' => $evaluation->subject->code,
                    'evaluations' => [],
                    'total_normalized' => 0,
                    'total_weight' => 0,
                ];
            }

            $subjectPerformance[$subjectId]['evaluations'][] = [
                'format_name' => $evaluation->evaluationFormat->name,
                'weight' => $evaluation->evaluationFormat->marks_weight,
                'full_marks' => $evaluation->evaluationFormat->full_marks,
                'obtained_marks' => $evaluation->obtained_marks,
                'normalized_marks' => $evaluation->normalized_marks,
                'percentage' => ($evaluation->obtained_marks / $evaluation->evaluationFormat->full_marks) * 100,
            ];

            $subjectPerformance[$subjectId]['total_normalized'] += $evaluation->normalized_marks;
            $subjectPerformance[$subjectId]['total_weight'] += $evaluation->evaluationFormat->marks_weight;
        }

        // Calculate overall percentage for each subject
        foreach ($subjectPerformance as &$subject) {
            $subject['overall_percentage'] = $subject['total_weight'] > 0
                ? ($subject['total_normalized'] / $subject['total_weight']) * 100
                : 0;
        }

        return response()->json([
            'student' => [
                'id' => $student->id,
                'name' => $student->fname . ' ' . $student->lname,
                'roll_number' => $student->roll_number,
            ],
            'subject_performance' => $subjectPerformance,
        ]);
    }

    /**
     * Get departments for filtering.
     */
    public function getDepartments(): JsonResponse
    {
        $teacherId = Auth::guard('teacher')->id();

        $departments = \App\Models\Department::select('departments.*')
            ->join('programs', 'programs.department_id', '=', 'departments.id')
            ->join('subjects', 'subjects.program_id', '=', 'programs.id')
            ->join('subject_teacher_mappings', 'subject_teacher_mappings.subject_id', '=', 'subjects.id')
            ->where('subject_teacher_mappings.teacher_id', $teacherId)
            ->distinct()
            ->get();

        return response()->json(['data' => $departments]);
    }

    /**
     * Get programs for a department.
     */
    public function getDepartmentPrograms(string $departmentId): JsonResponse
    {
        $teacherId = Auth::guard('teacher')->id();

        $programs = \App\Models\Program::select('programs.*')
            ->join('subjects', 'subjects.program_id', '=', 'programs.id')
            ->join('subject_teacher_mappings', 'subject_teacher_mappings.subject_id', '=', 'subjects.id')
            ->where('programs.department_id', $departmentId)
            ->where('subject_teacher_mappings.teacher_id', $teacherId)
            ->distinct()
            ->get();

        return response()->json(['data' => $programs]);
    }

    /**
     * Get subjects for a program.
     */
    public function getProgramSubjects(string $programId): JsonResponse
    {
        $teacherId = Auth::guard('teacher')->id();

        $subjects = Subject::select('subjects.*')
            ->join('subject_teacher_mappings', 'subject_teacher_mappings.subject_id', '=', 'subjects.id')
            ->where('subjects.program_id', $programId)
            ->where('subject_teacher_mappings.teacher_id', $teacherId)
            ->distinct()
            ->get();

        return response()->json(['data' => $subjects]);
    }

    public function destoryStudentEvaluation(string $evaluationId): JsonResponse
    {
        $teacherId = Auth::guard('teacher')->id();
        $evaluation = StudentEvaluationDetail::where('id', $evaluationId)
            ->first();

        if (!$evaluation) {
            return response()->json(['message' => 'Evaluation not found or unauthorized'], 404);
        }

        // Delete the evaluation
        $evaluation->delete();

        return response()->json(['message' => 'Evaluation deleted successfully']);
    }
}
