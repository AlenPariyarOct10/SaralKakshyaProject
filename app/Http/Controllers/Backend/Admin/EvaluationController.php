<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Attendance;
use App\Models\Batch;
use App\Models\InstituteSession;
use App\Models\Student;
use App\Models\StudentEvaluationDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EvaluationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::guard('admin')->user();
        return view('backend.admin.evaluation.index', compact('user'));
    }


    public function downloadResultsPdf(Request $request)
    {


        $program_id = $request->program_id;
        $semester = $request->semester;
        $institute_id = $request->institute_id;

        // Get the same data as your evaluation method
        $batch = Batch::where('program_id', $program_id)
            ->where('semester', $semester)
            ->where('institute_id', $institute_id)
            ->first();

        if (!$batch) {
            return back()->with('error', 'No matching batch found.');
        }

        $results = $this->getEvaluationResults($batch, $institute_id);

        $data = [
            'results' => $results,
            'batch' => $batch,
            'program' => $batch->program,
            'department' => $batch->program->department,
        ];

        $pdf = \PDF::loadView('backend.admin.evaluation.pdf-results', $data);

        return $pdf->download('evaluation-results-'.$batch->program->name.'-sem-'.$semester.'.pdf');
    }

// Helper method to reuse evaluation logic
    private function getEvaluationResults($batch, $institute_id)
    {
        $holidayDates = InstituteSession::where('institute_id', $institute_id)
            ->where('status', 'holiday')
            ->whereBetween('date', [$batch->start_date, $batch->end_date])
            ->pluck('date')
            ->toArray();

        $classDays = InstituteSession::where('institute_id', $institute_id)
            ->where('status', 'class')
            ->whereBetween('date', [$batch->start_date, $batch->end_date])
            ->pluck('date')
            ->toArray();
        $totalClassDays = count($classDays);

        $students = Student::where('batch_id', $batch->id)
            ->with([
                'assignmentSubmissions' => function ($query) use ($batch) {
                    $query->where('status', 'graded')
                        ->whereBetween('submitted_at', [$batch->start_date, $batch->end_date])
                        ->with('assignment');
                },
                'studentEvaluationDetails.subject',
                'studentEvaluationDetails.evaluationFormat',
            ])
            ->get();

        $result = $students->map(function ($student) use ($batch, $institute_id, $holidayDates, $totalClassDays) {
            // Same logic as your evaluation method
            // Attendance
            $presentDays = Attendance::where('attendee_id', $student->id)
                ->where('attendee_type', 'student')
                ->where('institute_id', $institute_id)
                ->where('status', 'present')
                ->whereBetween('date', [$batch->start_date, $batch->end_date])
                ->whereNotIn('date', $holidayDates)
                ->count();

            $attendanceFull = 100;
            $attendanceNormalized = $totalClassDays > 0 ? round(($presentDays / $totalClassDays) * $attendanceFull, 2) : 0;

            // Assignment
            $assignments = $student->assignmentSubmissions;
            $totalAssignments = Assignment::where('batch_id', $batch->id)
                ->whereBetween('created_at', [$batch->start_date, $batch->end_date])
                ->get();

            $totalAssignmentFull = 0;
            $obtainedAssignmentMarks = 0;

            foreach ($totalAssignments as $assignment) {
                $full = $assignment->full_marks ?? 0;
                $totalAssignmentFull += $full;
            }

            foreach ($assignments as $submission) {
                $marks = $submission->marks ?? 0;
                $assignmentFull = $submission->assignment->full_marks ?? 0;
                if ($assignmentFull > 0) {
                    $obtainedAssignmentMarks += $marks;
                }
            }

            $assignmentFull = 100;
            $assignmentNormalized = $totalAssignmentFull > 0
                ? round(($obtainedAssignmentMarks / $totalAssignmentFull) * $assignmentFull, 2)
                : 0;

            // Evaluation
            $examDetails = [];
            $totalEvalFull = 0;
            $totalEvalObtained = 0;
            $passMarks = [
                'Preboard' => 0,
                'Midterm' => 0,
            ];
            $obtainedMarks = [
                'Preboard' => 0,
                'Midterm' => 0,
            ];
            foreach ($student->studentEvaluationDetails as $eval) {
                $examType = $eval->evaluationFormat->criteria ?? 'N/A';
                $marks = $eval->obtained_marks ?? 0;
                $passMark = $eval->evaluationFormat->pass_marks ?? 0;
                $fullMarks = $eval->evaluationFormat->full_marks ?? 0;

                if (!isset($examDetails[$examType])) {
                    $examDetails[$examType] = ['obtained' => 0, 'full' => 0, 'pass' => 0];
                }

                $examDetails[$examType]['obtained'] += $marks;
                $examDetails[$examType]['full'] += $fullMarks;
                $examDetails[$examType]['pass'] = $passMark;

                $totalEvalObtained += $marks;
                $totalEvalFull += $fullMarks;

                if (array_key_exists($examType, $obtainedMarks)) {
                    $obtainedMarks[$examType] += $marks;
                    $passMarks[$examType] = $passMark; // Get last if multiple, or set once
                }
            }

            $evaluationFull = 100;

            // Final total (normalized to 300)
            $totalObtained = round($attendanceNormalized + $assignmentNormalized + $totalEvalObtained, 2);
            $totalFull = $attendanceFull + $assignmentFull + $totalEvalFull;

            // Pass conditions
            $attendancePass = $attendanceNormalized >= ($attendanceFull * 0.6); // 60% of 100
            $assignmentPass = $assignmentNormalized >= ($assignmentFull * 0.4); // 40% of 100

            $preboardPass = ($passMarks['Preboard'] > 0) ? ($obtainedMarks['Preboard'] >= $passMarks['Preboard']) : true;
            $midtermPass = ($passMarks['Midterm'] > 0) ? ($obtainedMarks['Midterm'] >= $passMarks['Midterm']) : true;

            $passStatus = ($attendancePass && $assignmentPass && $preboardPass && $midtermPass) ? 'Pass' : 'Fail';


            return [
                'student_name' => $student->fname . ' ' . $student->lname,
                'exam_details' => collect($examDetails)->map(function ($item) {
                    return [
                        'obtained_marks' => $item['obtained'],
                        'full_marks' => $item['full'],
                        'pass_marks' => $item['pass'],
                    ];
                }),
                'assignment' => [
                    'obtained_marks' => $assignmentNormalized,
                    'full_marks' => 100,
                ],
                'attendance' => [
                    'obtained_marks' => $attendanceNormalized,
                    'full_marks' => 100,
                ],
                'total' => [
                    'obtained_marks' => $totalObtained,
                    'full_marks' => $totalFull,
                ],
                'status' => $passStatus,
                'total_obtained' => $totalObtained,
            ];
        });

        return $result->sortByDesc('total_obtained')->values();
    }


    public function evaluation(Request $request)
    {
        $program_id = $request->program_id;
        $semester = $request->semester;
        $institute_id = $request->institute_id;

        $batch = Batch::where('program_id', $program_id)
            ->where('semester', $semester)
            ->where('institute_id', $institute_id)
            ->first();

        if (!$batch) {
            return response()->json(['message' => 'No matching batch found.'], 404);
        }

        $holidayDates = InstituteSession::where('institute_id', $institute_id)
            ->where('status', 'holiday')
            ->whereBetween('date', [$batch->start_date, $batch->end_date])
            ->pluck('date')
            ->toArray();

        $classDays = InstituteSession::where('institute_id', $institute_id)
            ->where('status', 'class')
            ->whereBetween('date', [$batch->start_date, $batch->end_date])
            ->pluck('date')
            ->toArray();
        $totalClassDays = count($classDays);

        $students = Student::where('batch_id', $batch->id)
            ->with([
                'assignmentSubmissions' => function ($query) use ($batch) {
                    $query->where('status', 'graded')
                        ->whereBetween('submitted_at', [$batch->start_date, $batch->end_date])
                        ->with('assignment');
                },
                'studentEvaluationDetails.subject',
                'studentEvaluationDetails.evaluationFormat',
            ])
            ->get();

        $result = $students->map(function ($student) use ($batch, $institute_id, $holidayDates, $totalClassDays) {
            // Attendance
            $presentDays = Attendance::where('attendee_id', $student->id)
                ->where('attendee_type', 'student')
                ->where('institute_id', $institute_id)
                ->where('status', 'present')
                ->whereBetween('date', [$batch->start_date, $batch->end_date])
                ->whereNotIn('date', $holidayDates)
                ->count();

            $attendanceFull = 100;
            $attendanceNormalized = $totalClassDays > 0 ? round(($presentDays / $totalClassDays) * $attendanceFull, 2) : 0;

            // Assignment
            $assignments = $student->assignmentSubmissions;
            $totalAssignments = Assignment::where('batch_id', $batch->id)
                ->whereBetween('created_at', [$batch->start_date, $batch->end_date])
                ->get();

            $totalAssignmentFull = 0;
            $obtainedAssignmentMarks = 0;

            foreach ($totalAssignments as $assignment) {
                $full = $assignment->full_marks ?? 0;
                $totalAssignmentFull += $full;
            }

            foreach ($assignments as $submission) {
                $marks = $submission->marks ?? 0;
                $assignmentFull = $submission->assignment->full_marks ?? 0;
                if ($assignmentFull > 0) {
                    $obtainedAssignmentMarks += $marks;
                }
            }

            $assignmentFull = 100;
            $assignmentNormalized = $totalAssignmentFull > 0
                ? round(($obtainedAssignmentMarks / $totalAssignmentFull) * $assignmentFull, 2)
                : 0;

            // Evaluation
            $examDetails = [];
            $totalEvalFull = 0;
            $totalEvalObtained = 0;
            $passMarks = [
                'Preboard' => 0,
                'Midterm' => 0,
            ];
            $obtainedMarks = [
                'Preboard' => 0,
                'Midterm' => 0,
            ];
            foreach ($student->studentEvaluationDetails as $eval) {
                $examType = $eval->evaluationFormat->criteria ?? 'N/A';
                $marks = $eval->obtained_marks ?? 0;
                $passMark = $eval->evaluationFormat->pass_marks ?? 0;
                $fullMarks = $eval->evaluationFormat->full_marks ?? 0;

                if (!isset($examDetails[$examType])) {
                    $examDetails[$examType] = ['obtained' => 0, 'full' => 0, 'pass' => 0];
                }

                $examDetails[$examType]['obtained'] += $marks;
                $examDetails[$examType]['full'] += $fullMarks;
                $examDetails[$examType]['pass'] = $passMark;

                $totalEvalObtained += $marks;
                $totalEvalFull += $fullMarks;

                if (array_key_exists($examType, $obtainedMarks)) {
                    $obtainedMarks[$examType] += $marks;
                    $passMarks[$examType] = $passMark; // Get last if multiple, or set once
                }
            }

            $evaluationFull = 100;

            // Final total (normalized to 300)
            $totalObtained = round($attendanceNormalized + $assignmentNormalized + $totalEvalObtained, 2);
            $totalFull = $attendanceFull + $assignmentFull + $totalEvalFull;

            // Pass conditions
            $attendancePass = $attendanceNormalized >= ($attendanceFull * 0.6); // 60% of 100
            $assignmentPass = $assignmentNormalized >= ($assignmentFull * 0.4); // 40% of 100

            $preboardPass = ($passMarks['Preboard'] > 0) ? ($obtainedMarks['Preboard'] >= $passMarks['Preboard']) : true;
            $midtermPass = ($passMarks['Midterm'] > 0) ? ($obtainedMarks['Midterm'] >= $passMarks['Midterm']) : true;

            $passStatus = ($attendancePass && $assignmentPass && $preboardPass && $midtermPass) ? 'Pass' : 'Fail';

            return [
                'student_name' => $student->fname . ' ' . $student->lname,
                'student_id' => $student->id,
                'exam_details' => collect($examDetails)->map(function ($item) {
                    return [
                        'obtained_marks' => $item['obtained'],
                        'full_marks' => $item['full'],
                        'pass_marks' => $item['pass'],
                    ];
                }),
                'assignment' => [
                    'obtained_marks' => $assignmentNormalized,
                    'full_marks' => 100,
                ],
                'attendance' => [
                    'obtained_marks' => $attendanceNormalized,
                    'full_marks' => 100,
                ],
                'total' => [
                    'obtained_marks' => $totalObtained,
                    'full_marks' => $totalFull,
                ],
                'status' => $passStatus,
                'total_obtained' => $totalObtained, // used for sorting
            ];
        });

        // Sort by total_obtained in descending order
        $sortedResults = $result->sortByDesc('total_obtained')->values();

        // Add rank
        $rankedResults = $sortedResults->map(function ($item, $index) use ($sortedResults) {
            if ($index > 0 && $item['total_obtained'] === $sortedResults[$index - 1]['total_obtained']) {
                $item['rank'] = $sortedResults[$index - 1]['rank'];
            } else {
                $item['rank'] = $index + 1;
            }

            unset($item['total_obtained']); // cleanup
            return $item;
        });

        return response()->json([
            'batch_id' => $batch->id,
            'results' => $rankedResults,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
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
