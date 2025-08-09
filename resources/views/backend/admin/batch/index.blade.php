@extends("backend.layout.admin-dashboard-layout")

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

@section("title")
    Batch Management
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
        @if(session('success'))
            <div class="bg-green-100 border border-green-500 text-green-700 px-4 py-3 mb-3 rounded relative" role="alert">
                <strong class="font-bold">Success!</strong>
                <span class="block sm:inline">{{session('success')}}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 border border-red-500 text-red-700 px-4 py-3 mb-3 rounded relative" role="alert">
                <strong class="font-bold">Error!</strong>
                <span class="block sm:inline">{{session('error')}}</span>
            </div>
        @endif

        <!-- Action Bar -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="relative">
                    <input type="text" id="searchBatches" placeholder="Search batches..." class="form-input pl-12 pr-4 py-2">


                </div>
                <div>
                    <select id="departmentFilter" class="form-input py-2">
                        <option value="">All Departments</option>
                        @foreach($allDepartments as $department)
                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <select id="programFilter" class="form-input py-2">
                        <option value="">All Programs</option>
                        <!-- Programs will be loaded dynamically based on department selection -->
                    </select>
                </div>
                <div>
                    <select id="statusFilter" class="form-input py-2">
                        <option value="">All Statuses</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>
            <div>
                <button id="addBatchBtn" class="btn-primary flex items-center justify-center">
                    <i class="fas fa-plus mr-2"></i> Add New Batch
                </button>
            </div>
        </div>

        <!-- Batches Table -->
        <div class="card">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th scope="col" class="table-header">Batch</th>
                        <th scope="col" class="table-header">Department</th>
                        <th scope="col" class="table-header">Program</th>
                        <th scope="col" class="table-header">Semester</th>
                        <th scope="col" class="table-header">Status</th>
                        <th scope="col" class="table-header">Actions</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700" id="batchesTableBody">
                    @forelse($batches as $batch)
                        <tr>
                            <td class="table-cell font-medium">{{ $batch->batch }}</td>
                            <td class="table-cell">{{ $batch->program->department->name }}</td>
                            <td class="table-cell">{{ $batch->program->name }}</td>
                            <td class="table-cell">{{ $batch->semester }}</td>
                            <td class="table-cell">
                                @if($batch->status == "active")
                                    <span class="badge bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">Active</span>
                                @else
                                    <span class="badge bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">Inactive</span>
                                @endif
                            </td>
                            <td class="table-cell">
                                <div class="flex items-center space-x-2">
                                    <button class="view-subjects-btn p-1.5 text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300 hover:bg-green-50 dark:hover:bg-green-900/20 rounded-full"
                                            data-id="{{ $batch->id }}"
                                            aria-label="View subjects">
                                        <i class="fas fa-book"></i>
                                    </button>
                                    <button class="edit-batch-btn p-1.5 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-full"
                                            data-id="{{ $batch->id }}"
                                            data-department="{{ $batch->department_id }}"
                                            data-program="{{ $batch->program_id }}"
                                            data-semester="{{ $batch->semester }}"
                                            data-batch="{{ $batch->batch }}"
                                            data-status="{{ $batch->status }}"
                                            data-start-date="{{ $batch->start_date }}"
                                            data-end-date="{{ $batch->end_date }}"
                                            aria-label="Edit batch">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="delete-batch-btn p-1.5 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-full"
                                            data-id="{{ $batch->id }}"
                                            aria-label="Delete batch">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="table-cell font-medium text-center" colspan="6">No Batches Found</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>
@endsection

