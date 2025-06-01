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
    Programs
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

            <x-show-success-failure-badge/>

        <!-- Action Bar -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="relative">
                    <input type="text" id="searchPrograms" placeholder="Search programs..." class="form-input pl-12 pr-4 py-2">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-4">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                </div>
                <div>
                    <select id="departmentFilter" class="form-input py-2">
                        <option value="">All Departments</option>

                    </select>
                </div>
            </div>
            <div class="flex flex-col sm:flex-row gap-3">
                <button id="manageSectionBtn" class="bg-yellow-500 text-white px-4 py-2 rounded flex items-center justify-center hover:bg-yellow-600">
                    <i class="fas fa-plus mr-2"></i> Manage Sections
                </button>

                <a href="{{route('admin.batches.index')}}" class="bg-green-500 text-white px-4 py-2 rounded flex items-center justify-center hover:bg-green-600">
                    <i class="fas fa-plus mr-2"></i> Manage Batch
                </a>
                <button id="addProgramBtn" class="btn-primary flex items-center justify-center">
                    <i class="fas fa-plus mr-2"></i> Add New Program
                </button>
            </div>
        </div>

        <!-- Programs Table -->
        <div class="card">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th scope="col" class="table-header">Program Name</th>
                        <th scope="col" class="table-header">Department</th>
                        <th scope="col" class="table-header">Total Batches</th>
                        <th scope="col" class="table-header">Total Semesters</th>
                        <th scope="col" class="table-header">Duration (Years)</th>
                        <th scope="col" class="table-header">Status</th>
                        <th scope="col" class="table-header">Actions</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700" id="programsTableBody">
                    @forelse($programs as $program)
                        <tr>
                            <td class="table-cell font-medium">{{$program->name}}</td>
                            <td class="table-cell">{{$program->department->name}}</td>
                            <td class="table-cell">4</td>
                            <td class="table-cell">{{$program->total_semesters}}</td>
                            <td class="table-cell">{{$program->duration}}</td>
                            <td class="table-cell">
                                @if($program->status == "active")
                                    <span class="badge bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">Active</span>
                                @endif

                                @if($program->status == "inactive")
                                    <span class="badge bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">Inactive</span>
                                @endif

                            </td>
                            <td class="table-cell">
                                <div class="flex items-center space-x-2">
                                    <button class="edit-program-btn p-1.5 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-full" data-id="{{$program->id}}" aria-label="Edit program">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="delete-program-btn p-1.5 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-full" data-id="{{$program->id}}" aria-label="Delete program">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="table-cell font-medium text-center" colspan="7">No Programs Found</td>
                        </tr>
                    @endforelse


                    </tbody>
                </table>
            </div>

        </div>
    </main>
@endsection

