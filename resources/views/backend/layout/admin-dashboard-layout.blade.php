@php use Illuminate\Support\Facades\Auth; @endphp
@php
    $user = Auth::user()
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    @livewireStyles
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{\App\Models\SystemSetting::all()->first()->name}} - Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{asset('css/swiper.css')}}">
    <script src="{{asset('js/sweetalert.js')}}"></script>
    <script src="{{asset('js/swiper.js')}}"></script>
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
            .card {
                @apply bg-white dark:bg-gray-800 rounded-lg shadow-md p-6;
            }
            .sidebar-item {
                @apply flex items-center gap-3 px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md transition-colors;
            }
            .sidebar-item.active {
                @apply bg-primary-50 dark:bg-gray-700 text-primary-600 dark:text-primary-400 font-medium;
            }
            /* Add scrollable content styles */
            .scrollable-content {
                @apply overflow-y-auto;
                height: calc(100vh - 64px); /* Adjust based on header height */
            }
        }
    </style>
    <style>
        .colored-toast.swal2-icon-success {
            background-color: #a5dc86 !important;
        }

        .colored-toast.swal2-icon-error {
            background-color: #f27474 !important;
        }

        .colored-toast.swal2-icon-warning {
            background-color: #f8bb86 !important;
        }

        .colored-toast.swal2-icon-info {
            background-color: #3fc3ee !important;
        }

        .colored-toast.swal2-icon-question {
            background-color: #87adbd !important;
        }

        .colored-toast .swal2-title {
            color: white;
        }

        .colored-toast .swal2-close {
            color: white;
        }

        .colored-toast .swal2-html-container {
            color: white;
        }
    </style>
    @stack('styles')
