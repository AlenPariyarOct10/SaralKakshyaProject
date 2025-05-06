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

@section("title")
    Dashbaord
@endsection

@section('content')
    <main class="scrollable-content p-4 md:p-6">

        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Welcome back, {{$user->fname}}!</h1>
            <p class="text-gray-600 dark:text-gray-400">Here's what's happening with your classes today.</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <!-- Total Students Card -->
            <div class="card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Students</p>
                        <h3 class="text-2xl font-bold text-gray-800 dark:text-white mt-1">128</h3>
                    </div>
                    <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900">
                        <i class="fas fa-users text-blue-500 dark:text-blue-300"></i>
                    </div>
                </div>
                <div class="mt-4">
                            <span class="text-sm text-green-500">
                                <i class="fas fa-arrow-up mr-1"></i> 12%
                            </span>
                    <span class="text-sm text-gray-500 dark:text-gray-400 ml-2">from last month</span>
                </div>
            </div>

            <!-- Upcoming Classes Card -->
            <div class="card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Upcoming Classes</p>
                        <h3 class="text-2xl font-bold text-gray-800 dark:text-white mt-1">4</h3>
                    </div>
                    <div class="p-3 rounded-full bg-green-100 dark:bg-green-900">
                        <i class="fas fa-calendar text-green-500 dark:text-green-300"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Next class in 2 hours</span>
                </div>
            </div>

            <!-- Pending Assignments Card -->
            <div class="card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Pending Assignments</p>
                        <h3 class="text-2xl font-bold text-gray-800 dark:text-white mt-1">7</h3>
                    </div>
                    <div class="p-3 rounded-full bg-yellow-100 dark:bg-yellow-900">
                        <i class="fas fa-book text-yellow-500 dark:text-yellow-300"></i>
                    </div>
                </div>
                <div class="mt-4">
                            <span class="text-sm text-red-500">
                                <i class="fas fa-exclamation-circle mr-1"></i> 2 due today
                            </span>
                </div>
            </div>

            <!-- Attendance Card -->
            <div class="card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Attendance Rate</p>
                        <h3 class="text-2xl font-bold text-gray-800 dark:text-white mt-1">92%</h3>
                    </div>
                    <div class="p-3 rounded-full bg-purple-100 dark:bg-purple-900">
                        <i class="fas fa-chart-line text-purple-500 dark:text-purple-300"></i>
                    </div>
                </div>
                <div class="mt-4">
                            <span class="text-sm text-green-500">
                                <i class="fas fa-arrow-up mr-1"></i> 3%
                            </span>
                    <span class="text-sm text-gray-500 dark:text-gray-400 ml-2">from last week</span>
                </div>
            </div>
        </div>

        <!-- Upcoming Classes & Announcements -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Upcoming Classes -->
            <div class="card">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Upcoming Classes</h3>
                    <a href="live-class.html" class="text-sm text-primary-600 hover:underline">View all</a>
                </div>

                <div class="space-y-4">
                    <div class="flex items-start p-3 bg-gray-50 dark:bg-gray-700 rounded-md">
                        <div class="p-2 bg-green-100 dark:bg-green-800 rounded-md mr-3">
                            <i class="fas fa-video text-green-500 dark:text-green-300"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-sm font-medium text-gray-800 dark:text-white">Mathematics - Algebra</h4>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Today, 10:00 AM - 11:30 AM</p>
                        </div>
                        <button class="px-3 py-1 text-xs bg-primary-600 text-white rounded-md hover:bg-primary-700">Join</button>
                    </div>

                    <div class="flex items-start p-3 bg-gray-50 dark:bg-gray-700 rounded-md">
                        <div class="p-2 bg-blue-100 dark:bg-blue-800 rounded-md mr-3">
                            <i class="fas fa-video text-blue-500 dark:text-blue-300"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-sm font-medium text-gray-800 dark:text-white">Physics - Mechanics</h4>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Today, 1:00 PM - 2:30 PM</p>
                        </div>
                        <button class="px-3 py-1 text-xs bg-primary-600 text-white rounded-md hover:bg-primary-700">Join</button>
                    </div>

                    <div class="flex items-start p-3 bg-gray-50 dark:bg-gray-700 rounded-md">
                        <div class="p-2 bg-purple-100 dark:bg-purple-800 rounded-md mr-3">
                            <i class="fas fa-video text-purple-500 dark:text-purple-300"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-sm font-medium text-gray-800 dark:text-white">Computer Science - Algorithms</h4>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Tomorrow, 9:00 AM - 10:30 AM</p>
                        </div>
                        <button class="px-3 py-1 text-xs bg-gray-300 text-gray-700 rounded-md cursor-not-allowed">Upcoming</button>
                    </div>
                </div>
            </div>

            <!-- Announcements -->
            <div class="card">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Announcements</h3>
                    <a href="announcements.html" class="text-sm text-primary-600 hover:underline">View all</a>
                </div>

                <div class="space-y-4">
                    <div class="p-3 border-l-4 border-red-500 bg-gray-50 dark:bg-gray-700 rounded-md">
                        <h4 class="text-sm font-medium text-gray-800 dark:text-white">Exam Schedule Released</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Final exams will be held from June 15-20. Check the portal for your schedule.</p>
                    </div>

                    <div class="p-3 border-l-4 border-yellow-500 bg-gray-50 dark:bg-gray-700 rounded-md">
                        <h4 class="text-sm font-medium text-gray-800 dark:text-white">Library Hours Extended</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">The library will remain open until 10 PM during exam week.</p>
                    </div>

                    <div class="p-3 border-l-4 border-green-500 bg-gray-50 dark:bg-gray-700 rounded-md">
                        <h4 class="text-sm font-medium text-gray-800 dark:text-white">Summer Program Registration</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Registration for summer courses opens next Monday. Limited spots available.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="card">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Recent Activity</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Activity</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Course</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-800 dark:text-white">Assignment Submitted</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500 dark:text-gray-400">Mathematics</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500 dark:text-gray-400">Today, 9:30 AM</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 dark:bg-green-800 text-green-800 dark:text-green-100">Completed</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-800 dark:text-white">Quiz Taken</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500 dark:text-gray-400">Physics</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500 dark:text-gray-400">Yesterday, 2:15 PM</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 dark:bg-green-800 text-green-800 dark:text-green-100">Passed</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-800 dark:text-white">Class Attended</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500 dark:text-gray-400">Computer Science</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500 dark:text-gray-400">Yesterday, 10:00 AM</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 dark:bg-green-800 text-green-800 dark:text-green-100">Present</span>
                        </td>
                    </tr>
                    <!-- Add more rows to demonstrate scrolling -->
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-800 dark:text-white">Assignment Viewed</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500 dark:text-gray-400">Biology</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500 dark:text-gray-400">2 days ago, 3:45 PM</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 dark:bg-yellow-800 text-yellow-800 dark:text-yellow-100">In Progress</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-800 dark:text-white">Resource Downloaded</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500 dark:text-gray-400">Chemistry</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500 dark:text-gray-400">3 days ago, 11:20 AM</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 dark:bg-blue-800 text-blue-800 dark:text-blue-100">Downloaded</span>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Additional content to demonstrate scrolling -->
        <div class="card mt-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Course Progress</h3>
            </div>

            <div class="space-y-4">
                <div>
                    <div class="flex justify-between mb-1">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Mathematics</span>
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">75%</span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                        <div class="bg-primary-600 h-2.5 rounded-full" style="width: 75%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between mb-1">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Physics</span>
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">60%</span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                        <div class="bg-primary-600 h-2.5 rounded-full" style="width: 60%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between mb-1">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Computer Science</span>
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">90%</span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                        <div class="bg-primary-600 h-2.5 rounded-full" style="width: 90%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between mb-1">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Biology</span>
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">45%</span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                        <div class="bg-primary-600 h-2.5 rounded-full" style="width: 45%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="mt-8 text-center text-sm text-gray-500 dark:text-gray-400 pb-6">
            <p>© 2025 Smart Classroom. All rights reserved.</p>
        </footer>

    </main>
@endsection
