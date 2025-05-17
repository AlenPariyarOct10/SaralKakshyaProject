@extends("backend.layout.teacher-dashboard-layout")

@section('title', 'Create Assignment')

@section('content')
    <!-- Main Content Area -->
    <main class="p-4 md:p-6">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-white mb-1">
                    Create New Assignment
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Create and assign new work for your students
                </p>
            </div>
            <a href="{{ route('teacher.assignment.index') }}" class="mt-4 md:mt-0 btn-secondary flex items-center justify-center">
                <i class="fas fa-arrow-left mr-2"></i> Back to Assignments
            </a>
        </div>

        <!-- Create Assignment Form -->
        <div class="card">
            <div class="p-6">
                <form action="{{ route('teacher.assignment.store') }}" method="POST" enctype="multipart/form-data" id="createAssignmentForm">
                    @csrf

                    @if ($errors->any())
                        <div class="mb-6 bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 p-4 rounded-md">
                            <ul class="list-disc pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Left Column -->
                        <div class="space-y-6">
                            <!-- Title -->
                            <div>
                                <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Assignment Title <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    id="title"
                                    name="title"
                                    value="{{ old('title') }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    placeholder="Enter assignment title"
                                    required
                                >
                            </div>

                            <!-- Description -->
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Description <span class="text-red-500">*</span>
                                </label>
                                <textarea
                                    id="description"
                                    name="description"
                                    rows="6"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    placeholder="Enter assignment description"
                                    required
                                >{{ old('description') }}</textarea>
                            </div>

                            <!-- Batch Selection -->
                            <div>
                                <label for="batch_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Batch <span class="text-red-500">*</span>
                                </label>
                                <select
                                    id="batch_id"
                                    name="batch_id"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    required
                                >
                                    <option value="">Select Batch</option>
                                    @foreach($batches as $batch)
                                        <option value="{{ $batch->id }}" {{ old('batch_id') == $batch->id ? 'selected' : '' }}>
                                             ({{ $batch->batch }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Subject Selection -->
                            <div>
                                <label for="subject_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Subject <span class="text-red-500">*</span>
                                </label>
                                <select
                                    id="subject_id"
                                    name="subject_id"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    required
                                >
                                    <option value="">Select Subject</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                            {{ $subject->name }} ({{ $subject->code }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="space-y-6">
                            <!-- Assigned Date -->
                            <div>
                                <label for="assigned_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Assigned Date <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="date"
                                    id="assigned_date"
                                    name="assigned_date"
                                    value="{{ old('assigned_date', date('Y-m-d')) }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    required
                                >
                            </div>

                            <!-- Due Date -->
                            <div>
                                <label for="due_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Due Date <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="date"
                                    id="due_date"
                                    name="due_date"
                                    value="{{ old('due_date') }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    required
                                >
                            </div>

                            <!-- Status -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Status <span class="text-red-500">*</span>
                                </label>
                                <select
                                    id="status"
                                    name="status"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    required
                                >
                                    <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
                                </select>
                            </div>

                            <!-- Assignment Files -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Assignment Resources
                                </label>
                                <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-md p-4 text-center" id="dropZone">
                                    <input type="file" id="attachments" name="attachments[]" class="hidden" multiple>
                                    <label for="attachments" class="cursor-pointer block">
                                        <i class="fas fa-cloud-upload-alt text-2xl text-gray-400 dark:text-gray-500 mb-2"></i>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Drag and drop files here or click to browse</p>
                                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Supported formats: PDF, DOC, DOCX, JPG, PNG, ZIP (Max: 10MB per file)</p>
                                    </label>
                                </div>

                                <div id="filePreview" class="mt-2 hidden">
                                    <!-- Files will be added here dynamically -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end space-x-3">
                        <a href="{{ route('teacher.assignment.index') }}" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                            Cancel
                        </a>
                        <button type="submit" name="save_draft" value="1" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                            Save as Draft
                        </button>
                        <button type="submit" name="publish" value="1" class="btn-primary">
                            Publish Assignment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // File Upload Preview
            const attachmentsInput = document.getElementById('attachments');
            const filePreview = document.getElementById('filePreview');
            const dropZone = document.getElementById('dropZone');
            const maxFileSize = 10 * 1024 * 1024; // 10MB in bytes
            const allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'image/jpeg', 'image/png', 'application/zip'];

            // Function to handle file selection
            function handleFiles(files) {
                if (files.length > 0) {
                    filePreview.classList.remove('hidden');

                    Array.from(files).forEach(file => {
                        // Validate file type
                        if (!allowedTypes.includes(file.type)) {
                            showError(`File type not allowed: ${file.name}`);
                            return;
                        }

                        // Validate file size
                        if (file.size > maxFileSize) {
                            showError(`File too large (max 10MB): ${file.name}`);
                            return;
                        }

                        // Create file preview item
                        const fileItem = document.createElement('div');
                        fileItem.className = 'p-2 bg-gray-50 dark:bg-gray-700 rounded-md mb-2 flex items-center justify-between';

                        // Determine file icon based on type
                        let fileIcon = 'fa-file';
                        if (file.type.includes('pdf')) fileIcon = 'fa-file-pdf text-red-500';
                        else if (file.type.includes('word')) fileIcon = 'fa-file-word text-blue-500';
                        else if (file.type.includes('image')) fileIcon = 'fa-file-image text-green-500';
                        else if (file.type.includes('zip')) fileIcon = 'fa-file-archive text-yellow-500';

                        fileItem.innerHTML = `
                            <div class="flex items-center">
                                <i class="fas ${fileIcon} mr-2"></i>
                                <div>
                                    <span class="text-sm text-gray-700 dark:text-gray-300">${file.name}</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400 block">${formatFileSize(file.size)}</span>
                                </div>
                            </div>
                            <button type="button" class="text-red-500 hover:text-red-700">
                                <i class="fas fa-times"></i>
                            </button>
                        `;

                        filePreview.appendChild(fileItem);

                        // Remove file button
                        const removeBtn = fileItem.querySelector('button');
                        removeBtn.addEventListener('click', function() {
                            fileItem.remove();
                            if (filePreview.children.length === 0) {
                                filePreview.classList.add('hidden');
                            }
                        });
                    });
                }
            }

            // Format file size
            function formatFileSize(bytes) {
                if (bytes < 1024) return bytes + ' bytes';
                else if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
                else return (bytes / 1048576).toFixed(1) + ' MB';
            }

            // Show error message
            function showError(message) {
                const errorDiv = document.createElement('div');
                errorDiv.className = 'bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4';
                errorDiv.innerHTML = `
                    <p>${message}</p>
                `;

                const form = document.getElementById('createAssignmentForm');
                form.insertBefore(errorDiv, form.firstChild);

                // Remove error after 5 seconds
                setTimeout(() => {
                    errorDiv.remove();
                }, 5000);
            }

            // File input change event
            if (attachmentsInput) {
                attachmentsInput.addEventListener('change', function() {
                    handleFiles(this.files);
                });
            }

            // Drag and drop functionality
            if (dropZone) {
                ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                    dropZone.addEventListener(eventName, preventDefaults, false);
                });

                function preventDefaults(e) {
                    e.preventDefault();
                    e.stopPropagation();
                }

                ['dragenter', 'dragover'].forEach(eventName => {
                    dropZone.addEventListener(eventName, highlight, false);
                });

                ['dragleave', 'drop'].forEach(eventName => {
                    dropZone.addEventListener(eventName, unhighlight, false);
                });

                function highlight() {
                    dropZone.classList.add('bg-gray-50', 'dark:bg-gray-700');
                }

                function unhighlight() {
                    dropZone.classList.remove('bg-gray-50', 'dark:bg-gray-700');
                }

                dropZone.addEventListener('drop', handleDrop, false);

                function handleDrop(e) {
                    const dt = e.dataTransfer;
                    const files = dt.files;
                    handleFiles(files);
                }
            }

            // Date validation
            const assignedDateInput = document.getElementById('assigned_date');
            const dueDateInput = document.getElementById('due_date');

            if (dueDateInput && assignedDateInput) {
                dueDateInput.addEventListener('change', function() {
                    if (assignedDateInput.value && this.value) {
                        if (new Date(this.value) < new Date(assignedDateInput.value)) {
                            showError('Due date cannot be earlier than assigned date');
                            this.value = '';
                        }
                    }
                });

                assignedDateInput.addEventListener('change', function() {
                    if (dueDateInput.value && this.value) {
                        if (new Date(dueDateInput.value) < new Date(this.value)) {
                            dueDateInput.value = '';
                        }
                    }
                });
            }

            // Form submission
            const form = document.getElementById('createAssignmentForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    // Additional validation can be added here if needed

                    // Set status based on which button was clicked
                    if (e.submitter.name === 'save_draft') {
                        document.getElementById('status').value = 'draft';
                    } else if (e.submitter.name === 'publish') {
                        document.getElementById('status').value = 'published';
                    }
                });
            }
        });
    </script>
@endsection
