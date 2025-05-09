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
                @apply px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition-colors duration-200 font-medium text-sm;
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
            .student-avatar {
                @apply h-10 w-10 rounded-full object-cover border-2 border-gray-200 dark:border-gray-700;
            }
            .stats-card {
                @apply p-6 bg-white dark:bg-gray-800 rounded-lg shadow-md transition-all duration-300 hover:shadow-lg;
            }
            .status-active {
                @apply bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100;
            }
            .status-inactive {
                @apply bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300;
            }
            .status-blocked {
                @apply bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100;
            }
            .status-pending {
                @apply bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100;
            }
        }
    </style>
@endpush

@section('content')
    <!-- Main Content Area -->
    <main class="scrollable-content p-4 md:p-6">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <div class="stats-card">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Students</p>
                        <h3 class="text-2xl font-bold text-gray-800 dark:text-white mt-1">1,248</h3>
                    </div>
                    <div class="p-3 bg-primary-50 dark:bg-gray-700 rounded-lg">
                        <i class="fas fa-users text-primary-600 dark:text-primary-400"></i>
                    </div>
                </div>
                <div class="mt-2 flex items-center text-sm">
                    <span class="text-green-500 dark:text-green-400 flex items-center">
                        <i class="fas fa-arrow-up text-xs mr-1"></i> 12%
                    </span>
                    <span class="text-gray-500 dark:text-gray-400 ml-2">from last semester</span>
                </div>
            </div>

            <div class="stats-card">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Active Students</p>
                        <h3 class="text-2xl font-bold text-gray-800 dark:text-white mt-1">1,182</h3>
                    </div>
                    <div class="p-3 bg-green-50 dark:bg-green-900/20 rounded-lg">
                        <i class="fas fa-user-check text-green-600 dark:text-green-400"></i>
                    </div>
                </div>
                <div class="mt-2 flex items-center text-sm">
                    <span class="text-gray-500 dark:text-gray-400">94.7% of total</span>
                </div>
            </div>

            <div class="stats-card">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">New Admissions</p>
                        <h3 class="text-2xl font-bold text-gray-800 dark:text-white mt-1">156</h3>
                    </div>
                    <div class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                        <i class="fas fa-user-plus text-blue-600 dark:text-blue-400"></i>
                    </div>
                </div>
                <div class="mt-2 flex items-center text-sm">
                    <span class="text-green-500 dark:text-green-400 flex items-center">
                        <i class="fas fa-arrow-up text-xs mr-1"></i> 5.3%
                    </span>
                    <span class="text-gray-500 dark:text-gray-400 ml-2">from last year</span>
                </div>
            </div>

            <div class="stats-card">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Gender Ratio</p>
                        <h3 class="text-2xl font-bold text-gray-800 dark:text-white mt-1">52:48</h3>
                    </div>
                    <div class="p-3 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
                        <i class="fas fa-venus-mars text-purple-600 dark:text-purple-400"></i>
                    </div>
                </div>
                <div class="mt-2 flex items-center text-sm">
                    <span class="text-gray-500 dark:text-gray-400">Male:Female</span>
                </div>
            </div>
        </div>

        <!-- Action Bar -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="relative">
                    <input type="text" id="searchStudents" placeholder="Search students..." class="form-input pl-12 pr-4 py-2">
                </div>
                <div>
                    <select id="batchFilter" class="form-input py-2">
                        <option value="">All Batches</option>
                        <option value="2023">Batch 2023</option>
                        <option value="2022">Batch 2022</option>
                        <option value="2021">Batch 2021</option>
                        <option value="2020">Batch 2020</option>
                    </select>
                </div>
                <div>
                    <select id="sectionFilter" class="form-input py-2">
                        <option value="">All Sections</option>
                        <option value="A">Section A</option>
                        <option value="B">Section B</option>
                        <option value="C">Section C</option>
                    </select>
                </div>
                <div>
                    <select id="statusFilter" class="form-input py-2">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="blocked">Blocked</option>
                        <option value="pending">Pending</option>
                    </select>
                </div>
            </div>

            <div class="flex gap-3">
                <a href="{{route('admin.student.download.excel')}}" class="btn-secondary flex items-center justify-center">
                    <i class="fas fa-download mr-2"></i> Export
                </a>
                <a href="{{route('admin.student.unapproved.index')}}" class="btn-primary flex items-center justify-center">
                    <i class="fas fa-user-alt mr-2"></i> View Pending Approvals
                </a>
            </div>
        </div>

        <!-- Students Table -->
        <div class="card">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th scope="col" class="table-header">
                            <div class="flex items-center cursor-pointer">
                                <span>Student</span>
                                <i class="fas fa-sort ml-1 text-gray-400 text-xs"></i>
                            </div>
                        </th>
                        <th scope="col" class="table-header">
                            <div class="flex items-center cursor-pointer">
                                <span>Roll No.</span>
                                <i class="fas fa-sort ml-1 text-gray-400 text-xs"></i>
                            </div>
                        </th>
                        <th scope="col" class="table-header">Email</th>
                        <th scope="col" class="table-header">
                            <div class="flex items-center cursor-pointer">
                                <span>Batch</span>
                                <i class="fas fa-sort ml-1 text-gray-400 text-xs"></i>
                            </div>
                        </th>
                        <th scope="col" class="table-header">Section</th>
                        <th scope="col" class="table-header">
                            <div class="flex items-center cursor-pointer">
                                <span>Admission Date</span>
                                <i class="fas fa-sort ml-1 text-gray-400 text-xs"></i>
                            </div>
                        </th>
                        <th scope="col" class="table-header">Status</th>
                        <th scope="col" class="table-header">Actions</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700" id="studentsTableBody">
                    <!--All Students-->
                    @foreach($students as $student)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors duration-150">
                        <td class="table-cell">
                            <div class="flex items-center">
                                <img class="student-avatar mr-3" src={{$student->profile_picture?asset("/storage/$student->profile_picture"):"https://randomuser.me/api/portraits/men/32.jpg"}} alt="Student">
                                <div>
                                    <div class="font-medium text-gray-800 dark:text-white">{{$student->fname." ".$student->lname}}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ucfirst($student->gender)}}</div>
                                </div>
                            </div>
                        </td>
                        <td class="table-cell">{{$student->roll_number}}</td>
                        <td class="table-cell">{{$student->email}}</td>
                        <td class="table-cell">{{$student->batch}}</td>
                        <td class="table-cell">{{$student->section}}</td>
                        <td class="table-cell">{{$student->admission_date->format("d M, Y")}}</td>
                        <td class="table-cell">
                            @if($student->status)
                                <span class="badge status-active">Active</span>
                            @else
                                <span class="badge status-pending">Pending</span>
                            @endif
                        </td>
                        <td class="table-cell">
                            <div class="flex items-center space-x-2">
                                <button class="view-profile-btn p-1.5 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-full" data-id="1" aria-label="View profile">
                                    <i class="fas fa-eye"></i>
                                </button>
                                @if($student->status)
                                    <button class="block-student-btn p-1.5 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-full" data-id="1" aria-label="Block student">
                                        <i class="fas fa-ban"></i>
                                    </button>
                                @else
                                    <button class="approve-student-btn p-1.5 text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300 hover:bg-green-50 dark:hover:bg-green-900/20 rounded-full" data-id="5" aria-label="Approve student">
                                        <i class="fas fa-check"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
{{--            <div class="px-6 py-4 bg-white dark:bg-gray-800 border-t dark:border-gray-700 flex flex-col sm:flex-row items-center justify-between gap-4">--}}
{{--                <div class="text-sm text-gray-500 dark:text-gray-400">--}}
{{--                    Showing <span class="font-medium text-gray-700 dark:text-gray-300">1</span> to <span class="font-medium text-gray-700 dark:text-gray-300">5</span> of <span class="font-medium text-gray-700 dark:text-gray-300">248</span> students--}}
{{--                </div>--}}
{{--                <div class="flex items-center space-x-1">--}}
{{--                    <button class="p-2 rounded-md text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 disabled:opacity-50 disabled:cursor-not-allowed" disabled aria-label="Previous page">--}}
{{--                        <i class="fas fa-chevron-left"></i>--}}
{{--                    </button>--}}
{{--                    <button class="p-2 rounded-md bg-primary-50 dark:bg-gray-700 text-primary-600 dark:text-primary-400" aria-label="Page 1">1</button>--}}
{{--                    <button class="p-2 rounded-md text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700" aria-label="Page 2">2</button>--}}
{{--                    <button class="p-2 rounded-md text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700" aria-label="Page 3">3</button>--}}
{{--                    <button class="p-2 rounded-md text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700" aria-label="Next page">--}}
{{--                        <i class="fas fa-chevron-right"></i>--}}
{{--                    </button>--}}
{{--                </div>--}}
{{--            </div>--}}
        </div>
    </main>
