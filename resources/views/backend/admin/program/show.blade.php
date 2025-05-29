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
    Program Details
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
            .tab-button {
                @apply px-4 py-2 font-medium text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 border-b-2 border-transparent hover:border-primary-500 transition-colors duration-200;
            }
            .tab-button.active {
                @apply text-primary-600 dark:text-primary-400 border-primary-500;
            }
            .tab-content {
                @apply hidden py-4;
            }
            .tab-content.active {
                @apply block;
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

        <!-- Breadcrumb -->
        <nav class="mb-4 flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-primary-600 dark:text-gray-400 dark:hover:text-white">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path></svg>
                        Dashboard
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                        <a href="{{ route('admin.programs.index') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-primary-600 md:ml-2 dark:text-gray-400 dark:hover:text-white">Programs</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">{{ $program->name }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Action Bar -->
        <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">{{ $program->name }}</h1>
            <div class="flex gap-2">
                <button class="edit-program-btn btn-primary flex items-center justify-center">
                    <i class="fas fa-edit mr-2"></i> Edit Program
                </button>
                <button id="deleteBtn" class="btn-danger flex items-center justify-center">
                    <i class="fas fa-trash-alt mr-2"></i> Delete
                </button>
                <a href="{{ route('admin.programs.index') }}" class="btn-secondary flex items-center justify-center">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Programs
                </a>
            </div>
        </div>

        <!-- Program Details Card -->
        <div class="card mb-6">
            <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4 border-b dark:border-gray-600">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Program Information</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Program Name</h3>
                        <p class="text-base font-medium text-gray-800 dark:text-white">{{ $program->name }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Department</h3>
                        <p class="text-base font-medium text-gray-800 dark:text-white">{{ $program->department->name }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Total Semesters</h3>
                        <p class="text-base font-medium text-gray-800 dark:text-white">{{ $program->total_semesters }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Duration (Years)</h3>
                        <p class="text-base font-medium text-gray-800 dark:text-white">{{ $program->duration }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Status</h3>
                        <div>
                            @if($program->status == "active")
                                <span class="badge bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">Active</span>
                            @endif

                            @if($program->status == "inactive")
                                <span class="badge bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">Inactive</span>
                            @endif
                        </div>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Created By</h3>
                        <p class="text-base font-medium text-gray-800 dark:text-white">
                            {{ $program->creator ? $program->creator->fname . ' ' . $program->creator->lname : 'N/A' }}
                        </p>
                    </div>
                </div>

                @if($program->description)
                    <div class="mt-6">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Description</h3>
                        <p class="text-gray-700 dark:text-gray-300">{{ $program->description }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Tabs Navigation -->
        <div class="card">
            <div class="border-b dark:border-gray-600">
                <nav class="flex overflow-x-auto" aria-label="Tabs">
                    <button class="tab-button active" data-tab="batches">
                        <i class="fas fa-layer-group mr-2"></i> Batches <span class="ml-1 text-xs rounded-full bg-gray-100 dark:bg-gray-700 px-2 py-0.5">{{ $program->batches->count() }}</span>
                    </button>
                    <button class="tab-button" data-tab="sections">
                        <i class="fas fa-th-large mr-2"></i> Sections <span class="ml-1 text-xs rounded-full bg-gray-100 dark:bg-gray-700 px-2 py-0.5">{{ $program->sections->count() }}</span>
                    </button>
                    <button class="tab-button" data-tab="subjects">
                        <i class="fas fa-book mr-2"></i> Subjects <span class="ml-1 text-xs rounded-full bg-gray-100 dark:bg-gray-700 px-2 py-0.5">{{ $program->subjects->count() }}</span>
                    </button>
                    <button class="tab-button" data-tab="assignments">
{{--                        <i class="fas fa-tasks mr-2"></i> Assignments <span class="ml-1 text-xs rounded-full bg-gray-100 dark:bg-gray-700 px-2 py-0.5">{{ $program->assignments->count() }}</span>--}}
                    </button>
                </nav>
            </div>

            <!-- Tab Contents -->
            <div class="p-6">
                <!-- Batches Tab -->
                <div id="batches-tab" class="tab-content active">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Program Batches</h3>
                        <button id="addBatchBtn" class="btn-primary flex items-center text-sm">
                            <i class="fas fa-plus mr-2"></i> Add Batch
                        </button>
                    </div>

                    @if($program->batches->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="table-header">Batch</th>
                                    <th scope="col" class="table-header">Semester</th>
                                    <th scope="col" class="table-header">Status</th>
                                    <th scope="col" class="table-header">Actions</th>
                                </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($program->batches as $batch)
                                    <tr>
                                        <td class="table-cell font-medium">{{ $batch->batch }}</td>
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
                                                <button class="edit-batch-btn p-1.5 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-full" data-id="{{ $batch->id }}" aria-label="Edit batch">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="delete-batch-btn p-1.5 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-full" data-id="{{ $batch->id }}" aria-label="Delete batch">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="mb-3 text-gray-400 dark:text-gray-500">
                                <i class="fas fa-layer-group text-5xl"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-700 dark:text-gray-300 mb-2">No Batches Found</h3>
                            <p class="text-gray-500 dark:text-gray-400 mb-4">There are no batches associated with this program yet.</p>
                            <button id="createBatchBtn" class="btn-primary">
                                <i class="fas fa-plus mr-2"></i> Create First Batch
                            </button>
                        </div>
                    @endif
                </div>

                <!-- Sections Tab -->
                <div id="sections-tab" class="tab-content">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Program Sections</h3>
                        <button id="addSectionBtn" class="btn-primary flex items-center text-sm">
                            <i class="fas fa-plus mr-2"></i> Add Section
                        </button>
                    </div>

                    @if($program->sections->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="table-header">Section Name</th>
                                    <th scope="col" class="table-header">Status</th>
                                    <th scope="col" class="table-header">Actions</th>
                                </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($program->sections as $section)
                                    <tr>
                                        <td class="table-cell font-medium">{{ $section->section_name }}</td>
                                        <td class="table-cell">
                                            @if($section->status == 1)
                                                <span class="badge bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">Active</span>
                                            @else
                                                <span class="badge bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">Inactive</span>
                                            @endif
                                        </td>
                                        <td class="table-cell">
                                            <div class="flex items-center space-x-2">
                                                <button class="edit-section-btn p-1.5 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-full" data-id="{{ $section->id }}" aria-label="Edit section">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="delete-section-btn p-1.5 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-full" data-id="{{ $section->id }}" aria-label="Delete section">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="mb-3 text-gray-400 dark:text-gray-500">
                                <i class="fas fa-th-large text-5xl"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-700 dark:text-gray-300 mb-2">No Sections Found</h3>
                            <p class="text-gray-500 dark:text-gray-400 mb-4">There are no sections associated with this program yet.</p>
                            <button id="createSectionBtn" class="btn-primary">
                                <i class="fas fa-plus mr-2"></i> Create First Section
                            </button>
                        </div>
                    @endif
                </div>

                <!-- Subjects Tab -->
                <div id="subjects-tab" class="tab-content">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Program Subjects</h3>

                    </div>

                    @if($program->subjects->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="table-header">Subject Name</th>
                                    <th scope="col" class="table-header">Subject Code</th>
                                    <th scope="col" class="table-header">Semester</th>
                                    <th scope="col" class="table-header">Credit Hours</th>
                                    <th scope="col" class="table-header">Status</th>
                                    <th scope="col" class="table-header">Actions</th>
                                </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($program->subjects as $subject)
                                    <tr>
                                        <td class="table-cell font-medium">{{ $subject->name }}</td>
                                        <td class="table-cell">{{ $subject->code }}</td>
                                        <td class="table-cell">{{ $subject->semester }}</td>
                                        <td class="table-cell">{{ $subject->credit }}</td>
                                        <td class="table-cell">
                                            @if($subject->status == 1)
                                                <span class="badge bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">Active</span>
                                            @else
                                                <span class="badge bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">Inactive</span>
                                            @endif
                                        </td>
                                        <td class="table-cell">
                                            <div class="flex items-center space-x-2">
                                                <button class="edit-subject-btn p-1.5 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-full" data-id="{{ $subject->id }}" aria-label="Edit subject">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="delete-subject-btn p-1.5 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-full" data-id="{{ $subject->id }}" aria-label="Delete subject">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="mb-3 text-gray-400 dark:text-gray-500">
                                <i class="fas fa-book text-5xl"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-700 dark:text-gray-300 mb-2">No Subjects Found</h3>
                            <p class="text-gray-500 dark:text-gray-400 mb-4">There are no subjects associated with this program yet.</p>
                            <button id="createSubjectBtn" class="btn-primary">
                                <i class="fas fa-plus mr-2"></i> Create First Subject
                            </button>
                        </div>
                    @endif
                </div>

                <!-- Assignments Tab -->
                <div id="assignments-tab" class="tab-content">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Program Assignments</h3>
                        <button id="addAssignmentBtn" class="btn-primary flex items-center text-sm">
                            <i class="fas fa-plus mr-2"></i> Add Assignment
                        </button>
                    </div>

            </div>
        </div>
        </div>
    </main>
@endsection

@section("modals")
    <!-- Edit Program Modal -->
    <div id="editProgramModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
        <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-white">Edit Program</h3>
                    <button id="closeEditProgramModal" class="p-1 rounded-md text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form id="editProgramForm">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label for="programName" class="form-label">Program Name</label>
                        <input type="text" id="programName" name="name" class="form-input" value="{{ $program->name }}" required>
                        <input type="hidden" id="programId" name="programId" value="{{ $program->id }}">
                    </div>

                    <div class="mb-4">
                        <label for="department" class="form-label">Department</label>
                        <select id="department" name="department_id" class="form-input" required>
                            @foreach($allDepartments as $department)
                                <option value="{{ $department->id }}" {{ $program->department_id == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="totalSemesters" class="form-label">Total Semesters</label>
                            <input type="number" id="totalSemesters" name="total_semesters" class="form-input" value="{{ $program->total_semesters }}" required>
                        </div>
                        <div>
                            <label for="duration" class="form-label">Duration (Years)</label>
                            <input type="number" id="duration" name="duration" class="form-input" value="{{ $program->duration }}" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="status" class="form-label">Status</label>
                        <select id="status" name="status" class="form-input" required>
                            <option value="active" {{ $program->status == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $program->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label">Description</label>
                        <textarea id="description" name="description" class="form-input" rows="3">{{ $program->description }}</textarea>
                    </div>

                    <div class="flex justify-end space-x-2">
                        <button type="button" class="btn-secondary" onclick="closeEditProgramModal()">Cancel</button>
                        <button type="submit" class="btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Batch Modal -->
    <div id="editBatchModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
        <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full mx-4">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-white">Edit Batch</h3>
                    <button id="closeEditBatchModal" class="p-1 rounded-md text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form id="editBatchForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="editBatchId" name="id">

                    <div class="mb-4">
                        <label for="batchName" class="form-label">Batch Name</label>
                        <input type="text" id="batchName" name="batch" class="form-input" required>
                    </div>

                    <div class="mb-4">
                        <label for="batchSemester" class="form-label">Semester</label>
                        <select id="batchSemester" name="semester" class="form-input" required>
                            @for($i = 1; $i <= $program->total_semesters; $i++)
                                <option value="{{ $i }}">Semester {{ $i }}</option>
                            @endfor
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="batchStatus" class="form-label">Status</label>
                        <select id="batchStatus" name="status" class="form-input" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>

                    <div class="flex justify-end space-x-2">
                        <button type="button" class="btn-secondary" onclick="closeEditBatchModal()">Cancel</button>
                        <button type="submit" class="btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Section Modal -->
    <div id="editSectionModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
        <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full mx-4">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-white">Edit Section</h3>
                    <button id="closeEditSectionModal" class="p-1 rounded-md text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form id="editSectionForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="editSectionId" name="id">

                    <div class="mb-4">
                        <label for="sectionName" class="form-label">Section Name</label>
                        <input type="text" id="sectionName" name="section_name" class="form-input" required>
                    </div>

                    <div class="mb-4">
                        <label for="sectionStatus" class="form-label">Status</label>
                        <select id="sectionStatus" name="status" class="form-input" required>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>

                    <div class="flex justify-end space-x-2">
                        <button type="button" class="btn-secondary" onclick="closeEditSectionModal()">Cancel</button>
                        <button type="submit" class="btn-primary">Save Changes</button>
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

                <p id="deleteConfirmationText" class="text-gray-600 dark:text-gray-400 mb-6">Are you sure you want to delete this item? This action cannot be undone.</p>

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
            // Tab Switching Functionality
            const tabButtons = document.querySelectorAll('.tab-button');
            const tabContents = document.querySelectorAll('.tab-content');
            const editProgramModal = document.getElementById('editProgramModal');
            const closeEditProgramModal = document.getElementById('closeEditProgramModal');
            const editProgramForm = document.getElementById('editProgramForm');
            const deleteBtn = document.getElementById('deleteBtn');
            const deleteModal = document.getElementById('deleteModal');
            const closeDeleteModal = document.getElementById('closeDeleteModal');
            const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
            const programId = document.getElementById('programId').value;


            document.querySelector('.edit-program-btn').addEventListener('click', () => {
                editProgramModal.classList.remove('hidden');
            });

            closeEditProgramModal.addEventListener('click', () => {
                editProgramModal.classList.add('hidden');
            });

            editProgramForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                const formData = new FormData(editProgramForm);

                console.log(editProgramForm);

                try {
                    const response = await fetch(`/admin/program/${programId}`, {
                        method: 'PUT',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });

                    const data = await response.json();

                    if (data.success) {
                        Toast.fire({
                            icon: 'success',
                            title: 'Program updated successfully'
                        });
                        location.reload();
                    } else {
                        throw new Error(data.message || 'Failed to update program');
                    }
                } catch (error) {
                    Toast.fire({
                        icon: 'error',
                        title: error.message
                    });
                }
            });

            // Edit Batch Modal Functionality
            const editBatchModal = document.getElementById('editBatchModal');
            const closeEditBatchModal = document.getElementById('closeEditBatchModal');
            const editBatchForm = document.getElementById('editBatchForm');

            document.querySelectorAll('.edit-batch-btn').forEach(btn => {
                btn.addEventListener('click', async () => {
                    const batchId = btn.getAttribute('data-id');
                    try {
                        const response = await fetch(`/admin/batches/${batchId}/edit`);
                        const data = await response.json();

                        document.getElementById('editBatchId').value = data.id;
                        document.getElementById('batchName').value = data.batch;
                        document.getElementById('batchSemester').value = data.semester;
                        document.getElementById('batchStatus').value = data.status;

                        editBatchModal.classList.remove('hidden');
                    } catch (error) {
                        Toast.fire({
                            icon: 'error',
                            title: 'Failed to load batch data'
                        });
                    }
                });
            });

            closeEditBatchModal.addEventListener('click', () => {
                editBatchModal.classList.add('hidden');
            });

            editBatchForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                const batchId = document.getElementById('editBatchId').value;
                const formData = new FormData(editBatchForm);

                try {
                    const response = await fetch(`/admin/batches/${batchId}`, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });

                    const data = await response.json();

                    if (data.success) {
                        Toast.fire({
                            icon: 'success',
                            title: 'Batch updated successfully'
                        });
                        location.reload();
                    } else {
                        throw new Error(data.message || 'Failed to update batch');
                    }
                } catch (error) {
                    Toast.fire({
                        icon: 'error',
                        title: error.message
                    });
                }
            });

            // Edit Section Modal Functionality
            const editSectionModal = document.getElementById('editSectionModal');
            const closeEditSectionModal = document.getElementById('closeEditSectionModal');
            const editSectionForm = document.getElementById('editSectionForm');

            document.querySelectorAll('.edit-section-btn').forEach(btn => {
                btn.addEventListener('click', async () => {
                    const sectionId = btn.getAttribute('data-id');
                    try {
                        const response = await fetch(`/admin/sections/${sectionId}/edit`);
                        const data = await response.json();

                        document.getElementById('editSectionId').value = data.id;
                        document.getElementById('sectionName').value = data.section_name;
                        document.getElementById('sectionStatus').value = data.status;

                        editSectionModal.classList.remove('hidden');
                    } catch (error) {
                        Toast.fire({
                            icon: 'error',
                            title: 'Failed to load section data'
                        });
                    }
                });
            });

            closeEditSectionModal.addEventListener('click', () => {
                editSectionModal.classList.add('hidden');
            });

            editSectionForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                const sectionId = document.getElementById('editSectionId').value;
                const formData = new FormData(editSectionForm);

                try {
                    const response = await fetch(`/admin/sections/${sectionId}`, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });

                    const data = await response.json();

                    if (data.success) {
                        Toast.fire({
                            icon: 'success',
                            title: 'Section updated successfully'
                        });
                        location.reload();
                    } else {
                        throw new Error(data.message || 'Failed to update section');
                    }
                } catch (error) {
                    Toast.fire({
                        icon: 'error',
                        title: error.message
                    });
                }
            });

            // Delete Functionality

            const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
            const deleteConfirmationText = document.getElementById('deleteConfirmationText');

            let deleteType = '';
            let deleteId = '';

            function showDeleteModal(type, id, name) {
                deleteType = type;
                deleteId = id;
                deleteConfirmationText.textContent = `Are you sure you want to delete this ${type}${name ? ` (${name})` : ''}? This action cannot be undone.`;
                deleteModal.classList.remove('hidden');
            }



            closeDeleteModal.addEventListener('click', closeDeleteModal);
            cancelDeleteBtn.addEventListener('click', closeDeleteModal);

            // Delete handlers for different types
            document.querySelectorAll('.delete-batch-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const id = btn.getAttribute('data-id');
                    const name = btn.closest('tr').querySelector('td:first-child').textContent;
                    showDeleteModal('batch', id, name);
                });
            });

            document.querySelectorAll('.delete-section-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const id = btn.getAttribute('data-id');
                    const name = btn.closest('tr').querySelector('td:first-child').textContent;
                    showDeleteModal('section', id, name);
                });
            });

            confirmDeleteBtn.addEventListener('click', async () => {
                if (!deleteType || !deleteId) return;

                try {
                    const response = await fetch(`/admin/${deleteType}s/${deleteId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    });

                    const data = await response.json();

                    if (data.success) {
                        Toast.fire({
                            icon: 'success',
                            title: `${deleteType.charAt(0).toUpperCase() + deleteType.slice(1)} deleted successfully`
                        });
                        location.reload();
                    } else {
                        throw new Error(data.message || `Failed to delete ${deleteType}`);
                    }
                } catch (error) {
                    Toast.fire({
                        icon: 'error',
                        title: error.message
                    });
                } finally {
                    closeDeleteModal();
                }
            });


            tabButtons.forEach(button => {
                button.addEventListener('click', () => {
                    // Remove active class from all buttons and contents
                    tabButtons.forEach(btn => btn.classList.remove('active'));
                    tabContents.forEach(content => content.classList.remove('active'));

                    // Add active class to clicked button
                    button.classList.add('active');

                    // Show corresponding content
                    const tabId = button.getAttribute('data-tab');
                    document.getElementById(`${tabId}-tab`).classList.add('active');
                });
            });

            // Delete Program Modal


            deleteBtn.addEventListener('click', () => {
                deleteModal.classList.remove('hidden');
            });


            cancelDeleteBtn.addEventListener('click', () => {
                deleteModal.classList.add('hidden');
            });

            // Button Click Handlers
            const addBatchBtn = document.getElementById('addBatchBtn');
            if (addBatchBtn) {
                addBatchBtn.addEventListener('click', () => {
                    // Open batch modal or redirect to batch creation page
                    window.location.href = "{{ route('admin.program.batch.create', $program->id) }}";
                });
            }

            const createBatchBtn = document.getElementById('createBatchBtn');
            if (createBatchBtn) {
                createBatchBtn.addEventListener('click', () => {
                    window.location.href = "{{ route('admin.program.batch.create', $program->id) }}";
                });
            }

            const addSectionBtn = document.getElementById('addSectionBtn');
            if (addSectionBtn) {
                addSectionBtn.addEventListener('click', () => {
                    // Open section modal or redirect to section creation page
                    window.location.href = "{{ route('admin.program.section.create', $program->id) }}";
                });
            }

            const createSectionBtn = document.getElementById('createSectionBtn');
            if (createSectionBtn) {
                createSectionBtn.addEventListener('click', () => {
                    window.location.href = "{{ route('admin.program.section.create', $program->id) }}";
                });
            }


            const createSubjectBtn = document.getElementById('createSubjectBtn');
            if (createSubjectBtn) {
                createSubjectBtn.addEventListener('click', () => {
{{--                    window.location.href = "{{ route('admin.program.subjects.create', $program->id) }}";--}}
                });
            }

            const addAssignmentBtn = document.getElementById('addAssignmentBtn');
            if (addAssignmentBtn) {
                addAssignmentBtn.addEventListener('click', () => {
                    // Open assignment modal or redirect to assignment creation page

                });
            }

            const createAssignmentBtn = document.getElementById('createAssignmentBtn');
            if (createAssignmentBtn) {
                createAssignmentBtn.addEventListener('click', () => {

                });
            }

            // Edit/Delete buttons for each item
            document.querySelectorAll('.edit-batch-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const batchId = this.getAttribute('data-id');
                    window.location.href = `/admin/batches/${batchId}/edit`;
                });
            });

            document.querySelectorAll('.delete-batch-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const batchId = this.getAttribute('data-id');
                    if (confirm('Are you sure you want to delete this batch?')) {
                        // Send delete request
                        fetch(`/admin/batches/${batchId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            }
                        })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    // Remove the row or refresh the page
                                    this.closest('tr').remove();
                                    Toast.fire({
                                        icon: 'success',
                                        title: 'Batch deleted successfully'
                                    });
                                } else {
                                    throw new Error(data.message || 'Failed to delete batch');
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                Toast.fire({
                                    icon: 'error',
                                    title: error.message || 'An error occurred'
                                });
                            });
                    }
                });
            });

            // Similar event listeners for section, subject and assignment buttons
            // You would add similar code for the other entity types
        });
    </script>
@endsection
