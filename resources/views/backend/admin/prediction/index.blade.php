@extends('backend.layout.admin-dashboard-layout')

@section('username')
    {{$user->fname}} {{$user->lname}}
@endsection

@section('fname')
    {{$user->fname}}
@endsection

@section('lname')
    {{$user->lname}}
@endsection

@section('profile_picture')
    {{$user->profile_picture}}
@endsection

@push("styles")
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                            950: '#082f49',
                        },
                    }
                }
            }
        }
    </script>
    <style type="text/tailwindcss">
        @layer utilities {
            .btn-primary {
                @apply px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-colors duration-200 font-medium text-sm;
            }
            .btn-secondary {
                @apply px-4 py-2 bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-200 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300 dark:focus:ring-gray-600 focus:ring-offset-2 transition-colors duration-200 font-medium text-sm;
            }
            .card {
                @apply bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden;
            }
            .form-input {
                @apply w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:text-white text-sm;
            }
            .form-label {
                @apply block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1;
            }
            .status-success {
                @apply bg-green-100 text-green-800 dark:bg-green-800/20 dark:text-green-400 px-3 py-2 rounded-md text-sm;
            }
            .status-error {
                @apply bg-red-100 text-red-800 dark:bg-red-800/20 dark:text-red-400 px-3 py-2 rounded-md text-sm;
            }
            .status-warning {
                @apply bg-yellow-100 text-yellow-800 dark:bg-yellow-800/20 dark:text-yellow-400 px-3 py-2 rounded-md text-sm;
            }
            .status-info {
                @apply bg-blue-100 text-blue-800 dark:bg-blue-800/20 dark:text-blue-400 px-3 py-2 rounded-md text-sm;
            }
            .prediction-result {
                @apply mt-6 p-4 rounded-lg text-center text-lg font-medium;
            }
            .prediction-pass {
                @apply bg-green-100 text-green-800 dark:bg-green-800/20 dark:text-green-400;
            }
            .prediction-fail {
                @apply bg-red-100 text-red-800 dark:bg-red-800/20 dark:text-red-400;
            }
        }
    </style>
@endpush

