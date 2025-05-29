@extends('backend.layout.student-dashboard-layout')
@php
    $user = Auth::user();
@endphp
@section('username', $user->fname . ' ' . $user->lname)

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
            .performance-card {
                @apply bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border border-blue-200 dark:border-blue-700;
            }
        }
    </style>
@endpush

@section('content')
    <div class="scrollable-content p-6 bg-gray-50 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto">
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">My Performance Prediction</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">AI-powered prediction of your academic performance</p>
            </div>

            <!-- Success/Error Messages -->
            @if(session('prediction_success'))
                <div class="status-success mb-6">
                    <i class="fas fa-check-circle mr-2"></i> {{ session('prediction_success') }}
                </div>
            @endif

            @if(session('prediction_error'))
                <div class="status-error mb-6">
                    <i class="fas fa-exclamation-circle mr-2"></i> {{ session('prediction_error') }}
                </div>
            @endif

            @if(isset($error))
                <div class="status-error mb-6">
                    <i class="fas fa-exclamation-circle mr-2"></i> {{ $error }}
                </div>
            @endif

            @if($errors->any())
                <div class="status-error mb-6">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Current Performance Summary -->
                <div class="lg:col-span-1">
                    <div class="card performance-card">
                        <div class="p-6">
                            <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">
                                <i class="fas fa-chart-line mr-2"></i>Current Performance
                            </h2>

                            @if($autoFillData)
                                <div class="space-y-4">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600 dark:text-gray-400">Assignments:</span>
                                        <span class="font-medium text-gray-800 dark:text-gray-200">
                                            {{ $autoFillData['performance_summary']['assignment_ratio'] }}%
                                        </span>
                                    </div>
                                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                        <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $autoFillData['performance_summary']['assignment_ratio'] }}%"></div>
                                    </div>

                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600 dark:text-gray-400">Attendance:</span>
                                        <span class="font-medium text-gray-800 dark:text-gray-200">
                                            {{ $autoFillData['performance_summary']['attendance_ratio'] }}%
                                        </span>
                                    </div>
                                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                        <div class="bg-green-500 h-2 rounded-full" style="width: {{ $autoFillData['performance_summary']['attendance_ratio'] }}%"></div>
                                    </div>

                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600 dark:text-gray-400">Midterm:</span>
                                        <span class="font-medium text-gray-800 dark:text-gray-200">
                                            {{ $autoFillData['performance_summary']['midterm_ratio'] }}%
                                        </span>
                                    </div>
                                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                        <div class="bg-yellow-500 h-2 rounded-full" style="width: {{ $autoFillData['performance_summary']['midterm_ratio'] }}%"></div>
                                    </div>

                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600 dark:text-gray-400">Preboard:</span>
                                        <span class="font-medium text-gray-800 dark:text-gray-200">
                                            {{ $autoFillData['performance_summary']['preboard_ratio'] }}%
                                        </span>
                                    </div>
                                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                        <div class="bg-purple-500 h-2 rounded-full" style="width: {{ $autoFillData['performance_summary']['preboard_ratio'] }}%"></div>
                                    </div>
                                </div>

                                <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                                    <button id="getRecommendations" class="btn-secondary w-full">
                                        <i class="fas fa-lightbulb mr-2"></i> Get Recommendations
                                    </button>
                                </div>
                            @else
                                <div class="text-center text-gray-500 dark:text-gray-400">
                                    <i class="fas fa-chart-line text-4xl mb-4"></i>
                                    <p>No performance data available</p>
                                </div>
                            @endif

                            <!-- Model Information -->
                            <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                                <h3 class="text-md font-medium text-gray-800 dark:text-white mb-3">Model Information</h3>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Status:</span>
                                        <span class="font-medium {{ $modelInfo['available'] ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $modelInfo['available'] ? 'Available' : 'Not Available' }}
                                        </span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Accuracy:</span>
                                        <span class="font-medium text-gray-800 dark:text-gray-200">
                                            {{ $modelInfo['modelAccuracy'] }}
                                        </span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Last Updated:</span>
                                        <span class="font-medium text-gray-800 dark:text-gray-200">
                                            {{ $modelInfo['lastTrained'] }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recommendations Panel -->
                    <div id="recommendationsPanel" class="card mt-6" style="display: none;">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">
                                <i class="fas fa-lightbulb mr-2"></i>Recommendations
                            </h3>
                            <div id="recommendationsList"></div>
                        </div>
                    </div>
                </div>

                <!-- Prediction Panel -->
                <div class="lg:col-span-2">
                    <div class="card">
                        <div class="p-6">
                            <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Predict My Result</h2>
                            <p class="text-gray-600 dark:text-gray-400 mb-6">
                                Your performance data has been automatically filled. You can adjust the values if needed.
                            </p>

                            <form action="{{ route('student.prediction.predict') }}" method="POST" id="predictionForm">
                                @csrf
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Assignments -->
                                    <div>
                                        <label for="assignments_done" class="form-label">Assignments Done</label>
                                        <input type="number" id="assignments_done" name="assignments_done"
                                               class="form-input" placeholder="0" min="0" required
                                               value="{{ old('assignments_done', $autoFillData['assignments_done'] ?? '') }}">
                                    </div>
                                    <div>
                                        <label for="assignments_total" class="form-label">Assignments Total</label>
                                        <input type="number" id="assignments_total" name="assignments_total"
                                               class="form-input" placeholder="10" min="1" required
                                               value="{{ old('assignments_total', $autoFillData['assignments_total'] ?? '') }}">
                                    </div>

                                    <!-- Attendance -->
                                    <div>
                                        <label for="attendance_present" class="form-label">Attendance Present</label>
                                        <input type="number" id="attendance_present" name="attendance_present"
                                               class="form-input" placeholder="0" min="0" required
                                               value="{{ old('attendance_present', $autoFillData['attendance_present'] ?? '') }}">
                                    </div>
                                    <div>
                                        <label for="attendance_total" class="form-label">Attendance Total</label>
                                        <input type="number" id="attendance_total" name="attendance_total"
                                               class="form-input" placeholder="100" min="1" required
                                               value="{{ old('attendance_total', $autoFillData['attendance_total'] ?? '') }}">
                                    </div>

                                    <!-- Mid-term Marks -->
                                    <div>
                                        <label for="midterm_marks" class="form-label">Mid-term Marks Obtained</label>
                                        <input type="number" id="midterm_marks" name="midterm_marks"
                                               class="form-input" placeholder="0" min="0" required
                                               value="{{ old('midterm_marks', $autoFillData['midterm_marks'] ?? '') }}">
                                    </div>
                                    <div>
                                        <label for="midterm_total" class="form-label">Mid-term Total Marks</label>
                                        <input type="number" id="midterm_total" name="midterm_total"
                                               class="form-input" placeholder="100" min="1" required
                                               value="{{ old('midterm_total', $autoFillData['midterm_total'] ?? '') }}">
                                    </div>

                                    <!-- Preboard Marks -->
                                    <div>
                                        <label for="preboard_marks" class="form-label">Preboard Marks Obtained</label>
                                        <input type="number" id="preboard_marks" name="preboard_marks"
                                               class="form-input" placeholder="0" min="0" required
                                               value="{{ old('preboard_marks', $autoFillData['preboard_marks'] ?? '') }}">
                                    </div>
                                    <div>
                                        <label for="preboard_total" class="form-label">Preboard Total Marks</label>
                                        <input type="number" id="preboard_total" name="preboard_total"
                                               class="form-input" placeholder="100" min="1" required
                                               value="{{ old('preboard_total', $autoFillData['preboard_total'] ?? '') }}">
                                    </div>
                                </div>

                                <div class="mt-6">
                                    <button type="submit" class="btn-primary w-full flex items-center justify-center" id="predictButton">
                                        <i class="fas fa-chart-line mr-2"></i> Predict My Result
                                    </button>
                                </div>
                            </form>

                            <!-- Prediction Result -->
                            @if(session('predicted'))
                                <div class="prediction-result {{ session('prediction_result') ? 'prediction-pass' : 'prediction-fail' }}">
                                    @if(session('prediction_result'))
                                        <div class="flex items-center justify-center">
                                            <span class="text-2xl mr-2">✅</span>
                                            <div>
                                                <div class="text-xl font-bold">Likely to Pass!</div>
                                                <div class="text-sm">{{ number_format(session('prediction_probability') * 100, 1) }}% probability</div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="flex items-center justify-center">
                                            <span class="text-2xl mr-2">⚠️</span>
                                            <div>
                                                <div class="text-xl font-bold">At Risk of Failing</div>
                                                <div class="text-sm">{{ number_format((1 - session('prediction_probability')) * 100, 1) }}% probability of failure</div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section("scripts")
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Form validation for prediction
            const predictionForm = document.getElementById('predictionForm');
            if (predictionForm) {
                predictionForm.addEventListener('submit', function(e) {
                    const inputs = predictionForm.querySelectorAll('input[type="number"]');
                    let isValid = true;

                    inputs.forEach(input => {
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

                // Real-time validation
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

            // Get recommendations
            const getRecommendationsBtn = document.getElementById('getRecommendations');
            if (getRecommendationsBtn) {
                getRecommendationsBtn.addEventListener('click', function() {
                    this.disabled = true;
                    this.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Loading...';

                    fetch('{{ route("student.prediction.recommendations") }}')
                        .then(response => response.json())
                        .then(data => {
                            displayRecommendations(data.recommendations);
                            document.getElementById('recommendationsPanel').style.display = 'block';
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Error loading recommendations');
                        })
                        .finally(() => {
                            this.disabled = false;
                            this.innerHTML = '<i class="fas fa-lightbulb mr-2"></i> Get Recommendations';
                        });
                });
            }

            function displayRecommendations(recommendations) {
                const container = document.getElementById('recommendationsList');
                container.innerHTML = '';

                recommendations.forEach(rec => {
                    const div = document.createElement('div');
                    div.className = `mb-4 p-3 rounded-md ${getPriorityClass(rec.priority)}`;
                    div.innerHTML = `
                        <div class="flex items-start">
                            <i class="fas ${getPriorityIcon(rec.priority)} mr-2 mt-1"></i>
                            <div>
                                <div class="font-medium">${rec.message}</div>
                                <div class="text-sm mt-1">${rec.action}</div>
                            </div>
                        </div>
                    `;
                    container.appendChild(div);
                });
            }

            function getPriorityClass(priority) {
                switch(priority) {
                    case 'critical': return 'bg-red-100 text-red-800 dark:bg-red-800/20 dark:text-red-400';
                    case 'high': return 'bg-orange-100 text-orange-800 dark:bg-orange-800/20 dark:text-orange-400';
                    case 'info': return 'bg-green-100 text-green-800 dark:bg-green-800/20 dark:text-green-400';
                    default: return 'bg-blue-100 text-blue-800 dark:bg-blue-800/20 dark:text-blue-400';
                }
            }

            function getPriorityIcon(priority) {
                switch(priority) {
                    case 'critical': return 'fa-exclamation-triangle';
                    case 'high': return 'fa-exclamation-circle';
                    case 'info': return 'fa-check-circle';
                    default: return 'fa-info-circle';
                }
            }
        });
    </script>
@endsection
