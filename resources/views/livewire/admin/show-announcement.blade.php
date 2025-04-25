<main class="scrollable-content p-4 md:p-6">

    <!-- Announcement Filters -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="relative">
                <select id="departmentFilter" class="w-full md:w-48 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                    <option value="all">All Departments</option>
                    @foreach($allDepartments as $department)
                        <option value="cs">{{$department->name}}</option>
                    @endforeach
                </select>
            </div>

            <div class="relative">
                <select id="dateFilter" class="w-full md:w-48 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                    <option value="">All Time</option>
                    <option value="today">Today</option>
                    <option value="week">This Week</option>
                    <option value="month">This Month</option>
                </select>
            </div>
            <a href="{{route('admin.announcement.create')}}" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors">
                Add Announcement
            </a>
        </div>

        <div class="relative">
            <div class="flex items-center border border-gray-300 rounded-md dark:border-gray-700 overflow-hidden">
                <input type="text" placeholder="Search announcements..." class="w-full px-4 py-2 focus:outline-none dark:bg-gray-800 dark:text-white">
                <button class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
    </div>

    @if(count($pinnedAnnouncements)>0)
    <!-- Important Announcements -->
    <div class="card mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Important Announcements</h3>
        </div>

        <div class="space-y-4">
            @foreach($pinnedAnnouncements as $pinned)
                @if($pinned->type=="regular")
                    <div class="p-4 border-l-4 border-blue-500 bg-blue-50 dark:bg-blue-900/20 rounded-md">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mr-3">
                                    <span class="flex items-center justify-center h-10 w-10 rounded-full bg-blue-100 dark:bg-blue-800">
                                        <i class="fas fa-exclamation-circle text-blue-600 dark:text-blue-300"></i>
                                    </span>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-md font-medium text-gray-800 dark:text-white">{{$pinned->title}}</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                   {{$pinned->content}}
                                </p>
                                <div class="flex items-center justify-between mt-2">
                                    <span class="text-xs text-gray-500 dark:text-gray-400">Posted by Admin • May 10, 2023</span>
                                    <button class="text-primary-600 hover:text-primary-800 text-sm">
                                        <i class="fas fa-thumbtack"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif($pinned->type=="important")
                    <div class="p-4 border-l-4 border-yellow-500 bg-yellow-50 dark:bg-yellow-900/20 rounded-md">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mr-3">
                                    <span class="flex items-center justify-center h-10 w-10 rounded-full bg-yellow-100 dark:bg-yellow-800">
                                        <i class="fas fa-clock text-yellow-600 dark:text-yellow-300"></i>
                                    </span>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-md font-medium text-gray-800 dark:text-white">Library Hours Extended</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                    The library will remain open until 10 PM during exam week. Additional study rooms have been made available for group study sessions.
                                </p>
                                <div class="flex items-center justify-between mt-2">
                                    <span class="text-xs text-gray-500 dark:text-gray-400">Posted by Library • May 8, 2023</span>
                                    <button class="text-primary-600 hover:text-primary-800 text-sm">
                                        <i class="fas fa-thumbtack"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="p-4 border-l-4 border-red-500 bg-red-50 dark:bg-red-900/20 rounded-md">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mr-3">
                                    <span class="flex items-center justify-center h-10 w-10 rounded-full bg-red-100 dark:bg-red-800">
                                        <i class="fas fa-exclamation-circle text-red-600 dark:text-red-300"></i>
                                    </span>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-md font-medium text-gray-800 dark:text-white">{{$pinned->title}}</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                    {{$pinned->content}}
                                </p>
                                <div class="flex items-center justify-between mt-2">
                                    <span class="text-xs text-gray-500 dark:text-gray-400">Posted by {{ ucfirst($pinned->creator_type)}} • May 10, 2023</span>
                                    <button class="text-primary-600 hover:text-primary-800 text-sm">
                                        <i class="fas fa-thumbtack"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
    @endif
    <!-- Recent Announcements -->
    <div class="card">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Recent Announcements</h3>
        </div>

        <div class="space-y-4">
            @foreach($allAnnouncements as $announcement)
                <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-md">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 mr-3">
                                    <span class="flex items-center justify-center h-10 w-10 rounded-full bg-green-100 dark:bg-green-800">
                                        <i class="fa fa-bullhorn text-gray-500 dark:text-gray-400" aria-hidden="true"></i>
                                    </span>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-md font-medium text-gray-800 dark:text-white">{{$announcement->title}}</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                {{$announcement->content}}
                            </p>
                            <div class="flex items-center justify-between mt-2">
                                <span class="text-xs text-gray-500 dark:text-gray-400">Posted by {{$announcement->creator_type}}</span>
                                <span class="text-xs text-gray-500 dark:text-gray-400" title="{{ $announcement->created_at->format('M d, Y h:i A') }}">
                                    {{ $announcement->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6 flex justify-center">
            <nav class="flex items-center space-x-2">
                <a href="#" class="px-3 py-1 rounded-md bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                    <i class="fas fa-chevron-left"></i>
                </a>
                <a href="#" class="px-3 py-1 rounded-md bg-primary-600 text-white">1</a>
                <a href="#" class="px-3 py-1 rounded-md bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">2</a>
                <a href="#" class="px-3 py-1 rounded-md bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">3</a>
                <a href="#" class="px-3 py-1 rounded-md bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                    <i class="fas fa-chevron-right"></i>
                </a>
            </nav>
        </div>
    </div>
</main>
