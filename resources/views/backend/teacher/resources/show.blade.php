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
                                    <div class="flex justify-between items-center mb-2">
                                        <h3 class="text-lg font-medium text-gray-800 dark:text-white">{{ $link->title }}</h3>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                            {{ ucfirst($link->link_type) }}
                                        </span>
                                    </div>

                                    @php
                                        $isYoutube = Str::contains($link->url, ['youtube.com', 'youtu.be']);
                                        $isGoogleDrive = Str::contains($link->url, ['drive.google.com']);
                                        $isOneDrive = Str::contains($link->url, ['onedrive.live.com']);
                                        $isDropbox = Str::contains($link->url, ['dropbox.com']);
                                    @endphp

                                    @if($isYoutube)
                                        <div class="mb-3">
                                            @php
                                                // Extract YouTube video ID
                                                $videoId = null;
                                                if (preg_match('/(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $link->url, $matches)) {
                                                    $videoId = $matches[1];
                                                }
                                            @endphp

                                            @if($videoId)
                                                <div class="relative" style="max-width: 320px;">
                                                    <a href="{{ $link->url }}" target="_blank" class="block relative">
                                                        <img src="https://img.youtube.com/vi/{{ $videoId }}/mqdefault.jpg" alt="{{ $link->title }}" class="w-full h-auto rounded-md" style="max-height: 180px; object-fit: cover;">
                                                        <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                                            <div class="bg-red-600 bg-opacity-80 text-white rounded-full p-2 shadow-md">
                                                                <i class="fas fa-play"></i>
                                                            </div>
                                                        </div>
                                                    </a>
                                                    <a href="{{ $link->url }}" target="_blank" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 mt-2 inline-block">
                                                        {{ $link->url }}
                                                        <i class="fas fa-external-link-alt ml-1 text-xs"></i>
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    @elseif($isGoogleDrive)
                                        <div class="mb-3 flex items-center">
                                            <i class="fab fa-google-drive text-blue-500 text-3xl mr-3"></i>
                                            <div>
                                                <span class="block text-sm text-gray-600 dark:text-gray-400">Google Drive Document</span>
                                                <div class="flex mt-2 space-x-2">
                                                    <a href="{{ $link->url }}" target="_blank" class="bg-blue-500 hover:bg-blue-600 text-white text-xs py-1 px-3 rounded-md transition duration-300">
                                                        <i class="fas fa-external-link-alt mr-1"></i> Open
                                                    </a>
                                                    <a href="{{ $link->url }}&preview=true" target="_blank" class="bg-gray-500 hover:bg-gray-600 text-white text-xs py-1 px-3 rounded-md transition duration-300">
                                                        <i class="fas fa-eye mr-1"></i> Preview
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @elseif($isOneDrive)
                                        <div class="mb-3 flex items-center">
                                            <i class="fab fa-microsoft text-blue-500 text-3xl mr-3"></i>
                                            <div>
                                                <span class="block text-sm text-gray-600 dark:text-gray-400">OneDrive Document</span>
                                                <div class="flex mt-2 space-x-2">
                                                    <a href="{{ $link->url }}" target="_blank" class="bg-blue-500 hover:bg-blue-600 text-white text-xs py-1 px-3 rounded-md transition duration-300">
                                                        <i class="fas fa-external-link-alt mr-1"></i> Open
                                                    </a>
                                                    <a href="{{ $link->url }}&embed=true" target="_blank" class="bg-gray-500 hover:bg-gray-600 text-white text-xs py-1 px-3 rounded-md transition duration-300">
                                                        <i class="fas fa-eye mr-1"></i> Preview
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @elseif($isDropbox)
                                        <div class="mb-3 flex items-center">
                                            <i class="fab fa-dropbox text-blue-500 text-3xl mr-3"></i>
                                            <div>
                                                <span class="block text-sm text-gray-600 dark:text-gray-400">Dropbox File</span>
                                                <div class="flex mt-2 space-x-2">
                                                    <a href="{{ $link->url }}" target="_blank" class="bg-blue-500 hover:bg-blue-600 text-white text-xs py-1 px-3 rounded-md transition duration-300">
                                                        <i class="fas fa-external-link-alt mr-1"></i> Open
                                                    </a>
                                                    <a href="{{ str_replace('www.dropbox.com', 'www.dropbox.com/preview', $link->url) }}" target="_blank" class="bg-gray-500 hover:bg-gray-600 text-white text-xs py-1 px-3 rounded-md transition duration-300">
                                                        <i class="fas fa-eye mr-1"></i> Preview
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="mb-3 flex items-center">
                                            <i class="fas fa-link text-gray-500 text-xl mr-3"></i>
                                            <a href="{{ $link->url }}" target="_blank" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 break-all">
                                                {{ $link->url }}
                                                <i class="fas fa-external-link-alt ml-1 text-xs"></i>
                                            </a>
                                        </div>
                                    @endif

                                    @if($link->description)
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">{{ $link->description }}</p>
                                    @endif
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
                                @php
                                    $fileExtension = pathinfo($attachment->path, PATHINFO_EXTENSION);
                                    $isImage = in_array(strtolower($fileExtension), ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg']);
                                    $isPdf = strtolower($fileExtension) === 'pdf';
                                    $isDoc = in_array(strtolower($fileExtension), ['doc', 'docx']);
                                    $isSpreadsheet = in_array(strtolower($fileExtension), ['xls', 'xlsx', 'csv']);
                                    $isPresentation = in_array(strtolower($fileExtension), ['ppt', 'pptx']);
                                    $isVideo = in_array(strtolower($fileExtension), ['mp4', 'webm', 'mov', 'avi']);
                                    $isAudio = in_array(strtolower($fileExtension), ['mp3', 'wav', 'ogg']);
                                @endphp

                                <div class="border border-gray-200 dark:border-gray-700 rounded-md p-4">
                                    <div class="flex justify-between items-center mb-2">
                                        <h3 class="text-lg font-medium text-gray-800 dark:text-white">{{ $attachment->title }}</h3>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            {{ $isImage ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' :
                                               ($isPdf ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' :
                                               ($isDoc ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' :
                                               ($isSpreadsheet ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' :
                                               ($isPresentation ? 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200' :
                                               ($isVideo ? 'bg-pink-100 text-pink-800 dark:bg-pink-900 dark:text-pink-200' :
                                               ($isAudio ? 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200' :
                                               'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200')))))) }}">
                                            {{ ucfirst($attachment->file_type) }}
                                        </span>
                                    </div>

                                    <div class="flex items-center mb-3">
                                        @if($isImage)
                                            <i class="fas fa-file-image text-green-500 text-xl mr-2"></i>
                                        @elseif($isPdf)
                                            <i class="fas fa-file-pdf text-red-500 text-xl mr-2"></i>
                                        @elseif($isDoc)
                                            <i class="fas fa-file-word text-blue-500 text-xl mr-2"></i>
                                        @elseif($isSpreadsheet)
                                            <i class="fas fa-file-excel text-green-600 text-xl mr-2"></i>
                                        @elseif($isPresentation)
                                            <i class="fas fa-file-powerpoint text-orange-500 text-xl mr-2"></i>
                                        @elseif($isVideo)
                                            <i class="fas fa-file-video text-pink-500 text-xl mr-2"></i>
                                        @elseif($isAudio)
                                            <i class="fas fa-file-audio text-purple-500 text-xl mr-2"></i>
                                        @else
                                            <i class="fas fa-file text-gray-500 text-xl mr-2"></i>
                                        @endif
                                        <span class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ \Illuminate\Support\Str::afterLast($attachment->path, '/') }}
                                        </span>
                                    </div>

                                    <div class="flex justify-between items-center">
                                        <div class="flex space-x-2">
                                            <a href="{{ asset('storage/' . $attachment->path) }}" download class="bg-blue-500 hover:bg-blue-600 text-white text-xs py-1 px-3 rounded-md transition duration-300">
                                                <i class="fas fa-download mr-1"></i> Download
                                            </a>

                                            @if($isImage || $isPdf || $isVideo || $isAudio)
                                                <button type="button" onclick="previewFile('{{ asset('storage/' . $attachment->path) }}', '{{ $fileExtension }}', '{{ $attachment->title }}')" class="bg-gray-500 hover:bg-gray-600 text-white text-xs py-1 px-3 rounded-md transition duration-300">
                                                    <i class="fas fa-eye mr-1"></i> Preview
                                                </button>
                                            @endif
                                        </div>

                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                            @if(file_exists(storage_path('app/public/' . $attachment->path)))
                                                {{ round(filesize(storage_path('app/public/' . $attachment->path)) / 1024, 2) }} KB
                                            @endif
                                        </span>
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
                            <span class="ml-2 text-gray-800 dark:text-white">{{ $resource->subject->name ?? 'Not specified' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600 dark:text-gray-400 font-medium">Chapter:</span>
                            <span class="ml-2 text-gray-800 dark:text-white">{{ $resource->chapter->name ?? 'Not assigned' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600 dark:text-gray-400 font-medium">Sub Chapter:</span>
                            <span class="ml-2 text-gray-800 dark:text-white">{{ $resource->subChapter->name ?? 'Not assigned' }}</span>
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

    <!-- Preview Modal -->
    <div id="previewModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg max-w-4xl w-full max-h-[90vh] overflow-hidden flex flex-col">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <h3 id="previewTitle" class="text-lg font-medium text-gray-800 dark:text-white"></h3>
                <button type="button" onclick="closePreview()" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="flex-1 overflow-auto p-4">
                <div id="previewContent" class="flex items-center justify-center min-h-[300px]"></div>
            </div>
        </div>
    </div>

    <script>
        function previewFile(url, fileType, title) {
            const modal = document.getElementById('previewModal');
            const previewContent = document.getElementById('previewContent');
            const previewTitle = document.getElementById('previewTitle');

            previewTitle.textContent = title;
            previewContent.innerHTML = '';

            fileType = fileType.toLowerCase();

            if (['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'].includes(fileType)) {
                // Image preview
                const img = document.createElement('img');
                img.src = url;
                img.className = 'max-w-full max-h-[70vh] object-contain';
                img.alt = title;
                previewContent.appendChild(img);
            } else if (fileType === 'pdf') {
                // PDF preview
                const iframe = document.createElement('iframe');
                iframe.src = url;
                iframe.className = 'w-full h-[70vh]';
                previewContent.appendChild(iframe);
            } else if (['mp4', 'webm', 'mov'].includes(fileType)) {
                // Video preview
                const video = document.createElement('video');
                video.src = url;
                video.className = 'max-w-full max-h-[70vh]';
                video.controls = true;
                video.autoplay = false;
                previewContent.appendChild(video);
            } else if (['mp3', 'wav', 'ogg'].includes(fileType)) {
                // Audio preview
                const audio = document.createElement('audio');
                audio.src = url;
                audio.className = 'w-full';
                audio.controls = true;
                audio.autoplay = false;

                const audioContainer = document.createElement('div');
                audioContainer.className = 'text-center';

                const icon = document.createElement('div');
                icon.innerHTML = '<i class="fas fa-music text-6xl text-gray-400 dark:text-gray-600 mb-4"></i>';

                audioContainer.appendChild(icon);
                audioContainer.appendChild(audio);
                previewContent.appendChild(audioContainer);
            } else {
                // Unsupported file type
                previewContent.innerHTML = `
                    <div class="text-center">
                        <i class="fas fa-file text-6xl text-gray-400 dark:text-gray-600 mb-4"></i>
                        <p class="text-gray-600 dark:text-gray-400">Preview not available for this file type.</p>
                        <a href="${url}" download class="mt-4 inline-block bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-md transition duration-300">
                            <i class="fas fa-download mr-2"></i> Download Instead
                        </a>
                    </div>
                `;
            }

            modal.classList.remove('hidden');
        }

        function closePreview() {
            const modal = document.getElementById('previewModal');
            const previewContent = document.getElementById('previewContent');

            // Stop any playing media
            const videos = previewContent.querySelectorAll('video');
            const audios = previewContent.querySelectorAll('audio');

            videos.forEach(video => video.pause());
            audios.forEach(audio => audio.pause());

            modal.classList.add('hidden');
        }

        // Close modal when clicking outside of content
        document.getElementById('previewModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closePreview();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !document.getElementById('previewModal').classList.contains('hidden')) {
                closePreview();
            }
        });
    </script>
@endsection
