<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SaralKakshya - @yield("title")</title>
    @vite(['resources/js/app.js', 'resources/css/app.css'])

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
            @layer utilities {
                .notification-badge {
                    @apply absolute top-0 right-0 flex items-center justify-center w-5 h-5 text-xs text-white bg-red-500 rounded-full;
                }
            }
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
                <h1 class="text-xl font-bold text-primary-600 dark:text-primary-400">SaralKakshya</h1>
                <button id="closeSidebar" class="p-2 rounded-md text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 md:hidden">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <nav class="p-4 space-y-1">
                <a href="{{route('student.dashboard')}}" class="sidebar-item {{ Route::is('student.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{route("student.attendance.index")}}" class="sidebar-item {{ Route::is('student.attendance.*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-check"></i>
                    <span>Attendance</span>
                </a>
                <a href="{{route("student.prediction.index")}}" class="sidebar-item {{ Route::is('student.prediction.*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-check"></i>
                    <span>Prediction</span>
                </a>
                <a href="{{route("student.assignment.index")}}" class="sidebar-item {{ Route::is('student.assignment.*') ? 'active' : '' }}">
                    <i class="fas fa-book"></i>
                    <span>Assignments</span>
                </a>
                <a href="{{route('student.announcement.index')}}" class="sidebar-item {{ Route::is('student.announcement.*') ? 'active' : '' }}">
                    <i class="fas fa-bullhorn"></i>
                    <span>Announcements</span>
                </a>
                <a href="{{route('student.subjects.index')}}" class="sidebar-item {{ Route::is('student.subjects.*') ? 'active' : '' }}">
                    <i class="fas fa-bullhorn"></i>
                    <span>Subjects</span>
                </a>
                <a href="{{route('student.routine.index')}}" class="sidebar-item {{ Route::is('student.routine.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-clock"></i>
                    <span>Routine</span>
                </a>

                <div class="pt-4 mt-4 border-t dark:border-gray-700">
                    <a href="{{route('student.profile')}}" class="sidebar-item {{ Route::is('student.profile') ? 'active' : '' }}">
                        <i class="fas fa-user"></i>
                        <span>Profile</span>
                    </a>
                    <form action="{{ route('student.logout') }}" method="get" class="sidebar-item text-red-500 dark:text-red-400">
                        <button type="submit" class="flex items-center">
                            <i class="fas fa-sign-out-alt"></i>
                            Logout Now
                        </button>
                    </form>

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
                        <h2 class="text-xl font-semibold text-gray-800 dark:text-white"> @yield("title", "SaralKakshya") </h2>
                    </div>

                    <div class="flex items-center space-x-4">
                        <!-- Dark Mode Toggle -->
                        <button id="darkModeToggle" class="p-2 rounded-md text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700">
                            <i class="fas fa-moon dark:hidden"></i>
                            <i class="fas fa-sun hidden dark:block"></i>
                        </button>

                        <!-- Notifications -->
                        <div class="relative">
                            <button id="notificationBtn" class="p-2 rounded-md text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 relative">
                                <i class="fas fa-bell"></i>
                                <span id="badgeContainer" class="hidden">
                                    <span id="notificationBadge" class="notification-badge hidden">0</span>
                                </span>
                            </button>

                            <!-- Notification Dropdown (Hidden by default) -->
                            <!-- Replace your existing notification dropdown with this -->
                            <div id="notificationDropdown" class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-md shadow-lg z-10 hidden">
                                <div class="p-4 border-b dark:border-gray-700">
                                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Notifications</h3>
                                </div>
                                <div class="max-h-96 overflow-y-auto">
                                    <div class="p-4 text-center">
                                        <i class="fas fa-spinner fa-spin text-primary-500"></i>
                                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Loading notifications...</p>
                                    </div>
                                </div>
                                <div class="p-2 text-center border-t dark:border-gray-700">
                                    <a href="/notifications" id="viewAllNotificationBtn" class="text-sm text-primary-600 hover:underline">View all notifications</a>
                                </div>
                            </div>
                        </div>

                        <!-- Profile Dropdown -->
                        <div class="relative">
                            <button id="profileBtn" class="flex items-center space-x-2">
                                <img src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($user->fname . ' ' . $user->lname) . '&background=0D8ABC&color=fff' }}"
                                     alt="Profile" class="w-8 h-8 rounded-full">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300 hidden md:block">@yield('username')</span>
                                <i class="fas fa-chevron-down text-xs text-gray-500 hidden md:block"></i>
                            </button>

                            <!-- Profile Dropdown (Hidden by default) -->
                            <div id="profileDropdown" class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg z-10 hidden">
                                <div class="py-1">
                                    <a href="{{route('student.profile')}}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                        <i class="fas fa-user w-5 mr-2"></i> Profile
                                    </a>
                                    <div class="border-t dark:border-gray-700"></div>
                                    <form action="{{route('student.logout')}}" method="get">
                                        <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-red-700 dark:text-red-600 hover:bg-gray-100 dark:hover:bg-gray-700">
                                            <i class="fas fa-sign-out-alt w-5 mr-2"></i> Logout
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

    @yield("scripts")
    <script>
        function showToast(message) {
            const toast = document.createElement('div');
            toast.textContent = message;
            toast.className = 'fixed bottom-5 right-5 z-50 px-4 py-3 bg-green-600 text-white rounded shadow-lg animate-slide-in';

            document.body.appendChild(toast);

            // Animate and remove toast after 3s
            setTimeout(() => {
                toast.classList.add('opacity-0', 'transition-opacity');
                setTimeout(() => document.body.removeChild(toast), 500);
            }, 3000);
        }
        window.addEventListener('load', () => {
            const notificationSound = new Audio('/sounds/notification-sound.wav');
                if (window.Echo) {
                    window.Echo.channel(`assignments.students.1`)
                        .listen('.assignment.created', (e) => {
                            console.log('Public assignment received:', e.assignment);

                            // Show SweetAlert Toast
                            Swal.fire({
                                toast: true,
                                position: 'bottom-end',
                                icon: 'info',
                                title: `New Assignment: ${e.assignment.title}`,
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                                didOpen: (toast) => {
                                    toast.addEventListener('mouseenter', Swal.stopTimer);
                                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                                }
                            });

                            // Reload notifications
                            if (typeof loadNotifications === 'function') {
                                loadNotifications();
                            }

                            notificationSound.play().catch(e => {
                                console.warn('Audio play was prevented:', e);
                            });
                        });
            } else {
                console.error('Echo not initialized');
            }
        });


        document.addEventListener('DOMContentLoaded', function() {
            const notificationBtn = document.getElementById('notificationBtn');
            const notificationDropdown = document.getElementById('notificationDropdown');
            const notificationBadge = document.getElementById('notificationBadge');
            const badgeContainer = document.getElementById('badgeContainer');
            const profileDropdown = document.getElementById('profileDropdown');

            // Load notifications immediately when page loads
            loadNotifications();

            // Function to load notifications
            async function loadNotifications() {
                try {
                    const response = await fetch('/api/student/notification', {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                        },
                        credentials: 'include' // Important for session auth
                    });

                    console.log("Response:", response);

                    if (!response.ok) {
                        throw new Error('Failed to fetch notifications');
                    }

                    const { data, meta } = await response.json();
                    console.log("Notifications data:", data);

                    // Clear existing notifications
                    const container = notificationDropdown.querySelector('.max-h-96');
                    container.innerHTML = '';

                    // Add new notifications
                    if (data && data.length > 0) {
                        data.forEach(notification => {
                            const notificationElement = document.createElement('a');
                            notificationElement.href = notification.url || '#';
                            notificationElement.className = 'block p-4 border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700';

                            if (notification.id) {
                                notificationElement.dataset.id = notification.id;
                            }

                            notificationElement.innerHTML = `
                            <p class="text-sm font-medium text-gray-800 dark:text-white">${notification.title}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                ${new Date(notification.created_at).toLocaleString()}
                                ${!notification.read_at ? '<span class="ml-2 inline-block w-2 h-2 bg-red-500 rounded-full"></span>' : ''}
                            </p>
                        `;
                            container.appendChild(notificationElement);
                        });

                        // Update badge count (unread notifications)
                        const unreadCount = data.filter(n => !n.read_at).length;
                        if (unreadCount > 0) {
                            notificationBadge.textContent = unreadCount > 9 ? '9+' : unreadCount;
                            notificationBadge.classList.remove('hidden');
                            badgeContainer.classList.remove('hidden');
                        } else {
                            notificationBadge.classList.add('hidden');
                            badgeContainer.classList.add('hidden');
                        }
                    } else {
                        container.innerHTML = '<p class="p-4 text-sm text-gray-500 dark:text-gray-400">No notifications found</p>';
                        notificationBadge.classList.add('hidden');
                        badgeContainer.classList.add('hidden');

                    }


                } catch (error) {
                    console.error('Error loading notifications:', error);
                    const container = notificationDropdown.querySelector('.max-h-96');
                    container.innerHTML = '<p class="p-4 text-sm text-red-500">Failed to load notifications</p>';
                }
            }

            // Toggle dropdown when notification button is clicked
            notificationBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                notificationDropdown.classList.toggle('hidden');
                profileDropdown.classList.add('hidden');
            });

            // Mark as read when clicked
            notificationDropdown.addEventListener('click', async (e) => {
                const notificationItem = e.target.closest('a[data-id]');
                if (notificationItem && notificationItem.getAttribute('href') === '#') {
                    e.preventDefault();
                    const notificationId = notificationItem.dataset.id;
                    try {
                        await fetch(`/api/notification/${notificationId}/read`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
                            },
                            credentials: 'include'
                        });
                        loadNotifications(); // Refresh notifications
                    } catch (error) {
                        console.error('Error marking notification as read:', error);
                    }
                }
            });

            // Poll for new notifications every 5 minutes
            setInterval(loadNotifications, 300000);

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

            // Profile dropdown
            const profileBtn = document.getElementById('profileBtn');

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
        });
    </script>
    </body>
</html>
