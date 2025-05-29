<?php

namespace App\Http\Controllers\Algorithm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

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
            // Ensure storage directories exist
            $this->ensureStorageDirectories();

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
                    (float) $data['assignments_ratio'],
                    (float) $data['attendance_ratio'],
                    (float) $data['midterm_ratio'],
                    (float) $data['preboard_ratio']
                ];
                $labels[] = (int) $data['passed'];
            }

            // Initialize weights and bias
            $numFeatures = count($features[0]);
            $this->weights = array_fill(0, $numFeatures, 0.0);
            $this->bias = 0.0;

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
            Log::error('Model training error: ' . $e->getMessage());
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

            // Additional validation: ensure done/present values don't exceed totals
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
                    ->with('training_error', 'Model not trained yet. Please train the model first.')
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

            return redirect()->back()->with([
                'prediction_result' => $prediction,
                'prediction_probability' => $probability,
                'predicted' => true
            ])->withInput();





        } catch (\Exception $e) {
            Log::error('Prediction error: ' . $e->getMessage());
            return redirect()->back()
                ->with('training_error', 'Error making prediction: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Ensure storage directories exist
     */
    private function ensureStorageDirectories()
    {
        $directory = dirname($this->modelPath);
        if (!Storage::exists($directory)) {
            Storage::makeDirectory($directory);
        }
    }

    /**
     * Get training data from the database
     */
    private function getTrainingData()
    {
        $mockData = [];
        if (Storage::exists($this->trainingDataPath)) {
            $data = json_decode(Storage::get($this->trainingDataPath), true);
            if ($data && is_array($data)) {
                return $data;
            }
        }

        // Generate mock data adhering to new rules
        for ($i = 0; $i < 500; $i++) {
            // Generate base ratios
            $assignmentsRatio = $this->generateRealisticRatio();
            $midtermRatio = $this->generateRealisticRatio();
            $preboardRatio = $this->generateRealisticRatio();
            $attendanceRatio = $this->generateRealisticRatio();

            // Introduce special cases
            $rand = mt_rand(1, 100);
            if ($rand <= 10) { // 10% special passing cases
                $whichParam = mt_rand(0, 2);
                switch ($whichParam) {
                    case 0:
                        $assignmentsRatio = mt_rand(0, 19) / 100;
                        $midtermRatio = 1.0;
                        $preboardRatio = 1.0;
                        break;
                    case 1:
                        $midtermRatio = mt_rand(0, 19) / 100;
                        $assignmentsRatio = 1.0;
                        $preboardRatio = 1.0;
                        break;
                    case 2:
                        $preboardRatio = mt_rand(0, 19) / 100;
                        $assignmentsRatio = 1.0;
                        $midtermRatio = 1.0;
                        break;
                }
                $attendanceRatio = mt_rand(70, 100) / 100;
            } elseif ($rand <= 20) { // 10% special failing cases
                $whichParam = mt_rand(0, 2);
                switch ($whichParam) {
                    case 0:
                        $assignmentsRatio = 0.0;
                        $midtermRatio = mt_rand(35, 65) / 100;
                        $preboardRatio = mt_rand(35, 65) / 100;
                        break;
                    case 1:
                        $midtermRatio = 0.0;
                        $assignmentsRatio = mt_rand(35, 65) / 100;
                        $preboardRatio = mt_rand(35, 65) / 100;
                        break;
                    case 2:
                        $preboardRatio = 0.0;
                        $assignmentsRatio = mt_rand(35, 65) / 100;
                        $midtermRatio = mt_rand(35, 65) / 100;
                        break;
                }
                $attendanceRatio = mt_rand(70, 100) / 100;
            }

            // Determine pass/fail using new rules
            $passed = $this->determinePassed(
                $assignmentsRatio,
                $attendanceRatio,
                $midtermRatio,
                $preboardRatio
            ) ? 1 : 0;

            $mockData[] = [
                'assignments_ratio' => round($assignmentsRatio, 3),
                'attendance_ratio' => round($attendanceRatio, 3),
                'midterm_ratio' => round($midtermRatio, 3),
                'preboard_ratio' => round($preboardRatio, 3),
                'passed' => $passed
            ];
        }

        Storage::put($this->trainingDataPath, json_encode($mockData, JSON_PRETTY_PRINT));
        return $mockData;
    }
    /**
     * Determine pass/fail using rules
     */
    private function determinePassed($assignmentsRatio, $attendanceRatio, $midtermRatio, $preboardRatio)
    {
        // 1. Attendance check
        if ($attendanceRatio < 0.70) {
            return false;
        }

        $params = [$assignmentsRatio, $midtermRatio, $preboardRatio];

        // 2. Special passing condition
        foreach ($params as $i => $value) {
            $others = array_filter($params, function ($key) use ($i) {
                return $key !== $i;
            }, ARRAY_FILTER_USE_KEY);
            if ($value < 0.20 && count($others) === 2 && min($others) >= 1.0) {
                return true;
            }
        }

        // 3. Special failing condition
        foreach ($params as $i => $value) {
            if ($value == 0.0) {
                $others = array_filter($params, function ($key) use ($i) {
                    return $key !== $i;
                }, ARRAY_FILTER_USE_KEY);
                $allAverage = true;
                foreach ($others as $other) {
                    if ($other < 0.35 || $other > 0.65) {
                        $allAverage = false;
                        break;
                    }
                }
                if ($allAverage) {
                    return false;
                }
            }
        }

        // 4. General pass logic
        return ($assignmentsRatio >= 0.35 && $midtermRatio >= 0.35 && $preboardRatio >= 0.35);
    }

    /**
     * Generate realistic performance ratios
     */
    private function generateRealisticRatio()
    {

        $random = mt_rand(0, 100);

        if ($random < 10) {
            // 10% poor performance (0-0.4)
            return mt_rand(0, 40) / 100;
        } elseif ($random < 25) {
            // 15% below average (0.4-0.6)
            return mt_rand(40, 60) / 100;
        } elseif ($random < 75) {
            // 50% average performance (0.6-0.8)
            return mt_rand(60, 80) / 100;
        } else {
            // 25% good performance (0.8-1.0)
            return mt_rand(80, 100) / 100;
        }
    }

    /**
     * Implement logistic regression using gradient descent
     */
    private function gradientDescent($features, $labels)
    {
        $m = count($features); // Number of training examples

        for ($iteration = 0; $iteration < $this->iterations; $iteration++) {
            $gradientWeights = array_fill(0, count($this->weights), 0.0);
            $gradientBias = 0.0;

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

            // Optional: Add convergence check
            if ($iteration % 100 == 0) {
                $cost = $this->calculateCost($features, $labels);
                Log::info("Iteration $iteration, Cost: $cost");
            }
        }
    }

    /**
     * Calculate cost function (log-likelihood)
     */
    private function calculateCost($features, $labels)
    {
        $m = count($features);
        $cost = 0;

        for ($i = 0; $i < $m; $i++) {
            $prediction = $this->sigmoid($this->dotProduct($features[$i], $this->weights) + $this->bias);
            $prediction = max(min($prediction, 0.9999), 0.0001); // Prevent log(0)

            $cost += -($labels[$i] * log($prediction) + (1 - $labels[$i]) * log(1 - $prediction));
        }

        return $cost / $m;
    }

    /**
     * Calculate the sigmoid function
     */
    private function sigmoid($z)
    {
        // Prevent overflow
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

        return $total > 0 ? $correct / $total : 0;
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
            'last_trained' => Carbon::now()->toDateTimeString(),
            'learning_rate' => $this->learningRate,
            'iterations' => $this->iterations
        ];

        Storage::put($this->modelPath, json_encode($model, JSON_PRETTY_PRINT));
    }

    /**
     * Load the trained model
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
            'trainingSize' => '0',
            'modelAccuracy' => 'N/A'
        ];

        if (Storage::exists($this->modelPath)) {
            try {
                $model = json_decode(Storage::get($this->modelPath), true);
                if ($model) {
                    $info['lastTrained'] = Carbon::parse($model['last_trained'])->format('M d, Y H:i');
                    $info['trainingSize'] = $model['training_size'];
                    $info['modelAccuracy'] = number_format($model['accuracy'] * 100, 1) . '%';
                }
            } catch (\Exception $e) {
                Log::error('Error reading model info: ' . $e->getMessage());
            }
        }

        return $info;
    }
}
