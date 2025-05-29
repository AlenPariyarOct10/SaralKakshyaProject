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


            <!-- Regular Announcements -->
            <div class="space-y-4">
                @foreach ($announcements as $announcement)
                    @if($announcement->type == "regular")
                <!-- Announcement Card -->
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                    <div class="p-4">
                        <div class="flex justify-between items-start">
                            <h4 class="text-base font-medium text-gray-900 dark:text-white">{{$announcement->title}}</h4>
                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">{{$announcement->type}}</span>
                        </div>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">{{$announcement->content}}</p>
                        <div class="mt-3 flex items-center justify-between">
                            <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                                <i class="far fa-calendar-alt mr-1"></i>
                                <span>{{$announcement->created_at->diffForHumans()}}</span>
                            </div>
                            <a href="{{ route('teacher.announcement.show', $announcement->id) }}" class="text-sm text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300">{{ __('Read More') }}</a>
                        </div>
                    </div>
                </div>
                    @endif
                    @if($announcement->type == "urgent")
                            <!-- Urgent Announcements -->
                            <div class="mb-6">
                                <div class="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 p-4 rounded-md mb-4">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-exclamation-circle text-red-500 text-lg"></i>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-red-800 dark:text-red-200">{{$announcement->title}}</h3>
                                            <div class="mt-2 text-sm text-red-700 dark:text-red-300">
                                                <p>{{$announcement->content}}</p>
                                            </div>
                                            <div class="mt-2">
                                                <a href=" {{ route('teacher.announcement.show', $announcement->id) }} " class="text-sm font-medium text-red-800 dark:text-red-200 hover:underline">View Details</a>
                                                <span class="text-xs text-red-600 dark:text-red-300 ml-2">{{$announcement->created_at->diffForHumans()}}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        @endif
                        @if($announcement->type == "important")
                            <!-- Urgent Announcements -->
                            <div class="mb-6">
                                <div class="bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-500 p-4 rounded-md mb-4">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-exclamation-circle text-blue-500 text-lg"></i>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">{{$announcement->title}}</h3>
                                            <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                                                <p>{{$announcement->content}}</p>
                                            </div>
                                            <div class="mt-2">
                                                <a href="{{ route('teacher.announcement.show', $announcement->id) }}" class="text-sm font-medium text-blue-800 dark:text-blue-200 hover:underline">View Details</a>
                                                <span class="text-xs text-blue-600 dark:text-blue-300 ml-2">{{$announcement->created_at->diffForHumans()}}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        @endif
                @endforeach
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
