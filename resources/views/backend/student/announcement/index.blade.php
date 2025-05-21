@extends('backend.layout.student-dashboard-layout')

@section('title', 'Announcements')

@section('username', auth()->guard('student')->user()->full_name)
@php
    $user = auth()->guard('student')->user();
@endphp
@section('content')
    <div class="scrollable-content p-4 md:p-6">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Announcements</h1>
            <p class="text-gray-600 dark:text-gray-400">View all announcements from your institute</p>
        </div>

        @if($announcements->isEmpty())
            <div class="card flex flex-col items-center justify-center py-12">
                <i class="fas fa-bullhorn text-4xl text-gray-400 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-700 dark:text-gray-300">No announcements yet</h3>
                <p class="text-gray-500 dark:text-gray-400 mt-1">Check back later for updates</p>
            </div>
        @else
            <div class="grid gap-4">
                @foreach($announcements as $announcement)
                    <div class="card hover:shadow-lg transition-shadow relative {{ $announcement->pinned ? 'border-l-4 border-primary-500' : '' }}">
                        @if($announcement->pinned)
                            <div class="absolute top-4 right-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800 dark:bg-primary-900 dark:text-primary-200">
                                <i class="fas fa-thumbtack mr-1"></i> Pinned
                            </span>
                            </div>
                        @endif

                        <div class="flex flex-col">
                            <div class="mb-2">
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                                    <a href="{{ route('student.announcement.show', $announcement->id) }}" class="hover:text-primary-600 dark:hover:text-primary-400">
                                        {{ $announcement->title }}
                                    </a>
                                </h3>
                                <div class="flex items-center text-sm text-gray-500 dark:text-gray-400 mt-1">
                                    <i class="far fa-calendar-alt mr-2"></i>
                                    <span>{{ $announcement->created_at->format('M d, Y') }}</span>

                                    @if($announcement->institute)
                                        <span class="mx-2">•</span>
                                        <i class="fas fa-university mr-2"></i>
                                        <span>{{ $announcement->institute->name }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="text-gray-600 dark:text-gray-300 mb-4">
                                {!! Str::limit(strip_tags($announcement->content), 150) !!}
                            </div>

                            <div class="flex justify-between items-center mt-auto">
                                <div class="flex items-center">
                                    @if($announcement->attachments->count() > 0)
                                        <span class="inline-flex items-center text-sm text-gray-500 dark:text-gray-400">
                                        <i class="fas fa-paperclip mr-1"></i>
                                        {{ $announcement->attachments->count() }} {{ Str::plural('attachment', $announcement->attachments->count()) }}
                                    </span>
                                    @endif
                                </div>
                                <a href="{{ route('student.announcement.show', $announcement->id) }}" class="text-primary-600 dark:text-primary-400 hover:underline text-sm font-medium">
                                    Read more <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $announcements->links() }}
            </div>
        @endif
    </div>
@endsection
