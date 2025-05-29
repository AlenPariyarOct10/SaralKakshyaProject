@extends("backend.layout.admin-dashboard-layout")

@section('username')
    {{ $user->fname }} {{ $user->lname }}
@endsection

@section('fname')
    {{ $user->fname }}
@endsection

@section('lname')
    {{ $user->lname }}
@endsection

@section('profile_picture')
    {{ $user->profile_picture }}
@endsection

@section("title")
    Edit Announcement
@endsection

@push("styles")
    <style type="text/tailwindcss">
        @layer utilities {
            .btn-primary {
                @apply px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-colors duration-200;
            }
            .btn-secondary {
                @apply px-4 py-2 bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-200 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300 dark:focus:ring-gray-600 focus:ring-offset-2 transition-colors duration-200;
            }
            .form-input {
                @apply w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:text-white;
            }
            .form-label {
                @apply block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1;
            }
            .form-select {
                @apply w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:text-white;
            }
            .form-checkbox {
                @apply h-4 w-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container mx-auto px-4 py-6 overflow-scroll">
        <x-show-success-failure-badge></x-show-success-failure-badge>
        <div class="max-w-3xl mx-auto">
            <!-- Breadcrumb -->
            <nav class="flex mb-6" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('admin.dashboard') }}" class="text-gray-700 hover:text-primary-600 dark:text-gray-300">
                            <i class="fas fa-home mr-2"></i>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <a href="{{ route('teacher.announcement.index') }}" class="text-gray-700 hover:text-primary-600 dark:text-gray-300">
                                Announcements
                            </a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <span class="text-gray-500 dark:text-gray-400">Edit Announcement</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <!-- Form Card -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Edit Announcement</h1>

                <form action="{{ route('teacher.announcement.update', $announcement->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Title -->
                    <div class="mb-4">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" id="title" name="title" class="form-input" value="{{ old('title', $announcement->title) }}" required>
                        @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Program -->
                    <div class="mb-4">
                        <label for="program_id" class="form-label">Program</label>
                        <select id="program_id" name="program_id" class="form-select" required>
                            <option value="">Select Program</option>
                            @foreach($programs as $program)
                                <option value="{{ $program->id }}" {{ old('program_id', $announcement->program_id) == $program->id ? 'selected' : '' }}>
                                    {{ $program->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('program_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Content -->
                    <div class="mb-4">
                        <label for="content" class="form-label">Content</label>
                        <textarea id="content" name="content" rows="6" class="form-input" required>{{ old('content', $announcement->content) }}</textarea>
                        @error('content')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Type -->
                    <div class="mb-4">
                        <label for="type" class="form-label">Announcement Type</label>
                        <select id="type" name="type" class="form-select" required>
                            <option value="regular" {{ old('type', $announcement->type) == 'regular' ? 'selected' : '' }}>Regular</option>
                            <option value="important" {{ old('type', $announcement->type) == 'important' ? 'selected' : '' }}>Important</option>
                            <option value="urgent" {{ old('type', $announcement->type) == 'urgent' ? 'selected' : '' }}>Urgent</option>
                        </select>
                        @error('type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Attachments -->
                    <div class="mb-6">
                        <label class="form-label">Attachments</label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md" id="drop-area">
                            <div class="space-y-1 text-center">
                                <i class="fas fa-cloud-upload-alt text-gray-400 text-3xl mb-3"></i>
                                <div class="flex text-sm text-gray-600">
                                    <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-primary-600 hover:text-primary-500">
                                        <span>Upload a file</span>
                                        <input id="file-upload" name="attachments[]" type="file" class="sr-only" multiple>
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, PDF up to 10MB</p>
                            </div>
                        </div>
                        @error('attachments')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        @if($announcement->attachments->count())
                            <div class="mt-4">
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Current Attachments</h4>
                                <div class="space-y-2">
                                    @foreach($announcement->attachments as $attachment)
                                        <div class="flex items-center justify-between p-2 bg-gray-50 rounded-md">
                                            <span class="text-sm text-gray-600">{{ $attachment->title }}</span>
                                            <a onclick="return confirm('Are you sure you want to delete this file?')" href="{{ route('teacher.announcements.deleteAttachment', $attachment->id) }}" class="text-red-500 hover:text-red-700">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Pin Option -->
                    <div class="mb-6">
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input id="is_pinned" name="is_pinned" type="checkbox" class="form-checkbox" {{ old('is_pinned', $announcement->is_pinned) ? 'checked' : '' }}>
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="is_pinned" class="font-medium text-gray-700 dark:text-gray-300">Pin Announcement</label>
                                <p class="text-gray-500">Pin this announcement to the top of the list</p>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('teacher.announcement.index') }}" class="btn-secondary">Cancel</a>
                        <button type="submit" class="btn-primary">Update Announcement</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const fileUpload = document.getElementById('file-upload');
        const dropZone = document.getElementById('drop-area');

        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, e => {
                e.preventDefault();
                e.stopPropagation();
            });
        });

        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => {
                dropZone.classList.add('border-primary-500');
            });
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => {
                dropZone.classList.remove('border-primary-500');
            });
        });

        dropZone.addEventListener('drop', e => {
            fileUpload.files = e.dataTransfer.files;
            console.log([...e.dataTransfer.files].map(f => f.name)); // You can preview file names or thumbnails if needed
        });
    </script>
@endsection
