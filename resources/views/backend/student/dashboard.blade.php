@extends("backend.layout.student-dashboard-layout")

@section('username')
    {{$user->fname}} {{$user->lname}}
@endsection

@section("title", "Dashboard")

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
    <main class="scrollable-content p-4 md:p-6">
        <!-- Welcome Message -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Welcome back, {{$user->fname}}!</h1>
            <p class="text-gray-600 dark:text-gray-400">Here's what's happening with your classes today.</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">



            <!-- Pending Assignments Card -->
            <div class="card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Assignments</p>
                        <h3 class="text-2xl font-bold text-gray-800 dark:text-white mt-1">{{$assignments->count()}}</h3>
                    </div>
                    <div class="p-3 rounded-full bg-yellow-100 dark:bg-yellow-900">
                        <i class="fas fa-book text-yellow-500 dark:text-yellow-300"></i>
                    </div>
                </div>
{{--                <div class="mt-4">--}}
{{--                            <span class="text-sm text-red-500">--}}
{{--                                <i class="fas fa-exclamation-circle mr-1"></i> 2 due today--}}
{{--                            </span>--}}
{{--                </div>--}}
            </div>

            <!-- Attendance Card -->
            <div class="card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Attendance Rate</p>
                        <h3 class="text-2xl font-bold text-gray-800 dark:text-white mt-1">{{$rate}}%</h3>
                    </div>
                    <div class="p-3 rounded-full bg-purple-100 dark:bg-purple-900">
                        <i class="fas fa-chart-line text-purple-500 dark:text-purple-300"></i>
                    </div>
                </div>
{{--                <div class="mt-4">--}}
{{--                            <span class="text-sm text-green-500">--}}
{{--                                <i class="fas fa-arrow-up mr-1"></i> 3%--}}
{{--                            </span>--}}
{{--                    <span class="text-sm text-gray-500 dark:text-gray-400 ml-2">from last week</span>--}}
{{--                </div>--}}
            </div>
        </div>

        <!-- Upcoming Classes & Announcements -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">


            <!-- Announcements -->
            <div class="card">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Announcements</h3>
                    <a href="{{route('student.announcement.index')}}" class="text-sm text-primary-600 hover:underline">View all</a>
                </div>
                <div class="space-y-4">
                    @forelse($announcements as $announcement)
                        <a href="{{route('student.announcement.show', $announcement->id)}}" class="block">
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
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Description</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($activityLog as $activity)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-800 dark:text-white">{{$activity->action_type}}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500 dark:text-gray-400">{{$activity->description}}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500 dark:text-gray-400">{{$activity->created_at->diffForHumans()}}</div>
                        </td>
                    </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>
        </div>


        <!-- Footer -->
        <footer class="mt-8 text-center text-sm text-gray-500 dark:text-gray-400 pb-6">
            <p>© 2025 SaralKakshya. All rights reserved.</p>
        </footer>
    </main>
@endsection
