<?php

namespace App\Http\Controllers\Backend\Student;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Attendance;
use App\Models\InstituteStudent;
use App\Models\InstituteSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PredictionController extends Controller
{
    // Model parameters (same as admin controller)
    private $weights = [];
    private $bias = 0;
    private $modelPath = 'models/logistic_regression_model.json';

    /**
     * Display the prediction page with auto-filled data
     */
    public function index()
    {
        $user = Auth::user();
        $studentId = $user->id;

        // Get student's institute information
        $instituteStudent = InstituteStudent::where('student_id', $studentId)
            ->where('is_approved', true)
            ->first();

        if (!$instituteStudent) {
            return view('backend.student.prediction.index', [
                'autoFillData' => null,
                'modelInfo' => $this->getModelInfo(),
                'error' => 'Student not enrolled in any institute'
            ]);
        }

        // Get auto-fill data based on student's performance
        $autoFillData = $this->getStudentPerformanceData($studentId, $instituteStudent);
        $modelInfo = $this->getModelInfo();

        return view('backend.student.prediction.index', compact('autoFillData', 'modelInfo'));
    }

    /**
     * Get student's performance data for auto-filling the form
     */
    private function getStudentPerformanceData($studentId, $instituteStudent)
    {
        $instituteId = $instituteStudent->institute_id;
        $batchId = $instituteStudent->batch_id;

        // Get current semester/academic period (you may need to adjust this based on your system)
        $currentDate = Carbon::now();
        $academicYearStart = Carbon::create($currentDate->year, 9, 1); // Assuming academic year starts in September
        if ($currentDate->month < 9) {
            $academicYearStart->subYear();
        }

        // 1. Assignment Performance
        $assignments = Assignment::where('batch_id', $batchId)
            ->where('status', 'active')
            ->where('assigned_date', '>=', $academicYearStart->format('Y-m-d'))
            ->get();

        $totalAssignments = $assignments->count();
        $submittedAssignments = 0;
        $totalAssignmentMarks = 0;
        $obtainedAssignmentMarks = 0;

        foreach ($assignments as $assignment) {
            $submission = AssignmentSubmission::where('assignment_id', $assignment->id)
                ->where('student_id', $studentId)
                ->where('status', 'submitted')
                ->first();

            if ($submission) {
                $submittedAssignments++;
                if ($submission->marks !== null && $assignment->full_marks > 0) {
                    $totalAssignmentMarks += $assignment->full_marks;
                    $obtainedAssignmentMarks += $submission->marks;
                }
            }
        }

        // 2. Attendance Performance
        $classSessions = InstituteSession::where('institute_id', $instituteId)
            ->where('status', 'class')
            ->where('date', '>=', $academicYearStart->format('Y-m-d'))
            ->where('date', '<=', $currentDate->format('Y-m-d'))
            ->get();

        $totalClassDays = $classSessions->count();
        $attendanceRecords = Attendance::where('attendee_id', $studentId)
            ->where('attendee_type', 'student')
            ->where('institute_id', $instituteId)
            ->where('date', '>=', $academicYearStart->format('Y-m-d'))
            ->where('date', '<=', $currentDate->format('Y-m-d'))
            ->get();

        $presentDays = $attendanceRecords->whereIn('status', ['present', 'late'])->count();

        // 3. Midterm Performance (mock data - you may need to adjust based on your exam system)
        // For now, we'll use assignment average as a proxy for midterm performance
        $midtermMarks = $totalAssignmentMarks > 0 ?
            round(($obtainedAssignmentMarks / $totalAssignmentMarks) * 100) : 0;
        $midtermTotal = 100;

        // 4. Preboard Performance (mock data - you may need to adjust based on your exam system)
        // For now, we'll use a slightly higher performance than assignments as preboard
        $preboardMarks = $totalAssignmentMarks > 0 ?
            round((($obtainedAssignmentMarks / $totalAssignmentMarks) * 100) * 1.1) : 0;
        $preboardMarks = min($preboardMarks, 100); // Cap at 100
        $preboardTotal = 100;

        return [
            'assignments_done' => $submittedAssignments,
            'assignments_total' => max($totalAssignments, 1), // Prevent division by zero
            'attendance_present' => $presentDays,
            'attendance_total' => max($totalClassDays, 1), // Prevent division by zero
            'midterm_marks' => $midtermMarks,
            'midterm_total' => $midtermTotal,
            'preboard_marks' => $preboardMarks,
            'preboard_total' => $preboardTotal,
            'performance_summary' => [
                'assignment_ratio' => $totalAssignments > 0 ? round(($submittedAssignments / $totalAssignments) * 100, 1) : 0,
                'attendance_ratio' => $totalClassDays > 0 ? round(($presentDays / $totalClassDays) * 100, 1) : 0,
                'midterm_ratio' => round(($midtermMarks / $midtermTotal) * 100, 1),
                'preboard_ratio' => round(($preboardMarks / $preboardTotal) * 100, 1),
            ]
        ];
    }

    /**
     * Make a prediction for the current student
     */
    public function predict(Request $request)
    {
        try {
            // Validate input
            $validated = $request->validate([
                'assignments_done' => 'required|numeric|min:0',
                'assignments_total' => 'required|numeric|min:1',
                'attendance_present' => 'required|numeric|min:0',
                'attendance_total' => 'required|numeric|min:1',
                'midterm_marks' => 'required|numeric|min:0',
                'midterm_total' => 'required|numeric|min:1',
                'preboard_marks' => 'required|numeric|min:0',
                'preboard_total' => 'required|numeric|min:1',
            ]);

            // Additional validation
            $validationErrors = [];
            if ($validated['assignments_done'] > $validated['assignments_total']) {
                $validationErrors[] = 'Assignments done cannot exceed total assignments';
            }
            if ($validated['attendance_present'] > $validated['attendance_total']) {
                $validationErrors[] = 'Attendance present cannot exceed total attendance';
            }
            if ($validated['midterm_marks'] > $validated['midterm_total']) {
                $validationErrors[] = 'Midterm marks cannot exceed total marks';
            }
            if ($validated['preboard_marks'] > $validated['preboard_total']) {
                $validationErrors[] = 'Preboard marks cannot exceed total marks';
            }

            if (!empty($validationErrors)) {
                return redirect()->back()
                    ->withErrors($validationErrors)
                    ->withInput();
            }

            // Check if model exists
            if (!$this->loadModel()) {
                return redirect()->back()
                    ->with('prediction_error', 'Prediction model not available. Please contact your administrator.')
                    ->withInput();
            }

            // Calculate ratios
            $features = [
                $validated['assignments_done'] / $validated['assignments_total'],
                $validated['attendance_present'] / $validated['attendance_total'],
                $validated['midterm_marks'] / $validated['midterm_total'],
                $validated['preboard_marks'] / $validated['preboard_total']
            ];

            // Make prediction
            $probability = $this->sigmoid($this->dotProduct($features, $this->weights) + $this->bias);
            $prediction = $probability >= 0.5;

            // Store prediction in session for display
            return redirect()->back()->with([
                'prediction_result' => $prediction,
                'prediction_probability' => $probability,
                'predicted' => true,
                'prediction_success' => 'Prediction completed successfully!'
            ])->withInput();

        } catch (\Exception $e) {
            Log::error('Student prediction error: ' . $e->getMessage());
            return redirect()->back()
                ->with('prediction_error', 'Error making prediction: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Get recommendations based on current performance
     */
    public function getRecommendations(Request $request)
    {
        $user = Auth::user();
        $studentId = $user->id;

        $instituteStudent = InstituteStudent::where('student_id', $studentId)
            ->where('is_approved', true)
            ->first();

        if (!$instituteStudent) {
            return response()->json(['error' => 'Student not enrolled'], 404);
        }

        $performanceData = $this->getStudentPerformanceData($studentId, $instituteStudent);
        $recommendations = $this->generateRecommendations($performanceData['performance_summary']);

        return response()->json([
            'recommendations' => $recommendations,
            'current_performance' => $performanceData['performance_summary']
        ]);
    }

    /**
     * Generate recommendations based on performance
     */
    private function generateRecommendations($performance)
    {
        $recommendations = [];

        // Assignment recommendations
        if ($performance['assignment_ratio'] < 70) {
            $recommendations[] = [
                'type' => 'assignment',
                'priority' => 'high',
                'message' => 'Focus on completing more assignments. Current completion rate: ' . $performance['assignment_ratio'] . '%',
                'action' => 'Submit pending assignments to improve your grade'
            ];
        }

        // Attendance recommendations
        if ($performance['attendance_ratio'] < 75) {
            $recommendations[] = [
                'type' => 'attendance',
                'priority' => 'critical',
                'message' => 'Improve attendance immediately. Current rate: ' . $performance['attendance_ratio'] . '%',
                'action' => 'Attend classes regularly to meet minimum requirements'
            ];
        }

        // Exam performance recommendations
        if ($performance['midterm_ratio'] < 60) {
            $recommendations[] = [
                'type' => 'exam',
                'priority' => 'high',
                'message' => 'Midterm performance needs improvement: ' . $performance['midterm_ratio'] . '%',
                'action' => 'Focus on exam preparation and seek help from teachers'
            ];
        }

        if ($performance['preboard_ratio'] < 60) {
            $recommendations[] = [
                'type' => 'exam',
                'priority' => 'high',
                'message' => 'Preboard performance needs improvement: ' . $performance['preboard_ratio'] . '%',
                'action' => 'Intensive study and practice for final exams'
            ];
        }

        // Positive reinforcement
        if (empty($recommendations)) {
            $recommendations[] = [
                'type' => 'positive',
                'priority' => 'info',
                'message' => 'Great job! Your performance is on track.',
                'action' => 'Keep up the good work and maintain consistency'
            ];
        }

        return $recommendations;
    }

    /**
     * Load the trained model (same as admin controller)
     */
    private function loadModel()
    {
        if (!Storage::exists($this->modelPath)) {
            return false;
        }

        try {
            $model = json_decode(Storage::get($this->modelPath), true);

            if (!$model || !isset($model['weights']) || !isset($model['bias'])) {
                return false;
            }

            $this->weights = $model['weights'];
            $this->bias = $model['bias'];

            return true;
        } catch (\Exception $e) {
            Log::error('Error loading model: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get model information
     */
    private function getModelInfo()
    {
        $info = [
            'lastTrained' => 'Never',
            'modelAccuracy' => 'N/A',
            'available' => false
        ];

        if (Storage::exists($this->modelPath)) {
            try {
                $model = json_decode(Storage::get($this->modelPath), true);
                if ($model) {
                    $info['lastTrained'] = Carbon::parse($model['last_trained'])->format('M d, Y H:i');
                    $info['modelAccuracy'] = number_format($model['accuracy'] * 100, 1) . '%';
                    $info['available'] = true;
                }
            } catch (\Exception $e) {
                Log::error('Error reading model info: ' . $e->getMessage());
            }
        }

        return $info;
    }

    /**
     * Calculate the sigmoid function
     */
    private function sigmoid($z)
    {
        $z = max(min($z, 500), -500);
        return 1 / (1 + exp(-$z));
    }

    /**
     * Calculate the dot product of two vectors
     */
    private function dotProduct($a, $b)
    {
        $result = 0.0;
        for ($i = 0; $i < count($a); $i++) {
            $result += (float)$a[$i] * (float)$b[$i];
        }
        return $result;
    }
}
