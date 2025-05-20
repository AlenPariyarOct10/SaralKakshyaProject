@php use Illuminate\Support\Str; @endphp
@extends("backend.layout.teacher-dashboard-layout")

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

                <a href="{{ route('teacher.assignment.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
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
                                @if($assignment->status == 'active' || $assignment->status == 'Active')
                                    <span class="px-3 py-1 text-xs font-medium rounded-full bg-green-100 dark:bg-green-800 text-green-800 dark:text-green-100">Active</span>
                                @else
                                    <span class="px-3 py-1 text-xs font-medium rounded-full bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300">Draft</span>
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
                                                    <a href="{{ route('teacher.assignment.download', [$assignment->id, $attachment->id]) }}"
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
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Created By</h4>
                                <p class="text-gray-800 dark:text-white">{{ $assignment->teacher->full_name}}</p>
                            </div>

                            <div>
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Created At</h4>
                                <p class="text-gray-800 dark:text-white">{{ Carbon::parse($assignment->created_at)->format('M d, Y') }} ( {{ Carbon::parse($assignment->created_at)->diffForHumans() }}   )</p>
                            </div>

                            @if($assignment->updated_at != $assignment->created_at)
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Last Updated</h4>
                                    <p class="text-gray-800 dark:text-white">{{ Carbon::parse($assignment->updated_at)->diffForHumans() }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Submission Stats -->
                <div class="card">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Submission Statistics</h3>

                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Students</h4>
                                <p class="text-gray-800 dark:text-white font-medium">{{ $assignment->total_students ?? 0 }}</p>
                            </div>

                            <div class="flex items-center justify-between">
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Submissions</h4>
                                <p class="text-gray-800 dark:text-white font-medium">{{ $assignment->submissions_count ?? 0 }}</p>
                            </div>

                            <div class="flex items-center justify-between">
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Graded</h4>
                                <p class="text-gray-800 dark:text-white font-medium">{{ $assignment->graded_count ?? 0 }}</p>
                            </div>

                            <div class="flex items-center justify-between">
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Average Score</h4>
                                <p class="text-gray-800 dark:text-white font-medium">
                                    @if(isset($assignment->average_score))
                                        {{ number_format($assignment->average_score, 1) }} / {{ $assignment->full_marks }}
                                    @else
                                        N/A
                                    @endif
                                </p>
                            </div>

                            <!-- Progress Bar -->
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Submission Rate</h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        @if(isset($assignment->total_students) && $assignment->total_students > 0)
                                            {{ round(($assignment->submissions_count / $assignment->total_students) * 100) }}%
                                        @else
                                            0%
                                        @endif
                                    </p>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                                    <div class="bg-primary-600 h-2.5 rounded-full" style="width: {{ isset($assignment->total_students) && $assignment->total_students > 0 ? round(($assignment->submissions_count / $assignment->total_students) * 100) : 0 }}%"></div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6">
                            <a href="{{ route('teacher.assignment.submissions', $assignment->id) }}" class="btn-primary w-full flex items-center justify-center">
                                <i class="fas fa-clipboard-list mr-2"></i> View Submissions
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-wrap gap-3 justify-end mb-6">
            <a href="{{ route('teacher.assignment.edit', $assignment->id) }}" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 flex items-center">
                <i class="fas fa-edit mr-2"></i> Edit Assignment
            </a>
            <button type="button" id="deleteAssignmentBtn" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 flex items-center">
                <i class="fas fa-trash-alt mr-2"></i> Delete Assignment
            </button>
        </div>
    </main>

    <!-- Delete Confirmation Modal (Hidden by default) -->
    <div id="deleteConfirmationModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
        <div class="relative bg-white dark:bg-gray-800 rounded-lg max-w-md w-full mx-4 max-h-[90vh] overflow-y-auto">
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

                <form action="{{ route('teacher.assignment.destroy', $assignment->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="flex justify-end space-x-3">
                        <button type="button" id="cancelDelete" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                            Delete
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>

        function openPreview(attachmentId) {
            const attachment = {!! $assignment->attachments->keyBy('id')->toJson() !!}[attachmentId];
            const modal = document.getElementById('previewModal');
            const frame = document.getElementById('previewFrame');
            const title = document.getElementById('previewTitle');
            const unsupported = document.getElementById('unsupportedPreview');

            title.textContent = attachment.original_name;
            const url = `/teacher/assignment/${attachment.parent_id}/view/${attachmentId}`;

            fetch(url)
                .then(response => {
                    if (response.ok) return response.blob(); // You forgot .blob()
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
        document.addEventListener('DOMContentLoaded', function() {
            // Delete Confirmation Modal
            const deleteAssignmentBtn = document.getElementById('deleteAssignmentBtn');
            const deleteConfirmationModal = document.getElementById('deleteConfirmationModal');
            const closeDeleteModal = document.getElementById('closeDeleteModal');
            const cancelDelete = document.getElementById('cancelDelete');

            // Show Delete Confirmation Modal
            if (deleteAssignmentBtn) {
                deleteAssignmentBtn.addEventListener('click', () => {
                    deleteConfirmationModal.classList.remove('hidden');
                    document.body.style.overflow = 'hidden'; // Prevent background scrolling
                });
            }

            // Close Delete Confirmation Modal
            if (closeDeleteModal) {
                closeDeleteModal.addEventListener('click', () => {
                    deleteConfirmationModal.classList.add('hidden');
                    document.body.style.overflow = ''; // Restore scrolling
                });
            }

            // Cancel Delete Confirmation Modal
            if (cancelDelete) {
                cancelDelete.addEventListener('click', () => {
                    deleteConfirmationModal.classList.add('hidden');
                    document.body.style.overflow = ''; // Restore scrolling
                });
            }
        });
    </script>
@endsection
