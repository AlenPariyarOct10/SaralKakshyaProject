<?php

namespace App\Http\Controllers\Backend\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
    public function getSubjectEvaluationFormats(string $subjectId): JsonResponse
    {
        $formats = SubjectEvaluationFormat::where('subject_id', $subjectId)
            ->get();

        return response()->json($formats);
    }

    /**
     * Get students for a batch.
     */
    public function getBatchStudents(string $batchId, Request $request): JsonResponse
    {
        $students = Student::where('batch_id', $batchId)
            ->where('status', 'active')
            ->get();

        // If subject_id and format_id are provided, check if students are already evaluated
        if ($request->has('subject_id') && $request->has('format_id')) {
            $subjectId = $request->subject_id;
            $formatId = $request->format_id;

            $students->each(function($student) use ($subjectId, $formatId) {
                $evaluation = StudentEvaluation::where('student_id', $student->id)
                    ->where('subject_id', $subjectId)
                    ->where('evaluation_format_id', $formatId)
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
            $evaluations = StudentEvaluation::where('batch_id', $validated['batch_id'])
                ->where('subject_id', $validated['subject_id'])
                ->where('evaluation_format_id', $format->id)
                ->where('evaluated_by', $teacherId)
                ->where('is_finalized', true)
                ->get();

            if ($evaluations->isNotEmpty()) {
                $formatStats[$format->id] = [
                    'format_name' => $format->name,
                    'weight' => $format->weight,
                    'full_marks' => $format->full_marks,
                    'avg_marks' => $evaluations->avg('total_obtained_marks'),
                    'max_marks' => $evaluations->max('total_obtained_marks'),
                    'min_marks' => $evaluations->min('total_obtained_marks'),
                    'count' => $evaluations->count(),
                    'distribution' => [
                        '0-20%' => $evaluations->filter(function($eval) use ($format) {
                            $percentage = ($eval->total_obtained_marks / $format->full_marks) * 100;
                            return $percentage >= 0 && $percentage < 20;
                        })->count(),
                        '20-40%' => $evaluations->filter(function($eval) use ($format) {
                            $percentage = ($eval->total_obtained_marks / $format->full_marks) * 100;
                            return $percentage >= 20 && $percentage < 40;
                        })->count(),
                        '40-60%' => $evaluations->filter(function($eval) use ($format) {
                            $percentage = ($eval->total_obtained_marks / $format->full_marks) * 100;
                            return $percentage >= 40 && $percentage < 60;
                        })->count(),
                        '60-80%' => $evaluations->filter(function($eval) use ($format) {
                            $percentage = ($eval->total_obtained_marks / $format->full_marks) * 100;
                            return $percentage >= 60 && $percentage < 80;
                        })->count(),
                        '80-100%' => $evaluations->filter(function($eval) use ($format) {
                            $percentage = ($eval->total_obtained_marks / $format->full_marks) * 100;
                            return $percentage >= 80 && $percentage <= 100;
                        })->count(),
                    ],
                ];
            }
        }

        // Get overall statistics
        $allEvaluations = StudentEvaluation::where('batch_id', $validated['batch_id'])
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
        $evaluations = StudentEvaluation::where('student_id', $studentId)
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
                'weight' => $evaluation->evaluationFormat->weight,
                'full_marks' => $evaluation->evaluationFormat->full_marks,
                'obtained_marks' => $evaluation->total_obtained_marks,
                'normalized_marks' => $evaluation->total_normalized_marks,
                'percentage' => ($evaluation->total_obtained_marks / $evaluation->evaluationFormat->full_marks) * 100,
            ];

            $subjectPerformance[$subjectId]['total_normalized'] += $evaluation->total_normalized_marks;
            $subjectPerformance[$subjectId]['total_weight'] += $evaluation->evaluationFormat->weight;
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
                'name' => $student->full_name,
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
}
