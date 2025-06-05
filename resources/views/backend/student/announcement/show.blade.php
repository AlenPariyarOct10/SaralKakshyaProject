@extends('backend.layout.student-dashboard-layout')
@section('title', $announcement->title)

@section('username', auth()->guard('student')->user()->full_name)

@php
    $user = auth()->guard('student')->user();
@endphp

@section('content')
    <div class="scrollable-content p-4 md:p-6">
        <div class="mb-4">
            <a href="{{ route('student.announcement.index') }}" class="inline-flex items-center text-sm font-medium text-primary-600 dark:text-primary-400 hover:underline">
                <i class="fas fa-arrow-left mr-2"></i> Back to announcements
            </a>
        </div>

        <div class="card mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4">
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">{{ $announcement->title }}</h1>

                @if($announcement->pinned)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800 dark:bg-primary-900 dark:text-primary-200 mt-2 md:mt-0">
                    <i class="fas fa-thumbtack mr-1"></i> Pinned
                </span>
                @endif
            </div>

            <div class="flex flex-wrap items-center text-sm text-gray-500 dark:text-gray-400 mb-6">
                <div class="flex items-center mr-4 mb-2">
                    <i class="far fa-calendar-alt mr-2"></i>
                    <span>{{ $announcement->created_at->format('M d, Y h:i A') }}</span>
                </div>

                @if($announcement->institute)
                    <div class="flex items-center mr-4 mb-2">
                        <i class="fas fa-university mr-2"></i>
                        <span>{{ $announcement->institute->name }}</span>
                    </div>
                @endif

                @if($announcement->type)
                    <div class="flex items-center mb-2">
                        <i class="fas fa-tag mr-2"></i>
                        <span>{{ ucfirst($announcement->type) }}</span>
                    </div>
                @endif
            </div>

            <div class="prose dark:prose-invert max-w-none mb-6">
                {!! $announcement->content !!}
            </div>

            @if($announcement->attachments->count() > 0)
                <div class="border-t dark:border-gray-700 pt-4 mt-6">
                    <h3 class="text-lg font-medium text-gray-800 dark:text-white mb-3">
                        <i class="fas fa-paperclip mr-2"></i> Attachments
                    </h3>

                    <div class="grid gap-3">
                        @foreach($announcement->attachments as $attachment)
                            <a href="{{ asset('storage/' . $attachment->path) }}"
                               target="_blank"
                               class="flex items-center p-3 border dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                                <div class="flex-shrink-0 mr-3">
                                    @php
                                        $extension = pathinfo($attachment->original_name, PATHINFO_EXTENSION);
                                        $iconClass = 'fa-file';

                                        if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'svg'])) {
                                            $iconClass = 'fa-file-image';
                                        } elseif (in_array($extension, ['pdf'])) {
                                            $iconClass = 'fa-file-pdf';
                                        } elseif (in_array($extension, ['doc', 'docx'])) {
                                            $iconClass = 'fa-file-word';
                                        } elseif (in_array($extension, ['xls', 'xlsx'])) {
                                            $iconClass = 'fa-file-excel';
                                        } elseif (in_array($extension, ['ppt', 'pptx'])) {
                                            $iconClass = 'fa-file-powerpoint';
                                        } elseif (in_array($extension, ['zip', 'rar', '7z'])) {
                                            $iconClass = 'fa-file-archive';
                                        }
                                    @endphp
                                    <i class="far {{ $iconClass }} text-2xl text-gray-500 dark:text-gray-400"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                        {{ $attachment->title ?? 'Untitled' }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $attachment->size_formatted }}
                                    </p>
                                </div>
                                <div class="ml-4">
                                    <i class="fas fa-download text-gray-500 dark:text-gray-400"></i>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