@section('content')
    <!-- Main Content Area -->
    <main class="scrollable-content p-4 md:p-6">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Student Performance Prediction</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Using Logistic Regression to predict student outcomes</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Model Training Panel -->
            <div class="lg:col-span-1">
                <div class="card">
                    <div class="p-6">
                        <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Train Model</h2>
                        <p class="text-gray-600 dark:text-gray-400 mb-6">
                            Train the logistic regression model using historical student performance data.
                        </p>

                        <form action="{{ route('admin.prediction.train') }}" method="POST" id="trainForm">
                            @csrf
                            <button type="submit" class="btn-primary w-full flex items-center justify-center" id="trainButton">
                                <i class="fas fa-cogs mr-2"></i> Train Now
                            </button>
                        </form>

                        <div class="mt-4" id="trainingStatus">
                            @if(session('training_success'))
                                <div class="status-success">
                                    <i class="fas fa-check-circle mr-2"></i> {{ session('training_success') }}
                                </div>
                            @elseif(session('training_error'))
                                <div class="status-error">
                                    <i class="fas fa-exclamation-circle mr-2"></i> {{ session('training_error') }}
                                </div>
                            @else
                                <div class="status-info">
                                    <i class="fas fa-info-circle mr-2"></i> Model ready for training
                                </div>
                            @endif
                        </div>

                        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <h3 class="text-md font-medium text-gray-800 dark:text-white mb-3">Model Information</h3>

                            <div class="space-y-3 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Last Trained:</span>
                                    <span class="font-medium text-gray-800 dark:text-gray-200" id="lastTrained">
                                        {{ $lastTrained ?? 'Never' }}
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Training Data Size:</span>
                                    <span class="font-medium text-gray-800 dark:text-gray-200" id="trainingSize">
                                        {{ $trainingSize ?? '0' }} records
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Model Accuracy:</span>
                                    <span class="font-medium text-gray-800 dark:text-gray-200" id="modelAccuracy">
                                        {{ $modelAccuracy ?? 'N/A' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Prediction Panel -->
            <div class="lg:col-span-2">
                <div class="card">
                    <div class="p-6">
                        <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Predict Student Result</h2>
                        <p class="text-gray-600 dark:text-gray-400 mb-6">
                            Enter student performance metrics to predict their likelihood of passing.
                        </p>

                        <form action="{{ route('admin.prediction.predict') }}" method="POST" id="predictionForm">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Assignments -->
                                <div>
                                    <label for="assignments_done" class="form-label">Assignments Done</label>
                                    <input type="number" id="assignments_done" name="assignments_done" class="form-input" placeholder="0" min="0" required>
                                </div>
                                <div>
                                    <label for="assignments_total" class="form-label">Assignments Total</label>
                                    <input type="number" id="assignments_total" name="assignments_total" class="form-input" placeholder="10" min="1" required>
                                </div>

                                <!-- Attendance -->
                                <div>
                                    <label for="attendance_present" class="form-label">Attendance Present</label>
                                    <input type="number" id="attendance_present" name="attendance_present" class="form-input" placeholder="0" min="0" required>
                                </div>
                                <div>
                                    <label for="attendance_total" class="form-label">Attendance Total</label>
                                    <input type="number" id="attendance_total" name="attendance_total" class="form-input" placeholder="100" min="1" required>
                                </div>

                                <!-- Presentations -->
                                <div>
                                    <label for="presentation_done" class="form-label">Presentations Done</label>
                                    <input type="number" id="presentation_done" name="presentation_done" class="form-input" placeholder="0" min="0" required>
                                </div>
                                <div>
                                    <label for="presentation_total" class="form-label">Presentations Total</label>
                                    <input type="number" id="presentation_total" name="presentation_total" class="form-input" placeholder="5" min="1" required>
                                </div>

                                <!-- Mid-term Marks -->
                                <div>
                                    <label for="midterm_marks" class="form-label">Mid-term Marks Obtained</label>
                                    <input type="number" id="midterm_marks" name="midterm_marks" class="form-input" placeholder="0" min="0" required>
                                </div>
                                <div>
                                    <label for="midterm_total" class="form-label">Mid-term Total Marks</label>
                                    <input type="number" id="midterm_total" name="midterm_total" class="form-input" placeholder="100" min="1" required>
                                </div>

                                <!-- Preboard Marks -->
                                <div>
                                    <label for="preboard_marks" class="form-label">Preboard Marks Obtained</label>
                                    <input type="number" id="preboard_marks" name="preboard_marks" class="form-input" placeholder="0" min="0" required>
                                </div>
                                <div>
                                    <label for="preboard_total" class="form-label">Preboard Total Marks</label>
                                    <input type="number" id="preboard_total" name="preboard_total" class="form-input" placeholder="100" min="1" required>
                                </div>
                            </div>

                            <div class="mt-6">
                                <button type="submit" class="btn-primary w-full flex items-center justify-center" id="predictButton">
                                    <i class="fas fa-chart-line mr-2"></i> Predict
                                </button>
                            </div>
                        </form>

                        @if(session('prediction_result'))
                            hello
                            <div class="prediction-result {{ session('prediction_result') ? 'prediction-pass' : 'prediction-fail' }}">
                                @if(session('prediction_result'))
                                    <div class="flex items-center justify-center">
                                        <span class="text-2xl mr-2">✅</span>
                                        <span>Likely to Pass ({{ number_format(session('prediction_probability') * 100, 1) }}% probability)</span>
                                    </div>
                                @else
                                    <div class="flex items-center justify-center">
                                        <span class="text-2xl mr-2">❌</span>
                                        <span>Likely to Fail ({{ number_format((1 - session('prediction_probability')) * 100, 1) }}% probability)</span>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section("scripts")
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const trainForm = document.getElementById('trainForm');
            const trainButton = document.getElementById('trainButton');
            const trainingStatus = document.getElementById('trainingStatus');

            // Handle training form submission
            if (trainForm) {
                trainForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    // Update UI to show training in progress
                    trainButton.disabled = true;
                    trainButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Training...';
                    trainingStatus.innerHTML = '<div class="status-warning"><i class="fas fa-spinner fa-spin mr-2"></i> Training in progress...</div>';

                    // Submit the form using fetch API
                    fetch(trainForm.action, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({})
                    })
                        .then(response => response.json())
                        .then(data => {
                            // Update UI based on response
                            if (data.success) {
                                trainingStatus.innerHTML = `<div class="status-success"><i class="fas fa-check-circle mr-2"></i> ${data.message}</div>`;

                                // Update model information
                                if (data.lastTrained) document.getElementById('lastTrained').textContent = data.lastTrained;
                                if (data.trainingSize) document.getElementById('trainingSize').textContent = data.trainingSize + ' records';
                                if (data.modelAccuracy) document.getElementById('modelAccuracy').textContent = data.modelAccuracy;
                            } else {
                                trainingStatus.innerHTML = `<div class="status-error"><i class="fas fa-exclamation-circle mr-2"></i> ${data.message}</div>`;
                            }
                        })
                        .catch(error => {
                            trainingStatus.innerHTML = '<div class="status-error"><i class="fas fa-exclamation-circle mr-2"></i> An error occurred during training</div>';
                            console.error('Error:', error);
                        })
                        .finally(() => {
                            // Re-enable the button
                            trainButton.disabled = false;
                            trainButton.innerHTML = '<i class="fas fa-cogs mr-2"></i> Train Now';
                        });
                });
            }

            // Form validation for prediction
            const predictionForm = document.getElementById('predictionForm');
            if (predictionForm) {
                predictionForm.addEventListener('submit', function(e) {
                    // Basic validation
                    const inputs = predictionForm.querySelectorAll('input[type="number"]');
                    let isValid = true;

                    inputs.forEach(input => {
                        // Check if obtained marks are not greater than total
                        if (input.id.includes('_done') || input.id.includes('_present') || input.id.includes('_marks')) {
                            const totalId = input.id.replace('_done', '_total').replace('_present', '_total').replace('_marks', '_total');
                            const totalInput = document.getElementById(totalId);

                            if (totalInput && parseInt(input.value) > parseInt(totalInput.value)) {
                                input.classList.add('border-red-500');
                                isValid = false;
                            } else {
                                input.classList.remove('border-red-500');
                            }
                        }
                    });

                    if (!isValid) {
                        e.preventDefault();
                        alert('Obtained values cannot be greater than total values');
                    }
                });

                // Real-time validation as user types
                predictionForm.querySelectorAll('input[type="number"]').forEach(input => {
                    input.addEventListener('input', function() {
                        if (input.id.includes('_done') || input.id.includes('_present') || input.id.includes('_marks')) {
                            const totalId = input.id.replace('_done', '_total').replace('_present', '_total').replace('_marks', '_total');
                            const totalInput = document.getElementById(totalId);

                            if (totalInput && parseInt(input.value) > parseInt(totalInput.value)) {
                                input.classList.add('border-red-500');
                            } else {
                                input.classList.remove('border-red-500');
                            }
                        }
                    });
                });
            }
        });
    </script>
@endsection
