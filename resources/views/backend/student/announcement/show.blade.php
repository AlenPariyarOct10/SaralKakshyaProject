@extends('backend.layout.student-dashboard-layout')
@section('title', $announcement->title)

@section('username', auth()->guard('student')->user()->full_name)

@php
    $user = auth()->guard('student')->user();
@endphp

@push('styles')
    <style>
        .attachment-viewer {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.9);
            z-index: 1000;
            backdrop-filter: blur(4px);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .attachment-viewer.active {
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 1;
        }

        .viewer-content {
            max-width: 95%;
            max-height: 95%;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            position: relative;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            transform: scale(0.9);
            transition: transform 0.3s ease;
        }

        .attachment-viewer.active .viewer-content {
            transform: scale(1);
        }

        .dark .viewer-content {
            background: #1f2937;
        }

        .viewer-header {
            padding: 1.5rem;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .dark .viewer-header {
            background: linear-gradient(135deg, #374151 0%, #4b5563 100%);
            border-bottom-color: #4b5563;
        }

        .viewer-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: #1f2937;
            margin: 0;
            max-width: 60%;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .dark .viewer-title {
            color: #f9fafb;
        }

        .viewer-actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .viewer-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            font-weight: 500;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
            text-decoration: none;
            border: none;
            cursor: pointer;
        }

        .viewer-download {
            background: #3b82f6;
            color: white;
        }

        .viewer-download:hover {
            background: #2563eb;
            transform: translateY(-1px);
        }

        .viewer-close {
            background: #ef4444;
            color: white;
            padding: 0.5rem;
            width: 2.5rem;
            height: 2.5rem;
            justify-content: center;
        }

        .viewer-close:hover {
            background: #dc2626;
            transform: translateY(-1px);
        }

        .viewer-body {
            padding: 0;
            max-height: 80vh;
            overflow: auto;
            position: relative;
        }

        .image-viewer {
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8fafc;
            min-height: 400px;
        }

        .dark .image-viewer {
            background: #111827;
        }

        .image-viewer img {
            max-width: 100%;
            max-height: 80vh;
            object-fit: contain;
            border-radius: 0.5rem;
        }

        .pdf-viewer {
            background: #f8fafc;
            min-height: 600px;
        }

        .dark .pdf-viewer {
            background: #111827;
        }

        .pdf-viewer iframe {
            width: 100%;
            height: 80vh;
            border: none;
            border-radius: 0 0 12px 12px;
        }

        .text-viewer {
            padding: 2rem;
            font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
            background: #f8fafc;
            min-height: 400px;
            max-height: 70vh;
            overflow-y: auto;
        }

        .dark .text-viewer {
            background: #111827;
            color: #e5e7eb;
        }

        .text-viewer pre {
            margin: 0;
            white-space: pre-wrap;
            word-wrap: break-word;
            line-height: 1.6;
            font-size: 0.875rem;
        }

        .attachment-item {
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .attachment-item:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .attachment-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .attachment-item:hover::before {
            left: 100%;
        }

        .download-btn {
            opacity: 0;
            transition: all 0.3s ease;
            transform: scale(0.8);
        }

        .attachment-item:hover .download-btn {
            opacity: 1;
            transform: scale(1);
        }

        .preview-btn {
            opacity: 0;
            transition: all 0.3s ease;
            transform: scale(0.8);
        }

        .attachment-item:hover .preview-btn {
            opacity: 1;
            transform: scale(1);
        }

        .loading-spinner {
            width: 3rem;
            height: 3rem;
            border: 4px solid #e5e7eb;
            border-top: 4px solid #3b82f6;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .error-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 3rem;
            text-align: center;
            color: #ef4444;
        }

        .dark .error-state {
            color: #f87171;
        }

        .fade-in {
            animation: fadeIn 0.3s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .viewer-content {
                max-width: 98%;
                max-height: 98%;
            }

            .viewer-header {
                padding: 1rem;
            }

            .viewer-title {
                font-size: 1rem;
                max-width: 50%;
            }

            .pdf-viewer iframe {
                height: 60vh;
            }

            .text-viewer {
                padding: 1rem;
            }
        }
    </style>
@endpush

@section('content')
    <div class="scrollable-content p-4 md:p-6">
        <!-- Breadcrumb Navigation -->
        <nav class="mb-6" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2 text-sm">
                <li>
                    <a href="{{ route('student.dashboard') }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors">
                        <i class="fas fa-home"></i>
                    </a>
                </li>
                <li class="text-gray-400">/</li>
                <li>
                    <a href="{{ route('student.announcement.index') }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors">
                        Announcements
                    </a>
                </li>
                <li class="text-gray-400">/</li>
                <li class="text-gray-900 dark:text-white font-medium">{{ Str::limit($announcement->title, 30) }}</li>
            </ol>
        </nav>

        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('student.announcement.index') }}"
               class="inline-flex items-center px-4 py-2 text-sm font-medium text-primary-600 dark:text-primary-400 bg-primary-50 dark:bg-primary-900/20 rounded-lg hover:bg-primary-100 dark:hover:bg-primary-900/30 transition-all duration-200 hover:scale-105">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Announcements
            </a>
        </div>

        <!-- Main Content Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
            <!-- Header Section -->
            <div class="p-6 bg-gradient-to-r from-primary-50 to-primary-100 dark:from-primary-900/20 dark:to-primary-800/20 border-b border-gray-200 dark:border-gray-700">
                <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex items-start gap-3 mb-4">
                            <h1 class="text-3xl font-bold text-gray-900 dark:text-white leading-tight">
                                {{ $announcement->title }}
                            </h1>
                            @if($announcement->pinned)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-200 animate-pulse">
                                    <i class="fas fa-thumbtack mr-1"></i>
                                    Pinned
                                </span>
                            @endif
                        </div>

                        <!-- Meta Information -->
                        <div class="flex flex-wrap items-center gap-6 text-sm text-gray-600 dark:text-gray-400">
                            <div class="flex items-center">
                                <i class="far fa-calendar-alt mr-2 text-primary-500"></i>
                                <span>{{ $announcement->created_at->format('M d, Y') }}</span>
                                <span class="mx-2">•</span>
                                <span>{{ $announcement->created_at->format('h:i A') }}</span>
                            </div>

                            @if($announcement->institute)
                                <div class="flex items-center">
                                    <i class="fas fa-university mr-2 text-primary-500"></i>
                                    <span>{{ $announcement->institute->name }}</span>
                                </div>
                            @endif

                            @if($announcement->type)
                                <div class="flex items-center">
                                    <i class="fas fa-tag mr-2 text-primary-500"></i>
                                    <span class="px-2 py-1 bg-white dark:bg-gray-700 rounded-full text-xs font-medium shadow-sm">
                                        {{ ucfirst($announcement->type) }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center gap-2">
                        <button onclick="window.print()"
                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-all duration-200 hover:scale-105 shadow-sm">
                            <i class="fas fa-print mr-2"></i>
                            Print
                        </button>

                    </div>
                </div>
            </div>

            <!-- Content Section -->
            <div class="p-6">
                <div class="prose prose-lg dark:prose-invert max-w-none">
                    {!! $announcement->content !!}
                </div>
            </div>

            <!-- Attachments Section -->
            @if($announcement->attachments->count() > 0)
                <div class="border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50 p-6">
                    <div class="flex items-center mb-6">
                        <div class="flex items-center justify-center w-10 h-10 bg-primary-100 dark:bg-primary-900/30 rounded-lg mr-3">
                            <i class="fas fa-paperclip text-primary-600 dark:text-primary-400"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                Attachments
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $announcement->attachments->count() }} {{ Str::plural('file', $announcement->attachments->count()) }} attached
                            </p>
                        </div>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach($announcement->attachments as $attachment)
                            @php
                                $extension = strtolower(pathinfo($attachment->original_name, PATHINFO_EXTENSION));
                                $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp']);
                                $isPdf = $extension === 'pdf';
                                $isText = in_array($extension, ['txt', 'md', 'csv']);
                                $canPreview = $isImage || $isPdf || $isText;

                                $iconClass = match($extension) {
                                    'jpg', 'jpeg', 'png', 'gif', 'svg', 'webp' => 'fa-file-image text-green-500',
                                    'pdf' => 'fa-file-pdf text-red-500',
                                    'doc', 'docx' => 'fa-file-word text-blue-500',
                                    'xls', 'xlsx' => 'fa-file-excel text-green-600',
                                    'ppt', 'pptx' => 'fa-file-powerpoint text-orange-500',
                                    'zip', 'rar', '7z' => 'fa-file-archive text-purple-500',
                                    'txt', 'md' => 'fa-file-alt text-gray-500',
                                    default => 'fa-file text-gray-500'
                                };

                                $bgColor = match($extension) {
                                    'jpg', 'jpeg', 'png', 'gif', 'svg', 'webp' => 'bg-green-50 dark:bg-green-900/20',
                                    'pdf' => 'bg-red-50 dark:bg-red-900/20',
                                    'doc', 'docx' => 'bg-blue-50 dark:bg-blue-900/20',
                                    'xls', 'xlsx' => 'bg-green-50 dark:bg-green-900/20',
                                    'ppt', 'pptx' => 'bg-orange-50 dark:bg-orange-900/20',
                                    'zip', 'rar', '7z' => 'bg-purple-50 dark:bg-purple-900/20',
                                    'txt', 'md' => 'bg-gray-50 dark:bg-gray-900/20',
                                    default => 'bg-gray-50 dark:bg-gray-900/20'
                                };
                            @endphp

                            <div class="attachment-item group relative bg-white dark:bg-gray-700 rounded-xl border border-gray-200 dark:border-gray-600 hover:border-primary-300 dark:hover:border-primary-500 transition-all duration-300 p-4 shadow-sm hover:shadow-md"
                                 @if($canPreview)
                                     onclick="openAttachmentViewer('{{ asset('storage/' . $attachment->path) }}', '{{ $attachment->original_name }}', '{{ $extension }}')"
                                 role="button"
                                 tabindex="0"
                                 aria-label="Preview {{ $attachment->original_name }}"
                                 @else
                                     onclick="window.open('{{ asset('storage/' . $attachment->path) }}', '_blank')"
                                 role="button"
                                 tabindex="0"
                                 aria-label="Download {{ $attachment->original_name }}"
                                @endif>

                                <!-- Action Buttons -->
                                <div class="absolute top-3 right-3 flex gap-1">
                                    @if($canPreview)
                                        <div class="preview-btn p-2 bg-primary-500 text-white rounded-full shadow-lg hover:bg-primary-600 transition-all duration-200">
                                            <i class="fas fa-eye text-xs"></i>
                                        </div>
                                    @endif
                                    <div class="download-btn">
                                        <a href="{{ asset('storage/' . $attachment->path) }}"
                                           download="{{ $attachment->original_name }}"
                                           onclick="event.stopPropagation()"
                                           class="flex items-center justify-center p-2 bg-gray-600 text-white rounded-full shadow-lg hover:bg-gray-700 transition-all duration-200"
                                           title="Download {{ $attachment->original_name }}">
                                            <i class="fas fa-download text-xs"></i>
                                        </a>
                                    </div>
                                </div>

                                <div class="flex items-start space-x-4">
                                    <!-- File Icon -->
                                    <div class="flex-shrink-0 {{ $bgColor }} p-3 rounded-lg">
                                        <i class="far {{ $iconClass }} text-2xl"></i>
                                    </div>

                                    <!-- File Info -->
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white truncate mb-2">
                                            {{ $attachment->title ?? pathinfo($attachment->original_name, PATHINFO_FILENAME) }}
                                        </h4>
                                        <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400 mb-2">
                                            <span class="px-2 py-1 bg-gray-100 dark:bg-gray-600 rounded text-xs font-mono">
                                                {{ strtoupper($extension) }}
                                            </span>
                                            <span>{{ $attachment->size_formatted }}</span>
                                        </div>
                                        @if($canPreview)
                                            <div class="flex items-center text-xs text-primary-600 dark:text-primary-400">
                                                <i class="fas fa-eye mr-1"></i>
                                                <span>Click to preview</span>
                                            </div>
                                        @else
                                            <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                                                <i class="fas fa-download mr-1"></i>
                                                <span>Click to download</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Enhanced Attachment Viewer Modal -->
    <div id="attachmentViewer" class="attachment-viewer" role="dialog" aria-labelledby="viewerTitle" aria-modal="true">
        <div class="viewer-content">
            <div class="viewer-header">
                <h4 id="viewerTitle" class="viewer-title"></h4>
                <div class="viewer-actions">
                    <a id="viewerDownload" href="#" download class="viewer-btn viewer-download">
                        <i class="fas fa-download"></i>
                        <span>Download</span>
                    </a>
                    <button class="viewer-btn viewer-close" onclick="closeAttachmentViewer()" aria-label="Close viewer">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="viewer-body" id="viewerBody">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let currentViewer = null;

        function openAttachmentViewer(url, filename, extension) {
            const viewer = document.getElementById('attachmentViewer');
            const title = document.getElementById('viewerTitle');
            const body = document.getElementById('viewerBody');
            const downloadBtn = document.getElementById('viewerDownload');

            // Set basic info
            title.textContent = filename;
            downloadBtn.href = url;
            downloadBtn.download = filename;

            // Clear previous content
            body.innerHTML = '';
            body.className = 'viewer-body';

            const ext = extension.toLowerCase();

            if (['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'].includes(ext)) {
                // Image viewer
                body.classList.add('image-viewer');
                const img = document.createElement('img');
                img.src = url;
                img.alt = filename;
                img.style.maxWidth = '100%';
                img.style.height = 'auto';

                img.onload = function() {
                    body.innerHTML = '';
                    body.appendChild(img);
                };

                img.onerror = function() {
                    showError('Failed to load image');
                };

                // Show loading state
                showLoading();

            } else if (ext === 'pdf') {
                // PDF viewer
                body.classList.add('pdf-viewer');
                showLoading();

                const iframe = document.createElement('iframe');
                iframe.src = url + '#toolbar=1&navpanes=1&scrollbar=1&view=FitH';
                iframe.width = '100%';
                iframe.style.height = '80vh';
                iframe.style.border = 'none';
                iframe.style.borderRadius = '0 0 12px 12px';

                iframe.onload = function() {
                    body.innerHTML = '';
                    body.appendChild(iframe);
                };

                iframe.onerror = function() {
                    showError('Failed to load PDF. You can still download it using the download button.');
                };

                // Add iframe after a short delay to ensure loading state is visible
                setTimeout(() => {
                    body.appendChild(iframe);
                }, 100);

            } else if (['txt', 'md', 'csv'].includes(ext)) {
                // Text viewer
                body.classList.add('text-viewer');
                showLoading();

                fetch(url)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.text();
                    })
                    .then(text => {
                        body.innerHTML = `<pre class="whitespace-pre-wrap text-sm leading-relaxed">${escapeHtml(text)}</pre>`;
                    })
                    .catch(error => {
                        console.error('Error loading file:', error);
                        showError('Failed to load file content');
                    });
            } else {
                // Unsupported file type
                showError('Preview not available for this file type');
            }

            // Show modal with animation
            viewer.classList.add('active');
            document.body.style.overflow = 'hidden';

            // Set focus for accessibility
            viewer.focus();

            currentViewer = viewer;
        }

        function closeAttachmentViewer() {
            const viewer = document.getElementById('attachmentViewer');
            viewer.classList.remove('active');
            document.body.style.overflow = '';
            currentViewer = null;
        }

        function showLoading() {
            const body = document.getElementById('viewerBody');
            body.innerHTML = `
                <div class="flex flex-col items-center justify-center py-16">
                    <div class="loading-spinner mb-4"></div>
                    <p class="text-gray-600 dark:text-gray-400">Loading...</p>
                </div>
            `;
        }

        function showError(message) {
            const body = document.getElementById('viewerBody');
            body.innerHTML = `
                <div class="error-state">
                    <i class="fas fa-exclamation-triangle text-4xl mb-4"></i>
                    <p class="text-lg font-medium mb-2">Oops! Something went wrong</p>
                    <p class="text-sm opacity-75">${message}</p>
                </div>
            `;
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function shareAnnouncement() {
            const title = '{{ addslashes($announcement->title) }}';
            const url = window.location.href;

            if (navigator.share) {
                navigator.share({
                    title: title,
                    text: 'Check out this announcement from {{ config("app.name") }}',
                    url: url
                }).catch(console.error);
            } else {
                // Fallback: copy to clipboard
                navigator.clipboard.writeText(url).then(() => {
                    // Show success message
                    const message = document.createElement('div');
                    message.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50 fade-in';
                    message.textContent = 'Link copied to clipboard!';
                    document.body.appendChild(message);

                    setTimeout(() => {
                        message.remove();
                    }, 3000);
                }).catch(() => {
                    alert('Unable to copy link. Please copy the URL manually.');
                });
            }
        }

        // Enhanced keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (!currentViewer) return;

            switch(e.key) {
                case 'Escape':
                    closeAttachmentViewer();
                    break;
                case 'ArrowLeft':
                case 'ArrowRight':
                    // Could implement next/previous attachment navigation here
                    break;
            }
        });

        // Close viewer on backdrop click
        document.getElementById('attachmentViewer').addEventListener('click', function(e) {
            if (e.target === this) {
                closeAttachmentViewer();
            }
        });

        // Prevent body scroll when modal is open
        document.getElementById('attachmentViewer').addEventListener('wheel', function(e) {
            e.stopPropagation();
        });

        // Add keyboard support for attachment items
        document.addEventListener('keydown', function(e) {
            if (e.target.classList.contains('attachment-item') && (e.key === 'Enter' || e.key === ' ')) {
                e.preventDefault();
                e.target.click();
            }
        });
    </script>
@endpush
