@extends("backend.layout.admin-dashboard-layout")

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

@section("scripts")
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fileUpload = document.getElementById('file-upload');
            const fileList = document.getElementById('file-list');
            const dropZone = fileUpload.closest('.border-dashed');

            // Constants
            const MAX_FILE_SIZE = 20 * 1024 * 1024; // 20MB
            const ALLOWED_TYPES = [
                'image/png', 'image/jpeg', 'image/gif', 'image/webp',
                'application/pdf',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'text/plain'
            ];

            // Load programs for the selected department on page load
            const initialDeptId = document.getElementById("department").value;
            if(initialDeptId) {
                loadPrograms(initialDeptId);
            }

            $("#department").change(function() {
                const selectedValue = $(this).val();
                loadPrograms(selectedValue);
            });

            function loadPrograms(departmentId) {
                $.ajax({
                    url: `/admin/department/get_department_programs`,
                    method: 'GET',
                    data: {
                        _token: '{{ csrf_token() }}',
                        department_id: departmentId,
                    },
                    success: function(response) {
                        let courseList = document.getElementById("course");
                        courseList.innerHTML = `<option value="">All Programs</option>`;
                        response.forEach((course) => {
                            const selected = course.id == {{ $announcement->program_id ?? 'null' }} ? 'selected' : '';
                            courseList.innerHTML += `<option value="${course.id}" ${selected}>${course.name}</option>`;
                        });
                    },
                    error: function(xhr) {
                        Toast.fire({
                            icon: 'error',
                            title: 'Failed to load programs',
                        });
                    }
                });
            }

            // File upload handling
            fileUpload.addEventListener('change', handleFiles);

            dropZone.addEventListener('dragover', function(e) {
                e.preventDefault();
                e.stopPropagation();
                dropZone.classList.add('border-primary-500');
            });

            dropZone.addEventListener('dragleave', function(e) {
                e.preventDefault();
                e.stopPropagation();
                dropZone.classList.remove('border-primary-500');
            });

            dropZone.addEventListener('drop', function(e) {
                e.preventDefault();
                e.stopPropagation();
                dropZone.classList.remove('border-primary-500');

                if (e.dataTransfer.files.length) {
                    handleFileUpload(e.dataTransfer.files[0]);
                }
            });

            function handleFiles(e) {
                const file = e.target.files[0];
                if (file) {
                    handleFileUpload(file);
                }
            }

            function handleFileUpload(file) {
                fileList.innerHTML = '';

                if (!ALLOWED_TYPES.includes(file.type)) {
                    alert('Invalid file type. Only images, PDF, Word, and TXT files are allowed.');
                    return;
                }

                if (file.size > MAX_FILE_SIZE) {
                    alert('File size exceeds the 20MB limit.');
                    return;
                }

                uploadFile(file);
            }

            function uploadFile(file) {
                const fileItem = document.createElement('div');
                fileItem.className = 'flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-md';

                const fileInfo = document.createElement('div');
                fileInfo.className = 'flex items-center space-x-2';

                const fileIcon = document.createElement('i');
                if (file.type.includes('pdf')) {
                    fileIcon.className = 'fas fa-file-pdf text-red-500';
                } else if (file.type.includes('word') || file.name.match(/\.(doc|docx)$/)) {
                    fileIcon.className = 'fas fa-file-word text-blue-500';
                } else if (file.type.includes('image')) {
                    fileIcon.className = 'fas fa-file-image text-green-500';
                } else if (file.type === 'text/plain') {
                    fileIcon.className = 'fas fa-file-alt text-gray-500';
                } else {
                    fileIcon.className = 'fas fa-file text-gray-500';
                }

                const fileDetails = document.createElement('div');
                fileDetails.innerHTML = `
                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300">${file.name}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">${formatFileSize(file.size)}</p>
                `;

                const statusContainer = document.createElement('div');
                statusContainer.className = 'flex items-center space-x-2';

                const progressContainer = document.createElement('div');
                progressContainer.className = 'w-24 bg-gray-200 rounded-full h-2.5 dark:bg-gray-700';

                const progressBar = document.createElement('div');
                progressBar.className = 'bg-primary-600 h-2.5 rounded-full';
                progressBar.style.width = '0%';

                const statusText = document.createElement('span');
                statusText.className = 'text-xs font-medium text-gray-500 dark:text-gray-400';
                statusText.textContent = 'Preparing...';

                progressContainer.appendChild(progressBar);
                statusContainer.appendChild(progressContainer);
                statusContainer.appendChild(statusText);

                fileInfo.appendChild(fileIcon);
                fileInfo.appendChild(fileDetails);

                fileItem.appendChild(fileInfo);
                fileItem.appendChild(statusContainer);

                fileList.appendChild(fileItem);

                simulateFileUpload(progressBar, statusText, fileItem);
            }

            function simulateFileUpload(progressBar, statusText, fileItem) {
                let progress = 0;
                statusText.textContent = 'Uploading...';

                const interval = setInterval(() => {
                    progress += Math.random() * 10;
                    if (progress >= 100) {
                        progress = 100;
                        clearInterval(interval);

                        setTimeout(() => {
                            statusText.textContent = 'Uploaded';
                            statusText.className = 'text-xs font-medium text-green-500';

                            const progressContainer = progressBar.parentElement;
                            progressContainer.innerHTML = '';

                            const successIcon = document.createElement('i');
                            successIcon.className = 'fas fa-check-circle text-green-500 text-lg';
                            progressContainer.appendChild(successIcon);

                            const removeBtn = document.createElement('button');
                            removeBtn.className = 'ml-2 text-gray-400 hover:text-red-500 focus:outline-none';
                            removeBtn.innerHTML = '<i class="fas fa-times"></i>';
                            removeBtn.addEventListener('click', function() {
                                fileItem.remove();
                            });

                            progressContainer.appendChild(removeBtn);
                        }, 500);
                    }

                    progressBar.style.width = `${progress}%`;
                }, 200);
            }

            function formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            }
        });
    </script>
