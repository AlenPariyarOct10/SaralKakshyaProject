
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

@section('content')
    <!-- Main Content Area -->
    <main class="scrollable-content p-4 md:p-6">

    <!-- Announcement Filters -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="relative">
                <select id="courseFilter" class="w-full md:w-48 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                    <option value="">All Courses</option>
                    <option value="math">Mathematics</option>
                    <option value="physics">Physics</option>
                    <option value="cs">Computer Science</option>
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

    <!-- Important Announcements -->
    <div class="card mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Important Announcements</h3>
        </div>

        <div class="space-y-4">
            <div class="p-4 border-l-4 border-red-500 bg-red-50 dark:bg-red-900/20 rounded-md">
                <div class="flex items-start">
                    <div class="flex-shrink-0 mr-3">
                                    <span class="flex items-center justify-center h-10 w-10 rounded-full bg-red-100 dark:bg-red-800">
                                        <i class="fas fa-exclamation-circle text-red-600 dark:text-red-300"></i>
                                    </span>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-md font-medium text-gray-800 dark:text-white">Exam Schedule Released</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                            Final exams will be held from June 15-20. Check the portal for your schedule. Make sure to review all course materials and attend review sessions.
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
        </div>
    </div>

    <!-- Recent Announcements -->
    <div class="card">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Recent Announcements</h3>
        </div>

        <div class="space-y-4">
            <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-md">
                <div class="flex items-start">
                    <div class="flex-shrink-0 mr-3">
                                    <span class="flex items-center justify-center h-10 w-10 rounded-full bg-green-100 dark:bg-green-800">
                                        <i class="fas fa-calendar-alt text-green-600 dark:text-green-300"></i>
                                    </span>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-md font-medium text-gray-800 dark:text-white">Summer Program Registration</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                            Registration for summer courses opens next Monday. Limited spots available. Early registration is recommended for popular courses.
                        </p>
                        <div class="flex items-center justify-between mt-2">
                            <span class="text-xs text-gray-500 dark:text-gray-400">Posted by Admin • May 7, 2023</span>
                            <button class="text-primary-600 hover:text-primary-800 text-sm">
                                <i class="far fa-thumbtack"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-md">
                <div class="flex items-start">
                    <div class="flex-shrink-0 mr-3">
                                    <span class="flex items-center justify-center h-10 w-10 rounded-full bg-blue-100 dark:bg-blue-800">
                                        <i class="fas fa-book text-blue-600 dark:text-blue-300"></i>
                                    </span>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-md font-medium text-gray-800 dark:text-white">Mathematics - Additional Resources</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                            Additional practice problems for the upcoming exam have been uploaded to the course materials section. These cover all topics that will be on the final exam.
                        </p>
                        <div class="flex items-center justify-between mt-2">
                            <span class="text-xs text-gray-500 dark:text-gray-400">Posted by Prof. Sarah Johnson • May 6, 2023</span>
                            <button class="text-primary-600 hover:text-primary-800 text-sm">
                                <i class="far fa-thumbtack"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-md">
                <div class="flex items-start">
                    <div class="flex-shrink-0 mr-3">
                                    <span class="flex items-center justify-center h-10 w-10 rounded-full bg-purple-100 dark:bg-purple-800">
                                        <i class="fas fa-users text-purple-600 dark:text-purple-300"></i>
                                    </span>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-md font-medium text-gray-800 dark:text-white">Student Council Elections</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                            Nominations for Student Council positions are now open. Submit your application by May 20. Elections will be held on June 1.
                        </p>
                        <div class="flex items-center justify-between mt-2">
                            <span class="text-xs text-gray-500 dark:text-gray-400">Posted by Student Affairs • May 5, 2023</span>
                            <button class="text-primary-600 hover:text-primary-800 text-sm">
                                <i class="far fa-thumbtack"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-md">
                <div class="flex items-start">
                    <div class="flex-shrink-0 mr-3">
                                    <span class="flex items-center justify-center h-10 w-10 rounded-full bg-orange-100 dark:bg-orange-800">
                                        <i class="fas fa-flask text-orange-600 dark:text-orange-300"></i>
                                    </span>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-md font-medium text-gray-800 dark:text-white">Physics - Lab Equipment Update</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                            New lab equipment has been installed in Lab Room 302. Training sessions will be held this Friday at 2 PM for all students enrolled in Physics courses.
                        </p>
                        <div class="flex items-center justify-between mt-2">
                            <span class="text-xs text-gray-500 dark:text-gray-400">Posted by Prof. Michael Brown • May 3, 2023</span>
                            <button class="text-primary-600 hover:text-primary-800 text-sm">
                                <i class="far fa-thumbtack"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
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
@endsection
