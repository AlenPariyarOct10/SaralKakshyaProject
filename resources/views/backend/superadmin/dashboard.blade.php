@extends('backend.layout.superadmin-dashboard-layout')

@php
    $system = \App\Models\SystemSetting::first();
@endphp

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
                                    <i class="fas fa-check-circle mr-1"></i> 142 Approved
                                </span>
                    </div>
                    <div>
                                <span class="text-sm text-yellow-500">
                                    <i class="fas fa-clock mr-1"></i> 14 Pending
                                </span>
                    </div>
                </div>
            </div>

            <!-- Teachers Stats Card -->
            <div class="card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Teachers</p>
                        <h3 class="text-2xl font-bold text-gray-800 dark:text-white mt-1">1,248</h3>
                    </div>
                    <div class="p-3 rounded-full bg-purple-100 dark:bg-purple-900">
                        <i class="fas fa-chalkboard-teacher text-purple-500 dark:text-purple-300"></i>
                    </div>
                </div>
                <div class="mt-4">
                            <span class="text-sm text-green-500">
                                <i class="fas fa-arrow-up mr-1"></i> 8%
                            </span>
                    <span class="text-sm text-gray-500 dark:text-gray-400 ml-2">from last month</span>
                </div>
            </div>

            <!-- Students Stats Card -->
            <div class="card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Students</p>
                        <h3 class="text-2xl font-bold text-gray-800 dark:text-white mt-1">24,853</h3>
                    </div>
                    <div class="p-3 rounded-full bg-yellow-100 dark:bg-yellow-900">
                        <i class="fas fa-user-graduate text-yellow-500 dark:text-yellow-300"></i>
                    </div>
                </div>
                <div class="mt-4">
                            <span class="text-sm text-green-500">
                                <i class="fas fa-arrow-up mr-1"></i> 12%
                            </span>
                    <span class="text-sm text-gray-500 dark:text-gray-400 ml-2">from last month</span>
                </div>
            </div>
        </div>

        <!-- Pending Approvals & System Alerts -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Pending Approvals -->
            <div class="card">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Pending Approvals</h3>
                    <a href="notifications.html" class="text-sm text-primary-600 hover:underline">View all</a>
                </div>

                <div class="space-y-4">
                    <div class="flex items-start p-3 bg-gray-50 dark:bg-gray-700 rounded-md">
                        <div class="p-2 bg-yellow-100 dark:bg-yellow-800 rounded-md mr-3">
                            <i class="fas fa-user-shield text-yellow-500 dark:text-yellow-300"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-sm font-medium text-gray-800 dark:text-white">New Admin Registration</h4>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">John Smith - john.smith@example.com</p>
                        </div>
                        <div class="flex space-x-2">
                            <button class="px-2 py-1 text-xs bg-green-500 text-white rounded-md hover:bg-green-600">
                                <i class="fas fa-check"></i>
                            </button>
                            <button class="px-2 py-1 text-xs bg-red-500 text-white rounded-md hover:bg-red-600">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-start p-3 bg-gray-50 dark:bg-gray-700 rounded-md">
                        <div class="p-2 bg-yellow-100 dark:bg-yellow-800 rounded-md mr-3">
                            <i class="fas fa-university text-yellow-500 dark:text-yellow-300"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-sm font-medium text-gray-800 dark:text-white">Institute Approval Request</h4>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Tech University - Added by admin@tech.edu</p>
                        </div>
                        <div class="flex space-x-2">
                            <button class="px-2 py-1 text-xs bg-green-500 text-white rounded-md hover:bg-green-600">
                                <i class="fas fa-check"></i>
                            </button>
                            <button class="px-2 py-1 text-xs bg-red-500 text-white rounded-md hover:bg-red-600">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-start p-3 bg-gray-50 dark:bg-gray-700 rounded-md">
                        <div class="p-2 bg-yellow-100 dark:bg-yellow-800 rounded-md mr-3">
                            <i class="fas fa-university text-yellow-500 dark:text-yellow-300"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-sm font-medium text-gray-800 dark:text-white">Institute Approval Request</h4>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Global College - Added by admin@global.edu</p>
                        </div>
                        <div class="flex space-x-2">
                            <button class="px-2 py-1 text-xs bg-green-500 text-white rounded-md hover:bg-green-600">
                                <i class="fas fa-check"></i>
                            </button>
                            <button class="px-2 py-1 text-xs bg-red-500 text-white rounded-md hover:bg-red-600">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- System Alerts -->
            <div class="card">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">System Alerts</h3>
                    <a href="#" class="text-sm text-primary-600 hover:underline">View all</a>
                </div>

                <div class="space-y-4">
                    <div class="p-3 border-l-4 border-red-500 bg-gray-50 dark:bg-gray-700 rounded-md">
                        <h4 class="text-sm font-medium text-gray-800 dark:text-white">System Update Required</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Security update available. Please update the system to version 2.4.0.</p>
                        <div class="mt-2">
                            <button class="px-3 py-1 text-xs bg-primary-600 text-white rounded-md hover:bg-primary-700">Update Now</button>
                        </div>
                    </div>

                    <div class="p-3 border-l-4 border-yellow-500 bg-gray-50 dark:bg-gray-700 rounded-md">
                        <h4 class="text-sm font-medium text-gray-800 dark:text-white">Database Backup</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Last backup was 3 days ago. Consider running a new backup.</p>
                        <div class="mt-2">
                            <button class="px-3 py-1 text-xs bg-primary-600 text-white rounded-md hover:bg-primary-700">Backup Now</button>
                        </div>
                    </div>

                    <div class="p-3 border-l-4 border-blue-500 bg-gray-50 dark:bg-gray-700 rounded-md">
                        <h4 class="text-sm font-medium text-gray-800 dark:text-white">Storage Usage</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">System storage is at 75% capacity. Consider cleaning up old files.</p>
                        <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2.5 mt-2">
                            <div class="bg-blue-500 h-2.5 rounded-full" style="width: 75%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="card">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Recent Activity</h3>
                <a href="reports.html" class="text-sm text-primary-600 hover:underline">View all</a>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Activity</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">User</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-800 dark:text-white">Admin Approved</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500 dark:text-gray-400">Super Admin</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500 dark:text-gray-400">Today, 9:30 AM</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="badge badge-success">Completed</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-800 dark:text-white">Institute Added</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500 dark:text-gray-400">admin@tech.edu</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500 dark:text-gray-400">Yesterday, 2:15 PM</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="badge badge-warning">Pending</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-800 dark:text-white">System Update</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500 dark:text-gray-400">System</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500 dark:text-gray-400">Yesterday, 10:00 AM</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="badge badge-success">Completed</span>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Footer -->
        @component('components.backend.dashboard-footer')
        @endcomponent
    </main>
@endsection
