@php use Illuminate\Support\Facades\Auth; @endphp
@extends("backend.layout.admin-dashboard-layout")

@php
    $user = Auth::user();
@endphp

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

            .btn-danger {
                @apply px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors duration-200 font-medium text-sm;
            }

            .card {
                @apply bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden;
            }

            .sidebar-item {
                @apply flex items-center gap-3 px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md transition-colors duration-200;
            }

            .sidebar-item.active {
                @apply bg-primary-50 dark:bg-gray-700 text-primary-600 dark:text-primary-400 font-medium;
            }

            .form-input {
                @apply w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:text-white text-sm;
            }

            .form-label {
                @apply block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1;
            }

            .table-header {
                @apply px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider;
            }

            .table-cell {
                @apply px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200;
            }

            .badge {
                @apply px-2.5 py-1 text-xs font-medium rounded-full;
            }

            .dropdown-item {
                @apply block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-150;
            }
        }
    </style>
@endpush

@section('content')
    <!-- Main Content Area -->
    <main class="scrollable-content p-4 md:p-6">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Add New Subject</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Create a new subject for your academic
                    program</p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{ route('admin.subjects.index') }}" class="btn-secondary flex items-center justify-center">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Subjects
                </a>
            </div>
        </div>


        <!-- error message -->
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 mb-3 rounded relative error-message hidden"
             role="alert">
            <strong class="font-bold">Whoops!</strong>
            <ul class="mt-2 list-disc list-inside text-sm" id="error-list">
            </ul>
        </div>
        <div class="card">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 mb-3 rounded relative" role="alert">
                    <strong class="font-bold">Success !</strong>
                    <span class="block sm:inline">{{session('success')}}</span>
                </div>
            @endif
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 mb-3 rounded relative" role="alert">
                    <strong class="font-bold">Whoops!</strong>
                    <ul class="mt-2 list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="p-6">
                <form action="{{ route('admin.subjects.store') }}" method="POST">
                    @csrf
                    <!-- Basic Information -->
                    <div class="mb-6">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Basic Information</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="subjectName" class="form-label">Subject Name <span class="text-red-500">*</span></label>
                                <input type="text" id="subjectName" name="name" class="form-input" placeholder="Enter subject name" required>
                                @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="subjectCode" class="form-label">Subject Code <span class="text-red-500">*</span></label>
                                <input type="text" id="subjectCode" name="code" class="form-input" placeholder="e.g. CS101" required>
                                @error('code')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <!--Subjects Marks-->
                            <div>
                                <label for="subjectName" class="form-label">External Full Marks <span class="text-red-500">*</span></label>
                                <input type="text" id="max_external_marks" name="max_external_marks" class="form-input" placeholder="Enter full marks (External)" required>
                                @error('max_external_marks')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="subjectName" class="form-label">Internal Full Marks <span class="text-red-500">*</span></label>
                                <input type="text" id="max_internal_marks" name="max_internal_marks" class="form-input" placeholder="Enter full marks (Internal)" required>
                                @error('max_internal_marks')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <!--Credit Hour-->
                            <div>
                                <label for="credit" class="form-label">Credit Hours <span class="text-red-500">*</span></label>
                                <input type="number" id="credit" name="credit" class="form-input" min="1" max="6" placeholder="e.g. 3" required>
                                @error('credit')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="status" class="form-label">Status</label>
                                <select id="status" name="status" class="form-input">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                                @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Program and Batch Information -->
                    <div class="mb-6">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Program Information</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Program Dropdown -->
                            <div>
                                <label for="program" class="form-label">Program <span class="text-red-500">*</span></label>
                                <select id="program" name="program_id" class="form-input" required>
                                    <option value="">Select Program</option>
                                @foreach($programs as $program)
                                        <option value="{{$program->id}}">{{$program->name}}</option>
                                @endforeach
                                </select>

                            </div>

                            <!-- Semester Dropdown -->
                            <div>
                                <label for="semester" class="form-label">Semester <span class="text-red-500">*</span></label>
                                <select id="semester" name="semester" class="form-input" required>
                                    <option value="">Select Semester</option>
                                </select>
                                @error('selectedSemester')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>


                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-6">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Description</h2>
                        <div>
                            <label for="description" class="form-label">Subject Description</label>
                            <textarea id="description" name="description" rows="4" class="form-input" placeholder="Enter subject description"></textarea>
                            @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <!-- Evaluation Formats -->
                    <div class="mb-6">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-white">Evaluation Formats  [<span id="total-weight" class="text-sm font-medium text-gray-900 dark:text-white mb-3">Evaluation Formats</span>]</h2>
                        <div class="mb-3">
                            <label for="reuse_format" class="form-label">Use existing format </label>
                            <select id="reuse_format" class="form-input">
                                <option value="">Select Subject to duplicate format</option>

                            </select>
                            @error('selectedProgram')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div id="evaluation-formats" class="space-y-4">
                            <div class="evaluation-format grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div>
                                    <label for="criteria_1" class="form-label">Criteria <span class="text-red-500">*</span></label>
                                    <input type="text" id="criteria_1" name="criteria[]" class="form-input" placeholder="e.g. Midterm Exam" required>
                                </div>
                                <div>
                                    <label for="full_marks_1" class="form-label">Full Marks <span class="text-red-500">*</span></label>
                                    <input type="number" id="full_marks_1" name="full_marks[]" class="form-input" min="1" placeholder="e.g. 100" required>
                                </div>
                                <div>
                                    <label for="pass_marks_1" class="form-label">Pass Marks <span class="text-red-500">*</span></label>
                                    <input type="number" id="pass_marks_1" name="pass_marks[]" class="form-input" min="1" placeholder="e.g. 40" required>
                                </div>
                                <div>
                                    <label for="marks_weight_1" class="form-label">Marks Weight <span class="text-red-500">*</span></label>
                                    <input type="number" id="marks_weight_1" name="marks_weight[]" class="form-input marks-weight" min="1" placeholder="e.g. 40" required>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3 grid grid-cols-1 md:grid-cols-4 gap-4">
                            <button type="button" id="add-evaluation-format" class="btn-secondary flex items-center">
                                <i class="fas fa-plus mr-2"></i> Add Another Evaluation Format
                            </button>
                        </div>
                    </div>
                    <!-- Form Actions -->
                    <div class="flex justify-end space-x-3 mt-8">
                        <a href="{{ route('admin.subjects.index') }}" class="btn-secondary">Cancel</a>
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-save mr-2"></i> Save Subject
                        </button>
                    </div>
                </form>
            </div>


        </div>

    </main>
