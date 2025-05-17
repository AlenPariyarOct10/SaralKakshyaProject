@extends("backend.layout.teacher-dashboard-layout")

@section('title', 'View Assignment')

@section('content')
    <!-- Main Content Area -->
    <main class="p-6 md:p-6 min-h-screen overflow-y-auto pb-16">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-white mb-1">
                    Assignment: {{ $assignment->title }}
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Created: {{ Carbon\Carbon::parse($assignment->created_at)->diffForHumans() }}
                </p>
            </div>
            <a href="{{ route('teacher.assignment.index') }}" class="mt-4 md:mt-0 btn-secondary flex items-center justify-center">
                <i class="fas fa-arrow-left mr-2"></i> Back to Assignments
            </a>
        </div>

        <!-- Assignment Details -->
        <div class="card mb-8">
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Left Column -->
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Details</h3>
                            <div class="space-y-4">
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Subject</p>
                                    <p class="text-gray-700 dark:text-gray-300">{{ $assignment->subject->name }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Chapter</p>
                                    <p class="text-gray-700 dark:text-gray-300">
                                        {{ $assignment->chapter->title }}
                                        @if($assignment->subChapter)
                                            <span class="text-gray-500 dark:text-gray-400"> → {{ $assignment->subChapter->title }}</span>
                                        @endif
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Status</p>
                                    <span class="px-2 py-1 text-xs rounded-full
                                        {{ $assignment->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ ucfirst($assignment->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Description</h3>
                            <div class="prose dark:prose-invert max-w-none">
                                {!! nl2br(e($assignment->description)) !!}
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Timeline</h3>
                            <div class="space-y-4">
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Assigned Date</p>
                                    <p class="text-gray-700 dark:text-gray-300">
                                        {{ \Carbon\Carbon::parse($assignment->assigned_date)->format('M d, Y') }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Due Date</p>
                                    @php
                                        use Carbon\Carbon;

                                        $dueDateTime = Carbon::parse($assignment->due_date . ' ' . $assignment->due_time);
                                    @endphp

                                    <p class="text-gray-700 dark:text-gray-300">
                                        {{ $dueDateTime->format('M d, Y') }} at {{ $dueDateTime->format('h:i A') }}
                                        <span class="ml-2 text-sm text-gray-500">
                                            @if($dueDateTime->isPast())
                                                (Past due)
                                            @else
                                                ({{ $dueDateTime->diffForHumans(null, true) }} left)
                                            @endif
                                         </span>
                                    </p>

                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Full Marks</p>
                                    <p class="text-gray-700 dark:text-gray-300">{{ $assignment->full_marks }}</p>
                                </div>
                            </div>
                        </div>

                        @if($assignment->attachments->count() > 0)
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Resources</h3>
                                <div class="space-y-2">
                                    @foreach($assignment->attachments as $attachment)
                                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-md">
                                            <div class="flex items-center">
                                                @php
                                                    $icon = 'fa-file';
                                                    if (Str::contains($attachment->file_type, 'pdf')) $icon = 'fa-file-pdf text-red-500';
                                                    elseif (Str::contains($attachment->file_type, 'word')) $icon = 'fa-file-word text-blue-500';
                                                    elseif (Str::contains($attachment->file_type, 'image')) $icon = 'fa-file-image text-green-500';
                                                    elseif (Str::contains($attachment->file_type, 'zip')) $icon = 'fa-file-archive text-yellow-500';
                                                @endphp
                                                <i class="fas {{ $icon }} mr-3"></i>
                                                <span class="text-gray-700 dark:text-gray-300 truncate max-w-xs">{{ $attachment->title }}</span>
                                            </div>
                                            <a href="{{ route('teacher.assignment.download', [$assignment->id, $attachment->id]) }}"
                                               class="text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300">
                                                <i class="fas fa-download"></i>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="mt-8 flex justify-end space-x-3">
                    <a href="{{ route('teacher.assignment.edit', $assignment->id) }}" class="btn-secondary">
                        <i class="fas fa-edit mr-2"></i> Edit
                    </a>
                </div>
            </div>
        </div>
    </main>
@endsection
