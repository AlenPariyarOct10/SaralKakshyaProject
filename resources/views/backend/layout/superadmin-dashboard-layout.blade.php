@php use Illuminate\Support\Facades\Auth; @endphp
@php
    $user = Auth::user()
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{\App\Models\SystemSetting::all()->first()->name}} - Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
            .btn-danger {
                @apply px-6 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors;
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
        }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-100 dark:bg-gray-900 min-h-screen">
<div class="flex h-screen overflow-hidden">
    <!-- Sidebar -->
    <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-gray-800 shadow-md transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out overflow-y-auto">
        <div class="flex items-center justify-between p-4 border-b dark:border-gray-700">
            <h1 class="text-xl font-bold text-primary-600 dark:text-primary-400">SuperAdmin Portal</h1>
            <button id="closeSidebar" class="p-2 rounded-md text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 md:hidden">
                <i class="fas fa-times"></i>
            </button>
        </div>

        @component('components.backend.super-admin-dashboard-sidebar') @endcomponent
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
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Dashboard</h2>
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
                                    <p class="text-sm font-medium text-gray-800 dark:text-white">New admin registration</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">10 minutes ago</p>
                                </a>
                                <a href="#" class="block p-4 border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <p class="text-sm font-medium text-gray-800 dark:text-white">Institute approval request</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">1 hour ago</p>
                                </a>
                                <a href="#" class="block p-4 hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <p class="text-sm font-medium text-gray-800 dark:text-white">System update available</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Yesterday</p>
                                </a>
                            </div>
                            <div class="p-2 text-center border-t dark:border-gray-700">
                                <a href="notifications.html" class="text-sm text-primary-600 hover:underline">View all notifications</a>
                            </div>
                        </div>
                    </div>

                    <!-- Profile Dropdown -->
                    <div class="relative">
                        <button id="profileBtn" class="flex items-center space-x-2">
                            <img src="{{($user->profile_picture)?asset('./'.$user->profile_picture):'https://ui-avatars.com/api/?name=Super+Admin&background=0D8ABC&color=fff'}}" alt="Profile" class="w-8 h-8 rounded-full">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300 hidden md:block">{{$user->fname}} {{$user->lname}}</span>
                            <i class="fas fa-chevron-down text-xs text-gray-500 hidden md:block"></i>
                        </button>

                        <!-- Profile Dropdown (Hidden by default) -->
                        <div id="profileDropdown" class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg z-10 hidden">
                            <div class="py-1">
                                <a href="profile.html" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <i class="fas fa-user mr-2"></i> Profile
                                </a>
                                <a href="settings.html" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <i class="fas fa-cog mr-2"></i> Settings
                                </a>
                                <div class="border-t dark:border-gray-700"></div>
                                <a href="index.html" class="block px-4 py-2 text-sm text-red-500 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content Area - Made Scrollable -->
        @yield("content")
        <div id="logoutModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50 transition-opacity hidden">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Confirm Logout</h3>
                        <button id="closeLogoutModal" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="space-y-4">
                        <p class="text-gray-600 dark:text-gray-400">Are you sure you want to log out of your account?</p>
                        <div class="flex justify-end space-x-2 mt-6">
                            <button id="cancelLogout" class="btn-secondary">Cancel</button>
                            <a href="{{route("superadmin.logout")}}" class="btn-danger">Yes, Log Out</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
</div>
<script>
    // Logout Modal Functionality
    const logoutLinks = document.querySelectorAll('a[href="index.html"]');
    const logoutModal = document.getElementById('logoutModal');
    const closeLogoutModal = document.getElementById('closeLogoutModal');
    const cancelLogout = document.getElementById('cancelLogout');

    // Add click event to all logout links
    logoutLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            // Only prevent default if it's not the button in the modal
            if (!this.classList.contains('btn-primary')) {
                e.preventDefault();
                logoutModal.classList.remove('hidden');
            }
        });
    });

    // Close modal events
    closeLogoutModal.addEventListener('click', () => {
        logoutModal.classList.add('hidden');
    });

    cancelLogout.addEventListener('click', () => {
        logoutModal.classList.add('hidden');
    });

    // Close modal when clicking outside
    logoutModal.addEventListener('click', (e) => {
        if (e.target === logoutModal) {
            logoutModal.classList.add('hidden');
        }
    });
</script>
<script>
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
</script>
</body>
</html>
