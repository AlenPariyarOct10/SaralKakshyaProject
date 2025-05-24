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
            <p class="text-gray-600 dark:text-gray-400">Here's what's happening with your classes.</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <!-- Total Students Card -->
            <div class="card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Students</p>
                        <h3 class="text-2xl font-bold text-gray-800 dark:text-white mt-1">{{$students->count()}}</h3>
                    </div>
                    <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900">
                        <i class="fas fa-users text-blue-500 dark:text-blue-300"></i>
                    </div>
                </div>
                @if($studentGrowth > 0)
                    <div class="mt-4">
                        <span class="text-sm text-green-500">
                            <i class="fas fa-arrow-up mr-1"></i> {{$studentGrowth}} %
                        </span>
                        <span class="text-sm text-gray-500 dark:text-gray-400 ml-2">from last month</span>
                    </div>
                @else
                    <div class="mt-4">
                        <span class="text-sm text-red-500">
                            <i class="fas fa-arrow-down mr-1"></i> {{$studentGrowth}} %
                        </span>
                        <span class="text-sm text-gray-500 dark:text-gray-400 ml-2">from last month</span>
                    </div>
                @endif
            </div>

            <!-- Upcoming Classes Card -->
            <div class="card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Departments</p>
                        <h3 class="text-2xl font-bold text-gray-800 dark:text-white mt-1">{{$departments->count()}}</h3>
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
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Resources</p>
                        <h3 class="text-2xl font-bold text-gray-800 dark:text-white mt-1">{{$resources->count()}}</h3>
                    </div>
                    <div class="p-3 rounded-full bg-yellow-100 dark:bg-yellow-900">
                        <i class="fas fa-book text-yellow-500 dark:text-yellow-300"></i>
                    </div>
                </div>
            </div>

            <!-- Attendance Card -->
            <div class="card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Attendance Rate</p>
                        <h3 class="text-2xl font-bold text-gray-800 dark:text-white mt-1">{{$attendanceReport['average_rate']}}%</h3>
                    </div>
                    <div class="p-3 rounded-full bg-purple-100 dark:bg-purple-900">
                        <i class="fas fa-chart-line text-purple-500 dark:text-purple-300"></i>
                    </div>
                </div>
                <div class="mt-4">
                            <span class="text-sm text-green-500">
                                <i class="fas fa-arrow-up mr-1"></i> {{$attendanceGrowth['growth_percentage']}}%
                            </span>
                    <span class="text-sm text-gray-500 dark:text-gray-400 ml-2">from last week</span>
                </div>
            </div>
        </div>

        <!-- Upcoming Classes & Announcements -->
        <div class="grid grid-cols-1 lg:grid-cols-1 gap-6 mb-6">


            <!-- Announcements -->
            <div class="card">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Announcements</h3>
                    <a href="{{route('admin.announcement.index')}}" class="text-sm text-primary-600 hover:underline">View all</a>
                </div>

                <div class="space-y-4">
                    @forelse($announcements as $announcement)
                        <a href="{{route('admin.announcement.show', $announcement->id)}}" class="block">
                        <div class="p-3 border-l-4 {{($announcement->type == 'important' ? 'border-red-500' : ($announcement->type == 'regular'? 'border-blue-500':'border-green-500'))}} bg-gray-50 dark:bg-gray-700 rounded-md">
                            <h4 class="text-sm font-medium text-gray-800 dark:text-white">{{$announcement->title}}</h4>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{$announcement->content}}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                {{ $announcement->created_at->diffForHumans() }}
                            </p>
                        </div>
                        </a>
                    @empty
                        <div class="p-3 border-l-4 border-red-500 bg-gray-50 dark:bg-gray-700 rounded-md">
                            <h4 class="text-sm font-medium text-gray-800 dark:text-white">No Announcements Found</h4>
                        </div>
                    @endforelse

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
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($activityLogs as $log)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-800 dark:text-white">{{ucfirst($log->action_type)}}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $log->created_at->diffForHumans() }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 dark:bg-green-800 text-green-800 dark:text-green-100">
                                {{ucfirst($log->description)}}
                                </span>
                            </td>
                        </tr>
                    @empty
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
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>


        <!-- Footer -->
        <footer class="mt-8 text-center text-sm text-gray-500 dark:text-gray-400 pb-6">
            <p>© 2025 Smart Classroom. All rights reserved.</p>
        </footer>

    </main>
@endsection
