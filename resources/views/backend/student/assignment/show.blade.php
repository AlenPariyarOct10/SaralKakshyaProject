@php use Illuminate\Support\Str; @endphp
@extends("backend.layout.student-dashboard-layout")

@section('title', 'View Assignment')

@section('content')
    <!-- Main Content Area -->
    <main class="p-6 md:p-6 min-h-screen overflow-y-auto pb-16">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-white mb-1">
                    {{ $assignment->title }}
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Assignment Details
                </p>
            </div>
            <div class="mt-4 md:mt-0 flex flex-col md:flex-row gap-3">
                <a href="{{ route('student.assignment.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Assignments
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 mb-6 rounded relative" role="alert">
                <strong class="font-bold">Success!</strong>
                <p class="mt-2 text-sm">{{ session('success') }}</p>
            </div>
        @endif
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 mb-3 rounded relative" role="alert">
                <strong class="font-bold">Whoops!</strong>
                <ul class="mt-2 list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Assignment Details -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <!-- Main Information -->
            <div class="lg:col-span-2">
                <div class="card h-full">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Assignment Information</h3>

                        <div class="space-y-6">
                            <!-- Status Badge -->
                            <div class="flex items-center">
                                @php
                                    $now = \Carbon\Carbon::now();
                                    $dueDateTime = \Carbon\Carbon::parse($assignment->due_date . ' ' . $assignment->due_time);
                                    $isOverdue = $now->gt($dueDateTime);

                                    // Check if student has submitted
                                    $hasSubmitted = isset($submission) && $submission;
                                @endphp

                                @if($hasSubmitted)
                                    <span class="px-3 py-1 text-xs font-medium rounded-full bg-blue-100 dark:bg-blue-800 text-blue-800 dark:text-blue-100">Submitted</span>
                                @elseif($isOverdue)
                                    <span class="px-3 py-1 text-xs font-medium rounded-full bg-red-100 dark:bg-red-800 text-red-800 dark:text-red-100">Overdue</span>
                                @else
                                    <span class="px-3 py-1 text-xs font-medium rounded-full bg-yellow-100 dark:bg-yellow-800 text-yellow-800 dark:text-yellow-100">Pending</span>
                                @endif
                            </div>

                            <!-- Description -->
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Description</h4>
                                <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-md max-h-[300px] overflow-y-auto">
                                    <p class="text-gray-800 dark:text-gray-200 whitespace-pre-line">{{ $assignment->description }}</p>
                                </div>
                            </div>

                            <!-- Subject, Chapter, Sub-Chapter -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Subject</h4>
                                    <p class="text-gray-800 dark:text-white">{{ $assignment->subject->name }} ({{ $assignment->subject->code }})</p>
                                </div>

                                <div>
                                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Chapter</h4>
                                    <p class="text-gray-800 dark:text-white">{{ $assignment->chapter->title ?? 'N/A' }}</p>
                                </div>

                                <div>
                                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Sub-Chapter</h4>
                                    <p class="text-gray-800 dark:text-white">{{ $assignment->subChapter->title ?? 'N/A' }}</p>
                                </div>
                            </div>

                            <!-- Assignment Files -->
                            @if($assignment->attachments && count($assignment->attachments) > 0)
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Assignment Resources</h4>
                                    <div class="space-y-2 max-h-[200px] overflow-y-auto pr-2">
                                        @foreach($assignment->attachments as $attachment)
                                            @php
                                                $icon = 'fa-file';
                                                $iconColor = 'text-gray-500';
                                                if (Str::contains($attachment->file_type, 'pdf')) {
                                                    $icon = 'fa-file-pdf';
                                                    $iconColor = 'text-red-500';
                                                    $viewable = true;
                                                } elseif (Str::contains($attachment->file_type, ['msword', 'wordprocessingml'])) {
                                                    $icon = 'fa-file-word';
                                                    $iconColor = 'text-blue-500';
                                                    $viewable = false;
                                                } elseif (Str::contains($attachment->file_type, 'image')) {
                                                    $icon = 'fa-file-image';
                                                    $iconColor = 'text-green-500';
                                                    $viewable = true;
                                                } elseif (Str::contains($attachment->file_type, ['zip', 'compressed'])) {
                                                    $icon = 'fa-file-archive';
                                                    $iconColor = 'text-yellow-500';
                                                    $viewable = false;
                                                } elseif (Str::contains($attachment->file_type, 'text') || Str::contains($attachment->mime_type, 'csv')) {
                                                    $icon = 'fa-file-alt';
                                                    $iconColor = 'text-gray-400';
                                                    $viewable = true;
                                                } else {
                                                    $viewable = false;
                                                }
                                            @endphp
                                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-md">
                                                <div class="flex items-start space-x-3 min-w-0">
                                                    <i class="fas {{ $icon }} {{ $iconColor }} mt-1"></i>
                                                    <div class="min-w-0">
                                                        <div class="text-sm font-semibold text-gray-800 dark:text-gray-200">
                                                            {{ $attachment->title ?? 'Untitled' }}
                                                        </div>
                                                        <div class="text-xs text-gray-600 dark:text-gray-400 truncate" title="{{ $attachment->original_name }}">
                                                            {{ $attachment->original_name }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="flex items-center space-x-2 flex-shrink-0 ml-2">
                                                    @if($viewable)
                                                        <button onclick="openPreview('{{ $attachment->id }}', '{{$attachment->title}}')"
                                                                class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300"
                                                                title="View file">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                    @endif
                                                    <a href="{{ route('student.assignment.download', [$assignment->id, $attachment->id]) }}"
                                                       class="text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300"
                                                       title="Download">
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Preview Modal -->
                                <div id="previewModal" class="fixed h-full inset-0 bg-black bg-opacity-75 z-50 hidden flex items-center justify-center p-4">
                                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl h-full max-w-8xl w-full flex flex-col">
                                        <div class="flex justify-between items-center p-4 border-b">
                                            <h3 class="text-lg font-medium" id="previewTitle"></h3>
                                            <button onclick="closePreview()" class="text-gray-500 hover:text-gray-700">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                        <div class="flex-1 overflow-auto p-4 h-full">
                                            <iframe id="previewFrame" class="w-full h-full border-0"></iframe>
                                            <div id="unsupportedPreview" class="hidden text-center py-8">
                                                <i class="fas fa-exclamation-triangle text-yellow-500 text-4xl mb-4"></i>
                                                <p class="text-gray-700 dark:text-gray-300">Preview not available for this file type</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Submission Section -->
                            <div class="mt-8 border-t pt-6">
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Your Submission</h3>

                                @if(isset($submission) && $submission)
                                    <!-- Existing Submission -->
                                    <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-md mb-4">
                                        <div class="flex items-center justify-between mb-3">
                                            <div>
                                                <h4 class="font-medium text-gray-800 dark:text-white">Submitted</h4>
                                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                                    {{ \Carbon\Carbon::parse($submission->created_at)->format('M d, Y h:i A') }}
                                                    ({{ \Carbon\Carbon::parse($submission->created_at)->diffForHumans() }})
                                                </p>
                                            </div>

                                            @if(isset($submission->grade))
                                                <div class="bg-green-100 dark:bg-green-800/30 px-3 py-1 rounded-full">
                                                    <span class="text-green-800 dark:text-green-300 font-medium">
                                                        Grade: {{ $submission->grade }}/{{ $assignment->full_marks }}
                                                    </span>
                                                </div>
                                            @else
                                                <div class="bg-yellow-100 dark:bg-yellow-800/30 px-3 py-1 rounded-full">
                                                    <span class="text-yellow-800 dark:text-yellow-300 font-medium">
                                                        Not graded yet
                                                    </span>
                                                </div>
                                            @endif
                                        </div>

                                        @if($submission->comment)
                                            <div class="mb-3">
                                                <h5 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Your Comment:</h5>
                                                <p class="text-gray-800 dark:text-gray-200 bg-white dark:bg-gray-800 p-3 rounded-md">
                                                    {{ $submission->comment }}
                                                </p>
                                            </div>
                                        @endif

                                        @if($submission->teacher_feedback)
                                            <div class="mb-3">
                                                <h5 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Teacher Feedback:</h5>
                                                <p class="text-gray-800 dark:text-gray-200 bg-white dark:bg-gray-800 p-3 rounded-md">
                                                    {{ $submission->teacher_feedback }}
                                                </p>
                                            </div>
                                        @endif

                                        <!-- Submission Files -->
                                        @if($submission->attachments && count($submission->attachments) > 0)
                                            <div class="mt-3">
                                                <h5 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Your Submitted Files:</h5>
                                                <div class="space-y-2">
                                                    @foreach($submission->attachments as $attachment)
                                                        @php
                                                            $icon = 'fa-file';
                                                            $iconColor = 'text-gray-500';
                                                            if (Str::contains($attachment->file_type, 'pdf')) {
                                                                $icon = 'fa-file-pdf';
                                                                $iconColor = 'text-red-500';
                                                                $viewable = true;
                                                            } elseif (Str::contains($attachment->file_type, ['msword', 'wordprocessingml'])) {
                                                                $icon = 'fa-file-word';
                                                                $iconColor = 'text-blue-500';
                                                                $viewable = false;
                                                            } elseif (Str::contains($attachment->file_type, 'image')) {
                                                                $icon = 'fa-file-image';
                                                                $iconColor = 'text-green-500';
                                                                $viewable = true;
                                                            } elseif (Str::contains($attachment->file_type, ['zip', 'compressed'])) {
                                                                $icon = 'fa-file-archive';
                                                                $iconColor = 'text-yellow-500';
                                                                $viewable = false;
                                                            } elseif (Str::contains($attachment->file_type, 'text') || Str::contains($attachment->mime_type, 'csv')) {
                                                                $icon = 'fa-file-alt';
                                                                $iconColor = 'text-gray-400';
                                                                $viewable = true;
                                                            } else {
                                                                $viewable = false;
                                                            }
                                                        @endphp
                                                        <div class="flex items-center justify-between p-3 bg-white dark:bg-gray-700 rounded-md">
                                                            <div class="flex items-start space-x-3 min-w-0">
                                                                <i class="fas {{ $icon }} {{ $iconColor }} mt-1"></i>
                                                                <div class="min-w-0">
                                                                    <div class="text-sm font-semibold text-gray-800 dark:text-gray-200 truncate">
                                                                        {{ $attachment->original_name }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="flex items-center space-x-2 flex-shrink-0 ml-2">
                                                                @if($viewable)
                                                                    <button onclick="openSubmissionPreview('{{ $attachment->id }}')"
                                                                            class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300"
                                                                            title="View file">
                                                                        <i class="fas fa-eye"></i>
                                                                    </button>
                                                                @endif
                                                                <a href="{{ route('student.submission.download', [$submission->id, $attachment->id]) }}"
                                                                   class="text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300"
                                                                   title="Download">
                                                                    <i class="fas fa-download"></i>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif

                                        <!-- Update Submission Button -->
                                        @if(!$isOverdue)
                                            <div class="mt-4">
                                                <button onclick="showSubmissionForm()" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                                    <i class="fas fa-edit mr-2"></i> Update Submission
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                @endif

                                <!-- Submission Form -->
                                <div id="submissionForm" class="{{ isset($submission) && $submission ? 'hidden' : '' }}">
                                    @if($isOverdue)
                                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 mb-4 rounded relative" role="alert">
                                            <strong class="font-bold">Assignment Overdue!</strong>
                                            <p class="mt-1 text-sm">This assignment is past its due date. Contact your teacher if you need an extension.</p>
                                        </div>
                                    @else
                                        <form action="{{ route('student.assignment.submit', $assignment->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf

                                            <div class="space-y-4">
                                                <div>
                                                    <label for="comment" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                        Comment (Optional)
                                                    </label>
                                                    <textarea id="comment" name="comment" rows="3"
                                                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                                                              placeholder="Add any comments about your submission here...">{{ $submission->comment ?? '' }}</textarea>
                                                </div>

                                                <div>
                                                    <label for="files" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                        Upload Files
                                                    </label>
                                                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-md">
                                                        <div class="space-y-1 text-center">
                                                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                            </svg>
                                                            <div class="flex text-sm text-gray-600 dark:text-gray-400">
                                                                <label for="file-upload" class="relative cursor-pointer bg-white dark:bg-gray-700 rounded-md font-medium text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300 focus-within:outline-none">
                                                                    <span>Upload files</span>
                                                                    <input id="file-upload" name="files[]" type="file" class="sr-only" multiple>
                                                                </label>
                                                                <p class="pl-1">or drag and drop</p>
                                                            </div>
                                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                                PDF, DOC, DOCX, JPG, PNG, ZIP up to 10MB
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div id="file-list" class="mt-2 space-y-1"></div>
                                                </div>

                                                <div class="flex justify-end">
                                                    <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700">
                                                        {{ isset($submission) && $submission ? 'Update Submission' : 'Submit Assignment' }}
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Information -->
            <div>
                <div class="card mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Assignment Details</h3>

                        <div class="space-y-4">
                            @php
                                use Carbon\Carbon;
                                $assignedDate = $assignment->assigned_date instanceof Carbon
                                    ? $assignment->assigned_date
                                    : Carbon::parse($assignment->assigned_date);
                            @endphp

                            <div>
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Assigned Date</h4>
                                <p class="text-gray-800 dark:text-white">{{ $assignedDate->format('M d, Y') }}</p>
                            </div>

                            <div>
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Due Date</h4>
                                @php
                                    $dueDate = $assignment->due_date instanceof Carbon ? $assignment->due_date : Carbon::parse($assignment->due_date);
                                    $dueTime = $assignment->due_time instanceof Carbon ? $assignment->due_time : Carbon::parse($assignment->due_time);
                                @endphp

                                <p class="text-gray-800 dark:text-white">
                                    {{ $dueDate->format('M d, Y') }} at {{ $dueTime->format('h:i A') }}
                                </p>

                                @php
                                    $now = \Carbon\Carbon::now();
                                    $dueDateTime = \Carbon\Carbon::parse($assignment->due_date . ' ' . $assignment->due_time);
                                    $diffInDays = $now->startOfDay()->diffInDays($dueDateTime->startOfDay(), false);
                                @endphp

                                @if($diffInDays < 0)
                                    <span class="text-xs text-red-600 font-medium">Overdue by {{ abs($diffInDays) }} days</span>
                                @elseif($diffInDays == 0)
                                    <span class="text-xs text-orange-600 font-medium">Due today</span>
                                @else
                                    <span class="text-xs text-green-600 font-medium">{{ abs($diffInDays) }} days remaining</span>
                                @endif
                            </div>

                            <div>
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Full Marks</h4>
                                <p class="text-gray-800 dark:text-white">{{ $assignment->full_marks }}</p>
                            </div>

                            <div>
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Teacher</h4>
                                <p class="text-gray-800 dark:text-white">{{ $assignment->teacher->full_name}}</p>
                            </div>

                            <!-- Submission Status -->
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Submission Status</h4>
                                @if(isset($submission) && $submission)
                                    <p class="text-green-600 dark:text-green-400 font-medium">Submitted</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">
                                        {{ Carbon::parse($submission->created_at)->format('M d, Y h:i A') }}
                                    </p>
                                @else
                                    <p class="text-yellow-600 dark:text-yellow-400 font-medium">Not Submitted</p>
                                @endif
                            </div>

                            <!-- Grade Status -->
                            @if(isset($submission) && $submission)
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Grade Status</h4>
                                    @if(isset($submission->grade))
                                        <p class="text-green-600 dark:text-green-400 font-medium">
                                            {{ $submission->grade }}/{{ $assignment->full_marks }}
                                            ({{ round(($submission->grade / $assignment->full_marks) * 100) }}%)
                                        </p>
                                    @else
                                        <p class="text-yellow-600 dark:text-yellow-400 font-medium">Not Graded Yet</p>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Submission Preview Modal (Hidden by default) -->
    <div id="submissionPreviewModal" class="fixed h-full inset-0 bg-black bg-opacity-75 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl h-full max-w-8xl w-full flex flex-col">
            <div class="flex justify-between items-center p-4 border-b">
                <h3 class="text-lg font-medium" id="submissionPreviewTitle">Submission Preview</h3>
                <button onclick="closeSubmissionPreview()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="flex-1 overflow-auto p-4 h-full">
                <iframe id="submissionPreviewFrame" class="w-full h-full border-0"></iframe>
                <div id="submissionUnsupportedPreview" class="hidden text-center py-8">
                    <i class="fas fa-exclamation-triangle text-yellow-500 text-4xl mb-4"></i>
                    <p class="text-gray-700 dark:text-gray-300">Preview not available for this file type</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // File preview for assignment resources
        function openPreview(attachmentId) {
            const attachment = {!! $assignment->attachments->keyBy('id')->toJson() !!}[attachmentId];
            const modal = document.getElementById('previewModal');
            const frame = document.getElementById('previewFrame');
            const title = document.getElementById('previewTitle');
            const unsupported = document.getElementById('unsupportedPreview');

            title.textContent = attachment.original_name;
            const url = `/student/assignment/${attachment.parent_id}/view/${attachmentId}`;

            fetch(url)
                .then(response => {
                    if (response.ok) return response.blob();
                    throw new Error('Failed to fetch the file');
                })
                .then(blob => {
                    const previewUrl = URL.createObjectURL(blob);
                    frame.src = previewUrl;
                })
                .catch(error => {
                    console.error('Error fetching the file:', error);
                });

            if (
                attachment.file_type.includes('pdf') ||
                attachment.file_type.includes('image') ||
                attachment.file_type.includes('text')
            ) {
                unsupported.classList.add('hidden');
                frame.classList.remove('hidden');
            } else {
                frame.classList.add('hidden');
                unsupported.classList.remove('hidden');
            }

            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closePreview() {
            document.getElementById('previewModal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
            document.getElementById('previewFrame').src = '';
        }

        // File preview for submission files
        function openSubmissionPreview(attachmentId) {
            const attachment = {!! isset($submission) && $submission->attachments ? $submission->attachments->keyBy('id')->toJson() : '{}' !!}[attachmentId];
            const modal = document.getElementById('submissionPreviewModal');
            const frame = document.getElementById('submissionPreviewFrame');
            const title = document.getElementById('submissionPreviewTitle');
            const unsupported = document.getElementById('submissionUnsupportedPreview');

            title.textContent = attachment.original_name;
            const url = `/student/submission/${attachment.parent_id}/view/${attachmentId}`;

            fetch(url)
                .then(response => {
                    if (response.ok) return response.blob();
                    throw new Error('Failed to fetch the file');
                })
                .then(blob => {
                    const previewUrl = URL.createObjectURL(blob);
                    frame.src = previewUrl;
                })
                .catch(error => {
                    console.error('Error fetching the file:', error);
                });

            if (
                attachment.file_type.includes('pdf') ||
                attachment.file_type.includes('image') ||
                attachment.file_type.includes('text')
            ) {
                unsupported.classList.add('hidden');
                frame.classList.remove('hidden');
            } else {
                frame.classList.add('hidden');
                unsupported.classList.remove('hidden');
            }

            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeSubmissionPreview() {
            document.getElementById('submissionPreviewModal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
            document.getElementById('submissionPreviewFrame').src = '';
        }

        // Show submission form when updating
        function showSubmissionForm() {
            document.getElementById('submissionForm').classList.remove('hidden');
        }

        // File upload preview
        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('file-upload');
            const fileList = document.getElementById('file-list');

            if (fileInput) {
                fileInput.addEventListener('change', function() {
                    fileList.innerHTML = '';

                    for (let i = 0; i < this.files.length; i++) {
                        const file = this.files[i];
                        const fileItem = document.createElement('div');
                        fileItem.className = 'flex items-center text-sm text-gray-600 dark:text-gray-400';

                        // Icon based on file type
                        let icon = 'fa-file';
                        if (file.type.includes('pdf')) {
                            icon = 'fa-file-pdf';
                        } else if (file.type.includes('word') || file.name.endsWith('.doc') || file.name.endsWith('.docx')) {
                            icon = 'fa-file-word';
                        } else if (file.type.includes('image')) {
                            icon = 'fa-file-image';
                        } else if (file.type.includes('zip') || file.name.endsWith('.zip')) {
                            icon = 'fa-file-archive';
                        }

                        fileItem.innerHTML = `
                            <i class="fas ${icon} mr-2"></i>
                            <span>${file.name}</span>
                            <span class="ml-2 text-xs">(${formatFileSize(file.size)})</span>
                        `;

                        fileList.appendChild(fileItem);
                    }
                });
            }

            function formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            }
        });
    </script>
@endsection
