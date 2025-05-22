@extends("backend.layout.teacher-dashboard-layout")

@section('title', 'Assignment Submissions')

@section('content')
    <!-- Main Content Area -->
    <main class="p-6 md:p-6 min-h-screen overflow-y-auto pb-16">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-white mb-1">
                    Assignment Submissions
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    View and grade student submissions for your assignments
                </p>
            </div>
            <a href="{{ route('teacher.assignments.index') }}" class="mt-4 md:mt-0 btn-secondary flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> Back to Assignments
            </a>
        </div>

        <!-- Submission Filters -->
        <div class="card mb-6 p-4">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Filters</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="relative">
                    <label for="assignmentFilter" class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 block">Assignment</label>
                    <select id="assignmentFilter" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                        <option value="">All Assignments</option>
                        @foreach($assignments as $assignment)
                            <option value="{{ $assignment->id }}">{{ $assignment->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="relative">
                    <label for="batchFilter" class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 block">Batch</label>
                    <select id="batchFilter" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                        <option value="">All Batches</option>
                        @foreach($batches as $batch)
                            <option value="{{ $batch->id }}">{{ $batch->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="relative">
                    <label for="statusFilter" class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 block">Status</label>
                    <select id="statusFilter" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                        <option value="">All Status</option>
                        <option value="submitted">Submitted</option>
                        <option value="graded">Graded</option>
                        <option value="late">Late</option>
                    </select>
                </div>

                <div class="relative">
                    <label for="dateFilter" class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 block">Submission Date</label>
                    <input type="date" id="dateFilter" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                </div>
            </div>

            <div class="flex flex-col md:flex-row gap-4 mt-4">
                <div class="flex-grow relative">
                    <label for="searchInput" class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 block">Search</label>
                    <div class="flex items-center border border-gray-300 rounded-md dark:border-gray-700 overflow-hidden">
                        <input id="searchInput" type="text" placeholder="Search by student name, ID..." class="w-full px-4 py-2 focus:outline-none dark:bg-gray-800 dark:text-white">
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

        <!-- Submissions List -->
        <div class="card">
            <div class="flex items-center justify-between mb-4 p-4">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">All Submissions</h3>
                <div class="text-sm text-gray-500 dark:text-gray-400">Total: {{ $submissions->total() }} submissions</div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Student</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Assignment</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Submitted At</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Marks</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($submissions as $submission)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <img class="h-10 w-10 rounded-full" src="{{ $submission->student->profile_picture ?? '/placeholder.svg?height=40&width=40' }}" alt="{{ $submission->student->full_name }}">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $submission->student->full_name }}</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">ID: {{ $submission->student->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">{{ $submission->assignment->title }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $submission->assignment->subject->name ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $submission->submitted_at }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    @if($submission->assignment->due_date < $submission->submitted_at)
                                        <span class="text-red-500">Late</span>
                                    @else
                                        <span class="text-green-500">On time</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-medium rounded-full {{ getStatusClass($submission->status) }}">
                                    {{ ucfirst($submission->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">
                                    @if($submission->marks)
                                        {{ $submission->marks }} / {{ $submission->assignment->full_marks }}
                                    @else
                                        Not graded
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex space-x-2">
                                    <a href="{{ route('teacher.assignment-submissions.show', $submission->id) }}" class="text-primary-600 hover:text-primary-800" title="View Submission">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('teacher.assignment-submissions.edit', $submission->id) }}" class="text-yellow-600 hover:text-yellow-800" title="Grade Submission">
                                        <i class="fas fa-check-square"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-800 dark:text-white text-center">No submissions found</div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="flex items-center justify-between border-t border-gray-200 dark:border-gray-700 px-4 py-3 sm:px-6">
                {{ $submissions->links() }}
            </div>
        </div>
    </main>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // DOM Elements
            const assignmentFilter = document.getElementById('assignmentFilter');
            const batchFilter = document.getElementById('batchFilter');
            const statusFilter = document.getElementById('statusFilter');
            const dateFilter = document.getElementById('dateFilter');
            const searchInput = document.getElementById('searchInput');
            const resetFilters = document.getElementById('resetFilters');

            // Event Listeners
            assignmentFilter.addEventListener('change', applyFilters);
            batchFilter.addEventListener('change', applyFilters);
            statusFilter.addEventListener('change', applyFilters);
            dateFilter.addEventListener('change', applyFilters);
            searchInput.addEventListener('input', debounce(applyFilters, 300));
            resetFilters.addEventListener('click', resetAllFilters);

            // Functions
            function applyFilters() {
                const params = new URLSearchParams(window.location.search);

                if (assignmentFilter.value) params.set('assignment_id', assignmentFilter.value);
                else params.delete('assignment_id');

                if (batchFilter.value) params.set('batch_id', batchFilter.value);
                else params.delete('batch_id');

                if (statusFilter.value) params.set('status', statusFilter.value);
                else params.delete('status');

                if (dateFilter.value) params.set('date', dateFilter.value);
                else params.delete('date');

                if (searchInput.value) params.set('search', searchInput.value);
                else params.delete('search');

                window.location.href = `${window.location.pathname}?${params.toString()}`;
            }

            function resetAllFilters() {
                assignmentFilter.value = '';
                batchFilter.value = '';
                statusFilter.value = '';
                dateFilter.value = '';
                searchInput.value = '';

                window.location.href = window.location.pathname;
            }

            function debounce(func, wait) {
                let timeout;
                return function() {
                    const context = this, args = arguments;
                    clearTimeout(timeout);
                    timeout = setTimeout(() => func.apply(context, args), wait);
                };
            }

            // Set filter values from URL params
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('assignment_id')) assignmentFilter.value = urlParams.get('assignment_id');
            if (urlParams.has('batch_id')) batchFilter.value = urlParams.get('batch_id');
            if (urlParams.has('status')) statusFilter.value = urlParams.get('status');
            if (urlParams.has('date')) dateFilter.value = urlParams.get('date');
            if (urlParams.has('search')) searchInput.value = urlParams.get('search');
        });

        function getStatusClass(status) {
            switch(status.toLowerCase()) {
                case 'submitted':
                    return 'bg-blue-100 dark:bg-blue-800 text-blue-800 dark:text-blue-100';
                case 'graded':
                    return 'bg-green-100 dark:bg-green-800 text-green-800 dark:text-green-100';
                case 'late':
                    return 'bg-yellow-100 dark:bg-yellow-800 text-yellow-800 dark:text-yellow-100';
                default:
                    return 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-100';
            }
        }
    </script>
@endsection
