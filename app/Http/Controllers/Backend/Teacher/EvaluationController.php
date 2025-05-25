<?php

namespace App\Http\Controllers\Backend\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Student;
use App\Models\StudentEvaluation;
use App\Models\StudentEvaluationDetail;
use App\Models\Subject;
use App\Models\SubjectEvaluationFormat;
use App\Models\Teacher;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EvaluationController extends Controller
{
    /**
     * Display a listing of the resource.
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

        // Get evaluation formats
        $evaluationFormats = SubjectEvaluationFormat::whereIn('subject_id', $subjects->pluck('id'))
            ->distinct()
            ->get();

        return view('backend.teacher.evaluation.index', compact('batches', 'subjects', 'evaluationFormats'));
    }

    /**
     * Get evaluations with filters - FIXED API response structure
     */
    public function getEvaluations(Request $request)
    {
        $teacherId = Auth::guard('teacher')->id();

        $query = StudentEvaluationDetail::with(['student', 'subject', 'evaluationFormat', 'batch'])
            ->where('evaluated_by', $teacherId);

        // Apply filters
        if ($request->filled('batch_id')) {
            $query->where('batch_id', $request->batch_id);
        }

        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        if ($request->filled('format_id')) {
            $query->where('evaluation_format_id', $request->format_id);
        }

        if ($request->filled('is_finalized')) {
            $query->where('is_finalized', $request->is_finalized);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('student', function($q) use ($search) {
                $q->where('fname', 'like', "%{$search}%")
                    ->orWhere('lname', 'like', "%{$search}%");
            });
        }

        // Get paginated results
        $evaluations = $query->latest()->paginate(10);

        // Transform the data to match frontend expectations
        $transformedData = $evaluations->getCollection()->map(function($evaluation) {
            return [
                'id' => $evaluation->id,
                'student' => $evaluation->student,
                'subject' => $evaluation->subject,
                'evaluation_format' => $evaluation->evaluationFormat,
                'batch' => $evaluation->batch,
                'obtained_marks' => $evaluation->obtained_marks,
                'normalized_marks' => $evaluation->normalized_marks,
                'comment' => $evaluation->comment,
                'is_finalized' => $evaluation->is_finalized,
            ];
        });

        return response()->json([
            'data' => $transformedData,
            'meta' => [
                'current_page' => $evaluations->currentPage(),
                'from' => $evaluations->firstItem(),
                'last_page' => $evaluations->lastPage(),
                'per_page' => $evaluations->perPage(),
                'to' => $evaluations->lastItem(),
                'total' => $evaluations->total(),
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
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
            ->with('program')
            ->distinct()
            ->get();

        return view('backend.teacher.evaluation.create', compact('batches'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $teacherId = Auth::guard('teacher')->id();

        $validated = $request->validate([
            'batch_id' => 'required|exists:batches,id',
            'subject_id' => 'required|exists:subjects,id',
            'evaluation_format_id' => 'required|exists:subject_evaluation_formats,id',
            'semester' => 'required|integer|min:1|max:8',
            'is_finalized' => 'required|boolean',
            'comment' => 'nullable|string',
            'students' => 'required|array',
            'students.*' => 'exists:students,id',
            'marks' => 'required|array',
            'marks.*' => 'required|numeric|min:0',
            'comments' => 'nullable|array',
            'comments.*' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // Get the evaluation format for validation and normalization
            $evaluationFormat = SubjectEvaluationFormat::findOrFail($validated['evaluation_format_id']);

            // Check if marks are within the allowed range
            foreach ($validated['marks'] as $studentId => $mark) {
                if ($mark > $evaluationFormat->full_marks) {
                    throw new \Exception("Marks for a student cannot exceed the maximum marks ({$evaluationFormat->full_marks}).");
                }
            }

            // Get institute ID from the teacher
            $teacher = Teacher::findOrFail($teacherId);
            $instituteId = $teacher->institutes->first()->id ?? null;

            if (!$instituteId) {
                throw new \Exception("Teacher is not associated with any institute.");
            }

            // Create evaluation details for each student
            foreach ($validated['students'] as $studentId) {
                $obtainedMarks = $validated['marks'][$studentId] ?? 0;

                // Calculate normalized marks based on weight
                $normalizedMarks = ($obtainedMarks / $evaluationFormat->full_marks) * $evaluationFormat->marks_weight;

                // Create evaluation detail
                StudentEvaluationDetail::create([
                    'evaluation_format_id' => $validated['evaluation_format_id'],
                    'subject_id' => $validated['subject_id'],
                    'student_id' => $studentId,
                    'evaluated_by' => $teacherId,
                    'comment' => $validated['comments'][$studentId] ?? null,
                    'obtained_marks' => $obtainedMarks,
                    'normalized_marks' => $normalizedMarks,
                    'semester' => $validated['semester'],
                    'institute_id' => $instituteId,
                    'created_by' => $teacherId,
                    'batch_id' => $validated['batch_id'],
                    'is_finalized' => $validated['is_finalized'],
                ]);
            }

            DB::commit();

            return redirect()->route('teacher.evaluation.index')
                ->with('success', 'Evaluation created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Error creating evaluation: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $teacherId = Auth::guard('teacher')->id();

        $evaluation = StudentEvaluationDetail::with([
            'student',
            'subject',
            'evaluationFormat',
            'evaluatedBy',
            'batch'
        ])
            ->where('evaluated_by', $teacherId)
            ->findOrFail($id);

        // Get all evaluation details for this evaluation
        $evaluationDetails = StudentEvaluationDetail::where('evaluation_format_id', $evaluation->evaluation_format_id)
            ->where('subject_id', $evaluation->subject_id)
            ->where('batch_id', $evaluation->batch_id)
            ->where('semester', $evaluation->semester)
            ->where('evaluated_by', $teacherId)
            ->with('student')
            ->get();

        return view('backend.teacher.evaluation.show', compact('evaluation', 'evaluationDetails'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $teacherId = Auth::guard('teacher')->id();

        $evaluation = StudentEvaluationDetail::with([
            'student',
            'subject',
            'evaluationFormat',
            'evaluatedBy',
            'batch'
        ])
            ->where('evaluated_by', $teacherId)
            ->findOrFail($id);

        // Check if evaluation is finalized
        if ($evaluation->is_finalized) {
            return redirect()->route('teacher.evaluation.show', $id)
                ->with('error', 'Finalized evaluations cannot be edited.');
        }

        // Get all evaluation details for this evaluation
        $evaluationDetails = StudentEvaluationDetail::where('evaluation_format_id', $evaluation->evaluation_format_id)
            ->where('subject_id', $evaluation->subject_id)
            ->where('batch_id', $evaluation->batch_id)
            ->where('semester', $evaluation->semester)
            ->where('evaluated_by', $teacherId)
            ->with('student')
            ->get();

        return view('backend.teacher.evaluation.edit', compact('evaluation', 'evaluationDetails'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $teacherId = Auth::guard('teacher')->id();

        $evaluation = StudentEvaluationDetail::where('evaluated_by', $teacherId)
            ->findOrFail($id);

        // Check if evaluation is finalized
        if ($evaluation->is_finalized) {
            return redirect()->route('teacher.evaluation.show', $id)
                ->with('error', 'Finalized evaluations cannot be edited.');
        }

        $validated = $request->validate([
            'is_finalized' => 'required|boolean',
            'comment' => 'nullable|string',
            'students' => 'required|array',
            'students.*' => 'exists:students,id',
            'marks' => 'required|array',
            'marks.*' => 'required|numeric|min:0',
            'comments' => 'nullable|array',
            'comments.*' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // Get the evaluation format for validation and normalization
            $evaluationFormat = $evaluation->evaluationFormat;

            // Check if marks are within the allowed range
            foreach ($validated['marks'] as $studentId => $mark) {
                if ($mark > $evaluationFormat->full_marks) {
                    throw new \Exception("Marks for a student cannot exceed the maximum marks ({$evaluationFormat->full_marks}).");
                }
            }

            // Update evaluation details for each student
            foreach ($validated['students'] as $studentId) {
                $obtainedMarks = $validated['marks'][$studentId] ?? 0;

                // Calculate normalized marks based on weight
                $normalizedMarks = ($obtainedMarks / $evaluationFormat->full_marks) * $evaluationFormat->marks_weight;

                // Find and update evaluation detail
                $detail = StudentEvaluationDetail::where('evaluation_format_id', $evaluation->evaluation_format_id)
                    ->where('subject_id', $evaluation->subject_id)
                    ->where('batch_id', $evaluation->batch_id)
                    ->where('semester', $evaluation->semester)
                    ->where('student_id', $studentId)
                    ->where('evaluated_by', $teacherId)
                    ->first();

                if ($detail) {
                    $detail->update([
                        'comment' => $validated['comments'][$studentId] ?? null,
                        'obtained_marks' => $obtainedMarks,
                        'normalized_marks' => $normalizedMarks,
                        'is_finalized' => $validated['is_finalized'],
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('teacher.evaluation.show', $evaluation->id)
                ->with('success', 'Evaluation updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Error updating evaluation: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Finalize an evaluation.
     */
    public function finalize(string $id)
    {
        $teacherId = Auth::guard('teacher')->id();

        $evaluation = StudentEvaluationDetail::where('evaluated_by', $teacherId)
            ->findOrFail($id);

        // Check if already finalized
        if ($evaluation->is_finalized) {
            return redirect()->route('teacher.evaluation.show', $id)
                ->with('info', 'Evaluation is already finalized.');
        }

        try {
            // Update all related evaluation details
            StudentEvaluationDetail::where('evaluation_format_id', $evaluation->evaluation_format_id)
                ->where('subject_id', $evaluation->subject_id)
                ->where('batch_id', $evaluation->batch_id)
                ->where('semester', $evaluation->semester)
                ->where('evaluated_by', $teacherId)
                ->update(['is_finalized' => true]);

            return redirect()->route('teacher.evaluation.show', $id)
                ->with('success', 'Evaluation finalized successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error finalizing evaluation: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $teacherId = Auth::guard('teacher')->id();

        $evaluation = StudentEvaluationDetail::where('evaluated_by', $teacherId)
            ->findOrFail($id);

        try {
            DB::beginTransaction();

            // Delete all related evaluation details
            StudentEvaluationDetail::where('evaluation_format_id', $evaluation->evaluation_format_id)
                ->where('subject_id', $evaluation->subject_id)
                ->where('batch_id', $evaluation->batch_id)
                ->where('semester', $evaluation->semester)
                ->where('evaluated_by', $teacherId)
                ->delete();

            DB::commit();

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Export evaluation to Excel.
     */
    public function export(string $id)
    {
        $teacherId = Auth::guard('teacher')->id();

        $evaluation = StudentEvaluationDetail::with([
            'student',
            'subject',
            'evaluationFormat',
            'evaluatedBy',
            'batch'
        ])
            ->where('evaluated_by', $teacherId)
            ->findOrFail($id);

        // Get evaluation details with student information
        $evaluationDetails = StudentEvaluationDetail::where('evaluation_format_id', $evaluation->evaluation_format_id)
            ->where('subject_id', $evaluation->subject_id)
            ->where('batch_id', $evaluation->batch_id)
            ->where('semester', $evaluation->semester)
            ->where('evaluated_by', $teacherId)
            ->with('student')
            ->get();

        // Generate Excel file
        $fileName = 'evaluation_' . $evaluation->id . '_' . date('Y-m-d') . '.xlsx';

        // This would typically use a package like Laravel Excel
        // For now, we'll just return a response indicating this would be implemented
        return response()->json([
            'message' => 'Export functionality would be implemented here',
            'evaluation' => $evaluation,
            'details' => $evaluationDetails,
        ]);
    }

    /**
     * Show batch evaluation form.
     */
    public function batchEvaluation()
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
            ->with('program')
            ->distinct()
            ->get();

        return view('backend.teacher.evaluation.batch', compact('batches'));
    }

    /**
     * Store batch evaluation.
     */
    public function storeBatchEvaluation(Request $request)
    {
        $teacherId = Auth::guard('teacher')->id();

        $validated = $request->validate([
            'batch_id' => 'required|exists:batches,id',
            'subject_id' => 'required|exists:subjects,id',
            'evaluation_format_id' => 'required|exists:subject_evaluation_formats,id',
            'semester' => 'required|integer|min:1|max:8',
            'is_finalized' => 'required|boolean',
            'students' => 'required|array',
            'students.*' => 'exists:students,id',
            'marks' => 'required|array',
            'marks.*' => 'required|numeric|min:0',
            'comments' => 'nullable|array',
            'comments.*' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // Get the evaluation format for validation and normalization
            $evaluationFormat = SubjectEvaluationFormat::findOrFail($validated['evaluation_format_id']);

            // Check if marks are within the allowed range
            foreach ($validated['marks'] as $studentId => $mark) {
                if ($mark > $evaluationFormat->full_marks) {
                    throw new \Exception("Marks for a student cannot exceed the maximum marks ({$evaluationFormat->full_marks}).");
                }
            }

            // Get institute ID from the teacher
            $teacher = Teacher::findOrFail($teacherId);
            $instituteId = $teacher->institutes->first()->id ?? null;

            if (!$instituteId) {
                throw new \Exception("Teacher is not associated with any institute.");
            }

            // Process each student
            foreach ($validated['students'] as $studentId) {
                $obtainedMarks = $validated['marks'][$studentId] ?? 0;

                // Calculate normalized marks based on weight
                $normalizedMarks = ($obtainedMarks / $evaluationFormat->full_marks) * $evaluationFormat->marks_weight;

                // Check if an evaluation already exists for this student, subject, and format
                $existingEvaluation = StudentEvaluationDetail::where('student_id', $studentId)
                    ->where('subject_id', $validated['subject_id'])
                    ->where('evaluation_format_id', $validated['evaluation_format_id'])
                    ->where('batch_id', $validated['batch_id'])
                    ->where('semester', $validated['semester'])
                    ->where('evaluated_by', $teacherId)
                    ->first();

                if ($existingEvaluation) {
                    // Update existing evaluation
                    $existingEvaluation->update([
                        'comment' => $validated['comments'][$studentId] ?? null,
                        'obtained_marks' => $obtainedMarks,
                        'normalized_marks' => $normalizedMarks,
                        'is_finalized' => $validated['is_finalized'],
                    ]);
                } else {
                    // Create new evaluation detail
                    StudentEvaluationDetail::create([
                        'evaluation_format_id' => $validated['evaluation_format_id'],
                        'subject_id' => $validated['subject_id'],
                        'student_id' => $studentId,
                        'evaluated_by' => $teacherId,
                        'comment' => $validated['comments'][$studentId] ?? null,
                        'obtained_marks' => $obtainedMarks,
                        'normalized_marks' => $normalizedMarks,
                        'semester' => $validated['semester'],
                        'institute_id' => $instituteId,
                        'created_by' => $teacherId,
                        'batch_id' => $validated['batch_id'],
                        'is_finalized' => $validated['is_finalized'],
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('teacher.evaluation.index')
                ->with('success', 'Batch evaluation completed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Error processing batch evaluation: ' . $e->getMessage())
                ->withInput();
        }
    }
}