@endsection

@section("scripts")
    <script>

        $(document).ready(function () {

            // Add more evaluation format fields
            let evaluationCount = 1;
            let max_internal_marks = 0;
            let total = 0;

            $('#add-evaluation-format').on('click', function() {
                addNewEvaluationFormat();
            });

            function calculateTotalWeight() {
                let total = 0;
                $(".marks-weight").each(function() {
                    const val = parseFloat($(this).val()) || 0;
                    total += val;
                });
                $("#total-weight").text(total);

                // Check against max internal marks
                const maxInternal = parseFloat($('#max_internal_marks').val()) || 0;
                if (total > maxInternal) {
                    $("#total-weight").addClass('text-red-500');
                } else {
                    $("#total-weight").removeClass('text-red-500');
                }
            }

// Recalculate when weights or max internal marks change
            $(document).on('input', '.marks-weight, #max_internal_marks', calculateTotalWeight);

            $(document).ready(function () {
                calculateTotalWeight();

                $(document).on('input', '.marks-weight', function () {
                    calculateTotalWeight();
                });
            });

            $(document).ready(function () {
                calculateTotalWeight();

                $(document).on('input', '#max_internal_marks', function () {
                    if (max_internal_marks < total) {
                        alert("hello");
                    }
                });
            });

            // Replace your existing form submit handler with this:

            $('form').on('submit', function(e) {
                e.preventDefault();

                // Show loading state
                const submitBtn = $(this).find('button[type="submit"]');
                submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i> Saving...');

                // Hide any previous errors
                $('.error-message').addClass('hidden');

                // Collect form data
                const formData = new FormData(this);

                // Send AJAX request
                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            // Show success message
                            Toast.fire({
                                icon: 'success',
                                title: response.message
                            });

                            // Redirect after a short delay
                            setTimeout(() => {
                                window.location.href = response.redirect;
                            }, 1500);
                        }
                    },
                    error: function(xhr) {
                        submitBtn.prop('disabled', false).html('<i class="fas fa-save mr-2"></i> Save Subject');

                        if (xhr.status === 422) {
                            // Validation errors
                            const errors = xhr.responseJSON.errors;
                            const errorList = $('#error-list').html('');

                            // Show error container
                            $('.error-message').removeClass('hidden');

                            // Add each error to the list
                            $.each(errors, function(key, value) {
                                errorList.append(`<li>${value}</li>`);
                            });

                            // Scroll to errors
                            $('html, body').animate({
                                scrollTop: $('.error-message').offset().top - 100
                            }, 500);
                        } else {
                            // Other errors
                            Toast.fire({
                                icon: 'error',
                                title: xhr.responseJSON?.message || 'An error occurred while saving the subject'
                            });
                        }
                    }
                });
            });



            document.getElementById('program').addEventListener('change', function() {
                const programId = this.value;
                const semesterSelect = document.getElementById('semester');
                const subjectSelect = document.getElementById('reuse_format');

                // Clear existing options
                semesterSelect.innerHTML = '<option value="">Select Semester</option>';
                subjectSelect.innerHTML = '<option value="">Select one</option>';

                if (!programId) return;

                // Show loading state
                const originalSemesterHTML = semesterSelect.innerHTML;
                semesterSelect.disabled = true;
                semesterSelect.innerHTML = '<option value="">Loading semesters...</option>';

                fetch(`/admin/programs/${programId}/semesters`)
                    .then(response => {
                        console.log(response);
                        if (!response.ok) {
                            throw new Error('Failed to fetch semesters');
                        }
                        return response.json();
                    })
                    .then(totalSemesters => {
                        console.log(totalSemesters);
                        semesterSelect.innerHTML = '<option value="">Select Semester</option>';

                        if (!totalSemesters || totalSemesters === 0) {
                            Toast.fire({
                                icon: 'warning',
                                title: 'No semesters found for this program.'
                            });
                            return Promise.reject('No semesters found'); // Prevent subject fetch
                        }

                        console.log("hello");

                        // Generate semester options in descending order
                        for (let i = totalSemesters; i >= 1; i--) {
                            const option = document.createElement('option');
                            option.value = i;
                            option.textContent = `${i} Semester`;
                            semesterSelect.appendChild(option);

                        }

                        // Now fetch subjects for this program
                        return fetch(`/admin/programs/${programId}/subjects`);
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Failed to fetch subjects');
                        }
                        return response.json();
                    })
                    .then(subjects => {
                        console.log(subjects);
                        if (!subjects || subjects.length === 0) {
                            Toast.fire({
                                icon: 'info',
                                title: 'No subjects found for this program.'
                            });
                            return;
                        }else{
                            subjects.subjects.forEach(subject => {
                                const option = document.createElement('option');
                                option.value = subject.id;
                                option.textContent = `Same as ${subject.name}`;
                                subjectSelect.appendChild(option);
                            });
                        }


                    })
                    .catch(error => {
                        console.error('Error:', error);
                        semesterSelect.innerHTML = originalSemesterHTML;
                        Toast.fire({
                            icon: 'error',
                            title: error.message || error
                        });
                    })
                    .finally(() => {
                        semesterSelect.disabled = false;
                    });

            });

            document.getElementById('reuse_format').addEventListener('change', function() {
                const subjectId = this.value;
                const evaluationFormats = $('#evaluation-formats');

                // Clear existing formats
                evaluationFormats.empty();
                evaluationCount = 1;

                if (!subjectId) {
                    // Add default empty format
                    addNewEvaluationFormat();
                    return;
                }

                // Show loading state
                evaluationFormats.html('<div class="text-center py-4"><i class="fas fa-spinner fa-spin"></i> Loading evaluation formats...</div>');
// Define the handleResponse function at the top of your script
                function handleResponse(response) {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                }
                // Fetch evaluation formats
                fetch(`/admin/subject/${subjectId}/evaluations`)
                    .then(handleResponse)
                    .then(formats => {
                        evaluationFormats.empty();

                        if (!formats || formats.length === 0) {
                            Toast.fire({
                                icon: 'info',
                                title: 'No evaluation formats found for this subject'
                            });
                            addNewEvaluationFormat();
                            return;
                        }

                        // Add each evaluation format
                        formats.forEach(format => {
                            const formatHtml = `
                    <div class="evaluation-format grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label for="criteria_${evaluationCount}" class="form-label">Criteria <span class="text-red-500">*</span></label>
                            <input type="text" id="criteria_${evaluationCount}" name="criteria[]" class="form-input" value="${format.criteria}" required>
                        </div>
                        <div>
                            <label for="full_marks_${evaluationCount}" class="form-label">Full Marks <span class="text-red-500">*</span></label>
                            <input type="number" id="full_marks_${evaluationCount}" name="full_marks[]" class="form-input" value="${format.full_marks}" min="1" required>
                        </div>
                        <div>
                            <label for="pass_marks_${evaluationCount}" class="form-label">Pass Marks <span class="text-red-500">*</span></label>
                            <input type="number" id="pass_marks_${evaluationCount}" name="pass_marks[]" class="form-input" value="${format.pass_marks}" min="1" required>
                        </div>
                        <div>
                            <label for="marks_weight_${evaluationCount}" class="form-label">Marks Weight <span class="text-red-500">*</span></label>
                            <div class="flex">
                                <input type="number" id="marks_weight_${evaluationCount}" name="marks_weight[]" class="form-input marks-weight" value="${format.marks_weight}" min="1" required>
                                <button type="button" class="remove-evaluation ml-2 p-2 text-red-500 hover:text-red-700 focus:outline-none" title="Remove">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                `;

                            evaluationFormats.append(formatHtml);
                            evaluationCount++;
                        });

                        // Recalculate total weight
                        calculateTotalWeight();

                        // Attach remove handlers
                        $('.remove-evaluation').off('click').on('click', function() {
                            $(this).closest('.evaluation-format').remove();
                            calculateTotalWeight();
                        });
                    })
                    .catch(error => {
                        console.error('Error loading evaluation formats:', error);
                        evaluationFormats.empty();
                        addNewEvaluationFormat();
                        Toast.fire({
                            icon: 'error',
                            title: 'Failed to load evaluation formats'
                        });
                    });
            });