@section("modals")
    <!-- Add/Edit Batch Modal -->
    <div id="batchModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
        <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 id="modalTitle" class="text-xl font-semibold text-gray-800 dark:text-white">Add New Batch</h3>
                    <button id="closeModal" class="p-1 rounded-md text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form id="batchForm">
                    <input type="hidden" id="batchId" name="id">
                    <meta name="csrf-token" content="{{ csrf_token() }}">

                    <div class="mb-4">
                        <label for="department" class="form-label">Department</label>
                        <select id="department" name="department_id" class="form-input" required>
                            <option value="">Select Department</option>
                            @foreach($allDepartments as $department)
                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="program" class="form-label">Program</label>
                        <select id="program" name="program_id" class="form-input" required disabled>
                            <option value="">Select Department First</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="semester" class="form-label">Semester</label>
                        <select id="semester" name="semester" class="form-input" required disabled>
                            <option value="">Select Program First</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="startDate" class="form-label">Start Date</label>
                        <input type="date" id="startDate" name="start_date" class="form-input" required>
                    </div>

                    <div class="mb-4">
                        <label for="endDate" class="form-label">End Date</label>
                        <input type="date" id="endDate" name="end_date" class="form-input" required>
                    </div>

                    <div class="mb-4">
                        <label for="batchName" class="form-label">Batch Name</label>
                        <input type="text" id="batchName" name="batch" class="form-input" placeholder="e.g. Fall 2023, Batch 2022-A" required>
                    </div>

                    <div class="mb-4">
                        <label for="batchStatus" class="form-label">Status</label>
                        <select id="batchStatus" name="status" class="form-input" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>

                    <div class="flex justify-end space-x-2 mt-6">
                        <button type="button" id="cancelBtn" class="btn-secondary">Cancel</button>
                        <button type="submit" id="saveBtn" class="btn-primary">Save Batch</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Subjects Modal -->
    <div id="subjectsModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
        <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-3xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 id="subjectsModalTitle" class="text-xl font-semibold text-gray-800 dark:text-white">Subjects for Batch</h3>
                    <button id="closeSubjectsModal" class="p-1 rounded-md text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div id="subjectsContainer" class="mt-4">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="table-header">Subject Code</th>
                                <th scope="col" class="table-header">Subject Name</th>
                                <th scope="col" class="table-header">Credit Hours</th>
                            </tr>
                            </thead>
                            <tbody id="subjectsTableBody" class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            <!-- Subjects will be loaded dynamically -->
                            <tr>
                                <td class="table-cell text-center" colspan="4">Loading subjects...</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="flex justify-end mt-6">
                    <button type="button" id="closeSubjectsBtn" class="btn-secondary">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
        <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full mx-4">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-white">Confirm Deletion</h3>
                    <button id="closeDeleteModal" class="p-1 rounded-md text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <p class="text-gray-600 dark:text-gray-400 mb-6">Are you sure you want to delete this batch? This action cannot be undone.</p>

                <div class="flex justify-end space-x-2">
                    <button id="cancelDeleteBtn" class="btn-secondary">Cancel</button>
                    <button id="confirmDeleteBtn" class="btn-danger">Delete</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section("scripts")
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toast notification setup (assuming you're using SweetAlert or similar)
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            // Batch Modal Elements
            const batchModal = document.getElementById('batchModal');
            const modalTitle = document.getElementById('modalTitle');
            const batchForm = document.getElementById('batchForm');
            const batchId = document.getElementById('batchId');
            const departmentSelect = document.getElementById('department');
            const programSelect = document.getElementById('program');
            const semesterSelect = document.getElementById('semester');
            const batchNameInput = document.getElementById('batchName');
            const batchStatusSelect = document.getElementById('batchStatus');
            const closeModal = document.getElementById('closeModal');
            const cancelBtn = document.getElementById('cancelBtn');
            const saveBtn = document.getElementById('saveBtn');

            // Subjects Modal Elements
            const subjectsModal = document.getElementById('subjectsModal');
            const subjectsModalTitle = document.getElementById('subjectsModalTitle');
            const subjectsTableBody = document.getElementById('subjectsTableBody');
            const closeSubjectsModal = document.getElementById('closeSubjectsModal');
            const closeSubjectsBtn = document.getElementById('closeSubjectsBtn');

            // Delete Modal Elements
            const deleteModal = document.getElementById('deleteModal');
            const closeDeleteModal = document.getElementById('closeDeleteModal');
            const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
            const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');

            // Filter Elements
            const searchInput = document.getElementById('searchBatches');
            const departmentFilter = document.getElementById('departmentFilter');
            const programFilter = document.getElementById('programFilter');
            const statusFilter = document.getElementById('statusFilter');

            // Add Batch Button
            const addBatchBtn = document.getElementById('addBatchBtn');

            // Open Add Batch Modal
            addBatchBtn.addEventListener('click', () => {
                modalTitle.textContent = 'Add New Batch';
                batchForm.reset();
                batchId.value = '';
                programSelect.disabled = true;
                semesterSelect.disabled = true;
                programSelect.innerHTML = '<option value="">Select Department First</option>';
                semesterSelect.innerHTML = '<option value="">Select Program First</option>';
                batchModal.classList.remove('hidden');
            });

            // Close Batch Modal
            closeModal.addEventListener('click', () => {
                batchModal.classList.add('hidden');
            });

            cancelBtn.addEventListener('click', () => {
                batchModal.classList.add('hidden');
            });

            // Department Change Handler (for Add/Edit Modal)
            departmentSelect.addEventListener('change', function() {
                const departmentId = this.value;
                programSelect.disabled = !departmentId;
                semesterSelect.disabled = true;

                if (!departmentId) {
                    programSelect.innerHTML = '<option value="">Select Department First</option>';
                    semesterSelect.innerHTML = '<option value="">Select Program First</option>';
                    return;
                }

                // Fetch programs for selected department
                fetch(`/admin/department/get_department_programs?department_id=${departmentId}`)
                    .then(response => response.json())
                    .then(data => {
                        programSelect.innerHTML = '<option value="">Select Program</option>';
                        data.forEach(program => {
                            programSelect.innerHTML += `<option value="${program.id}" data-semesters="${program.total_semesters}">${program.name}</option>`;
                        });
                    })
                    .catch(error => {
                        console.error('Error loading programs:', error);
                        Toast.fire({
                            icon: 'error',
                            title: 'Failed to load programs'
                        });
                    });
            });

            // Program Change Handler (for Add/Edit Modal)
            programSelect.addEventListener('change', function() {
                semesterSelect.innerHTML = '<option value="">Select Semester</option>';
                semesterSelect.disabled = !this.value;

                // Fetch subjects for this batch

                // console.log(this.value);

                fetch(`/admin/programs/${this.value}/semesters`)
                    .then(response => response.json())
                    .then(data => {
                        if (data) {
                            const totalSemester = data;
                            console.log(totalSemester);
                            for (let i = 1; i <= totalSemester; i++) {
                                semesterSelect.innerHTML += `<option value="${i}">Semester ${i}</option>`;
                            }
                        }

                    })
                    .catch(error => {

                    });

                if (!this.value) return;

                const selectedOption = this.options[this.selectedIndex];
                const totalSemesters = selectedOption.getAttribute('data-semesters');

                if (!totalSemesters || isNaN(totalSemesters)) return;

                for (let i = 1; i <= totalSemesters; i++) {
                    semesterSelect.innerHTML += `<option value="${i}">Semester ${i}</option>`;
                }
            });

            // Edit Batch
            document.querySelectorAll('.edit-batch-btn').forEach(button => {
                button.addEventListener('click', () => {
                    const id = button.getAttribute('data-id');
                    const departmentId = button.getAttribute('data-department');
                    const programId = button.getAttribute('data-program');
                    const semester = button.getAttribute('data-semester');
                    const batchName = button.getAttribute('data-batch');
                    const status = button.getAttribute('data-status');
                    const startDate = button.getAttribute('data-start-date');
                    const endDate = button.getAttribute('data-end-date');

                    modalTitle.textContent = 'Edit Batch';
                    batchId.value = id;

                    // Set department and trigger change event to load programs
                    departmentSelect.value = departmentId;
                    departmentSelect.dispatchEvent(new Event('change'));

                    // Set the date fields
                    document.getElementById('startDate').value = startDate;
                    document.getElementById('endDate').value = endDate;

                    // We need to wait for programs to load before setting program value
                    setTimeout(() => {
                        programSelect.value = programId;
                        programSelect.dispatchEvent(new Event('change'));

                        // Wait for semesters to load before setting semester value
                        setTimeout(() => {
                            semesterSelect.value = semester;
                            batchNameInput.value = batchName;
                            batchStatusSelect.value = status;
                        }, 300);
                    }, 300);

                    batchModal.classList.remove('hidden');
                });
            });

            // View Subjects
            document.querySelectorAll('.view-subjects-btn').forEach(button => {
                button.addEventListener('click', () => {
                    const batchId = button.getAttribute('data-id');
                    const batchName = button.closest('tr').querySelector('td:first-child').textContent;

                    subjectsModalTitle.textContent = `Subjects for ${batchName}`;
                    subjectsTableBody.innerHTML = '<tr><td class="table-cell text-center" colspan="4">Loading subjects...</td></tr>';

                    // Fetch subjects for this batch
                    fetch(`/admin/batches/${batchId}/subjects`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.length === 0) {
                                subjectsTableBody.innerHTML = '<tr><td class="table-cell text-center" colspan="4">No subjects found for this batch</td></tr>';
                                return;
                            }

                            subjectsTableBody.innerHTML = '';
                            data.forEach(subject => {
                                subjectsTableBody.innerHTML += `
                                    <tr>
                                        <td class="table-cell">${subject.code}</td>
                                        <td class="table-cell">${subject.name}</td>
                                        <td class="table-cell">${subject.credit}</td>
                                    </tr>
                                `;
                            });
                        })
                        .catch(error => {
                            console.error('Error loading subjects:', error);
                            subjectsTableBody.innerHTML = '<tr><td class="table-cell text-center text-red-500" colspan="4">Failed to load subjects</td></tr>';
                        });

                    subjectsModal.classList.remove('hidden');
                });
            });

            // Close Subjects Modal
            closeSubjectsModal.addEventListener('click', () => {
                subjectsModal.classList.add('hidden');
            });

            closeSubjectsBtn.addEventListener('click', () => {
                subjectsModal.classList.add('hidden');
            });

            // Delete Batch
            let batchToDelete = null;
            document.querySelectorAll('.delete-batch-btn').forEach(button => {
                button.addEventListener('click', () => {
                    batchToDelete = button.getAttribute('data-id');
                    deleteModal.classList.remove('hidden');
                });
            });

            // Close Delete Modal
            closeDeleteModal.addEventListener('click', () => {
                deleteModal.classList.add('hidden');
            });

            cancelDeleteBtn.addEventListener('click', () => {
                deleteModal.classList.add('hidden');
            });

            // Confirm Delete
            confirmDeleteBtn.addEventListener('click', () => {
                if (!batchToDelete) return;

                fetch(`/admin/batches/${batchToDelete}`, {
                    method: 'DELETE',
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                        "Accept": "application/json",
                    },
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === "success") {
                            Toast.fire({
                                icon: 'success',
                                title: data.message || 'Batch deleted successfully'
                            });
                            // Remove the row from the table
                            document.querySelector(`.delete-batch-btn[data-id="${batchToDelete}"]`)
                                .closest('tr').remove();
                        } else {
                            throw new Error(data.message || 'Delete failed');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Toast.fire({
                            icon: 'error',
                            title: error.message || 'Failed to delete batch'
                        });
                    })
                    .finally(() => {
                        deleteModal.classList.add('hidden');
                        batchToDelete = null;
                    });
            });

            // Form Submission
            batchForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const isEdit = batchId.value !== '';
                const url = isEdit ? `/admin/batches/${batchId.value}` : '/admin/program/batch';
                const method = isEdit ? 'PUT' : 'POST';

                fetch(url, {
                    method: method,
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                        "Accept": "application/json",
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify(Object.fromEntries(formData))
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === "success") {
                            Toast.fire({
                                icon: 'success',
                                title: data.message || (isEdit ? 'Batch updated successfully' : 'Batch created successfully')
                            });
                            batchModal.classList.add('hidden');
                            // Refresh the page to show updated data
                            setTimeout(() => {
                                location.reload();
                            }, 1000);
                        } else {
                            throw new Error(data.message || 'Operation failed');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Toast.fire({
                            icon: 'error',
                            title: error.message || 'An error occurred'
                        });
                    });
            });

            // Department Filter Change (for table filtering)
            departmentFilter.addEventListener('change', function() {
                const departmentId = this.value;

                // Reset program filter
                programFilter.innerHTML = '<option value="">All Programs</option>';

                if (!departmentId) {
                    filterBatches();
                    return;
                }

                // Fetch programs for selected department
                fetch(`/admin/department/get_department_programs?department_id=${departmentId}`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(program => {
                            programFilter.innerHTML += `<option value="${program.id}">${program.name}</option>`;
                        });
                        filterBatches();
                    })
                    .catch(error => {
                        console.error('Error loading programs:', error);
                    });
            });

            // Filter Batches
            function filterBatches() {
                const searchTerm = searchInput.value.toLowerCase();
                const department = departmentFilter.value;
                const program = programFilter.value;
                const status = statusFilter.value;

                const rows = document.querySelectorAll('#batchesTableBody tr');

                rows.forEach(row => {
                    if (row.cells.length === 1 && row.cells[0].colSpan === 6) {
                        // This is the "No Batches Found" row
                        return;
                    }

                    const batchName = row.cells[0].textContent.toLowerCase();
                    const departmentName = row.cells[1].textContent;
                    const programName = row.cells[2].textContent;
                    const statusText = row.cells[4].textContent.trim().toLowerCase();

                    const matchesSearch = batchName.includes(searchTerm);
                    const matchesDepartment = !department || row.querySelector('button').getAttribute('data-department') === department;
                    const matchesProgram = !program || row.querySelector('button').getAttribute('data-program') === program;
                    const matchesStatus = !status || statusText.includes(status.toLowerCase());

                    if (matchesSearch && matchesDepartment && matchesProgram && matchesStatus) {
                        row.classList.remove('hidden');
                    } else {
                        row.classList.add('hidden');
                    }
                });

                // Check if all rows are hidden
                const visibleRows = document.querySelectorAll('#batchesTableBody tr:not(.hidden)');
                const noResultsRow = document.querySelector('#batchesTableBody tr.no-results');

                if (visibleRows.length === 0 && !noResultsRow) {
                    const tbody = document.getElementById('batchesTableBody');
                    const newRow = document.createElement('tr');
                    newRow.className = 'no-results';
                    newRow.innerHTML = '<td class="table-cell font-medium text-center" colspan="6">No matching batches found</td>';
                    tbody.appendChild(newRow);
                } else if (visibleRows.length > 0 && noResultsRow) {
                    noResultsRow.remove();
                }
            }

            // Search Input
            let searchTimeout;
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(filterBatches, 300);
            });

            // Program Filter
            programFilter.addEventListener('change', filterBatches);

            // Status Filter
            statusFilter.addEventListener('change', filterBatches);
        });
    </script>
@endsection
