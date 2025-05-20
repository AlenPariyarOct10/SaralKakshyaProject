@extends("backend.layout.teacher-dashboard-layout")

@section('content')
    <!-- Main Content Area -->
    <main class="scrollable-content p-4 md:p-6">
        <!-- Create Announcement Form -->
        <div class="card mb-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Create New Announcement</h3>
                <a href="{{ route('announcements.index') }}" class="text-primary-600 hover:text-primary-900 dark:hover:text-primary-400 text-sm">
                    <i class="fas fa-arrow-left mr-1"></i> Back to Announcements
                </a>
            </div>

            <form action="{{ route('announcements.store') }}" method="POST">
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
                            <!-- Simple toolbar -->
                            <div class="bg-gray-50 dark:bg-gray-700 border-b border-gray-300 dark:border-gray-600 p-2 flex flex-wrap gap-2">
                                <button type="button" class="p-1 rounded hover:bg-gray-200 dark:hover:bg-gray-600">
                                    <i class="fas fa-bold text-gray-700 dark:text-gray-300"></i>
                                </button>
                                <button type="button" class="p-1 rounded hover:bg-gray-200 dark:hover:bg-gray-600">
                                    <i class="fas fa-italic text-gray-700 dark:text-gray-300"></i>
                                </button>
                                <button type="button" class="p-1 rounded hover:bg-gray-200 dark:hover:bg-gray-600">
                                    <i class="fas fa-underline text-gray-700 dark:text-gray-300"></i>
                                </button>
                                <button type="button" class="p-1 rounded hover:bg-gray-200 dark:hover:bg-gray-600">
                                    <i class="fas fa-list-ul text-gray-700 dark:text-gray-300"></i>
                                </button>
                                <button type="button" class="p-1 rounded hover:bg-gray-200 dark:hover:bg-gray-600">
                                    <i class="fas fa-list-ol text-gray-700 dark:text-gray-300"></i>
                                </button>
                                <button type="button" class="p-1 rounded hover:bg-gray-200 dark:hover:bg-gray-600">
                                    <i class="fas fa-link text-gray-700 dark:text-gray-300"></i>
                                </button>
                                <button type="button" class="p-1 rounded hover:bg-gray-200 dark:hover:bg-gray-600">
                                    <i class="fas fa-image text-gray-700 dark:text-gray-300"></i>
                                </button>
                            </div>
                            <textarea id="content" name="content" rows="8" class="w-full px-4 py-2 border-0 focus:outline-none focus:ring-0 dark:bg-gray-800 dark:text-white" placeholder="Enter announcement content"></textarea>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Target Recipients -->
                        <div>
                            <label for="recipients" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Target Recipients</label>
                            <select id="recipients" name="recipients" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                                <option value="all">All Students</option>
                                <option value="math">Mathematics Class</option>
                                <option value="physics">Physics Class</option>
                                <option value="cs">Computer Science Class</option>
                                <option value="history">History Class</option>
                                <option value="english">English Class</option>
                            </select>
                        </div>

                        <!-- Publish Date -->
                        <div>
                            <label for="publishDate" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Publish Date</label>
                            <input type="date" id="publishDate" name="publish_date" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                            <select id="status" name="status" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                                <option value="published">Published</option>
                                <option value="draft">Draft</option>
                            </select>
                        </div>

                        <!-- Priority -->
                        <div>
                            <label for="priority" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Priority</label>
                            <select id="priority" name="priority" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                                <option value="normal">Normal</option>
                                <option value="high">High</option>
                                <option value="urgent">Urgent</option>
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

                    <!-- Notification Options -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Notification Options</label>
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <input type="checkbox" id="emailNotification" name="email_notification" class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                <label for="emailNotification" class="ml-2 text-sm text-gray-700 dark:text-gray-300">Send email notification to recipients</label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" id="pushNotification" name="push_notification" class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                <label for="pushNotification" class="ml-2 text-sm text-gray-700 dark:text-gray-300">Send push notification to mobile app</label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" id="smsNotification" name="sms_notification" class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                <label for="smsNotification" class="ml-2 text-sm text-gray-700 dark:text-gray-300">Send SMS notification (charges may apply)</label>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-4">
                        <button type="button" id="previewAnnouncement" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:bg-gray-700">
                            Preview
                        </button>
                        <button type="button" id="saveAsDraft" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:bg-gray-700">
                            Save as Draft
                        </button>
                        <button type="submit" class="btn-primary">
                            Publish Announcement
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Tips and Guidelines -->
        <div class="card">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Tips for Effective Announcements</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-md">
                    <h4 class="text-sm font-medium text-gray-800 dark:text-white mb-2">Best Practices</h4>
                    <ul class="text-sm text-gray-600 dark:text-gray-300 space-y-2 list-disc pl-5">
                        <li>Keep titles clear and concise</li>
                        <li>Include all relevant details in the content</li>
                        <li>Use formatting to highlight important information</li>
                        <li>Specify the target audience accurately</li>
                        <li>Set appropriate priority levels</li>
                    </ul>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-md">
                    <h4 class="text-sm font-medium text-gray-800 dark:text-white mb-2">Notification Guidelines</h4>
                    <ul class="text-sm text-gray-600 dark:text-gray-300 space-y-2 list-disc pl-5">
                        <li>Use email notifications for detailed announcements</li>
                        <li>Push notifications are best for urgent matters</li>
                        <li>SMS should be reserved for critical information only</li>
                        <li>Consider timing when scheduling announcements</li>
                        <li>Avoid sending too many notifications in a short period</li>
                    </ul>
                </div>
            </div>
        </div>
    </main>
@endsection

@section("scripts")
    <script>
        // Set today's date as default for publish date
        const publishDate = document.getElementById('publishDate');
        const today = new Date().toISOString().split('T')[0];
        publishDate.value = today;

        // Preview announcement
        const previewAnnouncement = document.getElementById('previewAnnouncement');
        previewAnnouncement.addEventListener('click', () => {
            // In a real app, you would show a preview of the announcement
            alert('Preview functionality would be implemented here.');
        });

        // Save as draft
        const saveAsDraft = document.getElementById('saveAsDraft');
        saveAsDraft.addEventListener('click', () => {
            document.getElementById('status').value = 'draft';
            // In a real app, you would submit the form with draft status
            alert('Announcement saved as draft.');
        });

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
