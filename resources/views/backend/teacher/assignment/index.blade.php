@extends("backend.layout.teacher-dashboard-layout")

@section('title', 'List Assignments')

@section('content')
<!-- Main Content Area -->
<main class="p-4 md:p-6">
    <!-- Assignment Filters -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="relative">
                <select id="courseFilter" class="w-full md:w-48 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                    <option value="">All Courses</option>
                    <option value="math">Mathematics</option>
                    <option value="physics">Physics</option>
                    <option value="cs">Computer Science</option>
                </select>
            </div>

            <div class="relative">
                <select id="statusFilter" class="w-full md:w-48 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="submitted">Submitted</option>
                    <option value="graded">Graded</option>
                    <option value="overdue">Overdue</option>
                </select>
            </div>
        </div>

        <div class="relative">
            <div class="flex items-center border border-gray-300 rounded-md dark:border-gray-700 overflow-hidden">
                <input type="text" placeholder="Search assignments..." class="w-full px-4 py-2 focus:outline-none dark:bg-gray-800 dark:text-white">
                <button class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Pending Assignments -->
    <div class="card mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Pending Assignments</h3>
        </div>

        <div class="space-y-4">
            <!-- Assignment 1 -->
            <div class="border dark:border-gray-700 rounded-lg overflow-hidden">
                <div class="p-4 bg-gray-50 dark:bg-gray-700 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h4 class="text-md font-medium text-gray-800 dark:text-white">Mathematics - Linear Algebra Problem Set</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Due: May 15, 2023 (2 days left)</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100">Pending</span>
                        <button id="openAssignment1" class="px-3 py-1 text-sm bg-primary-600 text-white rounded-md hover:bg-primary-700">
                            View Details
                        </button>
                    </div>
                </div>

                <!-- Assignment Details (Hidden by default) -->
                <div id="assignment1Details" class="p-4 border-t dark:border-gray-700 hidden">
                    <div class="mb-4">
                        <h5 class="text-sm font-medium text-gray-800 dark:text-white mb-2">Description</h5>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Complete the linear algebra problem set from Chapter 5, problems 1-15. Show all your work and explain your reasoning for each step.
                        </p>
                    </div>

                    <div class="mb-4">
                        <h5 class="text-sm font-medium text-gray-800 dark:text-white mb-2">Resources</h5>
                        <div class="flex flex-wrap gap-2">
                            <a href="#" class="flex items-center px-3 py-1 text-xs bg-gray-100 dark:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-200 dark:hover:bg-gray-500">
                                <i class="fas fa-file-pdf mr-2 text-red-500"></i> Problem_Set.pdf
                            </a>
                            <a href="#" class="flex items-center px-3 py-1 text-xs bg-gray-100 dark:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-200 dark:hover:bg-gray-500">
                                <i class="fas fa-file-video mr-2 text-blue-500"></i> Tutorial_Video.mp4
                            </a>
                        </div>
                    </div>

                    <div>
                        <h5 class="text-sm font-medium text-gray-800 dark:text-white mb-2">Submit Assignment</h5>
                        <div class="flex flex-col gap-4">
                            <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-md p-4 text-center">
                                <input type="file" id="assignment1File" class="hidden">
                                <label for="assignment1File" class="cursor-pointer">
                                    <i class="fas fa-cloud-upload-alt text-2xl text-gray-400 dark:text-gray-500 mb-2"></i>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Drag and drop files here or click to browse</p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Supported formats: PDF, DOC, DOCX, JPG, PNG</p>
                                </label>
                            </div>

                            <div id="filePreview1" class="hidden p-3 bg-gray-50 dark:bg-gray-700 rounded-md">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <i class="fas fa-file-pdf text-red-500 mr-2"></i>
                                        <span class="text-sm text-gray-700 dark:text-gray-300 file-name">filename.pdf</span>
                                    </div>
                                    <button class="text-red-500 hover:text-red-700">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>

                            <button class="btn-primary">Submit Assignment</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Assignment 2 -->
            <div class="border dark:border-gray-700 rounded-lg overflow-hidden">
                <div class="p-4 bg-gray-50 dark:bg-gray-700 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h4 class="text-md font-medium text-gray-800 dark:text-white">Physics - Mechanics Lab Report</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Due: May 18, 2023 (5 days left)</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100">Pending</span>
                        <button id="openAssignment2" class="px-3 py-1 text-sm bg-primary-600 text-white rounded-md hover:bg-primary-700">
                            View Details
                        </button>
                    </div>
                </div>

                <!-- Assignment Details (Hidden by default) -->
                <div id="assignment2Details" class="p-4 border-t dark:border-gray-700 hidden">
                    <div class="mb-4">
                        <h5 class="text-sm font-medium text-gray-800 dark:text-white mb-2">Description</h5>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Write a detailed lab report on the pendulum experiment conducted in class. Include your data, calculations, error analysis, and conclusions.
                        </p>
                    </div>

                    <div class="mb-4">
                        <h5 class="text-sm font-medium text-gray-800 dark:text-white mb-2">Resources</h5>
                        <div class="flex flex-wrap gap-2">
                            <a href="#" class="flex items-center px-3 py-1 text-xs bg-gray-100 dark:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-200 dark:hover:bg-gray-500">
                                <i class="fas fa-file-excel mr-2 text-green-500"></i> Lab_Data.xlsx
                            </a>
                            <a href="#" class="flex items-center px-3 py-1 text-xs bg-gray-100 dark:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-200 dark:hover:bg-gray-500">
                                <i class="fas fa-file-word mr-2 text-blue-500"></i> Report_Template.docx
                            </a>
                        </div>
                    </div>

                    <div>
                        <h5 class="text-sm font-medium text-gray-800 dark:text-white mb-2">Submit Assignment</h5>
                        <div class="flex flex-col gap-4">
                            <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-md p-4 text-center">
                                <input type="file" id="assignment2File" class="hidden">
                                <label for="assignment2File" class="cursor-pointer">
                                    <i class="fas fa-cloud-upload-alt text-2xl text-gray-400 dark:text-gray-500 mb-2"></i>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Drag and drop files here or click to browse</p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Supported formats: PDF, DOC, DOCX, JPG, PNG</p>
                                </label>
                            </div>

                            <div id="filePreview2" class="hidden p-3 bg-gray-50 dark:bg-gray-700 rounded-md">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <i class="fas fa-file-word text-blue-500 mr-2"></i>
                                        <span class="text-sm text-gray-700 dark:text-gray-300 file-name">filename.docx</span>
                                    </div>
                                    <button class="text-red-500 hover:text-red-700">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>

                            <button class="btn-primary">Submit Assignment</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Assignment 3 -->
            <div class="border dark:border-gray-700 rounded-lg overflow-hidden">
                <div class="p-4 bg-gray-50 dark:bg-gray-700 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h4 class="text-md font-medium text-gray-800 dark:text-white">Computer Science - Algorithm Implementation</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Due: May 10, 2023 (<span class="text-red-500">Overdue</span>)</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100">Overdue</span>
                        <button id="openAssignment3" class="px-3 py-1 text-sm bg-primary-600 text-white rounded-md hover:bg-primary-700">
                            View Details
                        </button>
                    </div>
                </div>

                <!-- Assignment Details (Hidden by default) -->
                <div id="assignment3Details" class="p-4 border-t dark:border-gray-700 hidden">
                    <div class="mb-4">
                        <h5 class="text-sm font-medium text-gray-800 dark:text-white mb-2">Description</h5>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Implement the sorting algorithms discussed in class (Bubble Sort, Insertion Sort, and Quick Sort) and compare their performance on different input sizes.
                        </p>
                    </div>

                    <div class="mb-4">
                        <h5 class="text-sm font-medium text-gray-800 dark:text-white mb-2">Resources</h5>
                        <div class="flex flex-wrap gap-2">
                            <a href="#" class="flex items-center px-3 py-1 text-xs bg-gray-100 dark:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-200 dark:hover:bg-gray-500">
                                <i class="fas fa-file-code mr-2 text-purple-500"></i> Starter_Code.py
                            </a>
                            <a href="#" class="flex items-center px-3 py-1 text-xs bg-gray-100 dark:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-200 dark:hover:bg-gray-500">
                                <i class="fas fa-file-csv mr-2 text-green-500"></i> Test_Data.csv
                            </a>
                        </div>
                    </div>

                    <div>
                        <h5 class="text-sm font-medium text-gray-800 dark:text-white mb-2">Submit Assignment</h5>
                        <div class="flex flex-col gap-4">
                            <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-md p-4 text-center">
                                <input type="file" id="assignment3File" class="hidden">
                                <label for="assignment3File" class="cursor-pointer">
                                    <i class="fas fa-cloud-upload-alt text-2xl text-gray-400 dark:text-gray-500 mb-2"></i>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Drag and drop files here or click to browse</p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Supported formats: PDF, DOC, DOCX, PY, ZIP</p>
                                </label>
                            </div>

                            <div id="filePreview3" class="hidden p-3 bg-gray-50 dark:bg-gray-700 rounded-md">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <i class="fas fa-file-code text-purple-500 mr-2"></i>
                                        <span class="text-sm text-gray-700 dark:text-gray-300 file-name">filename.py</span>
                                    </div>
                                    <button class="text-red-500 hover:text-red-700">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="p-3 bg-red-50 dark:bg-red-900 text-red-700 dark:text-red-300 rounded-md text-sm">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                This assignment is overdue. Late submissions may be subject to penalties.
                            </div>

                            <button class="btn-primary">Submit Assignment</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Submitted Assignments -->
    <div class="card">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Submitted Assignments</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Assignment</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Course</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Submitted On</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Grade</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-800 dark:text-white">Calculus Problem Set</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-500 dark:text-gray-400">Mathematics</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-500 dark:text-gray-400">May 5, 2023</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 dark:bg-green-800 text-green-800 dark:text-green-100">Graded</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-800 dark:text-white">92/100</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <button class="text-primary-600 hover:text-primary-800">
                            <i class="fas fa-eye mr-1"></i> View
                        </button>
                    </td>
                </tr>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-800 dark:text-white">Thermodynamics Report</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-500 dark:text-gray-400">Physics</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-500 dark:text-gray-400">April 28, 2023</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 dark:bg-green-800 text-green-800 dark:text-green-100">Graded</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-800 dark:text-white">85/100</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <button class="text-primary-600 hover:text-primary-800">
                            <i class="fas fa-eye mr-1"></i> View
                        </button>
                    </td>
                </tr>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-800 dark:text-white">Data Structures Project</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-500 dark:text-gray-400">Computer Science</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-500 dark:text-gray-400">May 1, 2023</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 dark:bg-blue-800 text-blue-800 dark:text-blue-100">Submitted</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-500 dark:text-gray-400">Pending</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <button class="text-primary-600 hover:text-primary-800">
                            <i class="fas fa-eye mr-1"></i> View
                        </button>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</main>

@endsection
