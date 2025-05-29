
<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{csrf_token()}}">

    <title>{{\App\Models\SystemSetting::all()->first()->name}} - @yield("title", "")</title>
    <link rel="shortcut icon" href="{{\App\Models\SystemSetting::all()->first()->logo}}" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{asset('css/swiper.css')}}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
    <script src="{{asset('js/sweetalert.js')}}"></script>
    <script src="{{asset('js/swiper.js')}}"></script>
    <script src="{{asset('js/jquery.js')}}"></script>
    <script src="{{asset('js/alpine.js')}}"></script>

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
                <a href="{{route("admin.prediction.index")}}" class="sidebar-item  {{Route::is("admin.prediction.index")? "active":""}}">
                    <i class="fa-solid fa-building"></i>
                    <span>Predictions</span>
                </a>
                <a href="{{route("admin.session.index")}}" class="sidebar-item  {{Route::is("admin.session.index")? "active":""}}">
                    <i class="fa-solid fa-building"></i>
                    <span>Session</span>
                </a>
                <a href="{{route("admin.routine-planner.index")}}" class="sidebar-item  {{Route::is("admin.routine-planner.index")? "active":""}}">
                    <i class="fa-solid fa-calendar-days"></i>
                    <span>Class Routine</span>
                </a>
                <a href="{{route("admin.subjects.index")}}" class="sidebar-item  {{Route::is("admin.subjects.index")? "active":""}}">
                    <i class="fa-solid fa-building"></i>
                    <span>Subjects</span>
                </a>
                <a href="{{route("admin.attendance.index")}}" class="sidebar-item {{Route::is("admin.attendance.index")? "active":""}}">
                    <i class="fas fa-calendar-check"></i>
                    <span>Attendance</span>
                </a>

                <a href="{{route("admin.announcement.index")}}" class="sidebar-item {{Route::is("admin.announcement.index")? "active":""}}">
                    <i class="fas fa-bullhorn"></i>
                    <span>Announcements</span>
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
                    <a href="{{route("admin.profile.index")}}" class="sidebar-item">
                        <i class="fas fa-user"></i>
                        <span>Profile</span>
                    </a>
                    <a href="{{ route('admin.logout') }}" class="text-red-600 sidebar-item dark:text-red-400">
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


                        <!-- Profile Dropdown -->
                        <div class="relative">
                            <button id="profileBtn" class="flex items-center space-x-2">
                                <img
                                    src="{{ $user->profile_picture ? asset('storage/'.$user->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($user->fname . ' ' . $user->lname) . '&background=random' }}"
                                     alt="Profile" class="w-8 h-8 rounded-full">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300 hidden md:block">{{$user->fname." ".$user->lname}}</span>
                                <i class="fas fa-chevron-down text-xs text-gray-500 hidden md:block"></i>
                            </button>

                            <!-- Profile Dropdown (Hidden by default) -->
                            <div id="profileDropdown" class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg z-10 hidden">
                                <div class="py-1">
                                    <a href="{{route("admin.profile.index")}}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                        <i class="fas fa-user mr-2"></i> Profile
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

        const profileBtn = document.getElementById('profileBtn');
        const profileDropdown = document.getElementById('profileDropdown');



        profileBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            profileDropdown.classList.toggle('hidden');
        });

        // Close dropdowns when clicking outside
        document.addEventListener('click', () => {
            profileDropdown.classList.add('hidden');
        });

        @yield("js")

    </script>
    @yield("scripts")
    </body>
</html>
