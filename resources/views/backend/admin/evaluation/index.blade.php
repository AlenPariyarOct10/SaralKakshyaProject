@extends('backend.layout.admin-dashboard-layout')

@section('username')
    {{$user->fname}} {{$user->lname}}
@endsection

@section("title")
    Evaluation
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

        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
            <!-- Search & Filter -->
            <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">

                <!-- Department Filter -->
                <div class="relative w-full max-w-md">
                    <select id="departmentFilter" class="w-full pl-4 pr-4 py-2 rounded-lg border border-gray-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition bg-white text-gray-700"><option value="">All Departments</option></select>
                </div>
                <!-- Program Filter -->
                <div class="relative w-full max-w-md">
                    <select id="programFilter" class="w-full pl-4 pr-4 py-2 rounded-lg border border-gray-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition bg-white text-gray-700">
                        <option value="">All Programs</option>
                    </select>
                </div>
                <!-- Semester Filter -->
                <div class="relative w-full max-w-md">
                    <select id="semesterFilter" class="w-full pl-4 pr-4 py-2 rounded-lg border border-gray-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition bg-white text-gray-700">
                        <option value="">All Semester</option>
                    </select>
                </div>

                <button id="downloadPdfBtn" class="btn-primary flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                    Download PDF
                </button>
            </div>


        </div>
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Student Evaluations</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Using Logistic Regression to predict student outcomes</p>
        </div>

        <x-show-success-failure-badge></x-show-success-failure-badge>

                    <!-- Resukt Table -->
                    <div class="lg:col-span-2 w-full">
                        <div class="card">
                            <div class="p-6">
                                <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Student Results</h2>

                                <div class="overflow-x-auto">
                                    <table class="min-w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg">
                                        <thead class="bg-gray-50 dark:bg-gray-700">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider border-b border-gray-200 dark:border-gray-600">Student</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider border-b border-gray-200 dark:border-gray-600">Midterm</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider border-b border-gray-200 dark:border-gray-600">Preboard</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider border-b border-gray-200 dark:border-gray-600">Assignment</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider border-b border-gray-200 dark:border-gray-600">Attendance</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider border-b border-gray-200 dark:border-gray-600">Total</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider border-b border-gray-200 dark:border-gray-600">Status</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider border-b border-gray-200 dark:border-gray-600">Rank</th>
                                            </tr>
                                        </thead>
                                        <tbody id="resultsTable" class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                            <!-- Results will be loaded dynamically -->
                                            <tr>
                                                <td colspan="7" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                                    Please select filters to view student results
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
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

            const departmentFilter = document.getElementById('departmentFilter');
            const programFilter = document.getElementById('programFilter');
            const semesterFilter = document.getElementById('semesterFilter');
            const resultsTable = document.getElementById('resultsTable');


            // Add this to your existing script
            document.getElementById('downloadPdfBtn').addEventListener('click', function() {
                const semester = semesterFilter.value;
                const programId = programFilter.value;
                const departmentId = departmentFilter.value;
                const instituteId = {{ session('institute_id'); }};

                if (!semester || !programId) {
                    alert('Please select program and semester to download results');
                    return;
                }

                // Show loading state on the button
                const btn = this;
                const originalHtml = btn.innerHTML;
                btn.innerHTML = '<svg class="animate-spin h-5 w-5 mr-2" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Generating PDF...';
                btn.disabled = true;

                const url = `/admin/evaluations/download-pdf?institute_id=${instituteId}&program_id=${programId}&semester=${semester}`;

                // Trigger download
                fetch(url)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Failed to generate PDF');
                        }
                        return response.blob();
                    })
                    .then(blob => {
                        const url = window.URL.createObjectURL(blob);
                        const a = document.createElement('a');
                        a.href = url;
                        a.download = `evaluation-results-sem-${semester}.pdf`;
                        document.body.appendChild(a);
                        a.click();
                        window.URL.revokeObjectURL(url);
                        document.body.removeChild(a);
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Failed to generate PDF: ' + error.message);
                    })
                    .finally(() => {
                        btn.innerHTML = originalHtml;
                        btn.disabled = false;
                    });
            });


            fetch("/admin/department/getAllDepartments")
                .then(response => {
                    if (!response.ok) {
                        throw new Error("Network response was not ok");
                    }
                    return response.json(); // parse JSON
                })
                .then(data => {
                    data.forEach(department => {
                        const option = document.createElement('option');
                        option.value = department.id;
                        option.textContent = department.name;
                        departmentFilter.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error("Fetch error:", error);
                });

            departmentFilter.addEventListener('change', loadProgramsByDepartment);
            programFilter.addEventListener('change', loadSemestersByProgram);
            semesterFilter.addEventListener('change', loadEvaluations);

            function loadProgramsByDepartment() {
                const departmentId = departmentFilter.value;
                fetch(`/admin/department/get_department_programs?department_id=${departmentId}`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(program => {
                            const option = document.createElement('option');
                            option.value = program.id;
                            option.textContent = program.name;
                            programFilter.appendChild(option);
                        });
                    })
                    .catch(error => {
                        console.error("Fetch error:", error);
                    });
            }

            function loadSemestersByProgram() {
                const programId = programFilter.value;
                semesterFilter.innerHTML = '<option value="">All Semester</option>';

                fetch(`/admin/programs/${programId}/semesters`)
                    .then(response => response.json())
                    .then(data => {
                        for(let i=1; i<=data; i++){
                            const option = document.createElement('option');
                            option.value = i;
                            option.textContent = "Semester " + i;
                            semesterFilter.appendChild(option);
                        }
                    })
                    .catch(error => {
                        console.error("Fetch error:", error);
                    });
            }

            function loadEvaluations() {
                const tableBody = resultsTable;
                const semester = semesterFilter.value;
                const programId = programFilter.value;
                const departmentId = departmentFilter.value;
                const instituteId = {{ session('institute_id'); }};

                if (!semester || !programId) {
                    tableBody.innerHTML = `
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                Please select program and semester to view results
                            </td>
                        </tr>
                    `;
                    return;
                }

                // Show loading state
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center">
                                <svg class="animate-spin h-5 w-5 mr-3 text-primary-600" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Loading results...
                            </div>
                        </td>
                    </tr>
                `;

                const url = `/admin/evaluations/results?institute_id=${instituteId}&program_id=${programId}&semester=${semester}`;

                fetch(url)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Failed to fetch evaluation results');
                        }
                        return response.json();
                    })
                    .then(data => {
                        tableBody.innerHTML = '';

                        if (!data.results || data.results.length === 0) {
                            tableBody.innerHTML = `
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                        No evaluation records found
                                    </td>
                                </tr>
                            `;
                            return;
                        }

                        // Process each student's data
                        data.results.forEach((student, index) => {
                            // Create table row
                            const row = document.createElement('tr');
                            row.className = `hover:bg-gray-50 dark:hover:bg-gray-700 ${index % 2 === 0 ? 'bg-white' : 'bg-gray-50'} dark:bg-gray-800`;

                            const statusClass = student.status === 'Pass' ?
                                'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' :
                                'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100';

                            // Format exam details
                            let preboard = '';
                            if (student.preboard !== null) {
                                preboard = `Preboard: ${student.preboard}, `;
                            }

                            let midterm = '';
                            if (student.midterm !== null) {
                                midterm = `Midterm: ${student.midterm}, `;
                            }


                            row.innerHTML = `
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">${student.student_name}</td>
                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-300">${student.exam_details.Midterm.obtained_marks} / ${student.exam_details.Midterm.full_marks}</td>
                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-300">${student.exam_details.Preboard.obtained_marks} / ${student.exam_details.Preboard.full_marks}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">${student.assignment.obtained_marks}/${student.assignment.full_marks} %</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">${student.attendance.obtained_marks}/${student.attendance.full_marks} %</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">${student.total.obtained_marks}/${student.total.full_marks}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ${statusClass}">
                                        ${student.status}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">${student.rank}</td>
                            `;

                            tableBody.appendChild(row);
                        });
                    })
                    .catch(error => {
                        console.error("Fetch error:", error);
                        tableBody.innerHTML = `
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-red-500 dark:text-red-400">
                                    <div class="flex items-center justify-center">
                                        <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                        </svg>
                                        Error loading evaluation data
                                    </div>
                                </td>
                            </tr>
                        `;
                    });
            }

            // Add event listeners
            departmentFilter.addEventListener('change', loadProgramsByDepartment);
            programFilter.addEventListener('change', loadSemestersByProgram);
            semesterFilter.addEventListener('change', loadEvaluations);

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
