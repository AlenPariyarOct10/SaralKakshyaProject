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
            .btn-success {
                @apply px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors duration-200 font-medium text-sm;
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
            .form-select {
                @apply w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:text-white text-sm bg-white dark:bg-gray-700 pr-10;
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

@section("title")
    Manage Teacher-Subject Mappings
@endsection

@section('content')
    <main class="scrollable-content p-4 md:p-6">
        <!-- Page Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Teacher-Subject Management</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Assign teachers to subjects and manage existing Mappings</p>
        </div>

        <!-- Action Bar -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
            <!-- Search & Filter -->
            <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                <!-- Search -->
                <div class="relative w-full max-w-md">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input
                        type="text"
                        id="searchInput"
                        placeholder="Search teachers or subjects..."
                        class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                    >
                </div>

                <!-- Department Filter -->
                <div class="relative w-full max-w-md">
                    <select
                        id="departmentFilter"
                        class="form-select"
                    >
                        <option value="">All Departments</option>
                        <!-- Will be populated via JavaScript -->
                    </select>
                </div>
            </div>

            <div class="flex">
                <!-- Back to Subjects Button -->
                <div class="flex justify-end mr-2">
                    <a href="{{ route('admin.subjects.index') }}"
                       class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition shadow-sm">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Subjects
                    </a>
                </div>
                <!-- Add Mapping Button -->
                <div class="flex justify-end">
                    <button
                        id="addMappingBtn"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition shadow-sm">
                        <i class="fas fa-plus mr-2"></i> New Mapping
                    </button>
                </div>
            </div>
        </div>

        <!-- Mapping Form Card (Hidden by default) -->
        <div id="MappingFormCard" class="card mb-6 hidden">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white" id="formTitle">New Teacher-Subject Mapping</h2>
                    <button id="closeFormBtn" class="text-gray-400 hover:text-gray-500">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form id="teacherSubjectForm">
                    <input type="hidden" id="MappingId" value="">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <!-- Teacher Selection -->
                        <div>
                            <label for="teacherId" class="form-label">Select Teacher <span class="text-red-500">*</span></label>
                            <select id="teacherId" name="teacherId" class="form-select" required>
                                <option value="">Select Teacher</option>
                                <!-- Will be populated via JavaScript -->
                            </select>
                            <div id="teacherIdError" class="text-red-500 text-xs mt-1 hidden"></div>
                        </div>

                        <!-- Department Selection -->
                        <div>
                            <label for="departmentId" class="form-label">Select Department <span class="text-red-500">*</span></label>
                            <select id="departmentId" name="departmentId" class="form-select" required>
                                <option value="">Select Department</option>
                                <!-- Will be populated via JavaScript -->
                            </select>
                            <div id="departmentIdError" class="text-red-500 text-xs mt-1 hidden"></div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <!-- Subject Selection (Filtered by Department) -->
                        <div>
                            <label for="subjectId" class="form-label">Select Subject <span class="text-red-500">*</span></label>
                            <select id="subjectId" name="subjectId" class="form-select" required>
                                <option value="">Select Subject</option>
                                <!-- Will be populated via JavaScript based on department -->
                            </select>
                            <div id="subjectIdError" class="text-red-500 text-xs mt-1 hidden"></div>
                        </div>
                        <!-- Status -->
                        <div>
                            <label for="status" class="form-label">Status</label>
                            <select id="status" name="status" class="form-select">
                                <option value="1" selected>Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>





                    <div class="flex justify-end gap-2">
                        <button type="button" id="cancelBtn" class="btn-secondary">Cancel</button>
                        <button type="submit" id="saveBtn" class="btn-primary">Save Mapping</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Mappings Table -->
        <div class="card">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th scope="col" class="table-header">Teacher</th>
                        <th scope="col" class="table-header">Subject</th>
                        <th scope="col" class="table-header">Subject Code</th>
                        <th scope="col" class="table-header">Department</th>
                        <th scope="col" class="table-header">Sections</th>
                        <th scope="col" class="table-header">Time</th>
                        <th scope="col" class="table-header">Status</th>
                        <th scope="col" class="table-header">Action</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700" id="MappingsTableBody">
                    <!-- Table content will be loaded via JavaScript -->
                    <tr>
                        <td colspan="8" class="table-cell text-center py-8">
                            <div class="flex flex-col items-center justify-center text-gray-500 dark:text-gray-400">
                                <i class="fas fa-spinner fa-spin text-2xl mb-3"></i>
                                <span>Loading Mappings...</span>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 bg-white dark:bg-gray-800 border-t dark:border-gray-700 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-sm text-gray-500 dark:text-gray-400" id="paginationInfo">
                    Loading pagination information...
                </div>
                <div class="flex items-center space-x-1" id="paginationControls">
                    <!-- Pagination controls will be loaded via JavaScript -->
                </div>
            </div>
        </div>
    </main>
@endsection

@section("scripts")
    <script>
        // Global variables
        let currentPage = 1;
        let totalPages = 1;
        let teachers = [];
        let subjects = [];
        let departments = [];
        let sections = [];
        let Mappings = [];
        let isEditMode = false;

        // DOM elements
        const searchInput = document.getElementById('searchInput');
        const departmentFilter = document.getElementById('departmentFilter');
        const MappingFormCard = document.getElementById('MappingFormCard');
        const teacherSubjectForm = document.getElementById('teacherSubjectForm');
        const addMappingBtn = document.getElementById('addMappingBtn');
        const closeFormBtn = document.getElementById('closeFormBtn');
        const cancelBtn = document.getElementById('cancelBtn');
        const formTitle = document.getElementById('formTitle');
        const saveBtn = document.getElementById('saveBtn');
        const MappingsTableBody = document.getElementById('MappingsTableBody');
        const paginationInfo = document.getElementById('paginationInfo');
        const paginationControls = document.getElementById('paginationControls');

        // Form fields
        const MappingId = document.getElementById('MappingId');
        const teacherId = document.getElementById('teacherId');
        const departmentId = document.getElementById('departmentId');
        const subjectId = document.getElementById('subjectId');
        const status = document.getElementById('status');

        // Error elements
        const teacherIdError = document.getElementById('teacherIdError');
        const departmentIdError = document.getElementById('departmentIdError');
        const subjectIdError = document.getElementById('subjectIdError');

        // Initialize the page
        document.addEventListener('DOMContentLoaded', function() {
            // Load initial data
            loadDepartments();
            loadTeachers();
            loadMappings();

            // Set up event listeners
            setupEventListeners();
        });

        // Event listeners setup
        function setupEventListeners() {
            // Form toggle buttons
            addMappingBtn.addEventListener('click', showAddForm);
            closeFormBtn.addEventListener('click', hideForm);
            cancelBtn.addEventListener('click', hideForm);

            // Cascading dropdowns
            departmentId.addEventListener('change', loadSubjectsByDepartment);

            // Form submission
            teacherSubjectForm.addEventListener('submit', handleFormSubmit);

            // Search and filter
            searchInput.addEventListener('input', debounce(loadMappings, 300));
            departmentFilter.addEventListener('change', loadMappings);
        }

        // Load subjects based on selected department
        function loadSubjectsByDepartment() {
            const selectedDepartmentId = departmentId.value;

            // Clear subject dropdown
            subjectId.innerHTML = '<option value="">Select Subject</option>';

            // Clear section dropdown

            if (!selectedDepartmentId) return;

            // Fetch subjects for the selected department
            fetch(`/admin/department/getSubjects?department_id=${selectedDepartmentId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    // Check if data is an array (success case) or an object with message (error case)
                    if (Array.isArray(data)) {
                        // Populate subject dropdown
                        data.forEach(subject => {
                            subjectId.innerHTML += `<option value="${subject.id}">${subject.name} (${subject.code})</option>`;
                        });
                    } else if (data.message) {
                        console.error('No subjects found:', data.message);
                    }
                })
                .catch(error => console.error('Error loading subjects:', error));
        }
        // Show add form
        function showAddForm() {
            resetForm();
            isEditMode = false;
            formTitle.textContent = 'New Teacher-Subject Mapping';
            saveBtn.textContent = 'Save Mapping';
            MappingFormCard.classList.remove('hidden');

            // Smooth scroll to form
            MappingFormCard.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }

        // Show edit form
        function showEditForm(id) {
            resetForm();
            isEditMode = true;
            formTitle.textContent = 'Edit Teacher-Subject Mapping';
            saveBtn.textContent = 'Update Mapping';

            // Find the Mapping by ID
            const Mapping = Mappings.find(a => a.id === id);
            if (!Mapping) return;

            // Fill the form with data
            MappingId.value = Mapping.id;
            teacherId.value = Mapping.teacher_id;

            // Set department and trigger change event to load subjects
            departmentId.value = Mapping.department_id;
            const departmentChangeEvent = new Event('change');
            departmentId.dispatchEvent(departmentChangeEvent);

            // Set subject after a short delay to ensure subjects are loaded
            setTimeout(() => {
                subjectId.value = Mapping.subject_id;

                // Trigger subject change to load sections
                const subjectChangeEvent = new Event('change');
                subjectId.dispatchEvent(subjectChangeEvent);

            }, 300);

            startTime.value = Mapping.start_time;
            endTime.value = Mapping.end_time;
            status.value = Mapping.status;

            // Show the form
            MappingFormCard.classList.remove('hidden');

            // Smooth scroll to form
            MappingFormCard.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }

        // Hide form
        function hideForm() {
            MappingFormCard.classList.add('hidden');
            resetForm();
        }

        // Reset form
        function resetForm() {
            teacherSubjectForm.reset();
            MappingId.value = '';
            hideAllErrors();
        }

        // Hide all error messages
        function hideAllErrors() {
            teacherIdError.classList.add('hidden');
            departmentIdError.classList.add('hidden');
            subjectIdError.classList.add('hidden');

        }

        // Show validation errors
        function showValidationErrors(errors) {
            hideAllErrors();

            if (errors.teacherId) {
                teacherIdError.textContent = errors.teacherId;
                teacherIdError.classList.remove('hidden');
            }

            if (errors.departmentId) {
                departmentIdError.textContent = errors.departmentId;
                departmentIdError.classList.remove('hidden');
            }

            if (errors.subjectId) {
                subjectIdError.textContent = errors.subjectId;
                subjectIdError.classList.remove('hidden');
            }

        }

        // Handle form submission
        function handleFormSubmit(e) {
            e.preventDefault();
            hideAllErrors();

            // Validate form
            const formData = {
                teacher_id: teacherId.value,
                department_id: departmentId.value,
                subject_id: subjectId.value,
                status: status.value
            };

            const errors = validateForm(formData);
            if (Object.keys(errors).length > 0) {
                showValidationErrors(errors);
                return;
            }

            // Submit form based on mode (add or edit)
            if (isEditMode) {
                updateMapping(MappingId.value, formData);
            } else {
                createMapping(formData);
            }
        }

        // Validate form
        function validateForm(data) {
            const errors = {};

            if (!data.teacher_id) {
                errors.teacherId = 'Please select a teacher';
            }

            if (!data.department_id) {
                errors.departmentId = 'Please select a department';
            }

            if (!data.subject_id) {
                errors.subjectId = 'Please select a subject';
            }


            return errors;
        }

        // Create a new Mapping
        function createMapping(data) {
            // Show loading state
            saveBtn.disabled = true;
            saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Saving...';

            // API call to create Mapping
            fetch('/admin/subject-teacher', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{csrf_token()}}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
                .then(response => response.json())
                .then(async data => {
                    saveBtn.disabled = false;
                    saveBtn.innerHTML = 'Save Mapping';

                    if (data.status === 'success') {
                        hideForm();
                        loadMappings();
                        await Toast.fire({
                            icon: 'success',
                            title: 'Mapping created successfully',
                        });
                    } else {
                        if (data.errors) {
                            showValidationErrors(data.errors);
                        } else {
                            await Toast.fire({
                                icon: 'error',
                                title: 'Failed to create Mapping',
                            });
                        }
                    }
                })
                .catch(async error => {
                    console.error('Error:', error);
                    saveBtn.disabled = false;
                    saveBtn.innerHTML = 'Save Mapping';
                    await Toast.fire({
                        icon: 'error',
                        title: 'An error occurred',
                    });
                });
        }

        // Update an existing Mapping
        function updateMapping(id, data) {
            // Show loading state
            saveBtn.disabled = true;
            saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Updating...';

            // API call to update Mapping
            fetch(`/admin/subject-teacher/${id}`, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': '{{csrf_token()}}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
                .then(response => response.json())
                .then(async data => {
                    saveBtn.disabled = false;
                    saveBtn.innerHTML = 'Update Mapping';

                    if (data.status === 'success') {
                        hideForm();
                        loadMappings();
                        await Toast.fire({
                            icon: 'success',
                            title: 'Mapping updated successfully',
                        });
                    } else {
                        if (data.errors) {
                            showValidationErrors(data.errors);
                        } else {
                            await Toast.fire({
                                icon: 'error',
                                title: 'Failed to update Mapping',
                            });
                        }
                    }
                })
                .catch(async error => {
                    console.error('Error:', error);
                    saveBtn.disabled = false;
                    saveBtn.innerHTML = 'Update Mapping';
                    await Toast.fire({
                        icon: 'error',
                        title: 'An error occurred',
                    });
                });
        }

        // Delete an Mapping
        function confirmDelete(id, teacherName, subjectName) {
            Swal.fire({
                title: 'Are you sure?',
                text: `Remove "${teacherName}" from "${subjectName}"? This action cannot be undone.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteMapping(id);
                }
            });
        }

        // Delete an Mapping
        function deleteMapping(id) {
            fetch(`/admin/subject-teacher/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{csrf_token()}}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
                .then(response => response.json())
                .then(async data => {
                    if (data.status === 'success') {
                        loadMappings();
                        await Toast.fire({
                            icon: 'success',
                            title: 'Mapping deleted successfully',
                        });
                    } else {
                        await Toast.fire({
                            icon: 'error',
                            title: 'Failed to delete Mapping',
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Toast.fire({
                        icon: 'error',
                        title: 'An error occurred',
                    });
                });
        }

        // Load departments for filter
        function loadDepartments() {
            fetch('/admin/department/getAllDepartments')
                .then(response => response.json())
                .then(data => {
                    if (data) {
                        departments = data;
                        renderDepartmentFilter();
                        renderDepartmentDropdown();
                    }
                })
                .catch(error => console.error('Error loading departments:', error));
        }

        // Render department filter options
        function renderDepartmentFilter() {
            departmentFilter.innerHTML = '<option value="">All Departments</option>';
            departments.forEach(dept => {
                departmentFilter.innerHTML += `<option value="${dept.id}">${dept.name}</option>`;
            });
        }

        // Render department dropdown in form
        function renderDepartmentDropdown() {
            departmentId.innerHTML = '<option value="">Select Department</option>';
            departments.forEach(dept => {
                departmentId.innerHTML += `<option value="${dept.id}">${dept.name}</option>`;
            });
        }

        // Load teachers for dropdown
        function loadTeachers() {
            fetch('/admin/teachers/getAll')
                .then(response => response.json())
                .then(data => {
                    if (data) {
                        teachers = data.data.teachers;
                        renderTeacherDropdown();
                    }
                })
                .catch(error => console.error('Error loading teachers:', error));
        }

        // Render teacher dropdown options
        function renderTeacherDropdown() {
            teacherId.innerHTML = '<option value="">Select Teacher</option>';
            teachers.forEach(teacher => {
                teacherId.innerHTML += `<option value="${teacher.id}">${teacher.fname} ${teacher.lname}</option>`;
            });
        }

        // Load subjects for dropdown
        function loadSubjects() {
            fetch('/admin/subjects/getAll')
                .then(response => response.json())
                .then(data => {
                    if (data) {
                        subjects = data.data.subjects;
                    }
                })
                .catch(error => console.error('Error loading subjects:', error));
        }

        // Load Mappings with pagination
        function loadMappings() {
            // Show loading state
            MappingsTableBody.innerHTML = `
                <tr>
                    <td colspan="8" class="table-cell text-center py-8">
                        <div class="flex flex-col items-center justify-center text-gray-500 dark:text-gray-400">
                            <i class="fas fa-spinner fa-spin text-2xl mb-3"></i>
                            <span>Loading mappings...</span>
                        </div>
                    </td>
                </tr>
            `;

            // Build query parameters
            const params = new URLSearchParams();
            params.append('page', currentPage);

            // Add search term if provided
            if (searchInput.value.trim()) {
                params.append('search', searchInput.value.trim());
            }

            // Add department filter if selected
            if (departmentFilter.value) {
                params.append('department_id', departmentFilter.value);
            }

            // API call to get Mappings
            fetch(`/admin/subject-teacher?${params.toString()}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        Mappings = data.data;
                        totalPages = data.meta.last_page;
                        currentPage = data.meta.current_page;

                        // Load subjects if not already loaded
                        if (subjects.length === 0) {
                            loadSubjects();
                        }

                        // Render Mappings and pagination
                        renderMappings();
                        renderPagination(data.meta);
                    } else {
                        MappingsTableBody.innerHTML = `
                            <tr>
                                <td colspan="8" class="table-cell text-center py-8 text-gray-500 dark:text-gray-400">
                                    Failed to load Mappings
                                </td>
                            </tr>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error loading Mappings:', error);
                    MappingsTableBody.innerHTML = `
                        <tr>
                            <td colspan="8" class="table-cell text-center py-8 text-gray-500 dark:text-gray-400">
                                An error occurred while loading Mappings
                            </td>
                        </tr>
                    `;
                });
        }

        // Render Mappings table
        function renderMappings() {
            if (!Mappings || Mappings.length === 0) {
                MappingsTableBody.innerHTML = `
                    <tr>
                        <td colspan="8" class="table-cell text-center py-8 text-gray-500 dark:text-gray-400">
                            No Mappings found
                        </td>
                    </tr>
                `;
                return;
            }

            let html = '';
            Mappings.forEach(Mapping => {
                // Find teacher and subject details
                const teacher = teachers.find(t => t.id === Mapping.teacher_id) || { fname: 'Unknown', lname: 'Teacher' };
                const subject = subjects.find(s => s.id === Mapping.subject_id) || { name: 'Unknown Subject', code: 'N/A' };

                // Get department from subject
                const departmentName = subject.department ? subject.department.name : 'N/A';

                // Format sections
                const sectionsList = Mapping.sections ? Mapping.sections.map(s => s.name).join(', ') : 'N/A';

                // Format time
                const timeDisplay = Mapping.start_time && Mapping.end_time
                    ? `${Mapping.start_time} - ${Mapping.end_time}`
                    : 'N/A';

                // Create status badge
                let statusBadge = '';
                if (Mapping.status === 1) {
                    statusBadge = '<span class="badge bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">Active</span>';
                } else {
                    statusBadge = '<span class="badge bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">Inactive</span>';
                }

                html += `
                    <tr id="Mapping-row-${Mapping.id}">
                        <td class="table-cell font-medium">${teacher.title || ''} ${teacher.fname} ${teacher.lname}</td>
                        <td class="table-cell">${subject.name}</td>
                        <td class="table-cell">${subject.code}</td>
                        <td class="table-cell">${departmentName}</td>
                        <td class="table-cell">${sectionsList}</td>
                        <td class="table-cell">${timeDisplay}</td>
                        <td class="table-cell">${statusBadge}</td>
                        <td class="table-cell">
                            <div class="flex items-center space-x-2">
                                <button onclick="showEditForm(${Mapping.id})" class="p-1.5 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-full" aria-label="Edit Mapping">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="confirmDelete(${Mapping.id}, '${teacher.fname} ${teacher.lname}', '${subject.name}')" class="p-1.5 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-full" aria-label="Delete Mapping">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            });

            MappingsTableBody.innerHTML = html;
        }

        // Render pagination controls
        function renderPagination(meta) {
            // Update pagination info text
            paginationInfo.textContent = `Showing ${meta.from || 0} to ${meta.to || 0} of ${meta.total} Mappings`;

            // Generate pagination buttons
            let paginationHTML = '';

            // Previous button
            paginationHTML += `
                <button onclick="changePage(${meta.current_page - 1})" class="p-2 rounded-md text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 ${meta.current_page <= 1 ? 'disabled:opacity-50 disabled:cursor-not-allowed' : ''}" ${meta.current_page <= 1 ? 'disabled' : ''} aria-label="Previous page">
                    <i class="fas fa-chevron-left"></i>
                </button>
            `;

            // Page buttons
            const pageWindow = 2; // Number of pages to show on each side of current page
            const startPage = Math.max(1, meta.current_page - pageWindow);
            const endPage = Math.min(meta.last_page, meta.current_page + pageWindow);

            // First page
            if (startPage > 1) {
                paginationHTML += `
                    <button onclick="changePage(1)" class="p-2 rounded-md text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700" aria-label="Page 1">1</button>
                `;

                if (startPage > 2) {
                    paginationHTML += `<span class="p-2 text-gray-500">...</span>`;
                }
            }

            // Page numbers
            for (let i = startPage; i <= endPage; i++) {
                const isCurrentPage = i === meta.current_page;
                paginationHTML += `
                    <button onclick="changePage(${i})" class="p-2 rounded-md ${isCurrentPage ? 'bg-primary-50 dark:bg-gray-700 text-primary-600 dark:text-primary-400' : 'text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700'}" aria-label="Page ${i}">${i}</button>
                `;
            }

            // Last page
            if (endPage < meta.last_page) {
                if (endPage < meta.last_page - 1) {
                    paginationHTML += `<span class="p-2 text-gray-500">...</span>`;
                }

                paginationHTML += `
                    <button onclick="changePage(${meta.last_page})" class="p-2 rounded-md text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700" aria-label="Page ${meta.last_page}">${meta.last_page}</button>
                `;
            }

            // Next button
            paginationHTML += `
                <button onclick="changePage(${meta.current_page + 1})" class="p-2 rounded-md text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 ${meta.current_page >= meta.last_page ? 'disabled:opacity-50 disabled:cursor-not-allowed' : ''}" ${meta.current_page >= meta.last_page ? 'disabled' : ''} aria-label="Next page">
                    <i class="fas fa-chevron-right"></i>
                </button>
            `;

            paginationControls.innerHTML = paginationHTML;
        }

        // Change page
        function changePage(page) {
            if (page < 1 || page > totalPages) return;
            currentPage = page;
            loadMappings();

            // Scroll to top of table
            document.querySelector('.card').scrollIntoView({ behavior: 'smooth', block: 'start' });
        }

        // Utility function: Debounce
        function debounce(func, delay) {
            let timeout;
            return function() {
                const context = this;
                const args = arguments;
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(context, args), delay);
            };
        }

        // Make functions accessible globally
        window.showEditForm = showEditForm;
        window.confirmDelete = confirmDelete;
        window.changePage = changePage;
    </script>
@endsection
