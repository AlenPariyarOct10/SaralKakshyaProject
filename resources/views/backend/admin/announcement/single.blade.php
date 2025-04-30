@extends("backend.layout.admin-dashboard-layout")

@php
    $user = \Illuminate\Support\Facades\Auth::user();
@endphp

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
    <style type="text/tailwindcss">
        @layer utilities {
            .btn-primary {
                @apply px-6 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-colors duration-200;
            }
            .btn-secondary {
                @apply px-6 py-2 bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-200 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300 dark:focus:ring-gray-600 focus:ring-offset-2 transition-colors duration-200;
            }
            .card {
                @apply bg-white dark:bg-gray-800 rounded-lg shadow-md p-6;
            }
            .sidebar-item {
                @apply flex items-center gap-3 px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md transition-colors duration-200;
            }
            .sidebar-item.active {
                @apply bg-primary-50 dark:bg-gray-700 text-primary-600 dark:text-primary-400 font-medium;
            }
            .form-input {
                @apply w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:text-white;
            }
            .form-label {
                @apply block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1;
            }
            .form-select {
                @apply w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:text-white bg-white dark:bg-gray-700;
            }
            .form-checkbox {
                @apply h-4 w-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700;
            }
        }
    </style>

@endpush

@section('content')
    <main class="scrollable-content p-4 md:p-6">
        <div class="mb-6">
            <a href="{{ route('admin.announcement.index') }}" class="flex items-center text-primary-600 hover:text-primary-800">
                <i class="fas fa-arrow-left mr-2"></i> Back to Announcements
            </a>
        </div>

        <div class="card">
            <div class="mb-6">
                @if($announcement->type == "regular")
                    <div class="p-6 border-l-4 border-blue-500 bg-blue-50 dark:bg-blue-900/20 rounded-md">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mr-4">
                            <span class="flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 dark:bg-blue-800">
                                <i class="fas fa-bullhorn text-blue-600 dark:text-blue-300 text-xl"></i>
                            </span>
                            </div>
                            <div class="flex-1">
                                <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-2">{{ $announcement->title }}</h2>
                                <div class="flex items-center text-sm text-gray-500 dark:text-gray-400 mb-4">
                                <span class="mr-4">
                                    <i class="fas fa-user mr-1"></i> Posted by {{ ucfirst($announcement->creator_type) }}
                                </span>
                                    <span class="mr-4">
                                    <i class="fas fa-calendar mr-1"></i> {{ $announcement->created_at->format('M d, Y h:i A') }}
                                </span>
                                    @if($announcement->is_pinned)
                                        <span class="text-primary-600">
                                        <i class="fas fa-thumbtack mr-1"></i> Pinned
                                    </span>
                                    @endif
                                </div>
                                <div class="prose dark:prose-invert max-w-none">
                                    <p class="text-gray-700 dark:text-gray-300">
                                        {{ $announcement->content }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif($announcement->type == "important")
                    <div class="p-6 border-l-4 border-yellow-500 bg-yellow-50 dark:bg-yellow-900/20 rounded-md">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mr-4">
                            <span class="flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 dark:bg-yellow-800">
                                <i class="fas fa-clock text-yellow-600 dark:text-yellow-300 text-xl"></i>
                            </span>
                            </div>
                            <div class="flex-1">
                                <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-2">{{ $announcement->title }}</h2>
                                <div class="flex items-center text-sm text-gray-500 dark:text-gray-400 mb-4">
                                <span class="mr-4">
                                    <i class="fas fa-user mr-1"></i> Posted by {{ ucfirst($announcement->creator_type) }}
                                </span>
                                    <span class="mr-4">
                                    <i class="fas fa-calendar mr-1"></i> {{ $announcement->created_at->format('M d, Y h:i A') }}
                                </span>
                                    @if($announcement->is_pinned)
                                        <span class="text-primary-600">
                                        <i class="fas fa-thumbtack mr-1"></i> Pinned
                                    </span>
                                    @endif
                                </div>
                                <div class="prose dark:prose-invert max-w-none">
                                    <p class="text-gray-700 dark:text-gray-300">
                                        {{ $announcement->content }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="p-6 border-l-4 border-red-500 bg-red-50 dark:bg-red-900/20 rounded-md">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mr-4">
                            <span class="flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-800">
                                <i class="fas fa-exclamation-circle text-red-600 dark:text-red-300 text-xl"></i>
                            </span>
                            </div>
                            <div class="flex-1">
                                <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-2">{{ $announcement->title }}</h2>
                                <div class="flex items-center text-sm text-gray-500 dark:text-gray-400 mb-4">
                                <span class="mr-4">
                                    <i class="fas fa-user mr-1"></i> Posted by {{ ucfirst($announcement->creator_type) }}
                                </span>
                                    <span class="mr-4">
                                    <i class="fas fa-calendar mr-1"></i> {{ $announcement->created_at->format('M d, Y h:i A') }}
                                </span>
                                    @if($announcement->is_pinned)
                                        <span class="text-primary-600">
                                        <i class="fas fa-thumbtack mr-1"></i> Pinned
                                    </span>
                                    @endif
                                </div>
                                <div class="prose dark:prose-invert max-w-none">
                                    <p class="text-gray-700 dark:text-gray-300">
                                        {{ $announcement->content }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            @dd($announcement->attachments)
            <!-- Attachments Section -->
            @if($announcement->attachments && count($announcement->attachments) > 0)
                <div class="mt-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">
                        <i class="fas fa-paperclip mr-2"></i> Attachments
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($announcement->attachments as $attachment)

                            @php
                                $fileExtension = pathinfo($attachment->title, PATHINFO_EXTENSION);
                                $fileExtensionLower = strtolower($fileExtension);

                                // Determine file type
                                $isPdf = $fileExtensionLower === 'pdf';
                                $isImage = in_array($fileExtensionLower, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg']);

                                // Document types
                                $isWord = in_array($fileExtensionLower, ['doc', 'docx']);
                                $isExcel = in_array($fileExtensionLower, ['xls', 'xlsx', 'csv']);
                                $isPowerPoint = in_array($fileExtensionLower, ['ppt', 'pptx']);
                                $isText = in_array($fileExtensionLower, ['txt', 'rtf']);
                                $isZip = in_array($fileExtensionLower, ['zip', 'rar', '7z', 'tar', 'gz']);
                            @endphp

                            <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden bg-gray-50 dark:bg-gray-800">
                                @if($isImage)
                                    <div class="relative aspect-video bg-gray-100 dark:bg-gray-900">
                                        <img
                                            src="{{ asset('storage/' . $attachment->path) }}"
                                            alt="{{ $attachment->title }}"
                                            class="w-full h-full object-cover"
                                        />
                                    </div>
                                @elseif($isPdf)
                                    <div class="flex items-center justify-center aspect-video bg-gray-100 dark:bg-gray-900">
                                        <i class="fas fa-file-pdf text-red-500 text-5xl"></i>
                                    </div>
                                @elseif($isWord)
                                    <div class="flex items-center justify-center aspect-video bg-gray-100 dark:bg-gray-900">
                                        <i class="fas fa-file-word text-blue-600 text-5xl"></i>
                                    </div>
                                @elseif($isExcel)
                                    <div class="flex items-center justify-center aspect-video bg-gray-100 dark:bg-gray-900">
                                        <i class="fas fa-file-excel text-green-600 text-5xl"></i>
                                    </div>
                                @elseif($isPowerPoint)
                                    <div class="flex items-center justify-center aspect-video bg-gray-100 dark:bg-gray-900">
                                        <i class="fas fa-file-powerpoint text-orange-500 text-5xl"></i>
                                    </div>
                                @elseif($isText)
                                    <div class="flex items-center justify-center aspect-video bg-gray-100 dark:bg-gray-900">
                                        <i class="fas fa-file-alt text-gray-600 text-5xl"></i>
                                    </div>
                                @elseif($isZip)
                                    <div class="flex items-center justify-center aspect-video bg-gray-100 dark:bg-gray-900">
                                        <i class="fas fa-file-archive text-yellow-600 text-5xl"></i>
                                    </div>
                                @else
                                    <div class="flex items-center justify-center aspect-video bg-gray-100 dark:bg-gray-900">
                                        <i class="fas fa-file text-gray-500 text-5xl"></i>
                                    </div>
                                @endif

                                <div class="p-3">
                                    <div class="flex items-center justify-between">
                                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300 truncate" title="{{ $attachment->title }}">
                                            {{ $attachment->title }}
                                        </p>
                                        <div class="flex space-x-2">
                                            <a href="{{ asset('storage/' . $attachment->path) }}"
                                               target="_blank"
                                               class="text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300"
                                               title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ asset('storage/' . $attachment->path) }}"
                                               download="{{ $attachment->title }}"
                                               class="text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300"
                                               title="Download">
                                                <i class="fas fa-download"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-between mt-1">
                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                {{ strtoupper($fileExtension) }}
                            </span>
                                        @if($attachment->file_size)
                                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ round($attachment->file_size / 1024, 2) }} KB
                                </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if(auth()->check() && ( auth()->user()->id == $announcement->creator_id))
                <div class="mt-6 flex space-x-4">
                    <a href="{{ route('admin.announcement.edit', $announcement->id) }}" class="px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-colors">
                        <i class="fas fa-edit mr-2"></i> Edit
                    </a>
                    <form action="{{ route('admin.announcement.destroy', $announcement->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this announcement?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors">
                            <i class="fas fa-trash mr-2"></i> Delete
                        </button>
                    </form>
                    @if(!$announcement->is_pinned)
                        <form action="{{ route('admin.announcement.pin', $announcement->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                                <i class="fas fa-thumbtack mr-2"></i> Pin
                            </button>
                        </form>
                    @else
                        <form action="{{ route('admin.announcement.unpin', $announcement->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                                <i class="fas fa-thumbtack-slash mr-2"></i> Unpin
                            </button>
                        </form>
                    @endif
                </div>
            @endif

            @if(isset($relatedAnnouncements) && count($relatedAnnouncements) > 0)
                <div class="mt-10">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Related Announcements</h3>
                    <div class="space-y-4">
                        @foreach($relatedAnnouncements as $related)
                            <a href="{{ route('announcements.show', $related->id) }}" class="block p-4 border border-gray-200 dark:border-gray-700 rounded-md hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 mr-3">
                                        <span class="flex items-center justify-center h-10 w-10 rounded-full bg-gray-100 dark:bg-gray-800">
                                            <i class="fa fa-bullhorn text-gray-500 dark:text-gray-400" aria-hidden="true"></i>
                                        </span>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="text-md font-medium text-gray-800 dark:text-white">{{ $related->title }}</h4>
                                        <div class="flex items-center justify-between mt-2">
                                            <span class="text-xs text-gray-500 dark:text-gray-400">Posted by {{ ucfirst($related->creator_type) }}</span>
                                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $related->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </main>
@endsection
