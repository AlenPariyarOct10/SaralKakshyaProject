<?php

namespace App\Http\Controllers\Algorithm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StudentPassPredictionController extends Controller
{
    // Model parameters
    private $weights = [];
    private $bias = 0;
    private $learningRate = 0.01;
    private $iterations = 1000;
    private $modelPath = 'models/logistic_regression_model.json';
    private $trainingDataPath = 'models/training_data.json';

    /**
     * Display the prediction dashboard
     */
    public function index()
    {
        $modelInfo = $this->getModelInfo();

        return view('backend.admin.prediction.index', [
            'user' => auth()->user(),
            'lastTrained' => $modelInfo['lastTrained'],
            'trainingSize' => $modelInfo['trainingSize'],
            'modelAccuracy' => $modelInfo['modelAccuracy'],
        ]);
    }

    /**
     * Train the logistic regression model
     */
    public function train(Request $request)
    {
        try {
            // Get training data from database
            $trainingData = $this->getTrainingData();

            if (count($trainingData) < 10) {
                return response()->json([
                    'success' => false,
                    'message' => 'Not enough training data. Need at least 10 records.'
                ]);
            }

            // Prepare features and labels
            $features = [];
            $labels = [];

            foreach ($trainingData as $data) {
                $features[] = [
                    $data['assignments_ratio'],
                    $data['attendance_ratio'],
                    $data['presentation_ratio'],
                    $data['midterm_ratio'],
                    $data['preboard_ratio']
                ];
                $labels[] = $data['passed'];
            }

            // Initialize weights and bias
            $numFeatures = count($features[0]);
            $this->weights = array_fill(0, $numFeatures, 0);
            $this->bias = 0;

            // Train the model using gradient descent
            $this->gradientDescent($features, $labels);

            // Calculate accuracy
            $accuracy = $this->calculateAccuracy($features, $labels);

            // Save the model
            $this->saveModel($accuracy, count($trainingData));

            return response()->json([
                'success' => true,
                'message' => 'Model trained successfully!',
                'lastTrained' => Carbon::now()->format('M d, Y H:i'),
                'trainingSize' => count($trainingData),
                'modelAccuracy' => number_format($accuracy * 100, 1) . '%'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error training model: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Make a prediction for a student
     */
    public function predict(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'assignments_done' => 'required|numeric|min:0',
            'assignments_total' => 'required|numeric|min:1',
            'attendance_present' => 'required|numeric|min:0',
            'attendance_total' => 'required|numeric|min:1',
            'presentation_done' => 'required|numeric|min:0',
            'presentation_total' => 'required|numeric|min:1',
            'midterm_marks' => 'required|numeric|min:0',
            'midterm_total' => 'required|numeric|min:1',
            'preboard_marks' => 'required|numeric|min:0',
            'preboard_total' => 'required|numeric|min:1',
        ]);

        // Check if model exists
        if (!$this->loadModel()) {
            return redirect()->back()->with('training_error', 'Model not trained yet. Please train the model first.');
        }

        // Calculate ratios
        $features = [
            $validated['assignments_done'] / $validated['assignments_total'],
            $validated['attendance_present'] / $validated['attendance_total'],
            $validated['presentation_done'] / $validated['presentation_total'],
            $validated['midterm_marks'] / $validated['midterm_total'],
            $validated['preboard_marks'] / $validated['preboard_total']
        ];

        // Make prediction
        $probability = $this->sigmoid($this->dotProduct($features, $this->weights) + $this->bias);
        $prediction = $probability >= 0.5;

        return redirect()->back()->with([
            'prediction_result' => $prediction,
            'prediction_probability' => $prediction ? $probability : (1 - $probability)
        ])->withInput()->with('predicted', true);
    }

    /**
     * Get training data from the database
     */
    private function getTrainingData()
    {
        // In a real application, you would fetch this from your database
        // For this example, we'll use mock data if no saved training data exists

        if (Storage::exists($this->trainingDataPath)) {
            return json_decode(Storage::get($this->trainingDataPath), true);
        }

        // Generate mock training data
        $mockData = [];
        for ($i = 0; $i < 100; $i++) {
            // Students with good performance are more likely to pass
            $assignmentsRatio = mt_rand(0, 100) / 100;
            $attendanceRatio = mt_rand(0, 100) / 100;
            $presentationRatio = mt_rand(0, 100) / 100;
            $midtermRatio = mt_rand(0, 100) / 100;
            $preboardRatio = mt_rand(0, 100) / 100;

            // Simple rule: if average performance > 0.6, student passes
            $averagePerformance = ($assignmentsRatio + $attendanceRatio + $presentationRatio + $midtermRatio + $preboardRatio) / 5;
            $passed = $averagePerformance > 0.6;

            // Add some noise
            if (mt_rand(0, 100) < 10) {
                $passed = !$passed;
            }

            $mockData[] = [
                'assignments_ratio' => $assignmentsRatio,
                'attendance_ratio' => $attendanceRatio,
                'presentation_ratio' => $presentationRatio,
                'midterm_ratio' => $midtermRatio,
                'preboard_ratio' => $preboardRatio,
                'passed' => $passed ? 1 : 0
            ];
        }

        // Save mock data
        Storage::put($this->trainingDataPath, json_encode($mockData));

        return $mockData;
    }

    /**
     * Implement logistic regression using gradient descent
     */
    private function gradientDescent($features, $labels)
    {
        $m = count($features); // Number of training examples

        for ($iteration = 0; $iteration < $this->iterations; $iteration++) {
            $gradientWeights = array_fill(0, count($this->weights), 0);
            $gradientBias = 0;

            // Calculate gradients
            for ($i = 0; $i < $m; $i++) {
                $prediction = $this->sigmoid($this->dotProduct($features[$i], $this->weights) + $this->bias);
                $error = $prediction - $labels[$i];

                // Update gradients
                for ($j = 0; $j < count($this->weights); $j++) {
                    $gradientWeights[$j] += $error * $features[$i][$j];
                }
                $gradientBias += $error;
            }

            // Update weights and bias
            for ($j = 0; $j < count($this->weights); $j++) {
                $this->weights[$j] -= ($this->learningRate * $gradientWeights[$j]) / $m;
            }
            $this->bias -= ($this->learningRate * $gradientBias) / $m;
        }
    }

    /**
     * Calculate the sigmoid function
     */
    private function sigmoid($z)
    {
        return 1 / (1 + exp(-$z));
    }

    /**
     * Calculate the dot product of two vectors
     */
    private function dotProduct($a, $b)
    {
        $result = 0;
        for ($i = 0; $i < count($a); $i++) {
            $result += $a[$i] * $b[$i];
        }
        return $result;
    }

    /**
     * Calculate model accuracy
     */
    private function calculateAccuracy($features, $labels)
    {
        $correct = 0;
        $total = count($features);

        for ($i = 0; $i < $total; $i++) {
            $prediction = $this->sigmoid($this->dotProduct($features[$i], $this->weights) + $this->bias) >= 0.5 ? 1 : 0;
            if ($prediction == $labels[$i]) {
                $correct++;
            }
        }

        return $correct / $total;
    }

    /**
     * Save the trained model
     */
    private function saveModel($accuracy, $trainingSize)
    {
        $model = [
            'weights' => $this->weights,
            'bias' => $this->bias,
            'accuracy' => $accuracy,
            'training_size' => $trainingSize,
            'last_trained' => Carbon::now()->toDateTimeString()
        ];

        Storage::put($this->modelPath, json_encode($model));
    }

    /**
     * Load the trained model
     */
    private function loadModel()
    {
        if (!Storage::exists($this->modelPath)) {
            return false;
        }

        $model = json_decode(Storage::get($this->modelPath), true);
        $this->weights = $model['weights'];
        $this->bias = $model['bias'];

        return true;
    }

    /**
     * Get model information
     */
    private function getModelInfo()
    {
        $info = [
            'lastTrained' => 'Never',
            'trainingSize' => '0',
            'modelAccuracy' => 'N/A'
        ];

        if (Storage::exists($this->modelPath)) {
            $model = json_decode(Storage::get($this->modelPath), true);
            $info['lastTrained'] = Carbon::parse($model['last_trained'])->format('M d, Y H:i');
            $info['trainingSize'] = $model['training_size'];
            $info['modelAccuracy'] = number_format($model['accuracy'] * 100, 1) . '%';
        }

        return $info;
    }
}