@section("modals")
    <!-- Add Program Modal -->
    <div id="programModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
        <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 id="modalTitle" class="text-xl font-semibold text-gray-800 dark:text-white">Add New Program</h3>
                    <button id="closeModal" class="p-1 rounded-md text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form id="programForm" action="{{route('admin.programs.store')}}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="programName" class="form-label">Program Name</label>
                        <input type="text" name="name" id="programName" class="form-input" placeholder="Enter program name" required>
                    </div>

                    <div class="mb-4">
                        <label for="department" class="form-label">Department</label>
                        <select id="department" name="department_id" class="form-input" required>
                            <option value="">Select Department</option>
                            @forelse($allDepartments as $deprtment)
                                <option value="{{$deprtment->id}}">{{$deprtment->name}}</option>
                            @empty
                                <option value="">No Departments Found</option>
                            @endforelse
                        </select>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="totalSemesters" class="form-label">Total Semesters</label>
                            <input type="number" name="total_semesters" id="totalSemesters" class="form-input" min="1" placeholder="Semesters" required>
                        </div>

                        <div>
                            <label for="durationYears" class="form-label">Duration (Years)</label>
                            <input type="number" name="duration" id="durationYears" class="form-input" min="1" placeholder="Years" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="programStatus" class="form-label">Status</label>
                        <select id="programStatus" name="status" class="form-input" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="programDescription" class="form-label">Description (Optional)</label>
                        <textarea id="programDescription" name="description" class="form-input" rows="3" placeholder="Enter program description"></textarea>
                    </div>

                    <div class="flex justify-end space-x-2 mt-6">
                        <button type="button" id="cancelBtn" class="btn-secondary">Cancel</button>
                        <button type="submit" id="saveBtn" class="btn-primary">Save Program</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Manage Batch Modal -->
    <div id="manageBatchModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
        <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-white">Manage Batch</h3>
                    <button class="closeManageBatchModal p-1 rounded-md text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form id="manageBatchForm">
                    <div class="mb-4">
                        <label for="batchDepartment" class="form-label">Department</label>
                        <select id="batchDepartment" name="department_id" class="form-input" required>
                            <option value="">Select Department</option>
                            @forelse($allDepartments as $deprtment)
                                <option value="{{$deprtment->id}}">{{$deprtment->name}}</option>
                            @empty
                                <option value="">No Departments Found</option>
                            @endforelse
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="batchProgram" class="form-label">Program</label>
                        <select id="batchProgram" name="program" class="form-input" required>
                            <option value="">Select Program</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="batchSemester" class="form-label">Select Semesters</label>
                            <select id="batchSemester" name="batchSemester" class="form-input" required>
                                <option value="">Select Semester</option>
                            </select>
                        </div>
                        <div>
                            <label for="batchStatus" class="form-label">Status</label>
                            <select id="batchStatus" name="status" class="form-input" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="batchTitle" class="form-label">Enter Batch Title</label>
                            <input type="text" name="batch" id="batchTitle" class="form-input" placeholder="Batch Title" required="">
                        </div>
                        <div>
                            <label for="batchProgram" class="form-label">Insert Batch</label>
                            <button type="button" id="addBatchButton" class="btn-primary w-full">Add</button>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="allBatches" class="form-label">All Batches</label>
                        <textarea id="allBatches" class="form-input" placeholder="No batches yes" disabled></textarea>
                    </div>

                    <div class="flex justify-end space-x-2 mt-6">
                        <button type="button" class="closeManageBatchModal btn-danger">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Manage Section Modal -->
    <div id="manageSectionModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
        <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-white">Manage Sections</h3>
                    <button id="closeManageSectionModal" class="p-1 rounded-md text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <form id="manageSectionForm">
                    <!-- Select Department -->
                    <div class="mb-4">
                        <label for="sectionDepartment" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Department</label>
                        <select id="sectionDepartment" name="department_id" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white" required>
                            <option value="">Select Department</option>
                            @forelse($allDepartments as $department)
                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                            @empty
                                <option value="">No Departments Found</option>
                            @endforelse
                        </select>
                    </div>

                    <!-- Select Program (based on Department) -->
                    <div class="mb-4">
                        <label for="sectionProgram" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Program</label>
                        <select id="sectionProgram" name="program_id" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white" required disabled>
                            <option value="">Select Department First</option>
                        </select>
                    </div>

                    <!-- Add Section -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div class="md:col-span-2">
                            <label for="sectionName" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Section Name</label>
                            <input type="text" id="sectionName" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white" placeholder="e.g. A, B, C">
                        </div>
                        <div class="flex items-end">
                            <button type="button" id="addSectionButton" class="w-full px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md transition-colors">
                                Add Section
                            </button>
                        </div>
                    </div>

                    <!-- List of Added Sections -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Added Sections</label>
                        <div id="sectionList" class="border border-gray-300 dark:border-gray-600 rounded-md p-2 min-h-20 max-h-40 overflow-y-auto dark:bg-gray-700">
                            <p id="noSectionsMessage" class="text-gray-500 dark:text-gray-400 text-sm">No sections added yet</p>
                            <!-- Sections will be added here dynamically -->
                        </div>
                    </div>

                    <!-- Hidden input for storing sections data -->
                    <input type="hidden" name="sections" id="sectionsData">

                    <!-- Buttons -->
                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" id="cancelSectionBtn" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-md transition-colors dark:bg-gray-600 dark:hover:bg-gray-500 dark:text-white">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md transition-colors">
                            Save Sections
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!--Edit Program Modal -->
    <div id="editProgramModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
        <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-white">Edit Program</h3>
                    <button id="closeEditModal" class="p-1 rounded-md text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form id="editProgramForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="programId" name="id" value="">

                    <div class="mb-4">
                        <label for="editProgramName" class="form-label">Program Name</label>
                        <input type="text" id="editProgramName" name="name" class="form-input" placeholder="Enter program name" required>
                    </div>

                    <div class="mb-4">
                        <label for="editDepartment" class="form-label">Department</label>
                        <select id="editDepartment" name="department_id" class="form-input" required>
                            <option value="">Select Department</option>
                            @forelse($allDepartments as $deprtment)
                                <option value="{{$deprtment->id}}">{{$deprtment->name}}</option>
                            @empty
                                <option value="">No Departments Found</option>
                            @endforelse
                        </select>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="editTotalSemesters" class="form-label">Total Semesters</label>
                            <input type="number" id="editTotalSemesters" name="total_semesters" class="form-input" min="1" placeholder="Semesters" required>
                        </div>

                        <div>
                            <label for="editDurationYears" class="form-label">Duration (Years)</label>
                            <input type="number" id="editDurationYears" name="duration" class="form-input" min="1" placeholder="Years" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="editProgramStatus" class="form-label">Status</label>
                        <select id="editProgramStatus" name="status" class="form-input" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="editProgramDescription" class="form-label">Description (Optional)</label>
                        <textarea id="editProgramDescription" name="description" class="form-input" rows="3" placeholder="Enter program description"></textarea>
                    </div>

                    <div class="flex justify-end space-x-2 mt-6">
                        <button type="button" id="cancelEditBtn" class="btn-secondary">Cancel</button>
                        <button type="submit" id="saveEditBtn" class="btn-primary">Update Program</button>
                    </div>
                </form>
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

                <p class="text-gray-600 dark:text-gray-400 mb-6">Are you sure you want to delete this program? This action cannot be undone.</p>

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
        document.addEventListener('DOMContentLoaded', async function () {
            try {
                const response = await fetch('/admin/department/getAllDepartments', {
                    method: 'get',
                    headers: {
                        'Content-Type': 'application/json',
                    }
                });

                const departments = await response.json();

                console.log(departments);

                departments.forEach(department => {
                    const option = document.createElement('option');
                    option.value = department.name;
                    option.textContent = department.name;
                    document.getElementById('departmentFilter').appendChild(option);
                });

            } catch (error) {
                Toast.fire({
                    icon: 'error',
                    title: 'Error saving sections: ' + error.message
                });
            }
        });
        document.addEventListener('DOMContentLoaded', function() {
            // Toast notification setup (if you're using SweetAlert or similar)
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

            // Manage Section Modal
            const manageSectionModal = document.getElementById('manageSectionModal');
            const closeManageSectionBtn = document.getElementById('closeManageSectionModal');
            const cancelSectionBtn = document.getElementById('cancelSectionBtn');
            const sectionDepartmentSelect = document.getElementById('sectionDepartment');
            const sectionProgramSelect = document.getElementById('sectionProgram');
            const sectionNameInput = document.getElementById('sectionName');
            const addSectionBtn = document.getElementById('addSectionButton');
            const sectionList = document.getElementById('sectionList');
            const noSectionsMessage = document.getElementById('noSectionsMessage');
            const sectionsData = document.getElementById('sectionsData');
            const sectionForm = document.getElementById('manageSectionForm');

            let sections = [];

            // Open/close modal logic
            window.openManageSectionModal = function() {
                manageSectionModal.classList.remove('hidden');
            };

            function closeSectionModal() {
                manageSectionModal.classList.add('hidden');
                resetSectionForm();
            }

            closeManageSectionBtn.addEventListener('click', closeSectionModal);
            cancelSectionBtn.addEventListener('click', closeSectionModal);

            // Department change handler
            sectionDepartmentSelect.addEventListener('change', function() {
                const departmentId = this.value;
                sectionProgramSelect.disabled = !departmentId;

                if (!departmentId) {
                    sectionProgramSelect.innerHTML = '<option value="">Select Department First</option>';
                    return;
                }

                // Fetch programs for selected department
                fetch(`/admin/department/get_department_programs?department_id=${departmentId}`)
                    .then(response => response.json())
                    .then(data => {
                        sectionProgramSelect.innerHTML = '<option value="">Select Program</option>';
                        data.forEach(program => {
                            sectionProgramSelect.innerHTML += `<option value="${program.id}">${program.name}</option>`;
                        });
                    })
                    .catch(error => console.error('Error loading programs:', error));
            });

            // Add section handler
            addSectionBtn.addEventListener('click', function() {
                const sectionName = sectionNameInput.value.trim();
                const programId = sectionProgramSelect.value;

                if (!sectionName) {
                    alert('Please enter a section name');
                    return;
                }

                if (!programId) {
                    alert('Please select a program first');
                    return;
                }

                // Add section to list
                sections.push({
                    name: sectionName,
                    program_id: programId
                });

                updateSectionList();
                sectionNameInput.value = '';
                sectionNameInput.focus();
            });

            // Update section list display
            function updateSectionList() {
                if (sections.length === 0) {
                    noSectionsMessage.classList.remove('hidden');
                    sectionList.innerHTML = '';
                    sectionList.appendChild(noSectionsMessage);
                    return;
                }

                noSectionsMessage.classList.add('hidden');
                sectionList.innerHTML = '';

                sections.forEach((section, index) => {
                    const sectionItem = document.createElement('div');
                    sectionItem.className = 'flex justify-between items-center py-2 border-b border-gray-200 dark:border-gray-600 last:border-b-0';
                    sectionItem.innerHTML = `
                        <span>${section.name}</span>
                        <button type="button" class="text-red-500 hover:text-red-700" data-index="${index}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    `;
                    sectionList.appendChild(sectionItem);
                });

                // Add event listeners to delete buttons
                document.querySelectorAll('#sectionList button').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const index = parseInt(this.getAttribute('data-index'));
                        sections.splice(index, 1);
                        updateSectionList();
                    });
                });

                // Update hidden input with sections data
                sectionsData.value = JSON.stringify(sections);
            }

            // Form submission handler
            sectionForm.addEventListener('submit', async function(e) {
                e.preventDefault();

                if (sections.length === 0) {
                    alert('Please add at least one section');
                    return;
                }

                try {
                    const response = await fetch('/admin/department/section', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            program_id: sectionProgramSelect.value,
                            sections: sections
                        })
                    });

                    const result = await response.json();

                    if (response.ok && result.success) {
                        Toast.fire({
                            icon: 'success',
                            title: 'Sections saved successfully!'
                        });
                        closeSectionModal();
                        // Optional: Refresh sections list in parent page
                        if (typeof window.refreshSections === 'function') {
                            window.refreshSections();
                        }
                    } else {
                        throw new Error(result.message || 'Failed to save sections');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    Toast.fire({
                        icon: 'error',
                        title: 'Error saving sections: ' + error.message
                    });
                }
            });

            // Reset form
            function resetSectionForm() {
                sectionForm.reset();
                sections = [];
                sectionProgramSelect.innerHTML = '<option value="">Select Department First</option>';
                sectionProgramSelect.disabled = true;
                updateSectionList();
            }

            // Program Modal
            const programModal = document.getElementById('programModal');
            const addProgramBtn = document.getElementById('addProgramBtn');
            const closeModal = document.getElementById('closeModal');
            const cancelBtn = document.getElementById('cancelBtn');
            const modalTitle = document.getElementById('modalTitle');
            const programForm = document.getElementById('programForm');

            // Batch Modal
            const manageBatchModal = document.getElementById('manageBatchModal');
            const closeManageBatchModal = document.querySelectorAll('.closeManageBatchModal');

            // Edit Program Modal
            const editProgramModal = document.getElementById('editProgramModal');
            const closeEditModal = document.getElementById('closeEditModal');
            const cancelEditBtn = document.getElementById('cancelEditBtn');
            const editProgramForm = document.getElementById('editProgramForm');

            // Delete Modal
            const deleteModal = document.getElementById('deleteModal');
            const closeDeleteModal = document.getElementById('closeDeleteModal');
            const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
            const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');

            // Open Add Program Modal
            addProgramBtn.addEventListener('click', () => {
                modalTitle.textContent = 'Add New Program';
                programForm.reset();
                programModal.classList.remove('hidden');
            });

            // Close Program Modal
            closeModal.addEventListener('click', () => {
                programModal.classList.add('hidden');
            });

            cancelBtn.addEventListener('click', () => {
                programModal.classList.add('hidden');
            });



            // Close Manage Batch Model
            closeManageBatchModal.forEach((item) => {
                item.addEventListener('click', () => {
                    manageBatchModal.classList.add("hidden");
                });
            });

            // Edit Program Modal
            const editButtons = document.querySelectorAll('.edit-program-btn');

            editButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const id = button.getAttribute('data-id');

                    fetch(`/admin/programs/${id}/edit`, {
                        headers: {
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                            "Accept": "application/json",
                        },
                    })
                        .then(response => response.json())
                        .then(data => {
                            // Populate form fields
                            document.getElementById('programId').value = data.id;
                            document.getElementById('editProgramName').value = data.name;
                            document.getElementById('editDepartment').value = data.department_id;
                            document.getElementById('editTotalSemesters').value = data.total_semesters;
                            document.getElementById('editDurationYears').value = data.duration;
                            document.getElementById('editProgramDescription').value = data.description || '';
                            document.getElementById('editProgramStatus').value = data.status;

                            editProgramModal.classList.remove('hidden');
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Toast.fire({
                                icon: 'error',
                                title: 'Failed to load program data'
                            });
                        });
                });
            });

            // Close edit modal
            closeEditModal.addEventListener('click', () => {
                editProgramModal.classList.add('hidden');
            });

            cancelEditBtn.addEventListener('click', () => {
                editProgramModal.classList.add('hidden');
            });

            // Edit form submission
            editProgramForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const id = document.getElementById('programId').value;
                const formData = new FormData(this);

                fetch(`/admin/programs/${id}`, {
                    method: 'POST',
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Toast.fire({
                                icon: 'success',
                                title: 'Program updated successfully'
                            });
                            editProgramModal.classList.add('hidden');
                            // Refresh the page to show updated data
                            location.reload();
                        } else {
                            throw new Error(data.message || 'Update failed');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Toast.fire({
                            icon: 'error',
                            title: error.message
                        });
                    });
            });

            // Close Delete Modal
            closeDeleteModal.addEventListener('click', () => {
                deleteModal.classList.add('hidden');
            });

            cancelDeleteBtn.addEventListener('click', () => {
                deleteModal.classList.add('hidden');
            });

            // Delete Program
            let programToDelete = null;
            const deleteButtons = document.querySelectorAll('.delete-program-btn');

            deleteButtons.forEach(button => {
                button.addEventListener('click', () => {
                    programToDelete = button.getAttribute('data-id');
                    deleteModal.classList.remove('hidden');
                });
            });

            confirmDeleteBtn.addEventListener('click', () => {
                if (!programToDelete) return;

                fetch(`/admin/programs/${programToDelete}`, {
                    method: 'DELETE',
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                        "Accept": "application/json",
                    },
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Toast.fire({
                                icon: 'success',
                                title: 'Program deleted successfully'
                            });
                            // Remove the row from the table
                            document.querySelector(`.delete-program-btn[data-id="${programToDelete}"]`)
                                .closest('tr').remove();
                        } else {
                            throw new Error(data.message || 'Delete failed');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Toast.fire({
                            icon: 'error',
                            title: error.message
                        });
                    })
                    .finally(() => {
                        deleteModal.classList.add('hidden');
                        programToDelete = null;
                    });
            });

            // Batch Department Change
            $("#batchDepartment").on("change", function () {
                let departmentId = $(this).val();
                $("#batchProgram").html('<option value="">Select Program</option>');
                $("#batchSemester").html('<option value="">Select Semester</option>');

                if (departmentId === "") return;

                $.ajax({
                    url: "{{route('admin.department.get_department_programs')}}",
                    type: "GET",
                    data: {
                        department_id: departmentId,
                        _token: $('meta[name="csrf-token"]').attr("content")
                    },
                    success: function (response) {
                        let programDropdown = $("#batchProgram");
                        programDropdown.empty().append('<option value="">Select Program</option>');

                        response.forEach((item) => {
                            programDropdown.append(
                                `<option value="${item.id}" data-semesters="${item.total_semesters}">
                                    ${item.name}
                                </option>`
                            );
                        });
                    },
                    error: function (xhr, status, error) {
                        console.error("Error:", error);
                        Toast.fire({
                            icon: 'error',
                            title: 'Failed to load programs'
                        });
                    }
                });
            });

            // Batch Program Change
            $("#batchProgram").on("change", function () {
                let semesterDropdown = $("#batchSemester");
                semesterDropdown.empty().append('<option value="">Select Semester</option>');

                let selectedOption = $(this).find(":selected");
                if (selectedOption.val() === "") return;

                let totalSemesters = selectedOption.data("semesters");
                if (!totalSemesters || isNaN(totalSemesters)) return;

                for (let i = 1; i <= totalSemesters; i++) {
                    semesterDropdown.append(`<option value="${i}">Semester ${i}</option>`);
                }
            });

            // Add Batch Button
            $("#addBatchButton").click(function () {
                let department = $("#batchDepartment").val();
                let program = $("#batchProgram").val();
                let semester = $("#batchSemester").val();
                let status = $("#batchStatus").val();
                let batchTitle = $("#batchTitle").val();

                if (!department || !program || !semester || !batchTitle || !status ||
                    department === "" || program === "" || semester === "") {
                    Toast.fire({
                        icon: 'error',
                        title: 'Please fill all required fields'
                    });
                    return;
                }

                $.ajax({
                    url: "{{ route('admin.program.batch') }}",
                    type: "POST",
                    data: {
                        department_id: department,
                        program_id: program,
                        semester: semester,
                        batch: batchTitle,
                        status: status,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        if (response.success) {
                            Toast.fire({
                                icon: 'success',
                                title: response.message || 'Batch added successfully'
                            });
                            $("#batchTitle").val("");

                            // Update the all batches textarea
                            let currentBatches = $("#allBatches").val();
                            if (currentBatches === "No batches yes") {
                                $("#allBatches").val(batchTitle);
                            } else {
                                $("#allBatches").val(currentBatches + ", " + batchTitle);
                            }
                        } else {
                            throw new Error(response.message || 'Failed to add batch');
                        }
                    },
                    error: function (xhr) {
                        let errorMsg = xhr.responseJSON?.message || 'An error occurred';
                        Toast.fire({
                            icon: 'error',
                            title: errorMsg
                        });
                    }
                });
            });

            // Search and Filter
            const searchInput = document.getElementById('searchPrograms');
            const departmentFilter = document.getElementById('departmentFilter');

            function filterPrograms() {
                const searchTerm = searchInput.value.toLowerCase();
                const department = departmentFilter.value;

                const rows = document.querySelectorAll('#programsTableBody tr');

                rows.forEach(row => {
                    const programName = row.querySelector('td:first-child').textContent.toLowerCase();
                    const programDepartment = row.querySelector('td:nth-child(2)').textContent;

                    const matchesSearch = programName.includes(searchTerm);
                    const matchesDepartment = department === '' || programDepartment === department;

                    if (matchesSearch && matchesDepartment) {
                        row.classList.remove('hidden');
                    } else {
                        row.classList.add('hidden');
                    }
                });
            }

            searchInput.addEventListener('input', filterPrograms);
            departmentFilter.addEventListener('change', filterPrograms);
        });
    </script>
@endsection
