@extends("backend.layout.teacher-dashboard-layout")

@section('title', 'Create Announcement')


@section('content')
    <x-show-success-failure-badge></x-show-success-failure-badge>
    <!-- Main Content Area -->
    <main class="scrollable-content p-4 md:p-6">
        <!-- Create Announcement Form -->
        <div class="card mb-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Create New Announcement</h3>
                <a href="{{ route('teacher.announcement.index') }}" class="text-primary-600 hover:text-primary-900 dark:hover:text-primary-400 text-sm">
                    <i class="fas fa-arrow-left mr-1"></i> Back to Announcements
                </a>
            </div>

            <form action="{{ route('teacher.announcements.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="space-y-6">
                    <!-- Title -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Announcement Title</label>
                        <input type="text" id="title" name="title" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-800 dark:border-gray-700 dark:text-white" placeholder="Enter announcement title">
                    </div>

                    <!-- Content -->
                    <div>
                        <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Announcement Content</label>
                        <div class="border border-gray-300 rounded-md dark:border-gray-700 overflow-hidden">
                            <textarea id="content" name="content" rows="8" class="w-full px-4 py-2 border-0 focus:outline-none focus:ring-0 dark:bg-gray-800 dark:text-white" placeholder="Enter announcement content"></textarea>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">


                        <!-- Priority -->
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Type</label>
                            <select id="type" name="type" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-800 dark:border-gray-700 dark:text-white" required>
                                <option value="regular">Regular</option>
                                <option value="important">Important</option>
                                <option value="urgent">Urgent</option>
                            </select>
                        </div>

                        <div>
                            <label for="program_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Program</label>
                            <select id="program_id" name="program_id" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-800 dark:border-gray-700 dark:text-white" required>
                                <option value="">Select Program</option>
                                @foreach($programs as $program)
                                    <option value="{{$program->id}}">{{$program->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Attachments -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Attachments</label>
                        <div class="border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-md p-6 text-center">
                            <div class="space-y-2">
                                <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 dark:text-gray-500"></i>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Drag and drop files here, or click to select files</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Supported formats: PDF, DOC, DOCX, JPG, PNG (Max 5MB)</p>
                                <input type="file" id="attachments" name="attachments[]" multiple class="hidden">
                                <button type="button" onclick="document.getElementById('attachments').click()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600 text-sm">
                                    Select Files
                                </button>
                            </div>
                        </div>
                        <div id="fileList" class="mt-2 space-y-1"></div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-4">
                        <button type="submit" class="btn-primary">
                            Publish Announcement
                        </button>
                    </div>
                </div>
            </form>
        </div>

    </main>
@endsection

@section("scripts")
    <script>



        // File upload handling
        const attachmentsInput = document.getElementById('attachments');
        const fileList = document.getElementById('fileList');

        attachmentsInput.addEventListener('change', () => {
            fileList.innerHTML = '';

            for (const file of attachmentsInput.files) {
                const fileItem = document.createElement('div');
                fileItem.className = 'flex items-center justify-between text-sm text-gray-600 dark:text-gray-300 bg-gray-50 dark:bg-gray-700 p-2 rounded';

                const fileInfo = document.createElement('div');
                fileInfo.className = 'flex items-center';

                const fileIcon = document.createElement('i');
                fileIcon.className = 'fas fa-file mr-2 text-gray-400';

                const fileName = document.createElement('span');
                fileName.textContent = file.name;

                const fileSize = document.createElement('span');
                fileSize.className = 'text-gray-400 ml-2';
                fileSize.textContent = `(${(file.size / 1024).toFixed(1)} KB)`;

                const removeButton = document.createElement('button');
                removeButton.type = 'button';
                removeButton.className = 'text-red-500 hover:text-red-700';
                removeButton.innerHTML = '<i class="fas fa-times"></i>';
                removeButton.addEventListener('click', () => {
                    fileItem.remove();
                });

                fileInfo.appendChild(fileIcon);
                fileInfo.appendChild(fileName);
                fileInfo.appendChild(fileSize);

                fileItem.appendChild(fileInfo);
                fileItem.appendChild(removeButton);

                fileList.appendChild(fileItem);
            }
        });
    </script>
@endsection