// Helper function to add a new empty evaluation format
            function addNewEvaluationFormat() {
                const newFormat = `
        <div class="evaluation-format grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="criteria_${evaluationCount}" class="form-label">Criteria <span class="text-red-500">*</span></label>
                <input type="text" id="criteria_${evaluationCount}" name="criteria[]" class="form-input" placeholder="e.g. Final Exam" required>
            </div>
            <div>
                <label for="full_marks_${evaluationCount}" class="form-label">Full Marks <span class="text-red-500">*</span></label>
                <input type="number" id="full_marks_${evaluationCount}" name="full_marks[]" class="form-input" min="1" placeholder="e.g. 100" required>
            </div>
            <div>
                <label for="pass_marks_${evaluationCount}" class="form-label">Pass Marks <span class="text-red-500">*</span></label>
                <input type="number" id="pass_marks_${evaluationCount}" name="pass_marks[]" class="form-input" min="1" placeholder="e.g. 40" required>
            </div>
            <div>
                <label for="marks_weight_${evaluationCount}" class="form-label">Marks Weight <span class="text-red-500">*</span></label>
                <div class="flex">
                    <input type="number" id="marks_weight_${evaluationCount}" name="marks_weight[]" class="form-input marks-weight" min="1" placeholder="e.g. 40" required>
                    <button type="button" class="remove-evaluation ml-2 p-2 text-red-500 hover:text-red-700 focus:outline-none" title="Remove">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
            </div>
        </div>
    `;

                $('#evaluation-formats').append(newFormat);
                calculateTotalWeight();

                // Attach remove handler
                $('.remove-evaluation').last().on('click', function() {
                    $(this).closest('.evaluation-format').remove();
                    calculateTotalWeight();
                });
            }

        });
    </script>
@endsection
