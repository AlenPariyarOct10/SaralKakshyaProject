@extends("backend.layout.teacher-dashboard-layout")

@section('title', 'View Submission')

@php
    function getStatusClass($status) {
        switch(strtolower($status)) {
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

    function getFileIcon($filePath) {
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);

        switch(strtolower($extension)) {
            case 'pdf':
                return 'fa-file-pdf text-red-500';
            case 'doc':
            case 'docx':
                return 'fa-file-word text-blue-500';
            case 'xls':
            case 'xlsx':
                return 'fa-file-excel text-green-500';
            case 'ppt':
            case 'pptx':
                return 'fa-file-powerpoint text-orange-500';
            case 'jpg':
            case 'jpeg':
            case 'png':
            case 'gif':
                return 'fa-file-image text-purple-500';
            case 'zip':
            case 'rar':
                return 'fa-file-archive text-yellow-500';
            case 'mp4':
            case 'avi':
            case 'mov':
                return 'fa-file-video text-blue-500';
            default:
                return 'fa-file text-gray-500';
        }
    }

    function formatFileSize($bytes) {
        if ($bytes == 0) return '0 Bytes';

        $k = 1024;
        $sizes = ['Bytes', 'KB', 'MB', 'GB'];
        $i = floor(log($bytes) / log($k));

        return round($bytes / pow($k, $i), 2) . ' ' . $sizes[$i];
    }
@endphp

@section('content')
    <!-- Main Content Area -->
    <main class="p-6 md:p-6 min-h-screen overflow-y-auto pb-16">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-white mb-1">
                    Assignment Submission
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Viewing submission for {{ $submission->assignment->title }}
                </p>
            </div>
            <div class="mt-4 md:mt-0 flex space-x-3">
                <a href="{{ route('teacher.assignment.submission.index', \Illuminate\Support\Facades\Auth::user()->id) }}" class="btn-secondary flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Submissions
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Submission Details -->
            <div class="lg:col-span-2">
                <div class="card mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Submission Details</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Assignment</h4>
                                <p class="text-base text-gray-900 dark:text-white">{{ $submission->assignment->title }}</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Subject</h4>
                                <p class="text-base text-gray-900 dark:text-white">{{ $submission->assignment->subject->name ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Submitted At</h4>
                                <p class="text-base text-gray-900 dark:text-white">{{ $submission->submitted_at }}</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</h4>
                                <p class="text-base">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ getStatusClass($submission->status) }}">
                                        {{ ucfirst($submission->status) }}
                                    </span>
                                    @if($submission->assignment->due_date < $submission->submitted_at)
                                        <span class="ml-2 px-2 py-1 text-xs font-medium rounded-full bg-red-100 dark:bg-red-800 text-red-800 dark:text-red-100">
                                            Late
                                        </span>
                                    @endif
                                </p>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Marks</h4>
                                <p class="text-base text-gray-900 dark:text-white">
                                    @if($submission->marks)
                                        {{ $submission->marks }} / {{ $submission->assignment->full_marks }}
                                    @else
                                        Not graded
                                    @endif
                                </p>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Due Date</h4>
                                <p class="text-base text-gray-900 dark:text-white">{{ $submission->assignment->due_date }}</p>
                            </div>
                        </div>

                        @if($submission->feedback)
                            <div class="mb-6">
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Feedback</h4>
                                <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-md">
                                    <p class="text-gray-900 dark:text-white">{{ $submission->feedback }}</p>
                                </div>
                            </div>
                        @endif

                        <div>
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Attachments</h4>
                            @if($submission->attachments->count() > 0)
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach($submission->attachments as $attachment)
                                        <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-md hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                            <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center bg-gray-200 dark:bg-gray-600 rounded">
                                                <i class="fas {{ getFileIcon($attachment->path) }} text-gray-500 dark:text-gray-400"></i>
                                            </div>
                                            <div class="ml-3 flex-grow">
                                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $attachment->title }}</p>
                                            </div>
                                            <div class="flex space-x-2 ml-2">
                                                <a href="{{ route('teacher.assignment.submission.view',$attachment->id) }}"
                                                   class="text-blue-500 hover:text-blue-700"
                                                   title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('teacher.assignment.submission.download', $attachment->id) }}"
                                                   class="text-green-500 hover:text-green-700"
                                                   title="Download">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500 dark:text-gray-400">No attachments found</p>
                            @endif
                        </div>

                    </div>
                </div>

                <!-- Assignment Details -->
                <div class="card">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Assignment Details</h3>

                        <div class="mb-6">
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Description</h4>
                            <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-md">
                                <p class="text-gray-900 dark:text-white">{{ $submission->assignment->description }}</p>
                            </div>
                        </div>

                        <div>
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Assignment Resources</h4>
                            @if($submission->assignment->attachments->count() > 0)
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach($submission->assignment->attachments as $attachment)

                                        <a href="{{ route('teacher.assignment.download', $attachment->id) }}"
                                           class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-md hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                            <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center bg-gray-200 dark:bg-gray-600 rounded">
                                                <i class="fas {{ getFileIcon($attachment->path) }} text-gray-500 dark:text-gray-400"></i>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $attachment->original_name }}</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ formatFileSize($attachment->size) }}</p>
                                            </div>
                                        </a>

                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500 dark:text-gray-400">No resources attached to this assignment</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Student Information -->
            <div class="lg:col-span-1">
                <div class="card mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Student Information</h3>

                        <div class="flex items-center mb-6">
                            <div class="flex-shrink-0 h-16 w-16">
                                <img class="h-16 w-16 rounded-full" src="{{ asset("storage/".$submission->student->profile_picture) ?? '/placeholder.svg?height=64&width=64' }}" alt="{{ $submission->student->full_name }}">
                            </div>
                            <div class="ml-4">
                                <h4 class="text-lg font-medium text-gray-900 dark:text-white">{{ $submission->student->full_name }}</h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400">ID: {{ $submission->student->id }}</p>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</h4>
                                <p class="text-base text-gray-900 dark:text-white">{{ $submission->student->email }}</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Batch</h4>
                                <p class="text-base text-gray-900 dark:text-white">{{ $submission->student->batch->batch ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Program</h4>
                                <p class="text-base text-gray-900 dark:text-white">{{ $submission->student->batch->program->name ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Grading Form -->
                <div class="card">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Quick Grading</h3>

                        <form action="" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="space-y-4">
                                <div>
                                    <label for="marks" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Marks</label>
                                    <input type="number" id="marks" name="marks" value="{{ $submission->marks }}" min="0" max="{{ $submission->assignment->full_marks }}"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-800 dark:border-gray-700 dark:text-white"
                                           placeholder="Enter marks">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Out of {{ $submission->assignment->full_marks }}</p>
                                </div>

                                <div>
                                    <label for="feedback" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Feedback</label>
                                    <textarea id="feedback" name="feedback" rows="4"
                                              class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-800 dark:border-gray-700 dark:text-white"
                                              placeholder="Enter feedback for the student">{{ $submission->feedback }}</textarea>
                                </div>

                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                                    <select id="status" name="status"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                                        <option value="submitted" >Submitted</option>
                                        <option value="graded" selected>Graded</option>
                                    </select>
                                </div>

                                <div class="pt-4">
                                    <button type="submit" class="w-full btn-primary">
                                        Save Grading
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Any additional JavaScript can go here
        });
    </script>
@endsection
