@php use Illuminate\Support\Facades\Auth;
 use Carbon\Carbon;
@endphp
@extends('backend.layout.student-dashboard-layout')
@php $user = Auth::user(); @endphp
@section('username', $user->fname . ' ' . $user->lname)

@section('content')
    <div class="scrollable-content p-6 bg-gray-50 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto">
            <!-- Page Title and Actions -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">My Assignments</h1>
                <div class="mt-4 md:mt-0 flex flex-col md:flex-row gap-4">
                    <div class="relative">
                        <select id="courseFilter" name="subject_id" class="bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 rounded-md px-4 py-2 pr-8 focus:outline-none focus:ring-2 focus:ring-primary-500">
                            <option value="">All Courses</option>
                            @if($user->batch)
                                @foreach($user->batch->subjects as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="relative">
                        <select id="statusFilter" name="status" class="bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 rounded-md px-4 py-2 pr-8 focus:outline-none focus:ring-2 focus:ring-primary-500">
                            <option value="">All Status</option>
                            <option value="pending">Pending</option>
                            <option value="submitted">Submitted</option>
                            <option value="graded">Graded</option>
                            <option value="overdue">Overdue</option>
                        </select>
                    </div>

                    <div class="relative">
                        <div class="flex items-center border border-gray-300 rounded-md dark:border-gray-700 overflow-hidden">
                            <input type="text" id="searchInput" placeholder="Search assignments..." class="w-full px-4 py-2 focus:outline-none dark:bg-gray-800 dark:text-white">
                            <button class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <x-show-success-failure-badge/>

            <!-- Assignment Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Total Assignments Card -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Assignments</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $assignmentStats['total'] ?? 0 }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">All time</p>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                                <i class="fas fa-clipboard-list text-blue-600 dark:text-blue-400 text-xl"></i>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="flex items-center text-sm">
                            <span class="text-gray-500 dark:text-gray-400">This month: </span>
                            <span class="ml-1 font-medium text-gray-900 dark:text-white">{{ $assignmentStats['this_month'] ?? 0 }}</span>
                        </div>
                    </div>
                </div>

                <!-- Submitted Assignments Card -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Submitted</p>
                            <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $assignmentStats['submitted'] ?? 0 }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                {{ $assignmentStats['total'] > 0 ? round(($assignmentStats['submitted'] / $assignmentStats['total']) * 100, 1) : 0 }}% completion rate
                            </p>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                                <i class="fas fa-check-circle text-green-600 dark:text-green-400 text-xl"></i>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="flex items-center text-sm">
                            <span class="text-gray-500 dark:text-gray-400">Graded: </span>
                            <span class="ml-1 font-medium text-gray-900 dark:text-white">{{ $assignmentStats['graded'] ?? 0 }}</span>
                        </div>
                    </div>
                </div>

                <!-- Due Assignments Card -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Due Soon</p>
                            <p class="text-3xl font-bold text-orange-600 dark:text-orange-400">{{ $assignmentStats['due_soon'] ?? 0 }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Next 7 days</p>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900 rounded-lg flex items-center justify-center">
                                <i class="fas fa-clock text-orange-600 dark:text-orange-400 text-xl"></i>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="flex items-center text-sm">
                            <span class="text-red-500 dark:text-red-400">Overdue: </span>
                            <span class="ml-1 font-medium text-red-600 dark:text-red-400">{{ $assignmentStats['overdue'] ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Bar -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 mb-8">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div class="flex items-center space-x-4">
                        <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300">Quick Actions:</h3>
                        <button onclick="filterByStatus('pending')" class="px-3 py-1 text-xs bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200 rounded-full hover:bg-yellow-200 dark:hover:bg-yellow-800 transition-colors">
                            View Pending ({{ $assignmentStats['pending'] ?? 0 }})
                        </button>
                        <button onclick="filterByStatus('overdue')" class="px-3 py-1 text-xs bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 rounded-full hover:bg-red-200 dark:hover:bg-red-800 transition-colors">
                            View Overdue ({{ $assignmentStats['overdue'] ?? 0 }})
                        </button>
                        <button onclick="clearFilters()" class="px-3 py-1 text-xs bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-full hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                            Clear Filters
                        </button>
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        Last updated: {{ now()->format('M d, Y \a\t g:i A') }}
                    </div>
                </div>
            </div>

            <!-- Pending Assignments -->
            <div class="card mb-8">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-medium text-gray-700 dark:text-gray-300">
                        Pending Assignments
                        <span class="ml-2 px-2 py-1 text-xs bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200 rounded-full">
                            {{ count($pendingAssignments) }}
                        </span>
                    </h3>
                </div>

                <div class="space-y-4" id="pendingAssignments">
                    @forelse($pendingAssignments as $assignment)
                        <div class="border dark:border-gray-700 rounded-lg overflow-hidden assignment-item" data-subject-id="{{ $assignment->subject_id }}" data-status="{{ \Carbon\Carbon::parse($assignment->due_date)->isPast() ? 'overdue' : 'pending' }}">
                            <div class="p-4 bg-gray-50 dark:bg-gray-700 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                <div class="flex-1">
                                    <h4 class="text-md font-medium text-gray-800 dark:text-white assignment-title">{{ $assignment->title }}</h4>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                        {{ $assignment->subject->name ?? 'N/A' }} |
                                        Due: {{ $assignment->due_date }} ({{ Carbon::parse($assignment->due_date)->diffForHumans() }})
                                        @if(Carbon::parse($assignment->due_date)->isPast() && !$assignment->assignmentSubmissions()->where('student_id', $user->id)->exists())
                                            (<span class="text-red-500 font-medium">Overdue</span>)
                                        @endif
                                    </p>
                                    @if($assignment->full_marks)
                                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                                            <i class="fas fa-star mr-1"></i>Max Marks: {{ $assignment->full_marks }}
                                        </p>
                                    @endif
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ \Carbon\Carbon::parse($assignment->due_date)->isPast() ? 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100' }}">
                                        {{ \Carbon\Carbon::parse($assignment->due_date)->isPast() ? 'Overdue' : 'Pending' }}
                                    </span>
                                    <button type="button" id="openAssignment{{ $assignment->id }}" class="px-3 py-1 text-sm bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-md hover:bg-gray-300 dark:hover:bg-gray-500 transition-colors">
                                        <i class="fas fa-chevron-down mr-1"></i> Details
                                    </button>
                                    <a href="{{ route('student.assignment.show', $assignment->id) }}" class="px-3 py-1 text-sm bg-primary-500 text-white rounded-md hover:bg-primary-600 transition-colors">
                                        View Details
                                    </a>
                                </div>
                            </div>

                            <div id="assignment{{ $assignment->id }}Details" class="p-4 border-t dark:border-gray-700 hidden">
                                <div class="grid md:grid-cols-2 gap-6">
                                    <div>
                                        <h5 class="text-sm font-medium text-gray-800 dark:text-white mb-2">Description</h5>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            {{ $assignment->description ?? 'No description provided.' }}
                                        </p>
                                    </div>

                                    <div>
                                        <h5 class="text-sm font-medium text-gray-800 dark:text-white mb-2">Assignment Details</h5>
                                        <div class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                                            <div class="flex justify-between">
                                                <span>Created:</span>
                                                <span>{{ Carbon::parse($assignment->created_at)->format('M d, Y') }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span>Due Date:</span>
                                                <span class="{{ Carbon::parse($assignment->due_date)->isPast() ? 'text-red-500' : 'text-green-500' }}">
                                                    {{ Carbon::parse($assignment->due_date)->format('M d, Y g:i A') }}
                                                </span>
                                            </div>
                                            @if($assignment->full_marks)
                                                <div class="flex justify-between">
                                                    <span>Max Marks:</span>
                                                    <span>{{ $assignment->full_marks }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <h5 class="text-sm font-medium text-gray-800 dark:text-white mb-2">Resources</h5>
                                    <div class="flex flex-wrap gap-2">
                                        @forelse($assignment->attachments as $attachment)
                                            <a href="{{ route('student.assignment.download', $attachment->id) }}" class="flex items-center px-3 py-1 text-xs bg-gray-100 dark:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-200 dark:hover:bg-gray-500 transition-colors">
                                                <i class="fas {{ $attachment->file_type == 'application/pdf' ? 'fa-file-pdf text-red-500' : 'fa-file text-blue-500' }} mr-2"></i>
                                                {{ $attachment->title }}
                                            </a>
                                        @empty
                                            <p class="text-sm text-gray-500 dark:text-gray-400">No resources available.</p>
                                        @endforelse
                                    </div>
                                </div>

                                @if(\Carbon\Carbon::parse($assignment->due_date)->isPast() && !$assignment->submissions()->where('student_id', $user->id)->exists())
                                    <div class="mt-4 p-3 bg-red-50 dark:bg-red-900 text-red-700 dark:text-red-300 rounded-md text-sm">
                                        <i class="fas fa-exclamation-circle mr-2"></i>
                                        This assignment is overdue. Please contact your instructor if you need to submit late.
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-clipboard-check text-gray-400 text-2xl"></i>
                            </div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">No pending assignments found.</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Great job staying on top of your work!</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Submitted Assignments -->
            <div class="card">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-medium text-gray-700 dark:text-gray-300">
                        Submitted Assignments
                        <span class="ml-2 px-2 py-1 text-xs bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 rounded-full">
                            {{ count($submittedAssignments) }}
                        </span>
                    </h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Assignment</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Course</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Submitted On</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Grade</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700" id="submittedAssignments">
                        @forelse($submittedAssignments as $assignment)
                            @php
                                $submission = $assignment->assignmentSubmissions()->where('student_id', $user->id)->first();
                            @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 assignment-item" data-subject-id="{{ $assignment->subject_id }}" data-status="{{ $submission->status ?? 'submitted' }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-800 dark:text-white assignment-title">{{ $assignment->title }}</div>
                                    @if($assignment->full_marks)
                                        <div class="text-xs text-gray-500 dark:text-gray-400">Max: {{ $assignment->full_marks }} marks</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $assignment->subject->name ?? 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $submission->submitted_at ? \Carbon\Carbon::parse($submission->submitted_at)->format('M d, Y') : 'N/A' }}
                                    </div>
                                    @if($submission->submitted_at)
                                        <div class="text-xs text-gray-400 dark:text-gray-500">
                                            {{ \Carbon\Carbon::parse($submission->submitted_at)->format('g:i A') }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        {{ $submission->status == 'graded' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' :
                                           ($submission->status == 'submitted' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300' :
                                           'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300') }}">
                                        {{ ucfirst($submission->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-800 dark:text-white">
                                        @if($submission->marks !== null && $assignment->full_marks)
                                            <span class="text-green-600 dark:text-green-400">{{ $submission->marks }}/{{ $assignment->full_marks }}</span>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ round(($submission->marks / $assignment->full_marks) * 100, 1) }}%
                                            </div>
                                        @else
                                            <span class="text-gray-500">Pending</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('student.assignment.show', $assignment->id) }}" class="text-primary-600 hover:text-primary-800 transition-colors">
                                        <i class="fas fa-eye mr-1"></i> View
                                    </a>
                                    @if(!$assignment->due_date || (\Carbon\Carbon::parse($assignment->due_date)->isFuture() && $submission && $submission->status == 'submitted'))
                                        <form action="{{ route('student.assignment.destroy', $submission->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="ml-4 text-red-600 hover:text-red-800 transition-colors" onclick="return confirm('Are you sure you want to delete this submission?');">
                                                <i class="fas fa-trash mr-1"></i> Delete
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center">
                                    <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <i class="fas fa-inbox text-gray-400 text-2xl"></i>
                                    </div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">No submitted assignments found.</p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Complete some assignments to see them here.</p>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                @if(count($submittedAssignments) > 0)
                    <div class="flex justify-between items-center mt-6">
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            Showing <span id="recordsShown">{{ count($submittedAssignments) }}</span> of <span id="totalRecords">{{ count($submittedAssignments) }}</span> records
                        </div>
                        <div class="flex space-x-2">
                            <button class="px-3 py-1 rounded-md bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">Previous</button>
                            <button class="px-3 py-1 rounded-md bg-primary-500 text-white hover:bg-primary-600 transition-colors">Next</button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle assignment details
            document.querySelectorAll('[id^="openAssignment"]').forEach(button => {
                button.addEventListener('click', function() {
                    const assignmentId = this.id.replace('openAssignment', '');
                    const details = document.getElementById(`assignment${assignmentId}Details`);
                    details.classList.toggle('hidden');

                    // Toggle icon
                    const icon = this.querySelector('i');
                    if (icon.classList.contains('fa-chevron-down')) {
                        icon.classList.replace('fa-chevron-down', 'fa-chevron-up');
                    } else {
                        icon.classList.replace('fa-chevron-up', 'fa-chevron-down');
                    }
                });
            });

            // File input handlers
            document.querySelectorAll('input[type="file"]').forEach(input => {
                input.addEventListener('change', function() {
                    const filePreview = document.getElementById(`filePreview${this.id.replace('assignment', '').replace('File', '')}`);
                    const fileNameSpan = filePreview.querySelector('.file-name');
                    const file = this.files[0];
                    if (file) {
                        fileNameSpan.textContent = file.name;
                        filePreview.classList.remove('hidden');
                        const icon = filePreview.querySelector('i');
                        icon.className = `fas ${file.type === 'application/pdf' ? 'fa-file-pdf text-red-500' : 'fa-file text-blue-500'} mr-2`;
                    }
                });
            });

            // Remove file handlers
            document.querySelectorAll('.remove-file').forEach(button => {
                button.addEventListener('click', function() {
                    const filePreview = this.closest('[id^="filePreview"]');
                    const assignmentId = filePreview.id.replace('filePreview', '');
                    const input = document.getElementById(`assignment${assignmentId}File`);
                    input.value = '';
                    filePreview.classList.add('hidden');
                });
            });

            // Filter event listeners
            document.getElementById('courseFilter').addEventListener('change', filterAssignments);
            document.getElementById('statusFilter').addEventListener('change', filterAssignments);
            document.getElementById('searchInput').addEventListener('input', filterAssignments);
        });

        // Quick action functions
        function filterByStatus(status) {
            document.getElementById('statusFilter').value = status;
            filterAssignments();
        }

        function clearFilters() {
            document.getElementById('courseFilter').value = '';
            document.getElementById('statusFilter').value = '';
            document.getElementById('searchInput').value = '';
            filterAssignments();
        }

        // Enhanced filter function
        function filterAssignments() {
            const subjectId = document.getElementById('courseFilter').value;
            const status = document.getElementById('statusFilter').value;
            const search = document.getElementById('searchInput').value.toLowerCase();

            let visiblePending = 0;
            let visibleSubmitted = 0;

            // Filter pending assignments
            document.querySelectorAll('#pendingAssignments .assignment-item').forEach(item => {
                const title = item.querySelector('.assignment-title').textContent.toLowerCase();
                const itemSubjectId = item.dataset.subjectId || '';
                const itemStatus = item.dataset.status || '';

                const matchesSubject = !subjectId || itemSubjectId === subjectId;
                const matchesStatus = !status || itemStatus === status;
                const matchesSearch = !search || title.includes(search);

                if (matchesSubject && matchesStatus && matchesSearch) {
                    item.style.display = '';
                    visiblePending++;
                } else {
                    item.style.display = 'none';
                }
            });

            // Filter submitted assignments
            document.querySelectorAll('#submittedAssignments .assignment-item').forEach(item => {
                const title = item.querySelector('.assignment-title').textContent.toLowerCase();
                const itemSubjectId = item.dataset.subjectId || '';
                const itemStatus = item.dataset.status || '';

                const matchesSubject = !subjectId || itemSubjectId === subjectId;
                const matchesStatus = !status || itemStatus === status;
                const matchesSearch = !search || title.includes(search);

                if (matchesSubject && matchesStatus && matchesSearch) {
                    item.style.display = '';
                    visibleSubmitted++;
                } else {
                    item.style.display = 'none';
                }
            });

            // Update record counts
            const recordsShown = document.getElementById('recordsShown');
            const totalRecords = document.getElementById('totalRecords');
            if (recordsShown && totalRecords) {
                recordsShown.textContent = visibleSubmitted;
                totalRecords.textContent = document.querySelectorAll('#submittedAssignments .assignment-item').length;
            }
        }
    </script>
@endsection
