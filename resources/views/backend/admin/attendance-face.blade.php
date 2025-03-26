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

@section('content')
    <!-- Main Content Area -->
    <main class="scrollable-content p-4 md:p-6">
        <!-- Active Live Class -->
        <div class="card mb-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Active Live Class</h3>
                <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">Live Now</span>
            </div>

            <div class="bg-gray-900 rounded-lg overflow-hidden relative aspect-video mb-4">
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="text-center">
                        <i class="fas fa-video text-4xl text-gray-400 mb-2"></i>
                        <p class="text-gray-400">Video stream will appear here</p>
                        <button class="mt-4 px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">Join Live Class</button>
                    </div>
                </div>
            </div>

            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h4 class="text-md font-medium text-gray-800 dark:text-white">Mathematics - Linear Algebra</h4>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Prof. Sarah Johnson • Started 10 minutes ago</p>
                </div>

                <div class="flex gap-2 mt-4 md:mt-0">
                    <button class="flex items-center px-3 py-1 text-sm bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600">
                        <i class="fas fa-microphone-slash mr-2"></i> Mute
                    </button>
                    <button class="flex items-center px-3 py-1 text-sm bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600">
                        <i class="fas fa-video-slash mr-2"></i> Stop Video
                    </button>
                    <button class="flex items-center px-3 py-1 text-sm bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600">
                        <i class="fas fa-hand-paper mr-2"></i> Raise Hand
                    </button>
                    <button class="flex items-center px-3 py-1 text-sm bg-red-600 text-white rounded-md hover:bg-red-700">
                        <i class="fas fa-phone-slash mr-2"></i> Leave
                    </button>
                </div>
            </div>
        </div>

        <!-- Upcoming Classes -->
        <div class="card mb-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Upcoming Classes</h3>
            </div>

            <div class="space-y-4">
                <div class="flex items-start p-3 bg-gray-50 dark:bg-gray-700 rounded-md">
                    <div class="p-2 bg-blue-100 dark:bg-blue-800 rounded-md mr-3">
                        <i class="fas fa-video text-blue-500 dark:text-blue-300"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-sm font-medium text-gray-800 dark:text-white">Physics - Mechanics</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Today, 1:00 PM - 2:30 PM</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Prof. Michael Brown</p>
                    </div>
                    <div>
                        <button class="px-3 py-1 text-xs bg-primary-600 text-white rounded-md hover:bg-primary-700">Add to Calendar</button>
                    </div>
                </div>

                <div class="flex items-start p-3 bg-gray-50 dark:bg-gray-700 rounded-md">
                    <div class="p-2 bg-purple-100 dark:bg-purple-800 rounded-md mr-3">
                        <i class="fas fa-video text-purple-500 dark:text-purple-300"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-sm font-medium text-gray-800 dark:text-white">Computer Science - Algorithms</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Tomorrow, 9:00 AM - 10:30 AM</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Prof. David Wilson</p>
                    </div>
                    <div>
                        <button class="px-3 py-1 text-xs bg-primary-600 text-white rounded-md hover:bg-primary-700">Add to Calendar</button>
                    </div>
                </div>

                <div class="flex items-start p-3 bg-gray-50 dark:bg-gray-700 rounded-md">
                    <div class="p-2 bg-green-100 dark:bg-green-800 rounded-md mr-3">
                        <i class="fas fa-video text-green-500 dark:text-green-300"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-sm font-medium text-gray-800 dark:text-white">Biology - Genetics</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">May 18, 2023, 11:00 AM - 12:30 PM</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Prof. Emily Parker</p>
                    </div>
                    <div>
                        <button class="px-3 py-1 text-xs bg-primary-600 text-white rounded-md hover:bg-primary-700">Add to Calendar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recorded Classes -->
        <div class="card">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Recorded Classes</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="border dark:border-gray-700 rounded-lg overflow-hidden">
                    <div class="bg-gray-800 aspect-video relative">
                        <img src="https://ui-avatars.com/api/?name=Math&amp;background=0D8ABC&amp;color=fff&amp;size=128" alt="Math Thumbnail" class="w-full h-full object-cover">
                        <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50 opacity-0 hover:opacity-100 transition-opacity">
                            <button class="p-3 bg-white rounded-full">
                                <i class="fas fa-play text-gray-800"></i>
                            </button>
                        </div>
                        <span class="absolute bottom-2 right-2 px-2 py-1 text-xs bg-black bg-opacity-70 text-white rounded">45:20</span>
                    </div>
                    <div class="p-3">
                        <h4 class="text-sm font-medium text-gray-800 dark:text-white">Linear Algebra - Matrices and Determinants</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Recorded on May 5, 2023</p>
                        <div class="flex justify-between items-center mt-2">
                            <span class="text-xs text-gray-500 dark:text-gray-400">Prof. Sarah Johnson</span>
                            <button class="text-primary-600 hover:text-primary-800 text-sm">
                                <i class="fas fa-download"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="border dark:border-gray-700 rounded-lg overflow-hidden">
                    <div class="bg-gray-800 aspect-video relative">
                        <img src="https://ui-avatars.com/api/?name=Physics&amp;background=5D3FD3&amp;color=fff&amp;size=128" alt="Physics Thumbnail" class="w-full h-full object-cover">
                        <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50 opacity-0 hover:opacity-100 transition-opacity">
                            <button class="p-3 bg-white rounded-full">
                                <i class="fas fa-play text-gray-800"></i>
                            </button>
                        </div>
                        <span class="absolute bottom-2 right-2 px-2 py-1 text-xs bg-black bg-opacity-70 text-white rounded">52:15</span>
                    </div>
                    <div class="p-3">
                        <h4 class="text-sm font-medium text-gray-800 dark:text-white">Mechanics - Newton's Laws of Motion</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Recorded on May 3, 2023</p>
                        <div class="flex justify-between items-center mt-2">
                            <span class="text-xs text-gray-500 dark:text-gray-400">Prof. Michael Brown</span>
                            <button class="text-primary-600 hover:text-primary-800 text-sm">
                                <i class="fas fa-download"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="border dark:border-gray-700 rounded-lg overflow-hidden">
                    <div class="bg-gray-800 aspect-video relative">
                        <img src="https://ui-avatars.com/api/?name=CS&amp;background=E94560&amp;color=fff&amp;size=128" alt="CS Thumbnail" class="w-full h-full object-cover">
                        <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50 opacity-0 hover:opacity-100 transition-opacity">
                            <button class="p-3 bg-white rounded-full">
                                <i class="fas fa-play text-gray-800"></i>
                            </button>
                        </div>
                        <span class="absolute bottom-2 right-2 px-2 py-1 text-xs bg-black bg-opacity-70 text-white rounded">48:30</span>
                    </div>
                    <div class="p-3">
                        <h4 class="text-sm font-medium text-gray-800 dark:text-white">Algorithms - Sorting Algorithms</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Recorded on April 28, 2023</p>
                        <div class="flex justify-between items-center mt-2">
                            <span class="text-xs text-gray-500 dark:text-gray-400">Prof. David Wilson</span>
                            <button class="text-primary-600 hover:text-primary-800 text-sm">
                                <i class="fas fa-download"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
@section("scripts")

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
@endsection
