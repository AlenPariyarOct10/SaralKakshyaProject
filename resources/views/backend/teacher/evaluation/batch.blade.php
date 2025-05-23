@extends("backend.layout.teacher-dashboard-layout")

@section('title', 'Batch Evaluation')

@section('content')
    <!-- Main Content Area -->
    <main class="p-6 md:p-6 min-h-screen overflow-y-auto pb-16">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-white mb-1">
                    Batch Evaluation
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Evaluate multiple students at once for a specific batch
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

        <!-- Batch Selection Form -->
        <div class="card mb-6">
            <div class="p-6">
                <form id="batchSelectionForm">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
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
                                    <option value="{{ $batch->id }}">
                                        {{ $batch->name }} ({{ $batch->program->name }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="subject_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Subject <span class="text-red-500">*</span>
                            </label>
                            <select
                                id="subject_id"
                                name="subject_id"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                required
                                disabled
                            >
                                <option value="">Select Subject</option>
                            </select>
                        </div>

                        <div>
                            <label for="evaluation_format_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Evaluation Format <span class="text-red-500">*</span>
                            </label>
                            <select
                                id="evaluation_format_id"
                                name="evaluation_format_id"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                required
                                disabled
                            >
                                <option value="">Select Format</option>
                            </select>
                        </div>

                        <div class="flex items-end">
                            <button type="submit" class="btn-primary w-full">
                                Load Students
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Batch Evaluation Form -->
        <div id="batchEvaluationContainer" class="hidden">
            <div class="card mb-8">
                <div class="p-6">
                    <form action="{{ route('teacher.evaluation.batch.store') }}" method="POST" id="batchEvaluationForm">
                        @csrf
                        <input type="hidden" name="batch_id" id="form_batch_id">
                        <input type="hidden" name="subject_id" id="form_subject_id">
                        <input type="hidden" name="evaluation_format_id" id="form_evaluation_format_id">
                        <input type="hidden" name="semester" id="form_semester">

                        <div class="mb-6">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Student Evaluations</h3>
                                <div class="flex items-center space-x-4">
                                    <div class="flex items-center">
                                        <label for="is_finalized" class="mr-2 text-sm font-medium text-gray-700 dark:text-gray-300">Status:</label>
                                        <select
                                            id="is_finalized"
                                            name="is_finalized"
                                            class="px-3 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                        >
                                            <option value="0">Draft</option>
                                            <option value="1">Finalized</option>
                                        </select>
                                    </div>

                                    <div>
                                        <button type="button" id="applyToAllBtn" class="px-3 py-1 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600">
                                            Apply to All
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-md" id="applyToAllContainer" style="display: none;">
                                <div class="flex items-center space-x-4">
                                    <div>
                                        <label for="apply_marks" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            Marks
                                        </label>
                                        <input
                                            type="number"
                                            id="apply_marks"
                                            class="w-24 px-2 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                            min="0"
                                            step="0.01"
                                        >
                                    </div>

                                    <div class="flex-grow">
                                        <label for="apply_comment" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            Comment
                                        </label>
                                        <input
                                            type="text"
                                            id="apply_comment"
                                            class="w-full px-2 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                            placeholder="Comment for all students"
                                        >
                                    </div>

                                    <div class="flex items-end">
                                        <button type="button" id="confirmApplyBtn" class="px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700">
                                            Apply
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

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
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6 flex justify-end space-x-3">
                            <button type="button" id="cancelBatchEvaluation" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                Cancel
                            </button>
                            <button type="submit" class="btn-primary">
                                Save Evaluations
                            </button>
                        </div>
                    </form>
                </div>
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
            const batchSelectionForm = document.getElementById('batchSelectionForm');
            const batchEvaluationContainer = document.getElementById('batchEvaluationContainer');
            const batchEvaluationForm = document.getElementById('batchEvaluationForm');
            const studentList = document.getElementById('student-list');
            const formBatchId = document.getElementById('form_batch_id');
            const formSubjectId = document.getElementById('form_subject_id');
            const formEvaluationFormatId = document.getElementById('form_evaluation_format_id');
            const formSemester = document.getElementById('form_semester');
            const cancelBatchEvaluation = document.getElementById('cancelBatchEvaluation');
            const applyToAllBtn = document.getElementById('applyToAllBtn');
            const applyToAllContainer = document.getElementById('applyToAllContainer');
            const applyMarks = document.getElementById('apply_marks');
            const applyComment = document.getElementById('apply_comment');
            const confirmApplyBtn = document.getElementById('confirmApplyBtn');

            // Event Listeners
            batchSelect.addEventListener('change', loadSubjects);
            subjectSelect.addEventListener('change', loadEvaluationFormats);

            if (batchSelectionForm) {
                batchSelectionForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    loadStudents();
                });
            }

            if (cancelBatchEvaluation) {
                cancelBatchEvaluation.addEventListener('click', function() {
                    batchEvaluationContainer.classList.add('hidden');
                });
            }

            if (applyToAllBtn) {
                applyToAllBtn.addEventListener('click', function() {
                    applyToAllContainer.style.display = applyToAllContainer.style.display === 'none' ? 'block' : 'none';
                });
            }

            if (confirmApplyBtn) {
                confirmApplyBtn.addEventListener('click', applyToAll);
            }

            // Functions
            async function loadSubjects() {
                const batchId = batchSelect.value;

                // Reset subject and format dropdowns
                subjectSelect.innerHTML = '<option value="">Select Subject</option>';
                formatSelect.innerHTML = '<option value="">Select Evaluation Format</option>';

                // Disable dropdowns
                subjectSelect.disabled = !batchId;
                formatSelect.disabled = true;

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
                        option.setAttribute('data-semester', subject.semester);
                        subjectSelect.appendChild(option);
                    });

                    // Enable subject dropdown
                    subjectSelect.disabled = false;

                } catch (error) {
                    console.error('Error loading subjects:', error);
                    subjectSelect.innerHTML = '<option value="">Error loading subjects</option>';
                }
            }

            async function loadEvaluationFormats() {
                const subjectId = subjectSelect.value;

                // Reset format dropdown
                formatSelect.innerHTML = '<option value="">Select Evaluation Format</option>';

                // Disable format dropdown
                formatSelect.disabled = !subjectId;

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
                        option.textContent = format.name;
                        option.setAttribute('data-full-marks', format.full_marks);
                        formatSelect.appendChild(option);
                    });

                    // Enable format dropdown
                    formatSelect.disabled = false;

                } catch (error) {
                    console.error('Error loading evaluation formats:', error);
                    formatSelect.innerHTML = '<option value="">Error loading formats</option>';
                }
            }

            async function loadStudents() {
                const batchId = batchSelect.value;
                const subjectId = subjectSelect.value;
                const formatId = formatSelect.value;

                if (!batchId || !subjectId || !formatId) {
                    alert('Please select batch, subject, and evaluation format');
                    return;
                }

                try {
                    // Show loading state
                    studentList.innerHTML = '<tr><td colspan="4" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500 dark:text-gray-400">Loading students...</td></tr>';
                    batchEvaluationContainer.classList.remove('hidden');

                    const response = await fetch(`/teacher/batch/${batchId}/students?subject_id=${subjectId}`);
                    if (!response.ok) throw new Error('Failed to fetch students');

                    const students = await response.json();

                    if (students.length === 0) {
                        studentList.innerHTML = '<tr><td colspan="4" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500 dark:text-gray-400">No students found for this batch</td></tr>';
                        return;
                    }

                    // Get format details for max marks validation
                    const selectedOption = formatSelect.options[formatSelect.selectedIndex];
                    const fullMarks = selectedOption.getAttribute('data-full-marks');

                    // Set form hidden fields
                    formBatchId.value = batchId;
                    formSubjectId.value = subjectId;
                    formEvaluationFormatId.value = formatId;

                    // Get semester from subject
                    const selectedSubjectOption = subjectSelect.options[subjectSelect.selectedIndex];
                    const semester = selectedSubjectOption.getAttribute('data-semester');
                    formSemester.value = semester;

                    // Populate student list
                    studentList.innerHTML = '';
                    students.forEach(student => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <img class="h-10 w-10 rounded-full object-cover" src="${student.profile_picture || '/images/default-avatar.png'}" alt="${student.full_name}">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-800 dark:text-white">${student.full_name}</div>
                                        <input type="hidden" name="students[]" value="${student.id}">
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-800 dark:text-white">${student.roll_number}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input
                                    type="number"
                                    name="marks[${student.id}]"
                                    class="marks-input w-24 px-2 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    min="0"
                                    max="${fullMarks}"
                                    step="0.01"
                                    required
                                >
                                <span class="text-xs text-gray-500 dark:text-gray-400 ml-2">/ ${fullMarks}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input
                                    type="text"
                                    name="comments[${student.id}]"
                                    class="comment-input w-full px-2 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    placeholder="Optional comment"
                                >
                            </td>
                        `;
                        studentList.appendChild(row);
                    });

                    // Add event listeners for mark validation
                    document.querySelectorAll('.marks-input').forEach(input => {
                        input.addEventListener('input', function() {
                            const value = parseFloat(this.value);
                            const max = parseFloat(this.getAttribute('max'));

                            if (isNaN(value) || value < 0) {
                                this.value = '';
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
                    });

                } catch (error) {
                    console.error('Error loading students:', error);
                    studentList.innerHTML = '<tr><td colspan="4" class="px-6 py-4 whitespace-nowrap text-center text-sm text-red-600 dark:text-red-400">Error loading students</td></tr>';
                }
            }

            function applyToAll() {
                const marks = applyMarks.value;
                const comment = applyComment.value;
                const fullMarks = formatSelect.options[formatSelect.selectedIndex].getAttribute('data-full-marks');

                if (marks && (isNaN(parseFloat(marks)) || parseFloat(marks) < 0 || parseFloat(marks) > parseFloat(fullMarks))) {
                    alert(`Please enter a valid mark between 0 and ${fullMarks}`);
                    return;
                }

                // Apply to all students
                document.querySelectorAll('.marks-input').forEach(input => {
                    if (marks) {
                        input.value = marks;
                    }
                });

                document.querySelectorAll('.comment-input').forEach(input => {
                    if (comment) {
                        input.value = comment;
                    }
                });

                // Hide the apply to all container
                applyToAllContainer.style.display = 'none';
            }
        });
    </script>
@endsection
