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
    <main class="scrollable-content p-4 md:p-6">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 mb-3 rounded relative" role="alert">
                <strong class="font-bold">Success!</strong>
                <span class="block sm:inline">{{session('success')}}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 mb-3 rounded relative" role="alert">
                <strong class="font-bold">Error!</strong>
                <span class="block sm:inline">{{session('error')}}</span>
            </div>
        @endif

        <!-- Action Bar -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="relative">
                    <input type="text" id="searchStudents" placeholder="Search students..." class="form-input pl-12 pr-4 py-2">
                </div>
                <div>
                    <select id="batchFilter" class="form-input py-2">
                        <option value="">All Batches</option>
                        @foreach($batches as $batch)
                            <option value="{{$batch->id}}">{{$batch->batch}}</option>
                        @endforeach
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
                        <th scope="col" class="table-header">Student</th>
                        <th scope="col" class="table-header">Roll No.</th>
                        <th scope="col" class="table-header">Email</th>
                        <th scope="col" class="table-header">Batch</th>
                        <th scope="col" class="table-header">Section</th>
                        <th scope="col" class="table-header">Admission Date</th>
                        <th scope="col" class="table-header">Status</th>
                        <th scope="col" class="table-header">Actions</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($students as $student)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors duration-150">
                            <td class="table-cell">
                                <div class="flex items-center">
                                    <img class="h-10 w-10 rounded-full object-cover border-2 border-gray-200 dark:border-gray-700"
                                         src="{{$student->profile_picture ? asset("/storage/$student->profile_picture") : "https://ui-avatars.com/api/?name=".urlencode($student->fname.' '.$student->lname)}}"
                                         alt="{{$student->fname}} {{$student->lname}}">
                                    <div class="ml-4">
                                        <div class="font-medium text-gray-900 dark:text-white">
                                            {{$student->fname}} {{$student->lname}}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ucfirst($student->gender)}}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="table-cell">{{$student->roll_number}}</td>
                            <td class="table-cell">{{$student->email}}</td>
                            <td class="table-cell">{{$student->batch->batch}}</td>
                            <td class="table-cell">{{$student->section}}</td>
                            <td class="table-cell">{{$student->admission_date->format('d M, Y')}}</td>
                            <td class="table-cell">
                                @if($student->status)
                                    <span class="badge status-active">Active</span>
                                @else
                                    <span class="badge status-pending">Pending</span>
                                @endif
                            </td>
                            <td class="table-cell">
                                <div class="flex items-center space-x-2">
                                    <a href="{{route('admin.student.show', $student->id)}}"
                                       class="p-1.5 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-full"
                                       title="View profile">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($student->status)
                                        <form action="{{route('admin.student.status', $student->id)}}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit"
                                                    class="p-1.5 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-full"
                                                    title="Block student">
                                                <i class="fas fa-ban"></i>
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{route('admin.student.approve', $student->id)}}" method="POST" class="inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit"
                                                    class="p-1.5 text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300 hover:bg-green-50 dark:hover:bg-green-900/20 rounded-full"
                                                    title="Approve student">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

{{--            @if($students->hasPages())--}}
{{--                <div class="px-6 py-4 bg-white dark:bg-gray-800 border-t dark:border-gray-700">--}}
{{--                    {{ $students->links() }}--}}
{{--                </div>--}}
{{--            @endif--}}
        </div>
    </main>
@endsection

@section('scripts')
    <script>
        // Search and filter functionality
        const searchInput = document.getElementById('searchStudents');
        const batchFilter = document.getElementById('batchFilter');
        const sectionFilter = document.getElementById('sectionFilter');
        const statusFilter = document.getElementById('statusFilter');

        function filterStudents() {
            const searchTerm = searchInput.value.toLowerCase();
            const batch = batchFilter.value;
            const section = sectionFilter.value;
            const status = statusFilter.value;

            const rows = document.querySelectorAll('tbody tr');

            rows.forEach(row => {
                const studentName = row.querySelector('td:first-child').textContent.toLowerCase();
                const studentEmail = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                const studentRollNo = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                const studentBatch = row.querySelector('td:nth-child(4)').textContent;
                const studentSection = row.querySelector('td:nth-child(5)').textContent;
                const studentStatus = row.querySelector('td:nth-child(7) span').textContent.toLowerCase();

                const matchesSearch = studentName.includes(searchTerm) ||
                    studentEmail.includes(searchTerm) ||
                    studentRollNo.includes(searchTerm);

                const matchesBatch = !batch || studentBatch === batch;
                const matchesSection = !section || studentSection === section;
                const matchesStatus = !status || studentStatus === status.toLowerCase();

                row.style.display = (matchesSearch && matchesBatch && matchesSection && matchesStatus) ? '' : 'none';
            });
        }

        // Add event listeners
        searchInput.addEventListener('input', filterStudents);
        batchFilter.addEventListener('change', filterStudents);
        sectionFilter.addEventListener('change', filterStudents);
        statusFilter.addEventListener('change', filterStudents);

        // Initialize tooltips
        document.querySelectorAll('[title]').forEach(element => {
            new Tooltip(element);
        });
    </script>
@endsection
