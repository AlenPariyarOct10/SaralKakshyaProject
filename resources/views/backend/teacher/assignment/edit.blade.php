@extends("backend.layout.teacher-dashboard-layout")

@section('title', 'Edit Assignment')

@section('content')
    <!-- Main Content Area -->
    <main class="p-6 md:p-6 min-h-screen">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-white mb-1">
                    Edit Assignment
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Update assignment details and resources
                </p>
            </div>
            <div class="mt-4 md:mt-0 flex flex-col md:flex-row gap-3">
                <a href="{{ route('teacher.assignment.show', $assignment->id) }}" class="btn-outline flex items-center justify-center">
                    <i class="fas fa-eye mr-2"></i> View Assignment
                </a>
                <a href="{{ route('teacher.assignment.index') }}" class="btn-secondary flex items-center justify-center">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Assignments
                </a>
            </div>
        </div>

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

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 mb-3 rounded relative" role="alert">
                <strong class="font-bold">Success!</strong>
                <p class="mt-2 text-sm">{{ session('success') }}</p>
            </div>
        @endif

        <!-- Edit Assignment Form -->
        <div class="card mb-8">
            <div class="p-6">
                <form action="{{ route('teacher.assignment.update', $assignment->id) }}" method="POST" enctype="multipart/form-data" id="editAssignmentForm">
                    @csrf
                    @method('PUT')

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
                                    value="{{ old('title', $assignment->title) }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    placeholder="Enter assignment title"
                                    required
                                >
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
                                        <option value="{{ $subject->subject->id }}" {{ old('subject_id', $assignment->subject_id) == $subject->subject->id ? 'selected' : '' }}>
                                            {{ $subject->subject->name }} ({{ $subject->subject->code }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Chapter Selection -->
                            <div>
                                <label for="chapter_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Chapter <span class="text-red-500">*</span>
                                </label>
                                <select
                                    id="chapter_id"
                                    name="chapter_id"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    required
                                >
                                    <option value="">Select Chapter</option>
                                    <!-- Chapters will be populated dynamically based on subject selection -->
                                    @if($assignment->chapter)
                                        <option value="{{ $assignment->chapter_id }}" selected>{{ $assignment->chapter->title }}</option>
                                    @endif
                                </select>
                            </div>

                            <!-- Sub-Chapter Selection -->
                            <div>
                                <label for="sub_chapter_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Sub-Chapter
                                </label>
                                <select
                                    id="sub_chapter_id"
                                    name="sub_chapter_id"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                >
                                    <option value="">Select Sub-Chapter</option>
                                    <!-- Sub-chapters will be populated dynamically based on chapter selection -->
                                    @if($assignment->subChapter)
                                        <option value="{{ $assignment->sub_chapter_id }}" selected>{{ $assignment->subChapter->title }}</option>
                                    @endif
                                </select>
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
                                >{{ old('description', $assignment->description) }}</textarea>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="space-y-6">
                            <!-- Assigned Date -->
                            <div class="hidden">
                                <label for="assigned_date" class="hidden text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Assigned Date <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="date"
                                    id="assigned_date"
                                    name="assigned_date"
                                    value="{{ old('assigned_date', $assignment->assigned_date ? date('Y-m-d', strtotime($assignment->assigned_date)) : date('Y-m-d')) }}"
                                    class="hidden w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                >
                            </div>

                            <!-- Due Date and Time -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="due_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Due Date <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        type="date"
                                        id="due_date"
                                        name="due_date"
                                        value="{{ old('due_date', $assignment->due_date ? date('Y-m-d', strtotime($assignment->due_date)) : '') }}"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                        required
                                    >
                                    <p id="date-error" class="text-red-500 text-sm mt-1 hidden">Please select a future date.</p>
                                </div>
                                <div>
                                    <label for="due_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Due Time <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        type="time"
                                        id="due_time"
                                        name="due_time"
                                        value="{{ old('due_time', $assignment->due_time ? date('H:i', strtotime($assignment->due_time)) : '23:59') }}"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                        required
                                    >
                                </div>
                            </div>

                            <!-- Full Marks -->
                            <div>
                                <label for="full_marks" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Full Marks <span class="text-red-500">* will be converted according to format</span>
                                </label>
                                <input
                                    type="number"
                                    id="full_marks"
                                    name="full_marks"
                                    value="{{ old('full_marks', $assignment->full_marks) }}"
                                    min="1"
                                    max="100"
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
                                    <option value="active" {{ old('status', $assignment->status) == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="draft" {{ old('status', $assignment->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                </select>
                                @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Existing Attachments -->
                            @if($assignment->attachments && count($assignment->attachments) > 0)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Existing Resources
                                    </label>
                                    <div class="space-y-2 max-h-40 overflow-y-auto">
                                        @foreach($assignment->attachments as $attachment)
                                            <div class="p-2 bg-gray-50 dark:bg-gray-700 rounded-md flex items-center justify-between">
                                                <div class="flex items-center overflow-hidden">
                                                    @php
                                                        $icon = 'fa-file';
                                                        if (Str::contains($attachment->file_type ?? '', 'pdf')) $icon = 'fa-file-pdf text-red-500';
                                                        elseif (Str::contains($attachment->file_type ?? '', 'word')) $icon = 'fa-file-word text-blue-500';
                                                        elseif (Str::contains($attachment->file_type ?? '', 'image')) $icon = 'fa-file-image text-green-500';
                                                        elseif (Str::contains($attachment->file_type ?? '', 'zip')) $icon = 'fa-file-archive text-yellow-500';
                                                    @endphp
                                                    <i class="fas {{ $icon }} mr-2 flex-shrink-0"></i>
                                                    <span class="text-sm text-gray-700 dark:text-gray-300 truncate">{{ $attachment->title ?? $attachment->original_filename }}</span>
                                                </div>
                                                <div class="flex items-center space-x-2">
                                                    <a href="{{ route('teacher.assignment.download', [$assignment->id, $attachment->id]) }}" class="text-primary-600 hover:text-primary-800">
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                    <div class="flex items-center">
                                                        <input type="checkbox" id="delete_attachment_{{ $attachment->id }}" name="delete_attachments[]" value="{{ $attachment->id }}" class="mr-2">
                                                        <label for="delete_attachment_{{ $attachment->id }}" class="text-red-500 text-sm">Remove</label>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- New Assignment Files -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Add New Resources
                                </label>
                                <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-md p-4 text-center" id="dropZone">
                                    <input type="file" id="attachments" name="attachments[]" class="hidden" multiple>
                                    <label for="attachments" class="cursor-pointer block">
                                        <i class="fas fa-cloud-upload-alt text-2xl text-gray-400 dark:text-gray-500 mb-2"></i>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Drag and drop files here or click to browse</p>
                                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Supported formats: PDF, DOC, DOCX, JPG, PNG, ZIP (Max: 10MB per file)</p>
                                    </label>
                                </div>

                                <div id="filePreview" class="mt-2 hidden max-h-40 overflow-y-auto">
                                    <!-- Files will be added here dynamically -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 mb-4 flex justify-end space-x-3">
                        <a href="{{ route('teacher.assignment.index') }}" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                            Cancel
                        </a>
                        <button type="submit" name="update" value="1" class="btn-primary">
                            Update Assignment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
@endsection

@section('scripts')
    <script>
        // -------- Date Validation --------
        document.addEventListener('DOMContentLoaded', function () {
            const assignedDateInput = document.getElementById('assigned_date');
            const dueDateInput = document.getElementById('due_date');
            const errorText = document.getElementById('date-error');

            dueDateInput.addEventListener('change', function () {
                const selectedDate = new Date(this.value);
                const today = new Date();
                const assignedDate = new Date(assignedDateInput.value);

                // Remove time part from today to compare only the date
                today.setHours(0, 0, 0, 0);

                if (selectedDate < today) {
                    errorText.classList.remove('hidden');
                    errorText.textContent = 'Please select a future date.';
                    this.value = '{{ old('due_date', $assignment->due_date ? date('Y-m-d', strtotime($assignment->due_date)) : '') }}'; // Restore the original date
                    this.classList.add('border-red-500');
                } else if (selectedDate < assignedDate) {
                    errorText.classList.remove('hidden');
                    errorText.textContent = 'Due date cannot be earlier than assigned date.';
                    this.value = '{{ old('due_date', $assignment->due_date ? date('Y-m-d', strtotime($assignment->due_date)) : '') }}'; // Restore the original date
                    this.classList.add('border-red-500');
                } else {
                    errorText.classList.add('hidden');
                    this.classList.remove('border-red-500');
                }
            });

            assignedDateInput.addEventListener('change', function() {
                if (dueDateInput.value) {
                    const selectedDate = new Date(this.value);
                    const dueDate = new Date(dueDateInput.value);

                    if (dueDate < selectedDate) {
                        dueDateInput.value = '';
                        errorText.classList.remove('hidden');
                        errorText.textContent = 'Due date cannot be earlier than assigned date.';
                    }
                }
            });
        });
        // -------- End Date Validation --------

        // -------- Chapter and Sub-Chapter Handling --------
        document.addEventListener('DOMContentLoaded', function() {
            const subjectSelect = document.getElementById('subject_id');
            const chapterSelect = document.getElementById('chapter_id');
            const subChapterSelect = document.getElementById('sub_chapter_id');

            // Load chapters for the initially selected subject
            if (subjectSelect.value) {
                loadChapters(subjectSelect.value, {{ $assignment->chapter_id ?? 'null' }});
            }

            // When subject changes, update chapters
            subjectSelect.addEventListener('change', function() {
                const subjectId = this.value;
                loadChapters(subjectId);
            });

            // When chapter changes, update sub-chapters
            chapterSelect.addEventListener('change', function() {
                const chapterId = this.value;
                loadSubChapters(chapterId);
            });

            // Function to load chapters
            function loadChapters(subjectId, selectedChapterId = null) {
                // Clear chapter and sub-chapter dropdowns
                chapterSelect.innerHTML = '<option value="">Select Chapter</option>';
                subChapterSelect.innerHTML = '<option value="">Select Sub-Chapter</option>';

                if (subjectId) {
                    // Fetch chapters for the selected subject
                    fetch(`/teacher/subject/${subjectId}/chapters`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.length > 0) {
                                data.forEach(chapter => {
                                    const option = document.createElement('option');
                                    option.value = chapter.id;
                                    option.textContent = chapter.title;

                                    // Select the chapter if it matches the selected chapter ID
                                    if (selectedChapterId && chapter.id == selectedChapterId) {
                                        option.selected = true;
                                    }

                                    chapterSelect.appendChild(option);
                                });

                                // If a chapter is selected, load its sub-chapters
                                if (selectedChapterId) {
                                    loadSubChapters(selectedChapterId, {{ $assignment->sub_chapter_id ?? 'null' }});
                                }
                            }
                        })
                        .catch(error => console.error('Error fetching chapters:', error));
                }
            }

            // Function to load sub-chapters
            function loadSubChapters(chapterId, selectedSubChapterId = null) {
                // Clear sub-chapter dropdown
                subChapterSelect.innerHTML = '<option value="">Select Sub-Chapter</option>';

                if (chapterId) {
                    // Fetch sub-chapters for the selected chapter
                    fetch(`/teacher/chapter/${chapterId}/sub-chapters`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.length > 0) {
                                data.forEach(subChapter => {
                                    const option = document.createElement('option');
                                    option.value = subChapter.id;
                                    option.textContent = subChapter.title;

                                    // Select the sub-chapter if it matches the selected sub-chapter ID
                                    if (selectedSubChapterId && subChapter.id == selectedSubChapterId) {
                                        option.selected = true;
                                    }

                                    subChapterSelect.appendChild(option);
                                });
                            }
                        })
                        .catch(error => console.error('Error fetching sub-chapters:', error));
                }
            }
        });
        // -------- End Chapter and Sub-Chapter Handling --------

        // -------- File Upload Preview --------
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

                const form = document.getElementById('editAssignmentForm');
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

            // Form submission
            const form = document.getElementById('editAssignmentForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    // Additional validation can be added here if needed
                });
            }
        });
    </script>
@endsection