@endsection

@section('content')
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 mb-3 rounded relative" role="alert">
            <strong class="font-bold">Success !</strong>
            <span class="block sm:inline">{{session('success')}}</span>
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
    <!-- Main Content Area -->
    <main class="scrollable-content p-4 md:p-6">
        <div class="max-w-3xl mx-auto">
            <!-- Breadcrumbs -->
            <nav class="flex mb-6" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 text-sm mx-1"></i>
                            <a href="{{route('admin.announcement.index')}}" class="ml-1 text-sm font-medium text-gray-700 hover:text-primary-600 dark:text-gray-400 dark:hover:text-white">
                                Announcements
                            </a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 text-sm mx-1"></i>
                            <span class="ml-1 text-sm font-medium text-gray-500 dark:text-gray-400">
                                Edit Announcement
                            </span>
                        </div>
                    </li>
                </ol>
            </nav>

            <!-- Edit Announcement Form -->
            <div class="card">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-6">Edit Announcement</h3>

                <form id="announcementForm" action="{{route("admin.announcement.update", $announcement->id)}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="space-y-6">
                        <!-- Title -->
                        <div>
                            <label for="title" class="form-label">Announcement Title <span class="text-red-500">*</span></label>
                            <input type="text" id="title" name="title" value="{{ old('title', $announcement->title) }}" class="form-input" placeholder="Enter announcement title" required>
                        </div>

                        <!-- Department -->
                        <div>
                            <label for="department" class="form-label">Department <span class="text-red-500">*</span></label>
                            <div>
                                <select id="department" name="department_id" class="form-select">
                                    <option value="">All Departments</option>
                                    @foreach($departments as $department)
                                        <option value="{{ $department->id }}" {{ $announcement->department_id == $department->id ? 'selected' : '' }}>
                                            {{ $department->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Programs -->
                        <div>
                            <label for="course" class="form-label">Programs <span class="text-red-500">*</span></label>
                            <select id="course" name="program_id" class="form-select">
                                <option value="">All Programs</option>
                                @if($announcement->program)
                                    <option value="{{ $announcement->program->id }}" selected>{{ $announcement->program->name }}</option>
                                @endif
                            </select>
                        </div>

                        <!-- Announcement Type -->
                        <div>
                            <label for="type" class="form-label">Announcement Type <span class="text-red-500">*</span></label>
                            <select id="type" name="type" class="form-select" required>
                                <option value="regular" {{ $announcement->type == 'regular' ? 'selected' : '' }}>Regular</option>
                                <option value="important" {{ $announcement->type == 'important' ? 'selected' : '' }}>Important</option>
                                <option value="urgent" {{ $announcement->type == 'urgent' ? 'selected' : '' }}>Urgent</option>
                            </select>
                        </div>

                        <!-- Content -->
                        <div>
                            <label for="content" class="form-label">Announcement Content <span class="text-red-500">*</span></label>
                            <textarea id="content" name="content" rows="6" class="form-input" placeholder="Enter announcement content" required>{{ old('content', $announcement->content) }}</textarea>
                        </div>

                        <!-- Attachments -->
                        <div>
                            <label for="attachments" class="form-label">Attachments</label>

                            <!-- Display existing attachments -->
                            @if($announcement->attachments->count() > 0)
                                <div class="mt-2 mb-4">
                                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Current Attachments:</h4>
                                    <div class="space-y-2">
                                        @foreach($announcement->attachments as $attachment)
                                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-md">
                                                <div class="flex items-center space-x-2">
                                                    @if($attachment->file_type == 'pdf')
                                                        <i class="fas fa-file-pdf text-red-500"></i>
                                                    @elseif(in_array($attachment->file_type, ['doc', 'docx']))
                                                        <i class="fas fa-file-word text-blue-500"></i>
                                                    @elseif(in_array($attachment->file_type, ['png', 'jpg', 'jpeg', 'gif']))
                                                        <i class="fas fa-file-image text-green-500"></i>
                                                    @else
                                                        <i class="fas fa-file text-gray-500"></i>
                                                    @endif
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $attachment->title }}</p>
                                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ formatFileSize($attachment->size) }}</p>
                                                    </div>
                                                </div>
                                                <div>
                                                    <a href="{{ Storage::url($attachment->path) }}" target="_blank" class="text-primary-600 hover:text-primary-800 mr-3" download>
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                    <a href="{{ route('admin.announcement.deleteAttachment', $attachment->id) }}" class="text-red-500 hover:text-red-700" onclick="return confirm('Are you sure you want to delete this attachment?')">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- File upload section -->
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md dark:border-gray-600">
                                <div class="space-y-1 text-center">
                                    <i class="fas fa-cloud-upload-alt text-gray-400 text-3xl mb-2"></i>
                                    <div class="flex text-sm text-gray-600 dark:text-gray-400">
                                        <label for="file-upload" class="relative cursor-pointer bg-white dark:bg-gray-700 rounded-md font-medium text-primary-600 hover:text-primary-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-primary-500">
                                            <label for="file-upload" class="px-2">Upload files</label>
                                            <input class="hidden" type="file" id="file-upload" name="file">
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        PDF, DOC, DOCX, PNG, JPG, JPEG up to 20MB
                                    </p>
                                </div>
                            </div>
                            <div id="file-list" class="mt-2 space-y-2"></div>
                        </div>

                        <!-- Options -->
                        <div class="space-y-3">
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="pin" name="pinned" type="checkbox" class="form-checkbox" {{ $announcement->pinned ? 'checked' : '' }}>
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="pin" class="font-medium text-gray-700 dark:text-gray-300">Pin to top</label>
                                    <p class="text-gray-500 dark:text-gray-400">This announcement will be pinned to the top of the announcements page.</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="notify" name="notification" type="checkbox" class="form-checkbox" checked>
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="notify" class="font-medium text-gray-700 dark:text-gray-300">Send notification</label>
                                    <p class="text-gray-500 dark:text-gray-400">Users will receive a notification about this announcement.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex justify-end space-x-3 pt-4">
                            <a href="{{ route('admin.announcement.index') }}" class="btn-secondary">Cancel</a>
                            <button type="submit" class="btn-primary">Update Announcement</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>
@endsection

@php
    function formatFileSize($bytes) {
        if (!is_numeric($bytes) || $bytes <= 0) {
            return '0 Bytes';
        }

        $k = 1024;
        $sizes = ['Bytes', 'KB', 'MB', 'GB'];
        $i = floor(log($bytes, $k)); // You can also use log($bytes) / log($k)

        return round($bytes / pow($k, $i), 2) . ' ' . $sizes[$i];
    }
@endphp

