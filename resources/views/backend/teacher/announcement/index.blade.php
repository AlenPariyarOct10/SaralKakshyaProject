@extends("backend.layout.teacher-dashboard-layout")

@section('content')
    <!-- Main Content Area -->
    <main class="scrollable-content p-4 md:p-6">
        <!-- Announcements List -->
        <div class="card mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4 md:mb-0">Announcements</h3>

                <div class="flex flex-col md:flex-row gap-4">
                    <div class="relative">
                        <select id="statusFilter" class="w-full md:w-48 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                            <option value="">All Status</option>
                            <option value="published">Published</option>
                            <option value="draft">Draft</option>
                            <option value="archived">Archived</option>
                        </select>
                    </div>

                    <div class="relative">
                        <select id="targetFilter" class="w-full md:w-48 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                            <option value="">All Recipients</option>
                            <option value="all">All Students</option>
                            <option value="math">Mathematics Class</option>
                            <option value="physics">Physics Class</option>
                            <option value="cs">Computer Science Class</option>
                        </select>
                    </div>

                    <div class="relative">
                        <input type="text" id="searchAnnouncement" placeholder="Search announcements..." class="w-full md:w-64 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                    </div>

                    <a href="{{ route('announcements.create') }}" class="btn-primary">
                        Create Announcement
                    </a>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Title</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Recipients</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-800 dark:text-white">End of Semester Exam Schedule</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400 mt-1 line-clamp-1">Details about the upcoming final exams and preparation guidelines.</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500 dark:text-gray-400">May 15, 2023</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500 dark:text-gray-400">All Students</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                Published
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="#" class="text-primary-600 hover:text-primary-900 dark:hover:text-primary-400">View</a>
                                <a href="#" class="text-yellow-600 hover:text-yellow-900 dark:hover:text-yellow-400">Edit</a>
                                <a href="#" class="text-red-600 hover:text-red-900 dark:hover:text-red-400">Delete</a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-800 dark:text-white">Science Fair Registration</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400 mt-1 line-clamp-1">Registration details for the upcoming science fair event.</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500 dark:text-gray-400">May 10, 2023</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500 dark:text-gray-400">Science Classes</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                Published
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="#" class="text-primary-600 hover:text-primary-900 dark:hover:text-primary-400">View</a>
                                <a href="#" class="text-yellow-600 hover:text-yellow-900 dark:hover:text-yellow-400">Edit</a>
                                <a href="#" class="text-red-600 hover:text-red-900 dark:hover:text-red-400">Delete</a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-800 dark:text-white">Parent-Teacher Conference</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400 mt-1 line-clamp-1">Schedule and details for the upcoming parent-teacher meetings.</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500 dark:text-gray-400">May 5, 2023</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500 dark:text-gray-400">All Students</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100">
                                Draft
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="#" class="text-primary-600 hover:text-primary-900 dark:hover:text-primary-400">View</a>
                                <a href="#" class="text-yellow-600 hover:text-yellow-900 dark:hover:text-yellow-400">Edit</a>
                                <a href="#" class="text-red-600 hover:text-red-900 dark:hover:text-red-400">Delete</a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-800 dark:text-white">Summer Break Schedule</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400 mt-1 line-clamp-1">Important dates and information regarding the upcoming summer break.</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500 dark:text-gray-400">April 28, 2023</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500 dark:text-gray-400">All Students</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-100">
                                Archived
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="#" class="text-primary-600 hover:text-primary-900 dark:hover:text-primary-400">View</a>
                                <a href="#" class="text-yellow-600 hover:text-yellow-900 dark:hover:text-yellow-400">Edit</a>
                                <a href="#" class="text-red-600 hover:text-red-900 dark:hover:text-red-400">Delete</a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-800 dark:text-white">Field Trip Permission</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400 mt-1 line-clamp-1">Permission form and details for the upcoming museum field trip.</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500 dark:text-gray-400">April 20, 2023</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500 dark:text-gray-400">History Class</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                Published
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="#" class="text-primary-600 hover:text-primary-900 dark:hover:text-primary-400">View</a>
                                <a href="#" class="text-yellow-600 hover:text-yellow-900 dark:hover:text-yellow-400">Edit</a>
                                <a href="#" class="text-red-600 hover:text-red-900 dark:hover:text-red-400">Delete</a>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <div class="mt-6 flex justify-between items-center">
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    Showing <span class="font-medium">1</span> to <span class="font-medium">5</span> of <span class="font-medium">24</span> results
                </div>
                <div class="flex space-x-2">
                    <button class="px-3 py-1 border border-gray-300 rounded-md text-sm text-gray-500 dark:text-gray-400 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700">
                        Previous
                    </button>
                    <button class="px-3 py-1 border border-gray-300 rounded-md text-sm bg-primary-50 text-primary-600 dark:bg-primary-900 dark:text-primary-200 dark:border-primary-800">
                        1
                    </button>
                    <button class="px-3 py-1 border border-gray-300 rounded-md text-sm text-gray-500 dark:text-gray-400 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700">
                        2
                    </button>
                    <button class="px-3 py-1 border border-gray-300 rounded-md text-sm text-gray-500 dark:text-gray-400 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700">
                        3
                    </button>
                    <button class="px-3 py-1 border border-gray-300 rounded-md text-sm text-gray-500 dark:text-gray-400 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700">
                        Next
                    </button>
                </div>
            </div>
        </div>

        <!-- Announcement Stats -->
        <div class="card">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Announcement Statistics</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Total Announcements -->
                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-md">
                    <h4 class="text-sm font-medium text-gray-800 dark:text-white mb-2">Total Announcements</h4>
                    <div class="flex items-center">
                        <div class="w-16 h-16 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center mr-4">
                            <span class="text-xl font-bold text-blue-600 dark:text-blue-300">24</span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">All Time</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">+3 this month</p>
                        </div>
                    </div>
                </div>

                <!-- Announcements by Status -->
                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-md">
                    <h4 class="text-sm font-medium text-gray-800 dark:text-white mb-2">Announcements by Status</h4>
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-500 dark:text-gray-400">Published</span>
                            <div class="w-3/4 bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                                <div class="bg-green-500 h-2 rounded-full" style="width: 75%"></div>
                            </div>
                            <span class="text-xs font-medium text-gray-800 dark:text-white">18</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-500 dark:text-gray-400">Draft</span>
                            <div class="w-3/4 bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                                <div class="bg-yellow-500 h-2 rounded-full" style="width: 12.5%"></div>
                            </div>
                            <span class="text-xs font-medium text-gray-800 dark:text-white">3</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-500 dark:text-gray-400">Archived</span>
                            <div class="w-3/4 bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                                <div class="bg-gray-500 h-2 rounded-full" style="width: 12.5%"></div>
                            </div>
                            <span class="text-xs font-medium text-gray-800 dark:text-white">3</span>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-md">
                    <h4 class="text-sm font-medium text-gray-800 dark:text-white mb-2">Recent Activity</h4>
                    <div class="space-y-3">
                        <div class="flex items-start">
                            <div class="w-2 h-2 mt-1.5 rounded-full bg-green-500 mr-2"></div>
                            <div>
                                <p class="text-xs text-gray-800 dark:text-white">Published "End of Semester Exam Schedule"</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">May 15, 2023 - 10:30 AM</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="w-2 h-2 mt-1.5 rounded-full bg-yellow-500 mr-2"></div>
                            <div>
                                <p class="text-xs text-gray-800 dark:text-white">Drafted "Parent-Teacher Conference"</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">May 5, 2023 - 2:15 PM</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="w-2 h-2 mt-1.5 rounded-full bg-gray-500 mr-2"></div>
                            <div>
                                <p class="text-xs text-gray-800 dark:text-white">Archived "Summer Break Schedule"</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">April 28, 2023 - 9:45 AM</p>
                            </div>
                        </div>
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

        // Status filter
        const statusFilter = document.getElementById('statusFilter');
        statusFilter.addEventListener('change', () => {
            // In a real app, you would filter the announcements based on the selected status
            console.log('Filtering by status:', statusFilter.value);
        });

        // Target filter
        const targetFilter = document.getElementById('targetFilter');
        targetFilter.addEventListener('change', () => {
            // In a real app, you would filter the announcements based on the selected target
            console.log('Filtering by target:', targetFilter.value);
        });
    </script>
@endsection
