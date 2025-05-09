@extends('backend.layout.superadmin-dashboard-layout')

@php
    $system = \App\Models\SystemSetting::first();
@endphp

@section("title")
    Dashboard
@endsection

@section('content')
    <main class="scrollable-content p-4 md:p-6">
        <!-- Welcome Message -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Welcome, {{\Illuminate\Support\Facades\Auth::user()->fname}} - Super Admin!</h1>
            <p class="text-gray-600 dark:text-gray-400">Here's an overview of your system.</p>
        </div>


        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <!-- Admin Stats Card -->
            <div class="card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Admins</p>
                        <h3 class="text-2xl font-bold text-gray-800 dark:text-white mt-1">
                            {{ $admins->count() }}
                        </h3>
                    </div>
                    <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900">
                        <i class="fas fa-user-shield text-blue-500 dark:text-blue-300"></i>
                    </div>
                </div>
                <div class="mt-4 flex items-center justify-between">
                    <div>
                                <span class="text-sm text-green-500">
                                    <i class="fas fa-check-circle mr-1"></i> {{$admins->where('is_approved',1)->count()}} Approved
                                </span>
                    </div>
                    <div>
                                <span class="text-sm text-yellow-500">
                                    <i class="fas fa-clock mr-1"></i> {{$admins->where('is_approved',0)->count()}} Pending
                                </span>
                    </div>
                </div>
            </div>

            <!-- Institute Stats Card -->
            <div class="card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Institutes</p>
                        <h3 class="text-2xl font-bold text-gray-800 dark:text-white mt-1">{{$institutes->count()}}</h3>
                    </div>
                    <div class="p-3 rounded-full bg-green-100 dark:bg-green-900">
                        <i class="fas fa-university text-green-500 dark:text-green-300"></i>
                    </div>
                </div>
                <div class="mt-4 flex items-center justify-between">
                    <div>
                                <span class="text-sm text-green-500">
                                    <i class="fas fa-check-circle mr-1"></i> {{$institutes->where('deleted_at', null)->count()}} Active
                                </span>
                    </div>
                    <div>
                                <span class="text-sm text-red-500">
                                    <i class="fas fa-clock mr-1"></i> {{$institutes->whereNotNull('deleted_at')->count()}} Deleted
                                </span>
                    </div>
                </div>
            </div>

            <!-- Teachers Stats Card -->
            <div class="card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Teachers</p>
                        <h3 class="text-2xl font-bold text-gray-800 dark:text-white mt-1">{{$teachers->count()}}</h3>
                    </div>
                    <div class="p-3 rounded-full bg-purple-100 dark:bg-purple-900">
                        <i class="fas fa-chalkboard-teacher text-purple-500 dark:text-purple-300"></i>
                    </div>
                </div>
                @if($teachers->growth>0)
                    <div class="mt-4">
                            <span class="text-sm text-green-500">
                                <i class="fas fa-arrow-up mr-1"></i> {{$students->growth}}%
                            </span>
                        <span class="text-sm text-gray-500 dark:text-gray-400 ml-2">from last month</span>
                    </div>
                @elseif($teachers->growth<=0)
                    <div class="mt-4">
                            <span class="text-sm text-red-500">
                                <i class="fas fa-arrow-down mr-1"></i> {{$teachers->growth}}%
                            </span>
                        <span class="text-sm text-gray-500 dark:text-gray-400 ml-2">from last month</span>
                    </div>
                @endif
            </div>

            <!-- Students Stats Card -->
            <div class="card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Students</p>
                        <h3 class="text-2xl font-bold text-gray-800 dark:text-white mt-1">{{$students->count()}}</h3>
                    </div>
                    <div class="p-3 rounded-full bg-yellow-100 dark:bg-yellow-900">
                        <i class="fas fa-user-graduate text-yellow-500 dark:text-yellow-300"></i>
                    </div>
                </div>
                @if($students->growth>0)
                <div class="mt-4">
                            <span class="text-sm text-green-500">
                                <i class="fas fa-arrow-up mr-1"></i> {{$students->growth}}%
                            </span>
                    <span class="text-sm text-gray-500 dark:text-gray-400 ml-2">from last month</span>
                </div>
                @elseif($students->growth<=0)
                <div class="mt-4">
                            <span class="text-sm text-red-500">
                                <i class="fas fa-arrow-down mr-1"></i> {{$students->growth}}%
                            </span>
                    <span class="text-sm text-gray-500 dark:text-gray-400 ml-2">from last month</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Pending Approvals & System Alerts -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            @livewire('super-admin.dashboard-pending-approvals')

            <!-- System Alerts -->
{{--            <div class="card">--}}
{{--                <div class="flex items-center justify-between mb-4">--}}
{{--                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">System Alerts</h3>--}}
{{--                    <a href="#" class="text-sm text-primary-600 hover:underline">View all</a>--}}
{{--                </div>--}}

{{--                <div class="space-y-4">--}}
{{--                    <div class="p-3 border-l-4 border-red-500 bg-gray-50 dark:bg-gray-700 rounded-md">--}}
{{--                        <h4 class="text-sm font-medium text-gray-800 dark:text-white">System Update Required</h4>--}}
{{--                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Security update available. Please update the system to version 2.4.0.</p>--}}
{{--                        <div class="mt-2">--}}
{{--                            <button class="px-3 py-1 text-xs bg-primary-600 text-white rounded-md hover:bg-primary-700">Update Now</button>--}}
{{--                        </div>--}}
{{--                    </div>--}}

{{--                    <div class="p-3 border-l-4 border-yellow-500 bg-gray-50 dark:bg-gray-700 rounded-md">--}}
{{--                        <h4 class="text-sm font-medium text-gray-800 dark:text-white">Database Backup</h4>--}}
{{--                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Last backup was 3 days ago. Consider running a new backup.</p>--}}
{{--                        <div class="mt-2">--}}
{{--                            <button class="px-3 py-1 text-xs bg-primary-600 text-white rounded-md hover:bg-primary-700">Backup Now</button>--}}
{{--                        </div>--}}
{{--                    </div>--}}

{{--                    <div class="p-3 border-l-4 border-blue-500 bg-gray-50 dark:bg-gray-700 rounded-md">--}}
{{--                        <h4 class="text-sm font-medium text-gray-800 dark:text-white">Storage Usage</h4>--}}
{{--                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">System storage is at 75% capacity. Consider cleaning up old files.</p>--}}
{{--                        <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2.5 mt-2">--}}
{{--                            <div class="bg-blue-500 h-2.5 rounded-full" style="width: 75%"></div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
        </div>

        <!-- Recent Activity -->
{{--        <div class="card">--}}
{{--            <div class="flex items-center justify-between mb-4">--}}
{{--                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Recent Activity</h3>--}}
{{--                <a href="reports.html" class="text-sm text-primary-600 hover:underline">View all</a>--}}
{{--            </div>--}}

{{--            <div class="overflow-x-auto">--}}
{{--                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">--}}
{{--                    <thead class="bg-gray-50 dark:bg-gray-700">--}}
{{--                    <tr>--}}
{{--                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Activity</th>--}}
{{--                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">User</th>--}}
{{--                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>--}}
{{--                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>--}}
{{--                    </tr>--}}
{{--                    </thead>--}}
{{--                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">--}}
{{--                    <tr>--}}
{{--                        <td class="px-6 py-4 whitespace-nowrap">--}}
{{--                            <div class="text-sm font-medium text-gray-800 dark:text-white">Admin Approved</div>--}}
{{--                        </td>--}}
{{--                        <td class="px-6 py-4 whitespace-nowrap">--}}
{{--                            <div class="text-sm text-gray-500 dark:text-gray-400">Super Admin</div>--}}
{{--                        </td>--}}
{{--                        <td class="px-6 py-4 whitespace-nowrap">--}}
{{--                            <div class="text-sm text-gray-500 dark:text-gray-400">Today, 9:30 AM</div>--}}
{{--                        </td>--}}
{{--                        <td class="px-6 py-4 whitespace-nowrap">--}}
{{--                            <span class="badge badge-success">Completed</span>--}}
{{--                        </td>--}}
{{--                    </tr>--}}
{{--                    <tr>--}}
{{--                        <td class="px-6 py-4 whitespace-nowrap">--}}
{{--                            <div class="text-sm font-medium text-gray-800 dark:text-white">Institute Added</div>--}}
{{--                        </td>--}}
{{--                        <td class="px-6 py-4 whitespace-nowrap">--}}
{{--                            <div class="text-sm text-gray-500 dark:text-gray-400">admin@tech.edu</div>--}}
{{--                        </td>--}}
{{--                        <td class="px-6 py-4 whitespace-nowrap">--}}
{{--                            <div class="text-sm text-gray-500 dark:text-gray-400">Yesterday, 2:15 PM</div>--}}
{{--                        </td>--}}
{{--                        <td class="px-6 py-4 whitespace-nowrap">--}}
{{--                            <span class="badge badge-warning">Pending</span>--}}
{{--                        </td>--}}
{{--                    </tr>--}}
{{--                    <tr>--}}
{{--                        <td class="px-6 py-4 whitespace-nowrap">--}}
{{--                            <div class="text-sm font-medium text-gray-800 dark:text-white">System Update</div>--}}
{{--                        </td>--}}
{{--                        <td class="px-6 py-4 whitespace-nowrap">--}}
{{--                            <div class="text-sm text-gray-500 dark:text-gray-400">System</div>--}}
{{--                        </td>--}}
{{--                        <td class="px-6 py-4 whitespace-nowrap">--}}
{{--                            <div class="text-sm text-gray-500 dark:text-gray-400">Yesterday, 10:00 AM</div>--}}
{{--                        </td>--}}
{{--                        <td class="px-6 py-4 whitespace-nowrap">--}}
{{--                            <span class="badge badge-success">Completed</span>--}}
{{--                        </td>--}}
{{--                    </tr>--}}
{{--                    </tbody>--}}
{{--                </table>--}}
{{--            </div>--}}
{{--        </div>--}}

        <!-- Footer -->
        @component('components.backend.dashboard-footer')
        @endcomponent
    </main>
@endsection
