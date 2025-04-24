@extends('backend.layout.superadmin-dashboard-layout')

@php
    $system = \App\Models\SystemSetting::first();
@endphp

@push('styles')
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                        },
                    }
                }
            }
        }
    </script>
    <style type="text/tailwindcss">
        @layer utilities {
            .btn-primary {
                @apply px-6 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-colors;
            }
            .btn-secondary {
                @apply px-6 py-2 bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-200 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300 dark:focus:ring-gray-600 focus:ring-offset-2 transition-colors;
            }
            .card {
                @apply bg-white dark:bg-gray-800 rounded-lg shadow-md p-6;
            }
            .sidebar-item {
                @apply flex items-center gap-3 px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md transition-colors;
            }
            .sidebar-item.active {
                @apply bg-primary-50 dark:bg-gray-700 text-primary-600 dark:text-primary-400 font-medium;
            }
            .scrollable-content {
                @apply overflow-y-auto;
                height: calc(100vh - 64px); /* Adjust based on header height */
            }
            .badge {
                @apply px-2 py-1 text-xs font-medium rounded-full;
            }
            .badge-success {
                @apply bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100;
            }
            .badge-warning {
                @apply bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100;
            }
            .badge-danger {
                @apply bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100;
            }
            .badge-info {
                @apply bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100;
            }
            .tab {
                @apply px-4 py-2 text-sm font-medium rounded-t-lg;
            }
            .tab.active {
                @apply bg-white dark:bg-gray-800 text-primary-600 dark:text-primary-400 border-b-2 border-primary-600 dark:border-primary-400;
            }
            .tab:not(.active) {
                @apply text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300;
            }
            .modal {
                @apply fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50 transition-opacity;
            }
            .modal-content {
                @apply bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full max-h-[90vh] overflow-y-auto;
            }
            .form-input {
                @apply bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5;
            }
            .form-label {
                @apply block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1;
            }
            .form-error {
                @apply text-xs text-red-500 mt-1;
            }
        }
    </style>
@endpush

@section('content')
    <!-- Main Content Area - Made Scrollable -->
    <main class="scrollable-content p-4 md:p-6">
        <!-- Institute Management Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Institute Management</h1>
                <p class="text-gray-600 dark:text-gray-400">Manage all educational institutes in the system</p>
            </div>
        </div>


        <!-- Search and Filter -->
{{--        <div class="flex flex-col md:flex-row gap-4 mb-6">--}}
{{--            <div class="flex-1">--}}
{{--                <div class="relative">--}}
{{--                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">--}}
{{--                        <i class="fas fa-search text-gray-400"></i>--}}
{{--                    </div>--}}
{{--                    <input type="text" id="institute-search" class="form-input pl-10" placeholder="Search institutes...">--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <div class="flex flex-col sm:flex-row gap-2">--}}
{{--                <select id="type-filter" class="form-input">--}}
{{--                    <option selected value="">All Types</option>--}}
{{--                    <option value="university">University</option>--}}
{{--                    <option value="college">College</option>--}}
{{--                    <option value="school">School</option>--}}
{{--                    <option value="training">Training Center</option>--}}
{{--                </select>--}}
{{--                <select id="status-filter" class="form-input">--}}
{{--                    <option selected value="">All Status</option>--}}
{{--                    <option value="active">Active</option>--}}
{{--                    <option value="inactive">Inactive</option>--}}
{{--                    <option value="pending">Pending</option>--}}
{{--                </select>--}}
{{--            </div>--}}
{{--        </div>--}}

        <!-- Institute List -->
        <div class="card">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            <div class="flex items-center">
                                Institute
                                <i class="fas fa-sort ml-1"></i>
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            <div class="flex items-center">
                                Type
                                <i class="fas fa-sort ml-1"></i>
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            <div class="flex items-center">
                                Location
                                <i class="fas fa-sort ml-1"></i>
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            <div class="flex items-center">
                                Registered at
                                <i class="fas fa-sort ml-1"></i>
                            </div>
                        </th>

                    </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700" id="instituteTableBody">
                    @forelse($institutes as $institute)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name=Tech+University&background=0D8ABC&color=fff" alt="">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{$institute->name}}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{$institute->email}}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500 dark:text-gray-400">Institute</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{$institute->address}}</div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{$institute->created_at}}
                            </td>


                        </tr>
                    @empty
                    @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="flex items-center justify-between border-t border-gray-200 dark:border-gray-700 px-4 py-3 sm:px-6 mt-4">
                <div class="flex-1 flex justify-between sm:hidden">
                    <a href="#" class="relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        Previous
                    </a>
                    <a href="#" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        Next
                    </a>
                </div>
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700 dark:text-gray-300">
                            Showing <span class="font-medium">1</span> to <span class="font-medium">5</span> of <span class="font-medium">38</span> results
                        </p>
                    </div>
                    <div>
                        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                            <a href="#" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-sm font-medium text-gray-500 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <span class="sr-only">Previous</span>
                                <i class="fas fa-chevron-left"></i>
                            </a>
                            <a href="#" aria-current="page" class="z-10 bg-primary-50 dark:bg-primary-900 border-primary-500 dark:border-primary-500 text-primary-600 dark:text-primary-200 relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                                1
                            </a>
                            <a href="#" class="bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-500 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                                2
                            </a>
                            <a href="#" class="bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-500 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                                3
                            </a>
                            <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-sm font-medium text-gray-700 dark:text-gray-300">
                                        ...
                                    </span>
                            <a href="#" class="bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-500 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                                8
                            </a>
                            <a href="#" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-sm font-medium text-gray-500 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <span class="sr-only">Next</span>
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer -->
        @component('components.backend.dashboard-footer')
        @endcomponent
    </main>
@endsection