@endsection

@section("modals")
    <!-- Student Profile Modal -->
    <div id="studentProfileModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
        <div class="absolute inset-0 bg-black bg-opacity-50 backdrop-blur-sm"></div>
        <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-4xl mx-4 max-h-[90vh] overflow-hidden">
            <div class="flex flex-col h-full">
                <div class="flex items-center justify-between p-6 border-b dark:border-gray-700">
                    <h3 id="profileModalTitle" class="text-xl font-semibold text-gray-800 dark:text-white">Student Profile</h3>
                    <button id="closeProfileModal" class="p-1 rounded-md text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div class="overflow-y-auto p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Left Column - Basic Info -->
                        <div class="col-span-1">
                            <div class="flex flex-col items-center text-center mb-6">
                                <img id="studentProfileImage" class="h-32 w-32 rounded-full object-cover border-4 border-gray-200 dark:border-gray-700 mb-4" src="https://randomuser.me/api/portraits/men/32.jpg" alt="Student Profile">
                                <h4 id="studentName" class="text-xl font-bold text-gray-800 dark:text-white">John Smith</h4>
                                <p id="studentEmail" class="text-gray-600 dark:text-gray-400 mt-1">john.smith@example.com</p>
                                <div id="studentStatus" class="mt-3">
                                    <span class="badge status-active px-3 py-1.5">Active</span>
                                </div>
                            </div>

                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                                <h5 class="font-medium text-gray-800 dark:text-white mb-3">Contact Information</h5>
                                <div class="space-y-2">
                                    <div class="flex items-start">
                                        <i class="fas fa-phone-alt text-gray-500 dark:text-gray-400 mt-1 w-5"></i>
                                        <div class="ml-3">
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Phone</p>
                                            <p id="studentPhone" class="text-sm text-gray-800 dark:text-gray-200">+1 (555) 123-4567</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start">
                                        <i class="fas fa-map-marker-alt text-gray-500 dark:text-gray-400 mt-1 w-5"></i>
                                        <div class="ml-3">
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Address</p>
                                            <p id="studentAddress" class="text-sm text-gray-800 dark:text-gray-200">123 College Street, Apt 4B, New York, NY 10001</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start">
                                        <i class="fas fa-user-friends text-gray-500 dark:text-gray-400 mt-1 w-5"></i>
                                        <div class="ml-3">
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Guardian</p>
                                            <p id="guardianName" class="text-sm text-gray-800 dark:text-gray-200">Robert Smith</p>
                                            <p id="guardianPhone" class="text-sm text-gray-600 dark:text-gray-400">+1 (555) 987-6543</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column - Academic Details -->
                        <div class="col-span-1 lg:col-span-2">
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 mb-6">
                                <h5 class="font-medium text-gray-800 dark:text-white mb-3">Academic Information</h5>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Roll Number</p>
                                        <p id="studentRollNumber" class="text-sm font-medium text-gray-800 dark:text-gray-200">CS2023-001</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Batch</p>
                                        <p id="studentBatch" class="text-sm font-medium text-gray-800 dark:text-gray-200">2023</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Section</p>
                                        <p id="studentSection" class="text-sm font-medium text-gray-800 dark:text-gray-200">A</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Admission Date</p>
                                        <p id="studentAdmissionDate" class="text-sm font-medium text-gray-800 dark:text-gray-200">August 15, 2023</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Gender</p>
                                        <p id="studentGender" class="text-sm font-medium text-gray-800 dark:text-gray-200">Male</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Date of Birth</p>
                                        <p id="studentDOB" class="text-sm font-medium text-gray-800 dark:text-gray-200">January 12, 2003</p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 mb-6">
                                <h5 class="font-medium text-gray-800 dark:text-white mb-3">Account Information</h5>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Email Verified At</p>
                                        <p id="emailVerifiedAt" class="text-sm font-medium text-gray-800 dark:text-gray-200">August 16, 2023</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Account Created</p>
                                        <p id="accountCreatedAt" class="text-sm font-medium text-gray-800 dark:text-gray-200">August 15, 2023</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Last Updated</p>
                                        <p id="accountUpdatedAt" class="text-sm font-medium text-gray-800 dark:text-gray-200">August 20, 2023</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Institute ID</p>
                                        <p id="instituteId" class="text-sm font-medium text-gray-800 dark:text-gray-200">CS-NYC-001</p>
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-wrap gap-3 mt-6">
                                <button id="editProfileBtn" class="btn-secondary flex items-center">
                                    <i class="fas fa-pencil-alt mr-2"></i> Edit Profile
                                </button>
                                <button id="resetPasswordBtn" class="btn-secondary flex items-center">
                                    <i class="fas fa-key mr-2"></i> Reset Password
                                </button>
                                <button id="viewAttendanceBtn" class="btn-secondary flex items-center">
                                    <i class="fas fa-calendar-check mr-2"></i> Attendance
                                </button>
                                <button id="viewGradesBtn" class="btn-secondary flex items-center">
                                    <i class="fas fa-graduation-cap mr-2"></i> Grades
                                </button>
                                <button id="viewFeesBtn" class="btn-secondary flex items-center">
                                    <i class="fas fa-receipt mr-2"></i> Fees
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-6 border-t dark:border-gray-700 flex justify-end space-x-3">
                    <button id="closeProfileBtn" class="btn-secondary">Close</button>
                    <button id="blockUnblockBtn" class="btn-danger">
                        <i class="fas fa-ban mr-2"></i> Block Student
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit Student Modal -->
    <div id="studentFormModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
        <div class="absolute inset-0 bg-black bg-opacity-50 backdrop-blur-sm"></div>
        <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-4xl mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 id="formModalTitle" class="text-xl font-semibold text-gray-800 dark:text-white">Add New Student</h3>
                    <button id="closeFormModal" class="p-1 rounded-md text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form id="studentForm">
                    <input type="hidden" id="studentId" value="">

                    <!-- Personal Information Section -->
                    <div class="mb-6">
                        <h4 class="text-lg font-medium text-gray-800 dark:text-white mb-4">Personal Information</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div>
                                <label for="fname" class="form-label">First Name</label>
                                <input type="text" id="fname" name="fname" class="form-input" required>
                            </div>
                            <div>
                                <label for="lname" class="form-label">Last Name</label>
                                <input type="text" id="lname" name="lname" class="form-input" required>
                            </div>
                            <div>
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" id="email" name="email" class="form-input" required>
                            </div>
                            <div>
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="tel" id="phone" name="phone" class="form-input" required>
                            </div>
                            <div>
                                <label for="gender" class="form-label">Gender</label>
                                <select id="gender" name="gender" class="form-input" required>
                                    <option value="">Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div>
                                <label for="dob" class="form-label">Date of Birth</label>
                                <input type="date" id="dob" name="dob" class="form-input" required>
                            </div>
                            <div class="md:col-span-2 lg:col-span-3">
                                <label for="address" class="form-label">Address</label>
                                <textarea id="address" name="address" rows="2" class="form-input" required></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Guardian Information -->
                    <div class="mb-6">
                        <h4 class="text-lg font-medium text-gray-800 dark:text-white mb-4">Guardian Information</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="guardian_name" class="form-label">Guardian Name</label>
                                <input type="text" id="guardian_name" name="guardian_name" class="form-input" required>
                            </div>
                            <div>
                                <label for="guardian_phone" class="form-label">Guardian Phone</label>
                                <input type="tel" id="guardian_phone" name="guardian_phone" class="form-input" required>
                            </div>
                        </div>
                    </div>

                    <!-- Academic Information -->
                    <div class="mb-6">
                        <h4 class="text-lg font-medium text-gray-800 dark:text-white mb-4">Academic Information</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div>
                                <label for="roll_number" class="form-label">Roll Number</label>
                                <input type="text" id="roll_number" name="roll_number" class="form-input" required>
                            </div>
                            <div>
                                <label for="batch" class="form-label">Batch</label>
                                <select id="batch" name="batch" class="form-input" required>
                                    <option value="">Select Batch</option>
                                    <option value="2023">2023</option>
                                    <option value="2022">2022</option>
                                    <option value="2021">2021</option>
                                    <option value="2020">2020</option>
                                </select>
                            </div>
                            <div>
                                <label for="section" class="form-label">Section</label>
                                <select id="section" name="section" class="form-input" required>
                                    <option value="">Select Section</option>
                                    <option value="A">A</option>
                                    <option value="B">B</option>
                                    <option value="C">C</option>
                                </select>
                            </div>
                            <div>
                                <label for="admission_date" class="form-label">Admission Date</label>
                                <input type="date" id="admission_date" name="admission_date" class="form-input" required>
                            </div>
                            <div>
                                <label for="institute_id" class="form-label">Institute ID</label>
                                <input type="text" id="institute_id" name="institute_id" class="form-input" required>
                            </div>
                            <div>
                                <label for="status" class="form-label">Status</label>
                                <select id="status" name="status" class="form-input" required>
                                    <option value="active">Active</option>
                                    <option value="pending">Pending</option>
                                    <option value="blocked">Blocked</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Profile Picture -->
                    <div class="mb-6">
                        <h4 class="text-lg font-medium text-gray-800 dark:text-white mb-4">Profile Picture</h4>
                        <div class="flex items-center space-x-6">
                            <div class="shrink-0">
                                <img id="profilePreview" class="h-16 w-16 object-cover rounded-full" src="https://placehold.co/400?text=Photo" alt="Profile preview">
                            </div>
                            <div class="flex-1">
                                <label class="form-label" for="profile_picture">Upload Photo</label>
                                <input type="file" id="profile_picture" name="profile_picture" accept="image/*" class="form-input pt-1">
                            </div>
                        </div>
                    </div>

                    <!-- Account Information - Only for new students -->
                    <div class="mb-6" id="accountInfoSection">
                        <h4 class="text-lg font-medium text-gray-800 dark:text-white mb-4">Account Information</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="password" class="form-label">Password</label>
                                <input type="password" id="password" name="password" class="form-input" required>
                            </div>
                            <div>
                                <label for="password_confirmation" class="form-label">Confirm Password</label>
                                <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" required>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" id="cancelFormBtn" class="btn-secondary">Cancel</button>
                        <button type="submit" id="saveStudentBtn" class="btn-primary">
                            <i class="fas fa-save mr-2"></i> Save Student
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Block/Unblock Confirmation Modal -->
    <div id="blockConfirmModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
        <div class="absolute inset-0 bg-black bg-opacity-50 backdrop-blur-sm"></div>
        <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full mx-4">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 id="blockModalTitle" class="text-xl font-semibold text-gray-800 dark:text-white">Block Student</h3>
                    <button id="closeBlockModal" class="p-1 rounded-md text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <p id="blockModalMessage" class="text-gray-600 dark:text-gray-400 mb-6">Are you sure you want to block this student? They will lose access to all system resources.</p>

                <div class="mb-4" id="blockReasonContainer">
                    <label for="blockReason" class="form-label">Reason for Blocking (Optional)</label>
                    <textarea id="blockReason" rows="3" class="form-input" placeholder="Enter reason for blocking"></textarea>
                </div>

                <div class="flex justify-end space-x-3">
                    <button id="cancelBlockBtn" class="btn-secondary">Cancel</button>
                    <button id="confirmBlockBtn" class="btn-danger">
                        <i class="fas fa-ban mr-2"></i> <span id="blockBtnText">Block</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Export Options Modal -->
    <div id="exportModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
        <div class="absolute inset-0 bg-black bg-opacity-50 backdrop-blur-sm"></div>
        <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full mx-4">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-white">Export Students</h3>
                    <button id="closeExportModal" class="p-1 rounded-md text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <p class="text-gray-600 dark:text-gray-400 mb-4">Select the export format:</p>

                <div class="space-y-3 mb-6">
                    <div class="flex items-center p-3 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-750 cursor-pointer">
                        <i class="fas fa-file-csv text-green-600 dark:text-green-400 text-2xl mr-3"></i>
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-800 dark:text-white">CSV File</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Export as comma-separated values</p>
                        </div>
                        <input type="radio" name="exportFormat" value="csv" class="h-4 w-4 accent-primary-600" checked>
                    </div>

                    <div class="flex items-center p-3 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-750 cursor-pointer">
                        <i class="fas fa-file-excel text-green-600 dark:text-green-400 text-2xl mr-3"></i>
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-800 dark:text-white">Excel File</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Export as Microsoft Excel spreadsheet</p>
                        </div>
                        <input type="radio" name="exportFormat" value="excel" class="h-4 w-4 accent-primary-600">
                    </div>

                    <div class="flex items-center p-3 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-750 cursor-pointer">
                        <i class="fas fa-file-pdf text-red-600 dark:text-red-400 text-2xl mr-3"></i>
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-800 dark:text-white">PDF File</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Export as PDF document</p>
                        </div>
                        <input type="radio" name="exportFormat" value="pdf" class="h-4 w-4 accent-primary-600">
                    </div>
                </div>

                <div class="flex justify-end space-x-3">
                    <button id="cancelExportBtn" class="btn-secondary">Cancel</button>
                    <button id="confirmExportBtn" class="btn-primary">
                        <i class="fas fa-download mr-2"></i> Export
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section("scripts")
    <script>
        // DOM Elements - Main UI
        const searchInput = document.getElementById('searchStudents');
        const batchFilter = document.getElementById('batchFilter');
        const sectionFilter = document.getElementById('sectionFilter');
        const statusFilter = document.getElementById('statusFilter');
        const exportBtn = document.getElementById('exportBtn');
        const addStudentBtn = document.getElementById('addStudentBtn');

        // DOM Elements - Profile Modal
        const studentProfileModal = document.getElementById('studentProfileModal');
        const closeProfileModal = document.getElementById('closeProfileModal');
        const closeProfileBtn = document.getElementById('closeProfileBtn');
        const blockUnblockBtn = document.getElementById('blockUnblockBtn');

        // DOM Elements - Form Modal
        const studentFormModal = document.getElementById('studentFormModal');
        const formModalTitle = document.getElementById('formModalTitle');
        const closeFormModal = document.getElementById('closeFormModal');
        const cancelFormBtn = document.getElementById('cancelFormBtn');
        const studentForm = document.getElementById('studentForm');
        const studentId = document.getElementById('studentId');
        const accountInfoSection = document.getElementById('accountInfoSection');

        // DOM Elements - Block Confirmation Modal
        const blockConfirmModal = document.getElementById('blockConfirmModal');
        const blockModalTitle = document.getElementById('blockModalTitle');
        const blockModalMessage = document.getElementById('blockModalMessage');
        const blockBtnText = document.getElementById('blockBtnText');
        const closeBlockModal = document.getElementById('closeBlockModal');
        const cancelBlockBtn = document.getElementById('cancelBlockBtn');
        const confirmBlockBtn = document.getElementById('confirmBlockBtn');
        const blockReasonContainer = document.getElementById('blockReasonContainer');

        // DOM Elements - Export Modal
        const exportModal = document.getElementById('exportModal');
        const closeExportModal = document.getElementById('closeExportModal');
        const cancelExportBtn = document.getElementById('cancelExportBtn');
        const confirmExportBtn = document.getElementById('confirmExportBtn');

        // Current student being viewed/edited
        let currentStudent = null;

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            initEvents();
            initImagePreviews();
        });

        // Initialize all event listeners
        function initEvents() {
            // Main UI Events
            searchInput.addEventListener('input', filterStudents);
            batchFilter.addEventListener('change', filterStudents);
            sectionFilter.addEventListener('change', filterStudents);
            statusFilter.addEventListener('change', filterStudents);
            exportBtn.addEventListener('click', showExportModal);
            addStudentBtn.addEventListener('click', showAddStudentForm);

            // View Profile Button Events
            document.querySelectorAll('.view-profile-btn').forEach(btn => {
                btn.addEventListener('click', e => {
                    const studentId = e.currentTarget.getAttribute('data-id');
                    showStudentProfile(studentId);
                });
            });

            // Block/Unblock Button Events
            document.querySelectorAll('.block-student-btn, .unblock-student-btn').forEach(btn => {
                btn.addEventListener('click', e => {
                    const studentId = e.currentTarget.getAttribute('data-id');
                    const isBlocked = e.currentTarget.classList.contains('unblock-student-btn');
                    showBlockConfirmation(studentId, isBlocked);
                });
            });

            // Profile Modal Events
            closeProfileModal.addEventListener('click', closeModals);
            closeProfileBtn.addEventListener('click', closeModals);
            blockUnblockBtn.addEventListener('click', () => {
                closeModals();
                showBlockConfirmation(currentStudent, currentStudent.status === 'blocked');
            });

            // Form Modal Events
            closeFormModal.addEventListener('click', closeModals);
            cancelFormBtn.addEventListener('click', closeModals);
            studentForm.addEventListener('submit', handleFormSubmit);

            // Block Confirmation Modal Events
            closeBlockModal.addEventListener('click', closeModals);
            cancelBlockBtn.addEventListener('click', closeModals);
            confirmBlockBtn.addEventListener('click', handleBlockConfirm);

            // Export Modal Events
            closeExportModal.addEventListener('click', closeModals);
            cancelExportBtn.addEventListener('click', closeModals);
            confirmExportBtn.addEventListener('click', handleExport);
        }

        // Initialize Image Preview for file upload
        function initImagePreviews() {
            const profilePicture = document.getElementById('profile_picture');
            const profilePreview = document.getElementById('profilePreview');

            profilePicture.addEventListener('change', function(e) {
                if (e.target.files.length > 0) {
                    const file = e.target.files[0];
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        profilePreview.src = e.target.result;
                    };

                    reader.readAsDataURL(file);
                }
            });
        }

        // Filter students based on search and filter inputs
        function filterStudents() {
            const searchTerm = searchInput.value.toLowerCase();
            const batch = batchFilter.value;
            const section = sectionFilter.value;
            const status = statusFilter.value;

            const rows = document.querySelectorAll('#studentsTableBody tr');

            rows.forEach(row => {
                const studentName = row.querySelector('td:first-child div div:first-child').textContent.toLowerCase();
                const studentEmail = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                const studentRollNo = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                const studentBatch = row.querySelector('td:nth-child(4)').textContent;
                const studentSection = row.querySelector('td:nth-child(5)').textContent;
                const studentStatus = row.querySelector('td:nth-child(7) span').textContent.toLowerCase();

                const matchesSearch = studentName.includes(searchTerm) ||
                    studentEmail.includes(searchTerm) ||
                    studentRollNo.includes(searchTerm);

                const matchesBatch = batch === '' || studentBatch === batch;
                const matchesSection = section === '' || studentSection === section;
                const matchesStatus = status === '' ||
                    (status === 'active' && studentStatus === 'active') ||
                    (status === 'blocked' && studentStatus === 'blocked') ||
                    (status === 'pending' && studentStatus === 'pending');

                if (matchesSearch && matchesBatch && matchesSection && matchesStatus) {
                    row.classList.remove('hidden');
                } else {
                    row.classList.add('hidden');
                }
            });

            updateFilteredCount();
        }

        // Update count of filtered students
        function updateFilteredCount() {
            const visibleRows = document.querySelectorAll('#studentsTableBody tr:not(.hidden)').length;
            const countElement = document.querySelector('.text-sm.text-gray-500');

            if (countElement) {
                countElement.innerHTML = `Showing <span class="font-medium text-gray-700 dark:text-gray-300">1</span> to <span class="font-medium text-gray-700 dark:text-gray-300">${visibleRows}</span> of <span class="font-medium text-gray-700 dark:text-gray-300">248</span> students`;
            }
        }

        // Show Export Modal
        function showExportModal() {
            exportModal.classList.remove('hidden');
        }

        // Handle Export
        function handleExport(e) {
            e.preventDefault();

            const format = document.querySelector('input[name="exportFormat"]:checked').value;

            // In a real app, you would use AJAX to call a server endpoint
            alert(`Exporting student data as ${format.toUpperCase()} file...`);

            closeModals();

            // Simulate download success message with a slight delay
            setTimeout(() => {
                showNotification('Students data exported successfully!', 'success');
            }, 1500);
        }

        // Show Add Student Form
        function showAddStudentForm() {
            formModalTitle.textContent = 'Add New Student';
            studentId.value = '';
            studentForm.reset();

            // Show account info section for new students
            accountInfoSection.classList.remove('hidden');

            studentFormModal.classList.remove('hidden');
        }

        // Show Edit Student Form
        function showEditStudentForm(id) {
            formModalTitle.textContent = 'Edit Student';
            studentId.value = id;

            // In a real app, you would fetch student data from the server
            // For this demo, we'll use mock data

            // Hide account info section for editing
            accountInfoSection.classList.add('hidden');

            // Fill form with data based on student ID
            // This is mock data - in a real app you would use AJAX to get student details
            const row = document.querySelector(`.view-profile-btn[data-id="${id}"]`).closest('tr');
            const studentName = row.querySelector('td:first-child div div:first-child').textContent.split(' ');
            const firstName = studentName[0];
            const lastName = studentName[1] || '';
            const email = row.querySelector('td:nth-child(3)').textContent;
            const batch = row.querySelector('td:nth-child(4)').textContent;
            const section = row.querySelector('td:nth-child(5)').textContent;
            const gender = row.querySelector('td:first-child div div:nth-child(2)').textContent;

            // Set values in form
            document.getElementById('fname').value = firstName;
            document.getElementById('lname').value = lastName;
            document.getElementById('email').value = email;
            document.getElementById('batch').value = batch;
            document.getElementById('section').value = section;
            document.getElementById('gender').value = gender;

            // Other values would be filled from API in a real app
            document.getElementById('phone').value = '+1 (555) 123-4567';
            document.getElementById('address').value = '123 College Street, Apt 4B, New York, NY 10001';
            document.getElementById('dob').value = '2003-01-12';
            document.getElementById('guardian_name').value = 'Robert Smith';
            document.getElementById('guardian_phone').value = '+1 (555) 987-6543';
            document.getElementById('roll_number').value = row.querySelector('td:nth-child(2)').textContent;
            document.getElementById('admission_date').value = '2023-08-15';
            document.getElementById('institute_id').value = 'CS-NYC-001';

            // Set status based on badge text
            const status = row.querySelector('td:nth-child(7) span').textContent.toLowerCase();
            document.getElementById('status').value = status === 'active' ? 'active' :
                status === 'blocked' ? 'blocked' : 'pending';

            // Set profile preview image
            document.getElementById('profilePreview').src = row.querySelector('td:first-child img').src;

            studentFormModal.classList.remove('hidden');
        }

        // Show Student Profile
        function showStudentProfile(id) {
            // In a real app, you would fetch student data from the server
            // For this demo, we'll use mock data from the table row

            const row = document.querySelector(`.view-profile-btn[data-id="${id}"]`).closest('tr');
            const studentName = row.querySelector('td:first-child div div:first-child').textContent;
            const email = row.querySelector('td:nth-child(3)').textContent;
            const profileImg = row.querySelector('td:first-child img').src;
            const rollNumber = row.querySelector('td:nth-child(2)').textContent;
            const batch = row.querySelector('td:nth-child(4)').textContent;
            const section = row.querySelector('td:nth-child(5)').textContent;
            const admissionDate = row.querySelector('td:nth-child(6)').textContent;
            const status = row.querySelector('td:nth-child(7) span').textContent.toLowerCase();
            const gender = row.querySelector('td:first-child div div:nth-child(2)').textContent;

            // Set values in profile modal
            document.getElementById('studentName').textContent = studentName;
            document.getElementById('studentEmail').textContent = email;
            document.getElementById('studentProfileImage').src = profileImg;
            document.getElementById('studentRollNumber').textContent = rollNumber;
            document.getElementById('studentBatch').textContent = batch;
            document.getElementById('studentSection').textContent = section;
            document.getElementById('studentAdmissionDate').textContent = admissionDate;
            document.getElementById('studentGender').textContent = gender;

            // Set other values (these would come from API in a real app)
            document.getElementById('studentPhone').textContent = '+1 (555) 123-4567';
            document.getElementById('studentAddress').textContent = '123 College Street, Apt 4B, New York, NY 10001';
            document.getElementById('guardianName').textContent = 'Robert Smith';
            document.getElementById('guardianPhone').textContent = '+1 (555) 987-6543';
            document.getElementById('studentDOB').textContent = 'January 12, 2003';
            document.getElementById('emailVerifiedAt').textContent = 'August 16, 2023';
            document.getElementById('accountCreatedAt').textContent = 'August 15, 2023';
            document.getElementById('accountUpdatedAt').textContent = 'August 20, 2023';
            document.getElementById('instituteId').textContent = 'CS-NYC-001';

            // Set status badge
            const statusBadge = document.getElementById('studentStatus').querySelector('span');
            statusBadge.className = 'badge px-3 py-1.5';
            if (status === 'active') {
                statusBadge.classList.add('status-active');
                statusBadge.textContent = 'Active';
                blockUnblockBtn.textContent = 'Block Student';
                blockUnblockBtn.className = 'btn-danger';
                blockUnblockBtn.innerHTML = '<i class="fas fa-ban mr-2"></i> Block Student';
            } else if (status === 'blocked') {
                statusBadge.classList.add('status-blocked');
                statusBadge.textContent = 'Blocked';
                blockUnblockBtn.textContent = 'Unblock Student';
                blockUnblockBtn.className = 'btn-success';
                blockUnblockBtn.innerHTML = '<i class="fas fa-check-circle mr-2"></i> Unblock Student';
            } else {
                statusBadge.classList.add('status-pending');
                statusBadge.textContent = 'Pending';
                blockUnblockBtn.textContent = 'Approve Student';
                blockUnblockBtn.className = 'btn-success';
                blockUnblockBtn.innerHTML = '<i class="fas fa-check mr-2"></i> Approve Student';
            }

            // Store current student data
            currentStudent = {
                id: id,
                name: studentName,
                status: status.toLowerCase()
            };

            studentProfileModal.classList.remove('hidden');
        }

        // Show Block/Unblock Confirmation
        function showBlockConfirmation(id, isBlocked) {
            if (typeof id === 'object') {
                // If id is an object, it's the currentStudent object
                const student = id;
                id = student.id;
                isBlocked = student.status === 'blocked';
            } else {
                // Fetch student name from the table
                const row = document.querySelector(`[data-id="${id}"]`).closest('tr');
                const studentName = row.querySelector('td:first-child div div:first-child').textContent;
                currentStudent = {
                    id: id,
                    name: studentName,
                    status: isBlocked ? 'blocked' : 'active'
                };
            }

            if (isBlocked) {
                blockModalTitle.textContent = 'Unblock Student';
                blockModalMessage.textContent = `Are you sure you want to unblock ${currentStudent.name}? They will regain access to all system resources.`;
                blockBtnText.textContent = 'Unblock';
                confirmBlockBtn.className = 'btn-success';
                confirmBlockBtn.innerHTML = '<i class="fas fa-check-circle mr-2"></i> Unblock';
                blockReasonContainer.classList.add('hidden');
            } else {
                blockModalTitle.textContent = 'Block Student';
                blockModalMessage.textContent = `Are you sure you want to block ${currentStudent.name}? They will lose access to all system resources.`;
                blockBtnText.textContent = 'Block';
                confirmBlockBtn.className = 'btn-danger';
                confirmBlockBtn.innerHTML = '<i class="fas fa-ban mr-2"></i> Block';
                blockReasonContainer.classList.remove('hidden');
            }

            blockConfirmModal.classList.remove('hidden');
        }

        // Handle Form Submit
        function handleFormSubmit(e) {
            e.preventDefault();

            // In a real app, you would send form data to the server
            const isEditing = studentId.value !== '';
            const message = isEditing ? 'Student updated successfully' : 'Student added successfully';

            closeModals();

            // Simulate success message with a slight delay
            setTimeout(() => {
                showNotification(message, 'success');
            }, 500);
        }

        // Handle Block/Unblock Confirmation
        function handleBlockConfirm() {
            const isBlocked = blockBtnText.textContent === 'Unblock';
            const action = isBlocked ? 'unblocked' : 'blocked';

            // In a real app, you would send a request to the server
            closeModals();

            // Update UI based on action
            if (!isBlocked) {
                // Blocking a student
                const blockBtn = document.querySelector(`.block-student-btn[data-id="${currentStudent.id}"]`);
                if (blockBtn) {
                    const row = blockBtn.closest('tr');
                    const statusCell = row.querySelector('td:nth-child(7) span');

                    // Update status badge
                    statusCell.className = 'badge status-blocked';
                    statusCell.textContent = 'Blocked';

                    // Change block button to unblock
                    blockBtn.innerHTML = '<i class="fas fa-check-circle"></i>';
                    blockBtn.classList.remove('block-student-btn');
                    blockBtn.classList.add('unblock-student-btn');
                    blockBtn.setAttribute('aria-label', 'Unblock student');
                }
            } else {
                // Unblocking a student
                const unblockBtn = document.querySelector(`.unblock-student-btn[data-id="${currentStudent.id}"]`);
                if (unblockBtn) {
                    const row = unblockBtn.closest('tr');
                    const statusCell = row.querySelector('td:nth-child(7) span');

                    // Update status badge
                    statusCell.className = 'badge status-active';
                    statusCell.textContent = 'Active';

                    // Change unblock button to block
                    unblockBtn.innerHTML = '<i class="fas fa-ban"></i>';
                    unblockBtn.classList.remove('unblock-student-btn');
                    unblockBtn.classList.add('block-student-btn');
                    unblockBtn.setAttribute('aria-label', 'Block student');
                }
            }

            // Show success notification
            setTimeout(() => {
                showNotification(`Student ${action} successfully`, 'success');
            }, 500);
        }

        // Close all modals
        function closeModals() {
            studentProfileModal.classList.add('hidden');
            studentFormModal.classList.add('hidden');
            blockConfirmModal.classList.add('hidden');
            exportModal.classList.add('hidden');
        }

        // Show notification
        function showNotification(message, type = 'success') {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 transform transition-all duration-500 ease-in-out translate-x-full`;

            // Set appearance based on type
            if (type === 'success') {
                notification.classList.add('bg-green-100', 'text-green-800', 'dark:bg-green-800', 'dark:text-green-100');
                notification.innerHTML = `<div class="flex items-center"><i class="fas fa-check-circle mr-2"></i> ${message}</div>`;
            } else if (type === 'error') {
                notification.classList.add('bg-red-100', 'text-red-800', 'dark:bg-red-800', 'dark:text-red-100');
                notification.innerHTML = `<div class="flex items-center"><i class="fas fa-exclamation-circle mr-2"></i> ${message}</div>`;
            } else {
                notification.classList.add('bg-blue-100', 'text-blue-800', 'dark:bg-blue-800', 'dark:text-blue-100');
                notification.innerHTML = `<div class="flex items-center"><i class="fas fa-info-circle mr-2"></i> ${message}</div>`;
            }

            // Add to DOM
            document.body.appendChild(notification);

            // Animate in
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 10);

            // Animate out after delay
            setTimeout(() => {
                notification.classList.add('translate-x-full');

                // Remove from DOM after animation
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 500);
            }, 3000);
        }

        // Handle Edit Profile Button
        document.getElementById('editProfileBtn').addEventListener('click', () => {
            closeModals();
            showEditStudentForm(currentStudent.id);
        });

        // Approve Student Button
        document.querySelectorAll('.approve-student-btn').forEach(btn => {
            btn.addEventListener('click', e => {
                const studentId = e.currentTarget.getAttribute('data-id');
                const row = btn.closest('tr');
                const statusCell = row.querySelector('td:nth-child(7) span');

                // Update status badge
                statusCell.className = 'badge status-active';
                statusCell.textContent = 'Active';

                // Change approve button to block
                btn.innerHTML = '<i class="fas fa-ban"></i>';
                btn.classList.remove('approve-student-btn');
                btn.classList.add('block-student-btn');
                btn.setAttribute('aria-label', 'Block student');

                // Show success notification
                showNotification('Student approved successfully', 'success');
            });
        });
    </script>
@endsection
