@php use Illuminate\Support\Facades\Auth; @endphp
@extends('backend.layout.student-dashboard-layout')
@php $user = Auth::user(); @endphp
@section('username', $user->fname . ' ' . $user->lname)

@section('content')
    <div class="scrollable-content p-6 bg-gray-50 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto">
            <a class="px-3 py-1 text-sm bg-gray-500 text-white rounded-md hover:bg-gray-600" href="{{ route('student.subjects.index') }}">
                <i class="fa-solid fa-arrow-left"></i>
                Go Back
            </a>


            <!-- Page Title and Actions -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white"> Assignments : {{$subject->name}}</h1>
                <div class="mt-4 md:mt-0 flex flex-col md:flex-row gap-4">
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

            <!-- Pending Assignments -->
            <div class="card mb-8">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-medium text-gray-700 dark:text-gray-300">Pending Assignments</h3>
                </div>

                <div class="space-y-4" id="pendingAssignments">
                    @forelse($pendingAssignments as $assignment)
                        <div class="border dark:border-gray-700 rounded-lg overflow-hidden">
                            <div class="p-4 bg-gray-50 dark:bg-gray-700 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                <div>
                                    <h4 class="text-md font-medium text-gray-800 dark:text-white" data-subject-id="{{ $assignment->subject_id }}">{{ $assignment->title }}</h4>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                        {{ $assignment->subject->name ?? 'N/A' }} |
                                        Due: {{ $assignment->due_date }}
                                        @if(\Carbon\Carbon::parse($assignment->due_date)->isPast() && !$assignment->submissions()->where('student_id', $user->id)->exists())
                                            (<span class="text-red-500">Overdue</span>)
                                        @endif
                                    </p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ \Carbon\Carbon::parse($assignment->due_date)->isPast() ? 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100' }}">
                                        {{ \Carbon\Carbon::parse($assignment->due_date)->isPast() ? 'Overdue' : 'Pending' }}
                                    </span>
                                    <button type="button" id="openAssignment{{ $assignment->id }}" class="px-3 py-1 text-sm bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-md hover:bg-gray-300 dark:hover:bg-gray-500">
                                        <i class="fas fa-chevron-down mr-1"></i> Details
                                    </button>
                                    <a href="{{ route('student.assignment.show', $assignment->id) }}" class="px-3 py-1 text-sm bg-primary-500 text-white rounded-md hover:bg-primary-600">
                                        View Details
                                    </a>
                                </div>
                            </div>

                            <div id="assignment{{ $assignment->id }}Details" class="p-4 border-t dark:border-gray-700 hidden">
                                <div class="mb-4">
                                    <h5 class="text-sm font-medium text-gray-800 dark:text-white mb-2">Description</h5>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        {{ $assignment->description ?? 'No description provided.' }}
                                    </p>
                                </div>

                                <div class="mb-4">
                                    <h5 class="text-sm font-medium text-gray-800 dark:text-white mb-2">Resources</h5>
                                    <div class="flex flex-wrap gap-2">
                                        @forelse($assignment->attachments as $attachment)
                                            <a href="{{ route('student.assignment.download', $attachment->id) }}" class="flex items-center px-3 py-1 text-xs bg-gray-100 dark:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-200 dark:hover:bg-gray-500">
                                                <i class="fas {{ $attachment->file_type == 'application/pdf' ? 'fa-file-pdf text-red-500' : 'fa-file text-blue-500' }} mr-2"></i>
                                                {{ $attachment->title }}
                                            </a>
                                        @empty
                                            <p class="text-sm text-gray-500 dark:text-gray-400">No resources available.</p>
                                        @endforelse
                                    </div>
                                </div>

                                <div>

                                    <h5 class="text-sm font-medium text-gray-800 dark:text-white mb-2">Submit Assignment</h5>
                                    <form action="{{ route('student.assignment.store') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="assignment_id" value="{{ $assignment->id }}">
                                        @if(\Carbon\Carbon::parse($assignment->due_date)->isPast() && !$assignment->submissions()->where('student_id', $user->id)->exists())
                                        <div class="flex flex-col gap-4">
                                            <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-md p-4 text-center">
                                                <input type="file" id="assignment{{ $assignment->id }}File" name="file" class="hidden" accept=".pdf,.doc,.docx,.jpg,.png">
                                                <label for="assignment{{ $assignment->id }}File" class="cursor-pointer">
                                                    <i class="fas fa-cloud-upload-alt text-2xl text-gray-400 dark:text-gray-500 mb-2"></i>
                                                    <p class="text-sm text-gray-500 dark:text-gray-400">Drag and drop files here or click to browse</p>
                                                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Supported formats: PDF, DOC, DOCX, JPG, PNG</p>
                                                </label>
                                            </div>

                                            <div id="filePreview{{ $assignment->id }}" class="hidden p-3 bg-gray-50 dark:bg-gray-700 rounded-md">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center">
                                                        <i class="fas fa-file text-blue-500 mr-2"></i>
                                                        <span class="text-sm text-gray-700 dark:text-gray-300 file-name"></span>
                                                    </div>
                                                    <button type="button" class="text-red-500 hover:text-red-700 remove-file">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <button type="submit" class="px-4 py-2 bg-primary-500 text-white rounded-md hover:bg-primary-600">Submit Assignment</button>
                                            @endif
                                            @if(\Carbon\Carbon::parse($assignment->due_date)->isPast())
                                                <div class="p-3 bg-red-50 dark:bg-red-900 text-red-700 dark:text-red-300 rounded-md text-sm">
                                                    <i class="fas fa-exclamation-circle mr-2"></i>
                                                    This assignment is overdue.
                                                </div>
                                            @endif
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 dark:text-gray-400">No pending assignments found.</p>
                    @endforelse
                </div>
            </div>

            <!-- Submitted Assignments -->
            <div class="card">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-medium text-gray-700 dark:text-gray-300">Submitted Assignments</h3>
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
                                $submission = $assignment->submissions()->where('student_id', $user->id)->first();
                            @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-800 dark:text-white" data-subject-id="{{ $assignment->subject_id }}">{{ $assignment->title }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $assignment->subject->name ?? 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $submission->submitted_at ? \Carbon\Carbon::parse($submission->submitted_at)->format('d-m-Y') : 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $submission->status == 'graded' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300' }}">
                                        {{ $submission->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-800 dark:text-white">
                                        {{ $submission->marks ? $submission->marks . '/' . $assignment->full_marks : 'Pending' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('student.assignment.show', $assignment->id) }}" class="text-primary-600 hover:text-primary-800">
                                        <i class="fas fa-eye mr-1"></i> View
                                    </a>
                                    @if($submission->status == 'submitted')
                                        <a href="{{ route('student.assignment.edit', $submission->id) }}" class="ml-4 text-primary-600 hover:text-primary-800">
                                            <i class="fas fa-edit mr-1"></i> Edit
                                        </a>
                                        <form action="{{ route('student.assignment.destroy', $submission->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="ml-4 text-red-600 hover:text-red-800" onclick="return confirm('Are you sure you want to delete this submission?');">
                                                <i class="fas fa-trash mr-1"></i> Delete
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">No submitted assignments found.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="flex justify-between items-center mt-6">
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        Showing <span id="recordsShown">{{ count($submittedAssignments) }}</span> of <span id="totalRecords">{{ count($submittedAssignments) }}</span> records
                    </div>
                    <div class="flex space-x-2">
                        <button class="px-3 py-1 rounded-md bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-gray-600">Previous</button>
                        <button class="px-3 py-1 rounded-md bg-primary-500 text-white hover:bg-primary-600">Next</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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

            document.querySelectorAll('.remove-file').forEach(button => {
                button.addEventListener('click', function() {
                    const filePreview = this.closest('[id^="filePreview"]');
                    const assignmentId = filePreview.id.replace('filePreview', '');
                    const input = document.getElementById(`assignment${assignmentId}File`);
                    input.value = '';
                    filePreview.classList.add('hidden');
                });
            });

            document.getElementById('courseFilter').addEventListener('change', filterAssignments);
            document.getElementById('statusFilter').addEventListener('change', filterAssignments);
            document.getElementById('searchInput').addEventListener('input', filterAssignments);

            function filterAssignments() {
                const subjectId = document.getElementById('courseFilter').value;
                const status = document.getElementById('statusFilter').value;
                const search = document.getElementById('searchInput').value.toLowerCase();

                document.querySelectorAll('#pendingAssignments > div').forEach(row => {
                    if (row.querySelector('h4')) {
                        const title = row.querySelector('h4').textContent.toLowerCase();
                        const subjectIdAttr = row.querySelector('h4').dataset.subjectId || '';
                        const isOverdue = row.querySelector('.text-red-500') !== null;
                        let statusText = isOverdue ? 'overdue' : 'pending';

                        const matchesSubject = !subjectId || subjectIdAttr === subjectId;
                        const matchesStatus = !status || status === statusText;
                        const matchesSearch = !search || title.includes(search);

                        row.style.display = matchesSubject && matchesStatus && matchesSearch ? '' : 'none';
                    }
                });

                document.querySelectorAll('#submittedAssignments tr').forEach(row => {
                    if (row.querySelector('td:nth-child(1) div')) {
                        const title = row.querySelector('td:nth-child(1) div').textContent.toLowerCase();
                        const subjectIdAttr = row.querySelector('td:nth-child(1) div').dataset.subjectId || '';
                        const statusElement = row.querySelector('td:nth-child(4) span');
                        const statusText = statusElement ? statusElement.textContent.toLowerCase().trim() : '';

                        const matchesSubject = !subjectId || subjectIdAttr === subjectId;
                        const matchesStatus = !status || statusText === status;
                        const matchesSearch = !search || title.includes(search);

                        row.style.display = matchesSubject && matchesStatus && matchesSearch ? '' : 'none';
                    }
                });
            }
        });
    </script>
@endsection
