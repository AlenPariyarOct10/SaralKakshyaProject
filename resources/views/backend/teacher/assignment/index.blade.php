@extends("backend.layout.teacher-dashboard-layout")

@section('title', 'Manage Assignments')

@section('content')
    <!-- Main Content Area -->
    <main class="p-6 md:p-6 min-h-screen overflow-y-auto pb-16">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-white mb-1">
                    Manage Assignments
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Create, edit, and manage your class assignments
                </p>
            </div>
            <a href="{{route('teacher.assignment.create')}}" class="mt-4 md:mt-0 btn-primary flex items-center">
                <i class="fas fa-plus mr-2"></i> Create New Assignment
            </a>
        </div>

        <!-- Assignment Filters -->
        <div class="card mb-6 p-4">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Filters</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="relative">
                    <label for="departmentFilter" class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 block">Department</label>
                    <select id="departmentFilter" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                        <option value="">All Departments</option>
                    </select>
                </div>

                <div class="relative">
                    <label for="programFilter" class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 block">Program</label>
                    <select id="programFilter" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                        <option value="">All Programs</option>
                    </select>
                </div>

                <div class="relative">
                    <label for="subjectFilter" class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 block">Subject</label>
                    <select id="subjectFilter" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                        <option value="">All Subjects</option>
                    </select>
                </div>

                <div class="relative">
                    <label for="statusFilter" class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 block">Status</label>
                    <select id="statusFilter" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="draft">Draft</option>
                    </select>
                </div>
            </div>

            <div class="flex flex-col md:flex-row gap-4 mt-4">
                <div class="flex-grow relative">
                    <label for="searchInput" class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 block">Search</label>
                    <div class="flex items-center border border-gray-300 rounded-md dark:border-gray-700 overflow-hidden">
                        <input id="searchInput" type="text" placeholder="Search assignments by title, description..." class="w-full px-4 py-2 focus:outline-none dark:bg-gray-800 dark:text-white">
                        <button class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>

                <div class="flex items-end">
                    <button id="resetFilters" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                        Reset Filters
                    </button>
                </div>
            </div>
        </div>

        <!-- Assignments List -->
        <div class="card">
            <div class="flex items-center justify-between mb-4 p-4">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">All Assignments</h3>
                <div class="text-sm text-gray-500 dark:text-gray-400">Total: 6 assignments</div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Assignment Title</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Subject</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Department</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Program</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Due Date Time</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Submissions</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    <!-- Row 1 -->
                    <tr>
                        <td colspan="8" class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-800 dark:text-white text-center">Loading</div>
                        </td>
                    </tr>

                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="flex items-center justify-between border-t border-gray-200 dark:border-gray-700 px-4 py-3 sm:px-6">
                <div class="flex-1 flex justify-between sm:hidden">
                    <a href="#" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Previous
                    </a>
                    <a href="#" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Next
                    </a>
                </div>
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700 dark:text-gray-300">
                            Showing
                            <span class="font-medium">1</span>
                            to
                            <span class="font-medium">6</span>
                            of
                            <span class="font-medium">6</span>
                            results
                        </p>
                    </div>
                    <div>
                        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                            <a href="#" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-sm font-medium text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700">
                                <span class="sr-only">Previous</span>
                                <i class="fas fa-chevron-left"></i>
                            </a>
                            <a href="#" aria-current="page" class="z-10 bg-primary-50 dark:bg-primary-900 border-primary-500 text-primary-600 dark:text-primary-200 relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                                1
                            </a>
                            <a href="#" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-sm font-medium text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700">
                                <span class="sr-only">Next</span>
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Create Assignment Modal (Hidden by default) -->
    <div id="createAssignmentModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
        <div class="relative bg-white dark:bg-gray-800 rounded-lg max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Create New Assignment</h3>
                    <button id="closeCreateModal" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <form id="createAssignmentForm">
                    <div class="space-y-4">
                        <!-- Title -->
                        <div>
                            <label for="assignmentTitle" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Assignment Title</label>
                            <input type="text" id="assignmentTitle" name="assignmentTitle" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="Enter assignment title">
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="assignmentDescription" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
                            <textarea id="assignmentDescription" name="assignmentDescription" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="Enter assignment description"></textarea>
                        </div>

                        <!-- Department, Program, Subject in a row -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="dueDate" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Due Date</label>
                                <input type="date" id="dueDate" name="dueDate" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            </div>

                            <div>
                                <label for="maxPoints" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Maximum Points</label>
                                <input type="number" id="maxPoints" name="maxPoints" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="Enter maximum points">
                            </div>
                        </div>

                        <!-- Status and Visibility in a row -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="assignmentStatus" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                                <select id="assignmentStatus" name="assignmentStatus" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    <option value="draft">Draft</option>
                                    <option value="published">Published</option>
                                    <option value="archived">Archived</option>
                                </select>
                            </div>

                            <div>
                                <label for="assignmentVisibility" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Visibility</label>
                                <select id="assignmentVisibility" name="assignmentVisibility" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    <option value="all">All Students</option>
                                    <option value="specific">Specific Sections</option>
                                </select>
                            </div>
                        </div>

                        <!-- Assignment Files -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Assignment Resources</label>
                            <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-md p-4 text-center">
                                <input type="file" id="assignmentFiles" class="hidden" multiple>
                                <label for="assignmentFiles" class="cursor-pointer">
                                    <i class="fas fa-cloud-upload-alt text-2xl text-gray-400 dark:text-gray-500 mb-2"></i>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Drag and drop files here or click to browse</p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Supported formats: PDF, DOC, DOCX, JPG, PNG, ZIP</p>
                                </label>
                            </div>

                            <div id="filePreview" class="mt-2 hidden">
                                <!-- Files will be added here dynamically -->
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" id="cancelCreateAssignment" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                            Cancel
                        </button>
                        <button type="submit" class="btn-primary">
                            Create Assignment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal (Hidden by default) -->
    <div id="deleteConfirmationModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
        <div class="relative bg-white dark:bg-gray-800 rounded-lg max-w-md w-full mx-4">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Delete Assignment</h3>
                    <button id="closeDeleteModal" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <div class="mb-6">
                    <p class="text-gray-700 dark:text-gray-300">Are you sure you want to delete this assignment? This action cannot be undone.</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Note: All associated submissions and grades will also be deleted.</p>
                </div>

                <div class="flex justify-end space-x-3">
                    <button id="cancelDelete" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                        Cancel
                    </button>
                    <button id="confirmDelete" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- View Assignment Modal (Hidden by default) -->
    <div id="viewAssignmentModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
        <div class="relative bg-white dark:bg-gray-800 rounded-lg max-w-3xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white" id="viewAssignmentTitle">Linear Algebra Problem Set</h3>
                    <button id="closeViewModal" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Department</h4>
                            <p class="text-base text-gray-900 dark:text-white" id="viewAssignmentDepartment">Science</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Program</h4>
                            <p class="text-base text-gray-900 dark:text-white" id="viewAssignmentProgram">Undergraduate</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Subject</h4>
                            <p class="text-base text-gray-900 dark:text-white" id="viewAssignmentSubject">Mathematics</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Due Date</h4>
                            <p class="text-base text-gray-900 dark:text-white" id="viewAssignmentDueDate">May 15, 2023</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</h4>
                            <p class="text-base">
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 dark:bg-green-800 text-green-800 dark:text-green-100" id="viewAssignmentStatus">Published</span>
                            </p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Maximum Points</h4>
                            <p class="text-base text-gray-900 dark:text-white" id="viewAssignmentPoints">100</p>
                        </div>
                    </div>

                    <div>
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Description</h4>
                        <p class="text-base text-gray-900 dark:text-white" id="viewAssignmentDescription">
                            Complete the linear algebra problem set from Chapter 5, problems 1-15. Show all your work and explain your reasoning for each step.
                        </p>
                    </div>

                    <div>
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Resources</h4>
                        <div class="flex flex-wrap gap-2 mt-2" id="viewAssignmentResources">
                            <a href="#" class="flex items-center px-3 py-1 text-xs bg-gray-100 dark:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-200 dark:hover:bg-gray-500">
                                <i class="fas fa-file-pdf mr-2 text-red-500"></i> Problem_Set.pdf
                            </a>
                            <a href="#" class="flex items-center px-3 py-1 text-xs bg-gray-100 dark:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-200 dark:hover:bg-gray-500">
                                <i class="fas fa-file-video mr-2 text-blue-500"></i> Tutorial_Video.mp4
                            </a>
                        </div>
                    </div>

                    <div>
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Submission Statistics</h4>
                        <div class="mt-2 grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-md">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Total Students</p>
                                <p class="text-xl font-semibold text-gray-900 dark:text-white">25</p>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-md">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Submitted</p>
                                <p class="text-xl font-semibold text-gray-900 dark:text-white">18</p>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-md">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Graded</p>
                                <p class="text-xl font-semibold text-gray-900 dark:text-white">15</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <button id="closeViewAssignment" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                        Close
                    </button>
                    <button id="editViewedAssignment" class="px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700">
                        <i class="fas fa-edit mr-1"></i> Edit
                    </button>
                    <button id="viewSubmissions" class="btn-primary">
                        <i class="fas fa-clipboard-list mr-1"></i> View Submissions
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // DOM Elements
            const departmentFilter = document.getElementById('departmentFilter');
            const programFilter = document.getElementById('programFilter');
            const subjectFilter = document.getElementById('subjectFilter');
            const statusFilter = document.getElementById('statusFilter');
            const searchInput = document.getElementById('searchInput');
            const resetFilters = document.getElementById('resetFilters');
            const assignmentsTableBody = document.querySelector('tbody');

            // Modal Elements
            const createAssignmentModal = document.getElementById('createAssignmentModal');
            const closeCreateModal = document.getElementById('closeCreateModal');
            const cancelCreateAssignment = document.getElementById('cancelCreateAssignment');
            const createAssignmentForm = document.getElementById('createAssignmentForm');
            const deleteConfirmationModal = document.getElementById('deleteConfirmationModal');
            const closeDeleteModal = document.getElementById('closeDeleteModal');
            const cancelDelete = document.getElementById('cancelDelete');
            const confirmDelete = document.getElementById('confirmDelete');
            const viewAssignmentModal = document.getElementById('viewAssignmentModal');
            const closeViewModal = document.getElementById('closeViewModal');
            const closeViewAssignment = document.getElementById('closeViewAssignment');
            const editViewedAssignment = document.getElementById('editViewedAssignment');
            const viewSubmissions = document.getElementById('viewSubmissions');
            const assignmentFiles = document.getElementById('assignmentFiles');
            const filePreview = document.getElementById('filePreview');

            // Initialize filters and load data
            initializeFilters();
            loadAssignments();

            // Event Listeners
            departmentFilter.addEventListener('change', getDepartmentPrograms);
            programFilter.addEventListener('change', getDepartmentSubjects);
            statusFilter.addEventListener('change', loadAssignments);
            searchInput.addEventListener('input', debounce(loadAssignments, 300));
            resetFilters.addEventListener('click', resetAllFilters);

            // Modal Event Listeners
            if (closeCreateModal) closeCreateModal.addEventListener('click', () => toggleModal(createAssignmentModal));
            if (cancelCreateAssignment) cancelCreateAssignment.addEventListener('click', () => toggleModal(createAssignmentModal));
            if (closeDeleteModal) closeDeleteModal.addEventListener('click', () => toggleModal(deleteConfirmationModal));
            if (cancelDelete) cancelDelete.addEventListener('click', () => toggleModal(deleteConfirmationModal));
            if (closeViewModal) closeViewModal.addEventListener('click', () => toggleModal(viewAssignmentModal));
            if (closeViewAssignment) closeViewAssignment.addEventListener('click', () => toggleModal(viewAssignmentModal));

            // Form Submission
            if (createAssignmentForm) {
                createAssignmentForm.addEventListener('submit', handleCreateAssignment);
            }

            // Confirm Delete
            if (confirmDelete) {
                confirmDelete.addEventListener('click', handleDeleteAssignment);
            }

            // File Upload Preview
            if (assignmentFiles) {
                assignmentFiles.addEventListener('change', handleFileUpload);
            }

            // Functions
            function initializeFilters() {
                getDepartments();

            }

            async function loadAssignments() {
                try {
                    // Show loading state
                    assignmentsTableBody.innerHTML = '<tr><td colspan="8" class="px-6 py-4 whitespace-nowrap"><div class="text-sm font-medium text-gray-800 dark:text-white text-center">Loading assignments...</div></td></tr>';

                    // Build query parameters
                    const params = new URLSearchParams();
                    if (departmentFilter.value) params.append('department_id', departmentFilter.value);
                    if (programFilter.value) params.append('program_id', programFilter.value);
                    if (subjectFilter.value) params.append('subject_id', subjectFilter.value);
                    if (statusFilter.value) params.append('status', statusFilter.value);
                    if (searchInput.value) params.append('search', searchInput.value);

                    const response = await fetch(`/teacher/assignments?${params.toString()}`);
                    if (!response.ok) throw new Error('Failed to fetch assignments');

                    const { data: assignments, meta } = await response.json();

                    // Populate table
                    renderAssignmentsTable(assignments);
                    updatePagination(meta);

                } catch (error) {
                    console.error('Error loading assignments:', error);
                    assignmentsTableBody.innerHTML = '<tr><td colspan="8" class="px-6 py-4 whitespace-nowrap"><div class="text-sm font-medium text-gray-800 dark:text-white text-center">Error loading assignments</div></td></tr>';
                }
            }

            function renderAssignmentsTable(assignments) {
                assignmentsTableBody.innerHTML = '';

                if (assignments.length === 0) {
                    assignmentsTableBody.innerHTML = '<tr><td colspan="8" class="px-6 py-4 whitespace-nowrap"><div class="text-sm font-medium text-gray-800 dark:text-white text-center">No assignments found</div></td></tr>';
                    return;
                }

                console.log(assignments);

                assignments.data.forEach(assignment => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                    <td class="px-6 py-4 whitespace-normal">
                        <div class="text-sm font-medium text-gray-800 dark:text-white">${assignment.title}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-normal">
                        <div class="text-sm text-gray-500 dark:text-gray-400">${assignment.subject?.name || 'N/A'}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-normal">
                        <div class="text-sm text-gray-500 dark:text-gray-400">${assignment.department || 'N/A'}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-normal">
                        <div class="text-sm text-gray-500 dark:text-gray-400">${assignment.program || 'N/A'}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-normal">
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            ${assignment.due_date} <br> (${assignment.due_date_human})
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-normal">
                        <span class="px-2 py-1 text-xs font-medium rounded-full ${getStatusClass(assignment.status)}">
                            ${assignment.status.charAt(0).toUpperCase() + assignment.status.slice(1)}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-normal">
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            ${assignment.submissions_count || 0} / ${assignment.total_students || 0}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-normal">
                        <div class="flex space-x-2">
                            <button class="view-btn text-primary-600 hover:text-primary-800" title="View Assignment" data-id="${assignment.id}">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="edit-btn text-yellow-600 hover:text-yellow-800" title="Edit Assignment" data-id="${assignment.id}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="delete-btn text-red-600 hover:text-red-800" title="Delete Assignment" data-id="${assignment.id}">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </td>
                `;
                    assignmentsTableBody.appendChild(row);
                });

                // Setup event listeners for the buttons in the table
                setupTableEventListeners();
            }

            function getStatusClass(status) {
                switch(status.toLowerCase()) {
                    case 'active':
                    case 'published':
                        return 'bg-green-100 dark:bg-green-800 text-green-800 dark:text-green-100';
                    case 'draft':
                        return 'bg-yellow-100 dark:bg-yellow-800 text-yellow-800 dark:text-yellow-100';
                    case 'archived':
                        return 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-100';
                    default:
                        return 'bg-blue-100 dark:bg-blue-800 text-blue-800 dark:text-blue-100';
                }
            }

            function setupTableEventListeners() {
                // View Buttons
                document.querySelectorAll('.view-btn').forEach(button => {
                    button.addEventListener('click', (e) => {
                        const assignmentId = e.currentTarget.getAttribute('data-id');
                        window.location.href = `/teacher/assignment/${assignmentId}`;
                    });
                });

                // Edit Buttons
                document.querySelectorAll('.edit-btn').forEach(button => {
                    button.addEventListener('click', (e) => {
                        const assignmentId = e.currentTarget.getAttribute('data-id');
                        window.location.href = `/teacher/assignment/${assignmentId}/edit`;
                    });
                });

                // Delete Buttons
                document.querySelectorAll('.delete-btn').forEach(button => {
                    button.addEventListener('click', (e) => {
                        const assignmentId = e.currentTarget.getAttribute('data-id');
                        deleteConfirmationModal.setAttribute('data-assignment-id', assignmentId);
                        toggleModal(deleteConfirmationModal);
                    });
                });
            }

            async function viewAssignment(assignmentId) {
                try {
                    const response = await fetch(`/teacher/assignment/${assignmentId}`);
                    if (!response.ok) throw new Error('Failed to fetch assignment details');

                    const assignment = await response.json();

                    // Populate view modal
                    document.getElementById('viewAssignmentTitle').textContent = assignment.title;
                    document.getElementById('viewAssignmentDepartment').textContent = assignment.department?.name || 'N/A';
                    document.getElementById('viewAssignmentProgram').textContent = assignment.program?.name || 'N/A';
                    document.getElementById('viewAssignmentSubject').textContent = assignment.subject?.name || 'N/A';
                    document.getElementById('viewAssignmentDueDate').textContent =
                        `${new Date(assignment.due_date).toLocaleDateString()} at ${assignment.due_time}`;
                    document.getElementById('viewAssignmentStatus').textContent =
                        assignment.status.charAt(0).toUpperCase() + assignment.status.slice(1);
                    document.getElementById('viewAssignmentPoints').textContent = assignment.max_points;
                    document.getElementById('viewAssignmentDescription').textContent = assignment.description;

                    // Set status class
                    const statusElement = document.getElementById('viewAssignmentStatus');
                    statusElement.className = `px-2 py-1 text-xs font-medium rounded-full ${getStatusClass(assignment.status)}`;

                    // Populate resources
                    const resourcesContainer = document.getElementById('viewAssignmentResources');
                    resourcesContainer.innerHTML = '';
                    if (assignment.attachments && assignment.attachments.length > 0) {
                        assignment.attachments.forEach(attachment => {
                            const fileType = attachment.path.split('.').pop().toLowerCase();
                            let iconClass = 'fa-file';
                            if (['pdf'].includes(fileType)) iconClass = 'fa-file-pdf text-red-500';
                            if (['doc', 'docx'].includes(fileType)) iconClass = 'fa-file-word text-blue-500';
                            if (['jpg', 'jpeg', 'png', 'gif'].includes(fileType)) iconClass = 'fa-file-image text-green-500';
                            if (['mp4', 'mov', 'avi'].includes(fileType)) iconClass = 'fa-file-video text-purple-500';

                            const resourceElement = document.createElement('a');
                            resourceElement.href = `/teacher/assignment/${assignmentId}/attachment/${attachment.id}`;
                            resourceElement.className = 'flex items-center px-3 py-1 text-xs bg-gray-100 dark:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-200 dark:hover:bg-gray-500';
                            resourceElement.innerHTML = `<i class="fas ${iconClass} mr-2"></i> ${attachment.original_name}`;
                            resourceElement.target = '_blank';
                            resourcesContainer.appendChild(resourceElement);
                        });
                    } else {
                        resourcesContainer.innerHTML = '<p class="text-sm text-gray-500 dark:text-gray-400">No resources attached</p>';
                    }

                    // Set assignment ID for edit and view submissions buttons
                    viewAssignmentModal.setAttribute('data-assignment-id', assignmentId);

                    // Show the modal
                    toggleModal(viewAssignmentModal);

                } catch (error) {
                    console.error('Error viewing assignment:', error);
                    alert('Failed to load assignment details');
                }
            }

            async function getDepartments() {
                try {
                    const response = await fetch("/teacher/departments");
                    if (!response.ok) throw new Error('Failed to fetch departments');

                    const { data: departments } = await response.json();

                    // Clear existing options except the first one
                    departmentFilter.innerHTML = '<option value="">All Departments</option>';

                    departments.forEach(department => {
                        const option = document.createElement('option');
                        option.value = department.id;
                        option.textContent = department.name;
                        departmentFilter.appendChild(option);
                    });

                } catch (error) {
                    console.error('Error fetching departments:', error);
                    departmentFilter.innerHTML = '<option value="">Error loading departments</option>';
                }
            }

            async function getPrograms(departmentId = null) {
                try {
                    let url = "/teacher/programs";
                    if (departmentId) url = `/teacher/departments/${departmentId}/programs`;

                    const response = await fetch(url);
                    if (!response.ok) throw new Error('Failed to fetch programs');

                    const { data: programs } = await response.json();

                    // Clear existing options except the first one
                    programFilter.innerHTML = '<option value="">All Programs</option>';

                    programs.forEach(program => {
                        const option = document.createElement('option');
                        option.value = program.id;
                        option.textContent = program.name;
                        programFilter.appendChild(option);
                    });

                } catch (error) {
                    console.error('Error fetching programs:', error);
                    programFilter.innerHTML = '<option value="">Error loading programs</option>';
                }
            }

            async function getDepartmentPrograms() {
                const departmentId = departmentFilter.value;
                programFilter.disabled = !departmentId;
                subjectFilter.disabled = true;

                if (!departmentId) {
                    programFilter.innerHTML = '<option value="">All Programs</option>';
                    subjectFilter.innerHTML = '<option value="">All Subjects</option>';
                    loadAssignments();
                    return;
                }

                try {
                    programFilter.innerHTML = '<option value="">Loading programs...</option>';
                    await getPrograms(departmentId);
                    loadAssignments();
                } catch (error) {
                    console.error('Error fetching department programs:', error);
                    programFilter.innerHTML = '<option value="">Error loading programs</option>';
                }
            }

            async function getSubjects(programId = null) {
                try {
                    let url = "/teacher/subjects";
                    if (programId) url = `/teacher/programs/${programId}/subjects`;

                    const response = await fetch(url);
                    if (!response.ok) throw new Error('Failed to fetch subjects');

                    const { data: subjects } = await response.json();

                    // Clear existing options except the first one
                    subjectFilter.innerHTML = '<option value="">All Subjects</option>';

                    subjects.forEach(subject => {
                        const option = document.createElement('option');
                        option.value = subject.id;
                        option.textContent = subject.name;
                        subjectFilter.appendChild(option);
                    });

                } catch (error) {
                    console.error('Error fetching subjects:', error);
                    subjectFilter.innerHTML = '<option value="">Error loading subjects</option>';
                }
            }

            async function getDepartmentSubjects() {
                const programId = programFilter.value;
                subjectFilter.disabled = !programId;

                if (!programId) {
                    subjectFilter.innerHTML = '<option value="">All Subjects</option>';
                    loadAssignments();
                    return;
                }

                try {
                    subjectFilter.innerHTML = '<option value="">Loading subjects...</option>';
                    await getSubjects(programId);
                    loadAssignments();
                } catch (error) {
                    console.error('Error fetching program subjects:', error);
                    subjectFilter.innerHTML = '<option value="">Error loading subjects</option>';
                }
            }

            function resetAllFilters() {
                departmentFilter.value = '';
                programFilter.value = '';
                subjectFilter.value = '';
                statusFilter.value = '';
                searchInput.value = '';

                programFilter.disabled = true;
                subjectFilter.disabled = true;

                loadAssignments();
            }

            async function handleCreateAssignment(e) {
                e.preventDefault();

                try {
                    const formData = new FormData(createAssignmentForm);

                    // Add files to form data
                    const files = assignmentFiles.files;
                    for (let i = 0; i < files.length; i++) {
                        formData.append('attachments[]', files[i]);
                    }

                    const response = await fetch('/teacher/assignment', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });

                    if (!response.ok) {
                        const errorData = await response.json();
                        throw new Error(errorData.message || 'Failed to create assignment');
                    }

                    const assignment = await response.json();
                    toggleModal(createAssignmentModal);
                    loadAssignments();
                    alert('Assignment created successfully!');

                    // Reset form
                    createAssignmentForm.reset();
                    filePreview.innerHTML = '';
                    filePreview.classList.add('hidden');

                } catch (error) {
                    console.error('Error creating assignment:', error);
                    alert(`Error: ${error.message}`);
                }
            }

            async function handleDeleteAssignment() {
                const assignmentId = deleteConfirmationModal.getAttribute('data-assignment-id');

                try {
                    const response = await fetch(`/teacher/assignment/${assignmentId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    });

                    if (!response.ok) throw new Error('Failed to delete assignment');

                    toggleModal(deleteConfirmationModal);
                    loadAssignments();
                    alert('Assignment deleted successfully!');

                } catch (error) {
                    console.error('Error deleting assignment:', error);
                    alert('Failed to delete assignment');
                }
            }

            function handleFileUpload() {
                if (this.files.length > 0) {
                    filePreview.classList.remove('hidden');
                    filePreview.innerHTML = '';

                    Array.from(this.files).forEach(file => {
                        const fileItem = document.createElement('div');
                        fileItem.className = 'p-2 bg-gray-50 dark:bg-gray-700 rounded-md mb-2';
                        fileItem.innerHTML = `
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="fas fa-file mr-2 text-gray-500"></i>
                                <span class="text-sm text-gray-700 dark:text-gray-300">${file.name}</span>
                            </div>
                            <button type="button" class="text-red-500 hover:text-red-700">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    `;
                        filePreview.appendChild(fileItem);

                        // Remove file button
                        const removeBtn = fileItem.querySelector('button');
                        removeBtn.addEventListener('click', function() {
                            fileItem.remove();
                            if (filePreview.children.length === 0) {
                                filePreview.classList.add('hidden');
                            }
                        });
                    });
                }
            }

            function toggleModal(modal) {
                modal.classList.toggle('hidden');
                document.body.style.overflow = modal.classList.contains('hidden') ? '' : 'hidden';
            }

            function updatePagination(meta) {
                // Implement pagination update logic here
                // You'll need to update the pagination controls based on the meta data
                // This is just a placeholder implementation
                const totalElement = document.querySelector('.card .text-sm.text-gray-500.dark\\:text-gray-400');
                if (totalElement && meta) {
                    totalElement.textContent = `Total: ${meta.total} assignments`;
                }
            }

            function debounce(func, wait) {
                let timeout;
                return function() {
                    const context = this, args = arguments;
                    clearTimeout(timeout);
                    timeout = setTimeout(() => func.apply(context, args), wait);
                };
            }
        });
    </script>
@endsection
