@php use Illuminate\Support\Facades\Auth;
 $system_name = \App\Models\SystemSetting::all()->first()->name;
@endphp

    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$system_name}} - @yield("title") </title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="{{asset("js/sweetalert.js")}}"></script>
    @vite(['resources/js/app.js', 'resources/css/app.css'])

    <!-- Tailwind is already configured in tailwind.config.js -->
    <!-- These utility classes should be moved to app.css for better organization -->
    @stack("styles")
</head>
<body class="bg-gray-50 dark:bg-gray-900 min-h-screen text-gray-800 dark:text-gray-200 antialiased">
<div class="flex h-screen overflow-hidden">
    <!-- Sidebar -->
    <aside id="sidebar"
           class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-gray-800 shadow-lg transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out overflow-y-auto border-r border-gray-200 dark:border-gray-700">
        <div class="flex items-center justify-between p-4 border-b dark:border-gray-700">
            <h1 class="text-xl font-bold text-primary-600 dark:text-primary-400">{{\App\Models\SystemSetting::all()->first()->name}}</h1>
            <button id="closeSidebar"
                    class="p-2 rounded-md text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 md:hidden">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <nav class="p-4 space-y-1">
            <a href="{{route('teacher.dashboard')}}" class="sidebar-item {{(Route::is('teacher.dashboard')?'active':'')}}">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{route('teacher.attendance.index')}}" class="sidebar-item {{(Route::is('teacher.attendance.index')?'active':'')}}">
                <i class="fas fa-calendar-check"></i>
                <span>Attendance</span>
            </a>
            <a href="{{route('teacher.evaluation.index')}}" class="sidebar-item {{(Route::is('teacher.evaluation.index')?'active':'')}}">
                <i class="fas fa-calendar-check"></i>
                <span>Evaluation</span>
            </a>
            <a href="{{route('teacher.assignment.index')}}" class="sidebar-item {{(Route::is('teacher.assignment.index')?'active':'')}}">
                <i class="fas fa-book"></i>
                <span>Assignments</span>
            </a>
            <a href="{{route('teacher.announcement.index')}}" class="sidebar-item {{(Route::is('teacher.announcement.index')?'active':'')}}">
                <i class="fas fa-bullhorn"></i>
                <span>Announcements</span>
            </a>
            <a href="{{route('teacher.resources.index')}}" class="sidebar-item {{(Route::is('teacher.resources.index')?'active':'')}}">
                <i class="fa-solid fa-book"></i>
                <span>Resources</span>
            </a>

            <div class="pt-4 mt-4 border-t border-gray-200 dark:border-gray-700 space-y-2">
                <!-- Profile Link -->
                <a href="{{ route('teacher.profile.index') }}"
                   class="flex items-center px-4 py-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-800 transition {{ Route::is('teacher.profile.*') ? 'bg-gray-100 dark:bg-gray-800 font-semibold' : '' }}">
                    <i class="fas fa-user mr-2"></i>
                    <span>Profile</span>
                </a>
                <!-- Logout Form -->
                <form action="{{ route('teacher.logout') }}" method="POST" class="px-4">
                    @csrf
                    <button type="submit"
                            class="w-full flex items-center text-red-600 dark:text-red-400 py-2 rounded-md transition">
                        <i class="fas fa-sign-out-alt mr-2"></i>
                        <span>Logout Now</span>
                    </button>
                </form>
            </div>
        </nav>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 md:ml-64 flex flex-col h-screen">
        <!-- Top Navbar -->
        <header class="bg-white dark:bg-gray-800 shadow-sm sticky top-0 z-40 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between p-4">
                <div class="flex items-center">
                    <button id="openSidebar"
                            class="p-2 mr-2 rounded-md text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 md:hidden">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-white">@yield("title")</h2>
                </div>

                <div class="flex items-center space-x-4 ml-auto">
                    <!-- Dark Mode Toggle -->
                    <button id="darkModeToggle"
                            class="p-2 rounded-md text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-primary-500">
                        <i class="fas fa-moon dark:hidden"></i>
                        <i class="fas fa-sun hidden dark:block"></i>
                    </button>


                    <!-- Profile Dropdown -->
                    <div class="relative">
                        <button id="profileBtn" class="flex items-center space-x-2">
                            <img
                                src="{{ Auth::user()->profile_picture ? asset('./storage/'.Auth::user()->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->fname." ".Auth::user()->lname) . '&background=0D8ABC&color=fff' }}"
                                alt="Profile" class="w-8 h-8 rounded-full">
                            <span
                                class="text-sm font-medium text-gray-700 dark:text-gray-300 hidden md:block">{{Auth::user()->fname." ".Auth::user()->lname}}</span>
                            <i class="fas fa-chevron-down text-xs text-gray-500 hidden md:block"></i>
                        </button>

                        <!-- Profile Dropdown (Hidden by default) -->
                        <div id="profileDropdown"
                             class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg z-10 hidden">
                            <div class="py-1">
                                <a href="{{route('teacher.profile.index')}}"
                                   class="active block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <i class="fas fa-user mr-2"></i> Profile
                                </a>
                                <div class="border-t dark:border-gray-700"></div>
                                <form action="
                                        @if(auth('admin')->check()) {{ route('admin.logout') }}
                                        @elseif(auth('teacher')->check()) {{ route('teacher.logout') }}
                                        @elseif(auth('student')->check()) {{ route('student.logout') }}
                                        @else '#' @endif
                                    " method="get" class="sidebar-item text-red-500 dark:text-red-400">
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
        <div class="flex-1 overflow-auto">
            @yield("content")
        </div>
    </div>
</div>

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
    // window.addEventListener('DOMContentLoaded', () => {
    //     if (window.Echo) {
    //         Echo.channel('notifications.teachers')
    //             .listen('.new.announcement', (e) => {
    //                 console.log('Teacher-specific:', e.message);
    //             });
    //
    //         Echo.channel('notifications.all')
    //             .listen('.new.announcement', (e) => {
    //                 console.log('Global announcement:', e.message);
    //             });
    //     } else {
    //         console.error('Echo is not loaded yet');
    //     }
    // });
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
</script>
@yield("scripts")
</body>
</html>
