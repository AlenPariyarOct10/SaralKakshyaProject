@extends("backend.layout.teacher-dashboard-layout")

@section('title', 'Manage Assignments')

@section('content')
    <!-- Main Content Area -->
    <main class="p-4 md:p-6">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-800 dark:text-white mb-4 md:mb-0">
                Manage Assignments
            </h2>
            <a href="{{route('teacher.assignment.create')}}" class="btn-primary flex items-center">
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
                        <option value="science">Science</option>
                        <option value="arts">Arts</option>
                        <option value="engineering">Engineering</option>
                        <option value="business">Business</option>
                    </select>
                </div>

                <div class="relative">
                    <label for="programFilter" class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 block">Program</label>
                    <select id="programFilter" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                        <option value="">All Programs</option>
                        <option value="undergraduate">Undergraduate</option>
                        <option value="postgraduate">Postgraduate</option>
                        <option value="diploma">Diploma</option>
                        <option value="certificate">Certificate</option>
                    </select>
                </div>

                <div class="relative">
                    <label for="subjectFilter" class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 block">Subject</label>
                    <select id="subjectFilter" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                        <option value="">All Subjects</option>
                        <option value="math">Mathematics</option>
                        <option value="physics">Physics</option>
                        <option value="cs">Computer Science</option>
                        <option value="history">History</option>
                        <option value="literature">Literature</option>
                        <option value="biology">Biology</option>
                        <option value="chemistry">Chemistry</option>
                    </select>
                </div>

                <div class="relative">
                    <label for="statusFilter" class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 block">Status</label>
                    <select id="statusFilter" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                        <option value="">All Status</option>
                        <option value="draft">Draft</option>
                        <option value="published">Published</option>
                        <option value="archived">Archived</option>
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
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Due Date</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Submissions</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    <!-- Row 1 -->
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-800 dark:text-white">Linear Algebra Problem Set</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500 dark:text-gray-400">Mathematics</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500 dark:text-gray-400">Science</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500 dark:text-gray-400">Undergraduate</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500 dark:text-gray-400">May 15, 2023</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 dark:bg-green-800 text-green-800 dark:text-green-100">Published</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500 dark:text-gray-400">18/25</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex space-x-2">
                                <button class="text-primary-600 hover:text-primary-800" title="View Assignment">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="text-yellow-600 hover:text-yellow-800" title="Edit Assignment">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="text-red-600 hover:text-red-800" title="Delete Assignment">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Row 2 -->
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-800 dark:text-white">Mechanics Lab Report</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500 dark:text-gray-400">Physics</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500 dark:text-gray-400">Science</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500 dark:text-gray-400">Undergraduate</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500 dark:text-gray-400">May 18, 2023</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 dark:bg-green-800 text-green-800 dark:text-green-100">Published</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500 dark:text-gray-400">12/22</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex space-x-2">
                                <button class="text-primary-600 hover:text-primary-800" title="View Assignment">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="text-yellow-600 hover:text-yellow-800" title="Edit Assignment">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="text-red-600 hover:text-red-800" title="Delete Assignment">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Row 3 -->
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-800 dark:text-white">Algorithm Implementation</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500 dark:text-gray-400">Computer Science</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500 dark:text-gray-400">Engineering</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500 dark:text-gray-400">Undergraduate</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500 dark:text-gray-400">May 10, 2023</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 dark:bg-green-800 text-green-800 dark:text-green-100">Published</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500 dark:text-gray-400">20/20</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex space-x-2">
                                <button class="text-primary-600 hover:text-primary-800" title="View Assignment">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="text-yellow-600 hover:text-yellow-800" title="Edit Assignment">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="text-red-600 hover:text-red-800" title="Delete Assignment">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Row 4 -->
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-800 dark:text-white">Literary Analysis Essay</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500 dark:text-gray-400">Literature</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500 dark:text-gray-400">Arts</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500 dark:text-gray-400">Undergraduate</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500 dark:text-gray-400">May 25, 2023</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 dark:bg-blue-800 text-blue-800 dark:text-blue-100">Draft</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500 dark:text-gray-400">0/30</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex space-x-2">
                                <button class="text-primary-600 hover:text-primary-800" title="View Assignment">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="text-yellow-600 hover:text-yellow-800" title="Edit Assignment">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="text-red-600 hover:text-red-800" title="Delete Assignment">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Row 5 -->
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-800 dark:text-white">Business Case Study</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500 dark:text-gray-400">Business Administration</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500 dark:text-gray-400">Business</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500 dark:text-gray-400">Postgraduate</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500 dark:text-gray-400">June 5, 2023</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 dark:bg-blue-800 text-blue-800 dark:text-blue-100">Draft</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500 dark:text-gray-400">0/15</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex space-x-2">
                                <button class="text-primary-600 hover:text-primary-800" title="View Assignment">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="text-yellow-600 hover:text-yellow-800" title="Edit Assignment">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="text-red-600 hover:text-red-800" title="Delete Assignment">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Row 6 -->
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-800 dark:text-white">Gene Expression Analysis</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500 dark:text-gray-400">Biology</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500 dark:text-gray-400">Science</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500 dark:text-gray-400">Postgraduate</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500 dark:text-gray-400">April 30, 2023</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 dark:bg-gray-600 text-gray-800 dark:text-gray-100">Archived</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500 dark:text-gray-400">12/12</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex space-x-2">
                                <button class="text-primary-600 hover:text-primary-800" title="View Assignment">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="text-yellow-600 hover:text-yellow-800" title="Edit Assignment">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="text-red-600 hover:text-red-800" title="Delete Assignment">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
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
            // Create Assignment Modal
            const createAssignmentBtn = document.getElementById('createAssignmentBtn');
            const createAssignmentModal = document.getElementById('createAssignmentModal');
            const closeCreateModal = document.getElementById('closeCreateModal');
            const cancelCreateAssignment = document.getElementById('cancelCreateAssignment');
            const createAssignmentForm = document.getElementById('createAssignmentForm');

            // Delete Confirmation Modal
            const deleteConfirmationModal = document.getElementById('deleteConfirmationModal');
            const closeDeleteModal = document.getElementById('closeDeleteModal');
            const cancelDelete = document.getElementById('cancelDelete');
            const confirmDelete = document.getElementById('confirmDelete');

            // View Assignment Modal
            const viewAssignmentModal = document.getElementById('viewAssignmentModal');
            const closeViewModal = document.getElementById('closeViewModal');
            const closeViewAssignment = document.getElementById('closeViewAssignment');
            const editViewedAssignment = document.getElementById('editViewedAssignment');
            const viewSubmissions = document.getElementById('viewSubmissions');

            // Show Create Assignment Modal
            if (createAssignmentBtn) {
                createAssignmentBtn.addEventListener('click', () => {
                    createAssignmentModal.classList.remove('hidden');
                });
            }

            // Close Create Assignment Modal
            if (closeCreateModal) {
                closeCreateModal.addEventListener('click', () => {
                    createAssignmentModal.classList.add('hidden');
                });
            }

            // Cancel Create Assignment Modal
            if (cancelCreateAssignment) {
                cancelCreateAssignment.addEventListener('click', () => {
                    createAssignmentModal.classList.add('hidden');
                });
            }

            // Handle form submission
            if (createAssignmentForm) {
                createAssignmentForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    // Add form submission logic here

                    // Close modal after submission
                    createAssignmentModal.classList.add('hidden');

                    // Show success message
                    alert('Assignment created successfully!');
                });
            }

            // Close Delete Confirmation Modal
            if (closeDeleteModal) {
                closeDeleteModal.addEventListener('click', () => {
                    deleteConfirmationModal.classList.add('hidden');
                });
            }

            // Cancel Delete Confirmation Modal
            if (cancelDelete) {
                cancelDelete.addEventListener('click', () => {
                    deleteConfirmationModal.classList.add('hidden');
                });
            }

            // Confirm Delete
            if (confirmDelete) {
                confirmDelete.addEventListener('click', () => {
                    // Add delete logic here

                    // Close modal after deletion
                    deleteConfirmationModal.classList.add('hidden');

                    // Show success message
                    alert('Assignment deleted successfully!');
                });
            }

            // Close View Assignment Modal
            if (closeViewModal) {
                closeViewModal.addEventListener('click', () => {
                    viewAssignmentModal.classList.add('hidden');
                });
            }

            // Close View Assignment Button
            if (closeViewAssignment) {
                closeViewAssignment.addEventListener('click', () => {
                    viewAssignmentModal.classList.add('hidden');
                });
            }

            // Edit viewed assignment
            if (editViewedAssignment) {
                editViewedAssignment.addEventListener('click', () => {
                    viewAssignmentModal.classList.add('hidden');
                    // Show edit modal or redirect to edit page
                    // For now, just show the create modal as an example
                    createAssignmentModal.classList.remove('hidden');
                });
            }

            // View submissions
            if (viewSubmissions) {
                viewSubmissions.addEventListener('click', () => {
                    // Redirect to submissions page or show submissions modal
                    alert('Redirecting to submissions page...');
                });
            }

            // Delete Buttons Event Listeners
            document.querySelectorAll('[title="Delete Assignment"]').forEach(button => {
                button.addEventListener('click', () => {
                    deleteConfirmationModal.classList.remove('hidden');
                });
            });

            // View Buttons Event Listeners
            document.querySelectorAll('[title="View Assignment"]').forEach(button => {
                button.addEventListener('click', () => {
                    viewAssignmentModal.classList.remove('hidden');
                });
            });

            // Edit Buttons Event Listeners
            document.querySelectorAll('[title="Edit Assignment"]').forEach(button => {
                button.addEventListener('click', () => {
                    // Show edit modal or redirect to edit page
                    createAssignmentModal.classList.remove('hidden');
                });
            });

            // File Upload Preview
            const assignmentFiles = document.getElementById('assignmentFiles');
            const filePreview = document.getElementById('filePreview');

            if (assignmentFiles) {
                assignmentFiles.addEventListener('change', function() {
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
                });
            }

            // Filter Reset Button
            const resetFilters = document.getElementById('resetFilters');
            if (resetFilters) {
                resetFilters.addEventListener('click', () => {
                    document.getElementById('departmentFilter').value = '';
                    document.getElementById('programFilter').value = '';
                    document.getElementById('subjectFilter').value = '';
                    document.getElementById('statusFilter').value = '';
                    document.getElementById('searchInput').value = '';
                });
            }
        });
    </script>
@endsection
