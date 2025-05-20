@extends("backend.layout.student-dashboard-layout")

@section('content')
    <!-- Main Content Area -->
    <main class="scrollable-content p-4 md:p-6">
        <!-- Announcements List -->
        <div class="card mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4 md:mb-0">Announcements</h3>

                <div class="flex flex-col md:flex-row gap-4">
                    <div class="relative">
                        <select id="classFilter" class="w-full md:w-48 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                            <option value="">All Classes</option>
                            <option value="math">Mathematics</option>
                            <option value="physics">Physics</option>
                            <option value="cs">Computer Science</option>
                            <option value="history">History</option>
                            <option value="english">English</option>
                        </select>
                    </div>

                    <div class="relative">
                        <input type="text" id="searchAnnouncement" placeholder="Search announcements..." class="w-full md:w-64 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                    </div>
                </div>
            </div>

            <!-- Urgent Announcements -->
            <div class="mb-6">
                <div class="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 p-4 rounded-md mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-500 text-lg"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800 dark:text-red-200">End of Semester Exam Schedule</h3>
                            <div class="mt-2 text-sm text-red-700 dark:text-red-300">
                                <p>Final exams will be held from June 10-15, 2023. Please check the detailed schedule below and prepare accordingly. All students must arrive 30 minutes before their scheduled exam time.</p>
                            </div>
                            <div class="mt-2">
                                <a href="#" class="text-sm font-medium text-red-800 dark:text-red-200 hover:underline">View Details</a>
                                <span class="text-xs text-red-600 dark:text-red-300 ml-2">Posted on May 15, 2023</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Regular Announcements -->
            <div class="space-y-4">
                <!-- Announcement Card -->
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                    <div class="p-4">
                        <div class="flex justify-between items-start">
                            <h4 class="text-base font-medium text-gray-900 dark:text-white">Science Fair Registration</h4>
                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">Science</span>
                        </div>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">Registration for the annual science fair is now open. Students interested in participating should submit their project proposals by May 25, 2023.</p>
                        <div class="mt-3 flex items-center justify-between">
                            <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                                <i class="far fa-calendar-alt mr-1"></i>
                                <span>May 10, 2023</span>
                            </div>
                            <a href="#" class="text-sm text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300">Read More</a>
                        </div>
                    </div>
                </div>

                <!-- Announcement Card -->
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                    <div class="p-4">
                        <div class="flex justify-between items-start">
                            <h4 class="text-base font-medium text-gray-900 dark:text-white">Field Trip Permission</h4>
                            <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100">History</span>
                        </div>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">Permission form for the upcoming museum field trip on May 30, 2023. Please have your parents sign the attached form and return it by May 25.</p>
                        <div class="mt-3 flex items-center justify-between">
                            <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                                <i class="far fa-calendar-alt mr-1"></i>
                                <span>April 20, 2023</span>
                            </div>
                            <div class="flex items-center">
                                <a href="#" class="text-sm text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300 mr-3">Read More</a>
                                <a href="#" class="text-sm text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-300">
                                    <i class="fas fa-download"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Announcement Card -->
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                    <div class="p-4">
                        <div class="flex justify-between items-start">
                            <h4 class="text-base font-medium text-gray-900 dark:text-white">Summer Break Schedule</h4>
                            <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100">General</span>
                        </div>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">Important dates and information regarding the upcoming summer break. The last day of school will be June 20, and classes will resume on September 5.</p>
                        <div class="mt-3 flex items-center justify-between">
                            <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                                <i class="far fa-calendar-alt mr-1"></i>
                                <span>April 28, 2023</span>
                            </div>
                            <a href="#" class="text-sm text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300">Read More</a>
                        </div>
                    </div>
                </div>

                <!-- Announcement Card -->
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                    <div class="p-4">
                        <div class="flex justify-between items-start">
                            <h4 class="text-base font-medium text-gray-900 dark:text-white">Math Competition Results</h4>
                            <span class="px-2 py-1 text-xs rounded-full bg-purple-100 text-purple-800 dark:bg-purple-800 dark:text-purple-100">Mathematics</span>
                        </div>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">Congratulations to all participants in the regional math competition. Our school secured the second position overall, with three students winning individual medals.</p>
                        <div class="mt-3 flex items-center justify-between">
                            <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                                <i class="far fa-calendar-alt mr-1"></i>
                                <span>April 15, 2023</span>
                            </div>
                            <a href="#" class="text-sm text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300">Read More</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-center">
                <button class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:bg-gray-700">
                    Load More Announcements
                </button>
            </div>
        </div>

        <!-- Calendar and Upcoming Events -->
        <div class="card">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Upcoming Events</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Event Card -->
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden shadow-sm">
                    <div class="p-4">
                        <div class="flex items-center mb-3">
                            <div class="w-10 h-10 rounded-full bg-red-100 dark:bg-red-900 flex items-center justify-center mr-3">
                                <i class="fas fa-calendar-day text-red-600 dark:text-red-300"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-900 dark:text-white">Final Exams</h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400">June 10-15, 2023</p>
                            </div>
                        </div>
                        <p class="text-xs text-gray-600 dark:text-gray-300">End of semester examinations for all subjects. Check the detailed schedule for specific times and locations.</p>
                    </div>
                </div>

                <!-- Event Card -->
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden shadow-sm">
                    <div class="p-4">
                        <div class="flex items-center mb-3">
                            <div class="w-10 h-10 rounded-full bg-green-100 dark:bg-green-900 flex items-center justify-center mr-3">
                                <i class="fas fa-flask text-green-600 dark:text-green-300"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-900 dark:text-white">Science Fair</h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400">June 5, 2023</p>
                            </div>
                        </div>
                        <p class="text-xs text-gray-600 dark:text-gray-300">Annual science fair showcasing student projects. Open to all students and parents from 9 AM to 3 PM in the main hall.</p>
                    </div>
                </div>

                <!-- Event Card -->
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden shadow-sm">
                    <div class="p-4">
                        <div class="flex items-center mb-3">
                            <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center mr-3">
                                <i class="fas fa-graduation-cap text-blue-600 dark:text-blue-300"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-900 dark:text-white">Graduation Ceremony</h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400">June 25, 2023</p>
                            </div>
                        </div>
                        <p class="text-xs text-gray-600 dark:text-gray-300">Graduation ceremony for the senior class. The event will be held at the school auditorium starting at 10 AM.</p>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section("scripts")
    <script>
        // Search functionality
        const searchAnnouncement = document.getElementById('searchAnnouncement');
        searchAnnouncement.addEventListener('input', () => {
            // In a real app, you would filter the announcements based on the search term
            console.log('Searching for:', searchAnnouncement.value);
        });

        // Class filter
        const classFilter = document.getElementById('classFilter');
        classFilter.addEventListener('change', () => {
            // In a real app, you would filter the announcements based on the selected class
            console.log('Filtering by class:', classFilter.value);
        });

        // Load more button
        const loadMoreButton = document.querySelector('button');
        loadMoreButton.addEventListener('click', () => {
            // In a real app, you would load more announcements
            console.log('Loading more announcements...');
        });
    </script>
@endsection
