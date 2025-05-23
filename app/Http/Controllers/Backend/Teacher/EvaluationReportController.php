<?php

namespace App\Http\Controllers\Backend\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EvaluationReportController extends Controller
{
    /**
     * Display the evaluation report dashboard.
     */
    public function index()
    {
        $teacherId = Auth::guard('teacher')->id();

        // Get batches taught by the teacher
        $batches = Batch::select('batches.*')
            ->join('subjects', function($join) {
                $join->on('subjects.program_id', '=', 'batches.program_id')
                    ->whereColumn('subjects.semester', '=', 'batches.semester');
            })
            ->join('subject_teacher_mappings', 'subject_teacher_mappings.subject_id', '=', 'subjects.id')
            ->where('subject_teacher_mappings.teacher_id', $teacherId)
            ->distinct()
            ->get();

        // Get subjects taught by the teacher
        $subjects = Subject::select('subjects.*')
            ->join('subject_teacher_mappings', 'subject_teacher_mappings.subject_id', '=', 'subjects.id')
            ->where('subject_teacher_mappings.teacher_id', $teacherId)
            ->distinct()
            ->get();

        return view('backend.teacher.evaluation.reports.index', compact('batches', 'subjects'));
    }

    /**
     * Generate batch performance report.
     */
    public function batchPerformance(Request $request)
    {
        $teacherId = Auth::guard('teacher')->id();

        $validated = $request->validate([
            'batch_id' => 'required|exists:batches,id',
            'subject_id' => 'required|exists:subjects,id',
        ]);

        $batch = Batch::findOrFail($validated['batch_id']);
        $subject = Subject::findOrFail($validated['subject_id']);

        // Get all evaluation formats for this subject
        $formats = SubjectEvaluationFormat::where('subject_id', $validated['subject_id'])
            ->get();

        // Get all students in the batch
        $students = Student::where('batch_id', $validated['batch_id'])
            ->where('status', 'active')
            ->get();

        // Get evaluations for each student and format
        $evaluations = StudentEvaluation::where('batch_id', $validated['batch_id'])
            ->where('subject_id', $validated['subject_id'])
            ->where('evaluated_by', $teacherId)
            ->with(['evaluationFormat', 'student'])
            ->get();

        // Organize data for the report
        $reportData = [];

        foreach ($students as $student) {
            $studentData = [
                'id' => $student->id,
                'name' => $student->full_name,
                'roll_number' => $student->roll_number,
                'formats' => [],
                'total_normalized' => 0,
            ];

            foreach ($formats as $format) {
                $evaluation = $evaluations->first(function($eval) use ($student, $format) {
                    return $eval->student_id == $student->id && $eval->evaluation_format_id == $format->id;
                });

                $studentData['formats'][$format->id] = [
                    'name' => $format->name,
                    'weight' => $format->weight,
                    'full_marks' => $format->full_marks,
                    'obtained_marks' => $evaluation ? $evaluation->total_obtained_marks : null,
                    'normalized_marks' => $evaluation ? $evaluation->total_normalized_marks : null,
                    'is_finalized' => $evaluation ? $evaluation->is_finalized : false,
                ];

                if ($evaluation && $evaluation->is_finalized) {
                    $studentData['total_normalized'] += $evaluation->total_normalized_marks;
                }
            }

            $reportData[] = $studentData;
        }

        return view('backend.teacher.evaluation.reports.batch-performance', compact('batch', 'subject', 'formats', 'reportData'));
    }

    /**
     * Generate student performance report.
     */
    public function studentPerformance(Request $request)
    {
        $teacherId = Auth::guard('teacher')->id();

        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'nullable|exists:subjects,id',
        ]);

        $student = Student::findOrFail($validated['student_id']);

        // Build query for evaluations
        $query = StudentEvaluation::where('student_id', $validated['student_id'])
            ->where('evaluated_by', $teacherId)
            ->with(['subject', 'evaluationFormat']);

        // Filter by subject if provided
        if (isset($validated['subject_id'])) {
            $query->where('subject_id', $validated['subject_id']);
        }

        $evaluations = $query->get();

        // Group evaluations by subject
        $subjectEvaluations = $evaluations->groupBy('subject_id');

        // Calculate totals for each subject
        $subjectTotals = [];

        foreach ($subjectEvaluations as $subjectId => $evals) {
            $subject = Subject::find($subjectId);

            $totalNormalized = $evals->where('is_finalized', true)->sum('total_normalized_marks');
            $totalWeight = $evals->where('is_finalized', true)->sum(function($eval) {
                return $eval->evaluationFormat->weight;
            });

            $subjectTotals[$subjectId] = [
                'subject' => $subject,
                'total_normalized' => $totalNormalized,
                'total_weight' => $totalWeight,
                'percentage' => $totalWeight > 0 ? ($totalNormalized / $totalWeight) * 100 : 0,
                'evaluations' => $evals,
            ];
        }

        return view('backend.teacher.evaluation.reports.student-performance', compact('student', 'subjectTotals'));
    }

    /**
     * Generate format comparison report.
     */
    public function formatComparison(Request $request)
    {
        $teacherId = Auth::guard('teacher')->id();

        $validated = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'format_ids' => 'required|array',
            'format_ids.*' => 'exists:subject_evaluation_formats,id',
        ]);

        $subject = Subject::findOrFail($validated['subject_id']);

        // Get formats
        $formats = SubjectEvaluationFormat::whereIn('id', $validated['format_ids'])
            ->get();

        // Get batches for this subject
        $batches = Batch::select('batches.*')
            ->join('subjects', function($join) use ($validated) {
                $join->on('subjects.program_id', '=', 'batches.program_id')
                    ->whereColumn('subjects.semester', '=', 'batches.semester')
                    ->where('subjects.id', $validated['subject_id']);
            })
            ->distinct()
            ->get();

        // Get evaluations for each format and batch
        $batchData = [];

        foreach ($batches as $batch) {
            $batchEvaluations = [];

            foreach ($formats as $format) {
                $evaluations = StudentEvaluation::where('batch_id', $batch->id)
                    ->where('subject_id', $validated['subject_id'])
                    ->where('evaluation_format_id', $format->id)
                    ->where('evaluated_by', $teacherId)
                    ->where('is_finalized', true)
                    ->get();

                if ($evaluations->isNotEmpty()) {
                    $avgObtained = $evaluations->avg('total_obtained_marks');
                    $avgNormalized = $evaluations->avg('total_normalized_marks');
                    $maxObtained = $evaluations->max('total_obtained_marks');
                    $minObtained = $evaluations->min('total_obtained_marks');

                    $batchEvaluations[$format->id] = [
                        'format' => $format,
                        'avg_obtained' => $avgObtained,
                        'avg_normalized' => $avgNormalized,
                        'max_obtained' => $maxObtained,
                        'min_obtained' => $minObtained,
                        'count' => $evaluations->count(),
                    ];
                }
            }

            if (!empty($batchEvaluations)) {
                $batchData[$batch->id] = [
                    'batch' => $batch,
                    'evaluations' => $batchEvaluations,
                ];
            }
        }

        return view('backend.teacher.evaluation.reports.format-comparison', compact('subject', 'formats', 'batchData'));
    }
}
