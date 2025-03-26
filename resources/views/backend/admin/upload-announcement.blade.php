
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

@section('content')
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
                                        Upload Announcement
                                    </span>
                        </div>
                    </li>
                </ol>
            </nav>

            <!-- Upload Announcement Form -->
            <div class="card">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-6">Create New Announcement</h3>

                <form id="announcementForm">
                    <div class="space-y-6">
                        <!-- Title -->
                        <div>
                            <label for="title" class="form-label">Announcement Title <span class="text-red-500">*</span></label>
                            <input type="text" id="title" name="title" class="form-input" placeholder="Enter announcement title" required>
                        </div>

                        <!-- Course/Department -->
                        <div>
                            <label for="course" class="form-label">Course/Department <span class="text-red-500">*</span></label>
                            <select id="course" name="course" class="form-select" required>
                                <option value="">Select Course/Department</option>
                                <option value="all">All Courses</option>
                                <option value="math">Mathematics</option>
                                <option value="physics">Physics</option>
                                <option value="cs">Computer Science</option>
                                <option value="eng">Engineering</option>
                                <option value="bio">Biology</option>
                                <option value="chem">Chemistry</option>
                                <option value="arts">Arts & Humanities</option>
                            </select>
                        </div>

                        <!-- Announcement Type -->
                        <div>
                            <label for="type" class="form-label">Announcement Type <span class="text-red-500">*</span></label>
                            <select id="type" name="type" class="form-select" required>
                                <option value="regular">Regular</option>
                                <option value="important">Important</option>
                                <option value="urgent">Urgent</option>
                            </select>
                        </div>

                        <!-- Content -->
                        <div>
                            <label for="content" class="form-label">Announcement Content <span class="text-red-500">*</span></label>
                            <textarea id="content" name="content" rows="6" class="form-input" placeholder="Enter announcement content" required></textarea>
                        </div>

                        <!-- Attachments -->
                        <div>
                            <label for="attachments" class="form-label">Attachments</label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md dark:border-gray-600">
                                <div class="space-y-1 text-center">
                                    <i class="fas fa-cloud-upload-alt text-gray-400 text-3xl mb-2"></i>
                                    <div class="flex text-sm text-gray-600 dark:text-gray-400">
                                        <label for="file-upload" class="relative cursor-pointer bg-white dark:bg-gray-700 rounded-md font-medium text-primary-600 hover:text-primary-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-primary-500">
                                            <span class="px-2">Upload files</span>
                                            <input id="file-upload" name="file-upload" type="file" class="sr-only" multiple>
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        PDF, DOC, DOCX, PNG, JPG, JPEG up to 10MB
                                    </p>
                                </div>
                            </div>
                            <div id="file-list" class="mt-2 space-y-2"></div>
                        </div>

                        <!-- Options -->
                        <div class="space-y-3">
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="pin" name="pin" type="checkbox" class="form-checkbox">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="pin" class="font-medium text-gray-700 dark:text-gray-300">Pin to top</label>
                                    <p class="text-gray-500 dark:text-gray-400">This announcement will be pinned to the top of the announcements page.</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="notify" name="notify" type="checkbox" class="form-checkbox" checked>
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="notify" class="font-medium text-gray-700 dark:text-gray-300">Send notification</label>
                                    <p class="text-gray-500 dark:text-gray-400">Users will receive a notification about this announcement.</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="email" name="email" type="checkbox" class="form-checkbox">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="email" class="font-medium text-gray-700 dark:text-gray-300">Send email</label>
                                    <p class="text-gray-500 dark:text-gray-400">Users will receive an email about this announcement.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Schedule -->
                        <div>
                            <div class="flex items-start mb-2">
                                <div class="flex items-center h-5">
                                    <input id="schedule" name="schedule" type="checkbox" class="form-checkbox">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="schedule" class="font-medium text-gray-700 dark:text-gray-300">Schedule announcement</label>
                                    <p class="text-gray-500 dark:text-gray-400">Set a specific date and time for this announcement to be published.</p>
                                </div>
                            </div>

                            <div id="schedule-options" class="pl-8 mt-3 hidden">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="schedule-date" class="form-label">Date</label>
                                        <input type="date" id="schedule-date" name="schedule-date" class="form-input">
                                    </div>
                                    <div>
                                        <label for="schedule-time" class="form-label">Time</label>
                                        <input type="time" id="schedule-time" name="schedule-time" class="form-input">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Expiration -->
                        <div>
                            <div class="flex items-start mb-2">
                                <div class="flex items-center h-5">
                                    <input id="expiration" name="expiration" type="checkbox" class="form-checkbox">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="expiration" class="font-medium text-gray-700 dark:text-gray-300">Set expiration date</label>
                                    <p class="text-gray-500 dark:text-gray-400">The announcement will be automatically archived after this date.</p>
                                </div>
                            </div>

                            <div id="expiration-options" class="pl-8 mt-3 hidden">
                                <div>
                                    <label for="expiration-date" class="form-label">Expiration Date</label>
                                    <input type="date" id="expiration-date" name="expiration-date" class="form-input">
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex justify-end space-x-3 pt-4">
                            <a href="announcements.html" class="btn-secondary">Cancel</a>
                            <button type="submit" class="btn-primary">Publish Announcement</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>
@endsection
