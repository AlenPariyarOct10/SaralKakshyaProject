@extends("backend.layout.teacher-dashboard-layout")

@section('title', 'Create Evaluation')

@section('content')
    <!-- Main Content Area -->
    <main class="p-6 md:p-6 min-h-screen overflow-y-auto pb-16">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-white mb-1">
                    Create New Evaluation
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Evaluate student performance and provide feedback
                </p>
            </div>
            <a href="{{ route('teacher.evaluation.index') }}" class="mt-4 md:mt-0 btn-secondary flex items-center justify-center">
                <i class="fas fa-arrow-left mr-2"></i> Back to Evaluations
            </a>
        </div>

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 mb-3 rounded relative" role="alert">
                <strong class="font-bold">Whoops!</strong>
                <p class="text-sm mt-2">{{ session('error') }}</p>
            </div>
        @endif

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 mb-3 rounded relative" role="alert">
                <strong class="font-bold">Success!</strong>
                <p class="mt-2 text-sm">{{ session('success') }}</p>
            </div>
        @endif

        <!-- Create Evaluation Form -->
        <div class="card mb-8">
            <div class="p-6">
                <form action="{{ route('teacher.evaluation.store') }}" method="POST" id="createEvaluationForm">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Left Column -->
                        <div class="space-y-6">
                            <!-- Batch Selection -->
                            <div>
                                <label for="batch_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Batch <span class="text-red-500">*</span>
                                </label>
                                <select
                                    id="batch_id"
                                    name="batch_id"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    required
                                >
                                    <option value="">Select Batch</option>
                                    @foreach($batches as $batch)
                                        <option value="{{ $batch->id }}" {{ old('batch_id') == $batch->id ? 'selected' : '' }}>
                                            {{ $batch->batch }} ({{ $batch->program->name }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Subject Selection -->
                            <div>
                                <label for="subject_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Subject <span class="text-red-500">*</span>
                                </label>
                                <select
                                    id="subject_id"
                                    name="subject_id"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    required
                                >
                                    <option value="">Select Subject</option>
                                    <!-- Subjects will be populated based on batch selection -->
                                </select>
                            </div>

                            <!-- Evaluation Format Selection -->
                            <div>
                                <label for="evaluation_format_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Evaluation Format <span class="text-red-500">*</span>
                                </label>
                                <select
                                    id="evaluation_format_id"
                                    name="evaluation_format_id"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    required
                                >
                                    <option value="">Select Evaluation Format</option>
                                    <!-- Formats will be populated based on subject selection -->
                                </select>
                            </div>

                            <!-- Marks Source Selection -->
                            <div>
                                <label for="select_from" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Select From <span class="text-blue-500">(Optional)</span>
                                </label>
                                <select
                                    id="select_from"
                                    name="select_from"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                >
                                    <option value="">Select Marks Source</option>
                                    <option value="assignment">Assignment</option>
                                    <option value="attendance">Attendance</option>
                                </select>
                            </div>

                            <!-- Attendance Date Range -->
                            <div id="attendance-date-range" class="hidden">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Attendance Date Range <span class="text-blue-500">(Optional)</span>
                                </label>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="date_from" class="block text-xs text-gray-500 dark:text-gray-400 mb-1">
                                            Start Date
                                        </label>
                                        <input
                                            type="date"
                                            id="date_from"
                                            name="date_from"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm"
                                        />
                                    </div>
                                    <div>
                                        <label for="date_to" class="block text-xs text-gray-500 dark:text-gray-400 mb-1">
                                            End Date
                                        </label>
                                        <input
                                            type="date"
                                            id="date_to"
                                            name="date_to"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm"
                                        />
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    Leave empty to include all attendance records
                                </p>
                            </div>

                            <!-- Semester -->
                            <div class="hidden">
                                <label for="semester" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Semester <span class="text-red-500">*</span>
                                </label>
                                <select
                                    id="semester"
                                    name="semester"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    required
                                >
                                    <option value="">Select Semester</option>
                                    @for($i = 1; $i <= 8; $i++)
                                        <option value="{{ $i }}" {{ old('semester') == $i ? 'selected' : '' }}>
                                            Semester {{ $i }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="space-y-6">
                            <!-- Status -->
                            <div>
                                <label for="is_finalized" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Status <span class="text-red-500">*</span>
                                </label>
                                <select
                                    id="is_finalized"
                                    name="is_finalized"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    required
                                >
                                    <option value="0" {{ old('is_finalized', '0') == '0' ? 'selected' : '' }}>Draft</option>
                                    <option value="1" {{ old('is_finalized') == '1' ? 'selected' : '' }}>Finalized</option>
                                </select>
                            </div>

                            <!-- Comment -->
                            <div>
                                <label for="comment" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    General Comment
                                </label>
                                <textarea
                                    id="comment"
                                    name="comment"
                                    rows="4"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    placeholder="Enter general comment about the evaluation"
                                >{{ old('comment') }}</textarea>
                            </div>

                            <!-- Format Information -->
                            <div id="format-info" class="p-4 bg-gray-50 dark:bg-gray-700 rounded-md hidden">
                                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Format Information</h4>
                                <div class="space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600 dark:text-gray-400">Name:</span>
                                        <span class="text-sm font-medium text-gray-800 dark:text-white" id="format-name">-</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600 dark:text-gray-400">Full Marks:</span>
                                        <span class="text-sm font-medium text-gray-800 dark:text-white" id="format-marks">-</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600 dark:text-gray-400">Weight:</span>
                                        <span class="text-sm font-medium text-gray-800 dark:text-white" id="format-weight">-</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600 dark:text-gray-400">Description:</span>
                                        <span class="text-sm font-medium text-gray-800 dark:text-white" id="format-description">-</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Student List Section -->
                    <div class="mt-8">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Student Evaluations</h3>

                        <div id="student-list-container" class="hidden">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Student</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Roll Number</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Obtained Marks <span class="text-red-500">*</span></th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Comment</th>
                                    </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700" id="student-list">
                                    <!-- Students will be populated dynamically -->
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500 dark:text-gray-400">
                                            Please select a batch, subject, and evaluation format to load students
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div id="no-students-message" class="p-4 bg-yellow-50 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200 rounded-md hidden">
                            <p class="flex items-center">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                No students found for the selected batch. Please select a different batch or ensure students are assigned to this batch.
                            </p>
                        </div>
                    </div>

                    <div class="mt-8 mb-4 flex justify-end space-x-3">
                        <a href="{{ route('teacher.evaluation.index') }}" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                            Cancel
                        </a>

                        <button type="submit" name="finalize" value="1" class="btn-primary">
                            Save Evaluation
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // DOM Elements
            const batchSelect = document.getElementById('batch_id');
            const subjectSelect = document.getElementById('subject_id');
            const formatSelect = document.getElementById('evaluation_format_id');
            const selectFrom = document.getElementById('select_from');
            const semesterSelect = document.getElementById('semester');
            const studentListContainer = document.getElementById('student-list-container');
            const studentList = document.getElementById('student-list');
            const noStudentsMessage = document.getElementById('no-students-message');
            const formatInfo = document.getElementById('format-info');
            const formatName = document.getElementById('format-name');
            const formatMarks = document.getElementById('format-marks');
            const formatWeight = document.getElementById('format-weight');
            const formatDescription = document.getElementById('format-description');
            const createEvaluationForm = document.getElementById('createEvaluationForm');
            const attendanceDateRange = document.getElementById('attendance-date-range');
            const dateFrom = document.getElementById('date_from');
            const dateTo = document.getElementById('date_to');
            let GlobalfullMarks = 0;

            // Event Listeners
            batchSelect.addEventListener('change', loadSubjects);
            subjectSelect.addEventListener('change', loadEvaluationFormats);
            formatSelect.addEventListener('change', loadFormatDetails);
            selectFrom.addEventListener('change', handleMarksSourceChange);

            // Add event listeners for date range changes
            dateFrom.addEventListener('change', handleDateRangeChange);
            dateTo.addEventListener('change', handleDateRangeChange);

            // When batch, subject, and format are all selected, load students
            [batchSelect, subjectSelect, formatSelect].forEach(select => {
                select.addEventListener('change', checkAndLoadStudents);
            });

            // Form submission
            if (createEvaluationForm) {
                createEvaluationForm.addEventListener('submit', function(e) {
                    // Validate that all students have marks entered
                    const markInputs = document.querySelectorAll('input[name^="marks["]');
                    let valid = true;

                    markInputs.forEach(input => {
                        const value = parseFloat(input.value);
                        if (!input.value || isNaN(value) || value < 0) {
                            valid = false;
                            input.classList.add('border-red-500');
                        } else {
                            input.classList.remove('border-red-500');
                        }
                    });

                    if (!valid) {
                        e.preventDefault();
                        alert('Please enter valid marks for all students');
                        return false;
                    }

                    // Validate date range if attendance is selected
                    if (selectFrom.value === 'attendance' && dateFrom.value && dateTo.value) {
                        if (new Date(dateFrom.value) > new Date(dateTo.value)) {
                            e.preventDefault();
                            alert('Start date cannot be later than end date');
                            return false;
                        }
                    }

                    // Set is_finalized based on which button was clicked
                    const isFinalized = document.getElementById('is_finalized');
                    if (e.submitter.name === 'finalize') {
                        isFinalized.value = '1';
                    } else if (e.submitter.name === 'save_draft') {
                        isFinalized.value = '0';
                    }
                });
            }

            // Functions
            async function loadSubjects() {
                const batchId = batchSelect.value;

                // Clear subject and format dropdowns
                subjectSelect.innerHTML = '<option value="">Select Subject</option>';
                formatSelect.innerHTML = '<option value="">Select Evaluation Format</option>';

                // Hide student list and format info
                studentListContainer.classList.add('hidden');
                formatInfo.classList.add('hidden');

                if (!batchId) return;

                try {
                    // Show loading state
                    subjectSelect.innerHTML = '<option value="">Loading subjects...</option>';

                    const response = await fetch(`/teacher/batch/${batchId}/subjects`);
                    if (!response.ok) throw new Error('Failed to fetch subjects');

                    const subjects = await response.json();

                    // Reset subject dropdown
                    subjectSelect.innerHTML = '<option value="">Select Subject</option>';

                    // Populate subjects
                    subjects.forEach(subject => {
                        const option = document.createElement('option');
                        option.value = subject.id;
                        option.textContent = `${subject.name} (${subject.code})`;
                        subjectSelect.appendChild(option);
                    });

                    // Set semester from batch if available
                    if (subjects.length > 0 && subjects[0].semester) {
                        semesterSelect.value = subjects[0].semester;
                    }

                } catch (error) {
                    console.error('Error loading subjects:', error);
                    subjectSelect.innerHTML = '<option value="">Error loading subjects</option>';
                }
            }

            async function loadEvaluationFormats() {
                const subjectId = subjectSelect.value;

                // Clear format dropdown
                formatSelect.innerHTML = '<option value="">Select Evaluation Format</option>';

                // Hide student list and format info
                studentListContainer.classList.add('hidden');
                formatInfo.classList.add('hidden');

                if (!subjectId) return;

                try {
                    // Show loading state
                    formatSelect.innerHTML = '<option value="">Loading formats...</option>';

                    const response = await fetch(`/teacher/subject/${subjectId}/evaluation-formats`);
                    if (!response.ok) throw new Error('Failed to fetch evaluation formats');

                    const formats = await response.json();

                    // Reset format dropdown
                    formatSelect.innerHTML = '<option value="">Select Evaluation Format</option>';

                    // Populate formats
                    formats.forEach(format => {
                        const option = document.createElement('option');
                        option.value = format.id;
                        option.textContent = format.criteria;
                        option.setAttribute('data-full-marks', format.full_marks);
                        option.setAttribute('data-weight', format.marks_weight);
                        option.setAttribute('data-description', format.description || 'No description available');
                        formatSelect.appendChild(option);
                    });

                } catch (error) {
                    console.error('Error loading evaluation formats:', error);
                    formatSelect.innerHTML = '<option value="">Error loading formats</option>';
                }
            }

            function loadFormatDetails() {
                const selectedOption = formatSelect.options[formatSelect.selectedIndex];

                if (!selectedOption || !selectedOption.value) {
                    formatInfo.classList.add('hidden');
                    return;
                }

                // Display format details
                formatName.textContent = selectedOption.textContent;
                formatMarks.textContent = selectedOption.getAttribute('data-full-marks');
                formatWeight.textContent = selectedOption.getAttribute('data-weight') + ' Marks';
                formatDescription.textContent = selectedOption.getAttribute('data-description');

                GlobalfullMarks = parseFloat(selectedOption.getAttribute('data-full-marks')) || 0;
                formatInfo.classList.remove('hidden');
            }

            function handleMarksSourceChange() {
                if (selectFrom.value === 'attendance') {
                    attendanceDateRange.classList.remove('hidden');
                } else {
                    attendanceDateRange.classList.add('hidden');
                    // Clear date values when not using attendance
                    dateFrom.value = '';
                    dateTo.value = '';
                }

                // Reload students with marks if all required fields are selected
                checkAndLoadStudents();
            }

            function handleDateRangeChange() {
                // Only reload if attendance is selected and we have the required fields
                if (selectFrom.value === 'attendance') {
                    checkAndLoadStudents();
                }
            }

            function checkAndLoadStudents() {
                const batchId = batchSelect.value;
                const subjectId = subjectSelect.value;
                const formatId = formatSelect.value;

                if (batchId && subjectId && formatId) {
                    if (selectFrom.value) {
                        loadStudentsWithMarks(batchId, subjectId, formatId, selectFrom.value);
                    } else {
                        loadStudents(batchId, subjectId, formatId);
                    }
                }
            }

            async function loadStudents(batchId, subjectId, formatId) {
                try {
                    // Show loading state
                    studentList.innerHTML = '<tr><td colspan="4" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500 dark:text-gray-400">Loading students...</td></tr>';
                    studentListContainer.classList.remove('hidden');
                    noStudentsMessage.classList.add('hidden');

                    const response = await fetch(`/teacher/batch/${batchId}/students?subject_id=${subjectId}`);
                    if (!response.ok) throw new Error('Failed to fetch students');

                    const students = await response.json();

                    if (students.length === 0) {
                        studentListContainer.classList.add('hidden');
                        noStudentsMessage.classList.remove('hidden');
                        return;
                    }

                    // Get format details for max marks validation
                    const selectedOption = formatSelect.options[formatSelect.selectedIndex];
                    const fullMarks = parseFloat(selectedOption.getAttribute('data-full-marks')) || 0;

                    // Populate student list
                    studentList.innerHTML = '';
                    students.forEach(student => {
                        const row = createStudentRow(student, fullMarks, '');
                        studentList.appendChild(row);
                    });

                    // Add event listeners for mark validation
                    addMarkValidationListeners();

                } catch (error) {
                    console.error('Error loading students:', error);
                    studentList.innerHTML = '<tr><td colspan="4" class="px-6 py-4 whitespace-nowrap text-center text-sm text-red-600 dark:text-red-400">Error loading students</td></tr>';
                }
            }

            async function loadStudentsWithMarks(batchId, subjectId, formatId, source) {
                try {
                    // Show loading state
                    studentList.innerHTML = '<tr><td colspan="4" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500 dark:text-gray-400">Loading students with marks...</td></tr>';
                    studentListContainer.classList.remove('hidden');
                    noStudentsMessage.classList.add('hidden');

                    // Build URL with parameters
                    let url = `/teacher/batch/${batchId}/students?subject_id=${subjectId}&source=${source}`;

                    // Add date parameters if attendance is selected
                    if (source === 'attendance') {
                        if (dateFrom.value) {
                            url += `&date_from=${dateFrom.value}`;
                        }
                        if (dateTo.value) {
                            url += `&date_to=${dateTo.value}`;
                        }
                    }

                    const response = await fetch(url);
                    if (!response.ok) throw new Error('Failed to fetch students with marks');

                    const students = await response.json();

                    if (students.length === 0) {
                        studentListContainer.classList.add('hidden');
                        noStudentsMessage.classList.remove('hidden');
                        return;
                    }

                    // Get format details for max marks validation
                    const selectedOption = formatSelect.options[formatSelect.selectedIndex];
                    const fullMarks = parseFloat(selectedOption.getAttribute('data-full-marks')) || 0;

                    // Populate student list with calculated marks
                    studentList.innerHTML = '';
                    students.forEach(student => {
                        // Calculate marks based on submission rate or attendance
                        let calculatedMarks = '';
                        let commentText = '';

                        if (student.submission_rate !== undefined && student.submission_rate !== null) {
                            // For assignments - calculate based on submission rate
                            const rate = parseFloat(student.submission_rate) || 0;
                            calculatedMarks = Math.max(1, Math.round(fullMarks * (rate / 100)));
                            commentText = `Submitted ${student.submitted_assignments || 0} out of ${student.total_assignments || 0} assignments`;
                        } else if (student.attendance_rate !== undefined && student.attendance_rate !== null) {
                            // For attendance - calculate based on attendance percentage
                            const percentage = parseFloat(student.attendance_rate) || 0;
                            calculatedMarks = Math.max(1, Math.round(fullMarks * (percentage / 100)));

                            // Create attendance comment with date range info
                            let dateRangeText = '';
                            if (dateFrom.value && dateTo.value) {
                                dateRangeText = ` (${dateFrom.value} to ${dateTo.value})`;
                            } else if (dateFrom.value) {
                                dateRangeText = ` (from ${dateFrom.value})`;
                            } else if (dateTo.value) {
                                dateRangeText = ` (until ${dateTo.value})`;
                            }

                            commentText = `Attendance: ${percentage.toFixed(1)}%${dateRangeText}`;
                        }

                        // Ensure calculated marks don't exceed full marks
                        if (calculatedMarks > fullMarks) {
                            calculatedMarks = fullMarks;
                        }

                        const row = createStudentRow(student, fullMarks, calculatedMarks, commentText);
                        studentList.appendChild(row);
                    });

                    // Add event listeners for mark validation
                    addMarkValidationListeners();

                } catch (error) {
                    console.error('Error loading students with marks:', error);
                    studentList.innerHTML = '<tr><td colspan="4" class="px-6 py-4 whitespace-nowrap text-center text-sm text-red-600 dark:text-red-400">Error loading students with marks</td></tr>';
                }
            }

            function createStudentRow(student, fullMarks, prefilledMarks = '', prefilledComment = '') {
                const row = document.createElement('tr');

                // Ensure we have valid values
                const studentId = student.id || '';
                const firstName = student.fname || '';
                const lastName = student.lname || '';
                const rollNumber = student.roll_number || 'N/A';
                const profilePicture = student.profile_picture || '/images/default-avatar.png';
                const marksValue = prefilledMarks !== '' ? prefilledMarks : '';
                const commentValue = prefilledComment || '';

                row.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <img class="h-10 w-10 rounded-full object-cover"
                                     src="/storage/${profilePicture}"
                                     alt="${firstName} ${lastName}"
                                     onerror="this.src='/images/default-avatar.png'">
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-800 dark:text-white">${firstName} ${lastName}</div>
                                <input type="hidden" name="students[]" value="${studentId}">
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-800 dark:text-white">${rollNumber}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <input
                            type="number"
                            name="marks[${studentId}]"
                            class="w-24 px-2 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            min="0"
                            max="${fullMarks}"
                            step="0.01"
                            value="${marksValue}"
                            required
                        >
                        <span class="text-xs text-gray-500 dark:text-gray-400 ml-2">/ ${fullMarks}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <input
                            type="text"
                            name="comments[${studentId}]"
                            class="w-full px-2 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            placeholder="Optional comment"
                            value="${commentValue}"
                        >
                    </td>
                `;

                return row;
            }

            function addMarkValidationListeners() {
                document.querySelectorAll('input[name^="marks["]').forEach(input => {
                    input.addEventListener('input', function() {
                        const value = parseFloat(this.value);
                        const max = parseFloat(this.getAttribute('max'));

                        if (this.value === '' || isNaN(value) || value < 0) {
                            this.classList.add('border-red-500');
                        } else if (value > max) {
                            this.value = max;
                            this.classList.add('border-red-500');
                            setTimeout(() => {
                                this.classList.remove('border-red-500');
                            }, 1000);
                        } else {
                            this.classList.remove('border-red-500');
                        }
                    });

                    // Also validate on blur to ensure proper formatting
                    input.addEventListener('blur', function() {
                        const value = parseFloat(this.value);
                        if (!isNaN(value) && value >= 0) {
                            // Format to 2 decimal places if needed
                            this.value = Math.round(value * 100) / 100;
                        }
                    });
                });
            }
        });
    </script>
@endsection