</head>
@section("content")
    <body class="bg-gray-100 dark:bg-gray-900 min-h-screen">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-gray-800 shadow-md transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out overflow-y-auto">
            <div class="flex items-center justify-between p-4 border-b dark:border-gray-700">
                <h1 class="text-xl font-bold text-primary-600 dark:text-primary-400">{{\App\Models\SystemSetting::all()->first()->name}}</h1>
                <button id="closeSidebar" class="p-2 rounded-md text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 md:hidden">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <nav class="p-4 space-y-1">
                <a href="{{route("admin.dashboard")}}" class="sidebar-item {{Route::is("admin.dashboard")? "active":""}}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{route("admin.programs.index")}}" class="sidebar-item  {{Route::is("admin.programs.index")? "active":""}}">
                    <i class="fas fa-book"></i>
                    <span>Programs</span>
                </a>
                <a href="{{route("admin.department.index")}}" class="sidebar-item  {{Route::is("admin.department.index")? "active":""}}">
                    <i class="fa-solid fa-building"></i>
                    <span>Departments</span>
                </a>
                <a href="{{route("admin.attendance.index")}}" class="sidebar-item {{Route::is("admin.attendance.index")? "active":""}}">
                    <i class="fas fa-calendar-check"></i>
                    <span>Attendance</span>
                </a>

                <a href="{{route("admin.attendance-face.index")}}" class="sidebar-item {{Route::is("admin.attendance-face.index")? "active":""}}">
                    <i class="fas fa-video"></i>
                    <span>Attendance - Face</span>
                </a>
                <a href="{{route("admin.announcement.index")}}" class="sidebar-item {{Route::is("admin.announcement.index")? "active":""}}">
                    <i class="fas fa-bullhorn"></i>
                    <span>Announcements</span>
                </a>
                <a href="{{route("admin.testimonial.index")}}" class="sidebar-item {{Route::is("admin.testimonial.index")? "active":""}}">
                    <i class="fa-solid fa-star-half-stroke"></i>
                    <span>Testimonial</span>
                </a>
                <div class="pt-4 mt-4 border-t dark:border-gray-700">
                    <a href="{{route("admin.student.index")}}" class="sidebar-item {{Route::is("admin.student.index")? "active":""}}">
                        <i class="fa-solid fa-graduation-cap"></i>
                        <span>Students</span>
                    </a>
                    <a href="{{route("admin.teacher.index")}}" class="sidebar-item {{Route::is("admin.teacher.index")? "active":""}}">
                        <i class="fa-solid fa-chalkboard-user"></i>
                        <span>Teachers</span>
                    </a>
                </div>
                <div class="pt-4 mt-4 border-t dark:border-gray-700">
                    <a href="" class="sidebar-item">
                        <i class="fas fa-user"></i>
                        <span>Profile</span>
                    </a>
                    <a href="settings.html" class="sidebar-item">
                        <i class="fas fa-cog"></i>
                        <span>Settings</span>
                    </a>
                    <a href="{{ route('admin.logout') }}" class="sidebar-item text-red-500 dark:text-red-400">
                            <i class="fas fa-sign-out-alt"></i>
                            <span> Logout Now</span>
                    </a>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 md:ml-64 flex flex-col h-screen">
            <!-- Top Navbar -->
            <header class="bg-white dark:bg-gray-800 shadow-sm">
                <div class="flex items-center justify-between p-4">
                    <div class="flex items-center">
                        <button id="openSidebar" class="p-2 mr-2 rounded-md text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 md:hidden">
                            <i class="fas fa-bars"></i>
                        </button>
                        <h2 class="text-xl font-semibold text-gray-800 dark:text-white">@yield("title")</h2>
                    </div>

                    <div class="flex items-center space-x-4">
                        <!-- Dark Mode Toggle -->
                        <button id="darkModeToggle" class="p-2 rounded-md text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700">
                            <i class="fas fa-moon dark:hidden"></i>
                            <i class="fas fa-sun hidden dark:block"></i>
                        </button>

                        <!-- Notifications -->
                        <div class="relative">
                            <button id="notificationBtn" class="p-2 rounded-md text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700">
                                <i class="fas fa-bell"></i>
                                <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full"></span>
                            </button>

                            <!-- Notification Dropdown (Hidden by default) -->
                            <div id="notificationDropdown" class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-md shadow-lg z-10 hidden">
                                <div class="p-4 border-b dark:border-gray-700">
                                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Notifications</h3>
                                </div>
                                <div class="max-h-96 overflow-y-auto">
                                    <a href="#" class="block p-4 border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <p class="text-sm font-medium text-gray-800 dark:text-white">New assignment posted</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">10 minutes ago</p>
                                    </a>
                                    <a href="#" class="block p-4 border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <p class="text-sm font-medium text-gray-800 dark:text-white">Live class scheduled</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">1 hour ago</p>
                                    </a>
                                    <a href="#" class="block p-4 hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <p class="text-sm font-medium text-gray-800 dark:text-white">Attendance marked</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Yesterday</p>
                                    </a>
                                </div>
                                <div class="p-2 text-center border-t dark:border-gray-700">
                                    <a href="#" class="text-sm text-primary-600 hover:underline">View all notifications</a>
                                </div>
                            </div>
                        </div>

                        <!-- Profile Dropdown -->
                        <div class="relative">
                            <button id="profileBtn" class="flex items-center space-x-2">
                                <img src="{{ $user->profile_picture ? asset($user->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($user->fname . ' ' . $user->lname) . '&background=0D8ABC&color=fff' }}"
                                     alt="Profile" class="w-8 h-8 rounded-full">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300 hidden md:block">{{$user->fname." ".$user->lname}}</span>
                                <i class="fas fa-chevron-down text-xs text-gray-500 hidden md:block"></i>
                            </button>

                            <!-- Profile Dropdown (Hidden by default) -->
                            <div id="profileDropdown" class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg z-10 hidden">
                                <div class="py-1">
                                    <a href="" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                        <i class="fas fa-user mr-2"></i> Profile
                                    </a>
                                    <a href="settings.html" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                        <i class="fas fa-cog mr-2"></i> Settings
                                    </a>
                                    <div class="border-t dark:border-gray-700"></div>
                                    <form action="{{route('admin.logout') }}" method="get" class="sidebar-item text-red-500 dark:text-red-400">
                                        <button type="submit" class="flex items-center">
                                            <i class="fas fa-sign-out-alt"></i>
                                            Logout
                                        </button>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content Area - Made Scrollable -->
            @yield("content")
        </div>
    </div>

    @yield("modals")
    @livewireScripts
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'bottom-end',
            iconColor: 'white',
            customClass: {
                popup: 'colored-toast',
            },
            showConfirmButton: false,
            timer: 1500,
            timerProgressBar: true,
        });

        // Sidebar Toggle
        const sidebar = document.getElementById('sidebar');
        const openSidebar = document.getElementById('openSidebar');
        const closeSidebar = document.getElementById('closeSidebar');

        openSidebar.addEventListener('click', () => {
            sidebar.classList.remove('-translate-x-full');
        });

        closeSidebar.addEventListener('click', () => {
            sidebar.classList.add('-translate-x-full');
        });

        // Dark Mode Toggle
        const darkModeToggle = document.getElementById('darkModeToggle');

        // Check for saved theme preference or use the system preference
        if (localStorage.getItem('darkMode') === 'true' ||
            (!localStorage.getItem('darkMode') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }

        darkModeToggle.addEventListener('click', () => {
            document.documentElement.classList.toggle('dark');
            localStorage.setItem('darkMode', document.documentElement.classList.contains('dark'));
        });

        // Dropdowns
        const notificationBtn = document.getElementById('notificationBtn');
        const notificationDropdown = document.getElementById('notificationDropdown');
        const profileBtn = document.getElementById('profileBtn');
        const profileDropdown = document.getElementById('profileDropdown');

        notificationBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            notificationDropdown.classList.toggle('hidden');
            profileDropdown.classList.add('hidden');
        });

        profileBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            profileDropdown.classList.toggle('hidden');
            notificationDropdown.classList.add('hidden');
        });

        // Close dropdowns when clicking outside
        document.addEventListener('click', () => {
            notificationDropdown.classList.add('hidden');
            profileDropdown.classList.add('hidden');
        });

        @yield("js")

    </script>
    @yield("scripts")
    </body>
</html>
