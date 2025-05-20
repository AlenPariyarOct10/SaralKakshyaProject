@extends("backend.layout.teacher-dashboard-layout")

@section('title', $resource->title)

@section('content')
    <main class="scrollable-content p-4 md:p-6">
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-semibold text-gray-800 dark:text-white">{{ $resource->title }}</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                        {{ $resource->type == 'document' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' :
                           ($resource->type == 'video' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' :
                           ($resource->type == 'audio' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' :
                           ($resource->type == 'link' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200'))) }}">
                        {{ ucfirst($resource->type) }}
                    </span>
                    <span class="ml-2">Added on {{ $resource->created_at->format('M d, Y') }}</span>
                </p>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('teacher.resources.edit', $resource->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded-md transition duration-300 ease-in-out">
                    <i class="fas fa-edit mr-2"></i> Edit
                </a>
                <a href="{{ route('teacher.resources.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-md transition duration-300 ease-in-out">
                    <i class="fas fa-arrow-left mr-2"></i> Back
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="md:col-span-2">
                <div class="card mb-6">
                    <h2 class="text-xl font-medium text-gray-800 dark:text-white mb-4">Description</h2>
                    <div class="prose max-w-none dark:prose-invert">
                        {{ $resource->description }}
                    </div>
                </div>

                <!-- Links -->
                @if($resource->links->count() > 0)
                    <div class="card mb-6">
                        <h2 class="text-xl font-medium text-gray-800 dark:text-white mb-4">Links</h2>
                        <div class="space-y-4">
                            @foreach($resource->links as $link)
                                <div class="border border-gray-200 dark:border-gray-700 rounded-md p-4">
                                    <div class="flex justify-between items-center">
                                        <h3 class="text-lg font-medium text-gray-800 dark:text-white">{{ $link->title }}</h3>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                            {{ ucfirst($link->link_type) }}
                                        </span>
                                    </div>
                                    <a href="{{ $link->url }}" target="_blank" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 break-all">
                                        {{ $link->url }}
                                        <i class="fas fa-external-link-alt ml-1 text-xs"></i>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Attachments -->
                @if(isset($attachments) && $attachments->count() > 0)
                    <div class="card mb-6">
                        <h2 class="text-xl font-medium text-gray-800 dark:text-white mb-4">Attachments</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($attachments as $attachment)
                                <div class="border border-gray-200 dark:border-gray-700 rounded-md p-4">
                                    <div class="flex justify-between items-center mb-2">
                                        <h3 class="text-lg font-medium text-gray-800 dark:text-white">{{ $attachment->title }}</h3>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                            {{ ucfirst($attachment->file_type) }}
                                        </span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ \Illuminate\Support\Str::afterLast($attachment->path, '/') }}
                                        </span>
                                        <a href="{{ asset('storage/' . $attachment->path) }}" download class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                            <i class="fas fa-download"></i> Download
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="md:col-span-1">
                <div class="card mb-6">
                    <h2 class="text-xl font-medium text-gray-800 dark:text-white mb-4">Details</h2>
                    <div class="space-y-3">
                        <div>
                            <span class="text-gray-600 dark:text-gray-400 font-medium">Subject:</span>
                            <span class="ml-2 text-gray-800 dark:text-white">{{ $resource->subject->name ?? 'N/A' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600 dark:text-gray-400 font-medium">Chapter:</span>
                            <span class="ml-2 text-gray-800 dark:text-white">{{ $resource->chapter->name ?? 'N/A' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600 dark:text-gray-400 font-medium">Sub Chapter:</span>
                            <span class="ml-2 text-gray-800 dark:text-white">{{ $resource->subChapter->name ?? 'N/A' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600 dark:text-gray-400 font-medium">Created:</span>
                            <span class="ml-2 text-gray-800 dark:text-white">{{ $resource->created_at->format('M d, Y') }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600 dark:text-gray-400 font-medium">Last Updated:</span>
                            <span class="ml-2 text-gray-800 dark:text-white">{{ $resource->updated_at->format('M d, Y') }}</span>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <h2 class="text-xl font-medium text-gray-800 dark:text-white mb-4">Statistics</h2>
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <i class="fas fa-eye text-blue-500 mr-2"></i>
                            <span class="text-gray-600 dark:text-gray-400 font-medium">Views:</span>
                            <span class="ml-2 text-gray-800 dark:text-white">{{ $resource->views_count }}</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-download text-green-500 mr-2"></i>
                            <span class="text-gray-600 dark:text-gray-400 font-medium">Downloads:</span>
                            <span class="ml-2 text-gray-800 dark:text-white">{{ $resource->download_count }}</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-link text-purple-500 mr-2"></i>
                            <span class="text-gray-600 dark:text-gray-400 font-medium">Links:</span>
                            <span class="ml-2 text-gray-800 dark:text-white">{{ $resource->links->count() }}</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-paperclip text-yellow-500 mr-2"></i>
                            <span class="text-gray-600 dark:text-gray-400 font-medium">Attachments:</span>
                            <span class="ml-2 text-gray-800 dark:text-white">{{ isset($attachments) ? $attachments->count() : 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
