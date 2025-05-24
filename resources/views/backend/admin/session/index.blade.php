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
        <!-- Session Management Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Session Management</h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Manage all sessions for {{$institute->name}}</p>
            </div>
            <div class="mt-4 md:mt-0">
                <button onclick="showSessionModal()" class="btn-primary">
                    <i class="fas fa-plus mr-2"></i> Schedule New Session
                </button>
            </div>
        </div>

        <!-- Filters and Search -->
        <div class="card mb-6">
            <div class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <label for="search" class="sr-only">Search</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input id="search" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-800 dark:border-gray-700 dark:text-white" placeholder="Search sessions..." type="search">
                    </div>
                </div>
                <div class="w-full md:w-48">
                    <label for="status" class="sr-only">Status</label>
                    <select id="status" class="block w-full px-3 py-2 border border-gray-300 rounded-md leading-5 bg-white focus:outline-none focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                        <option value="">All Statuses</option>
                        <option value="scheduled">Scheduled</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                <div class="w-full md:w-48">
                    <label for="date-range" class="sr-only">Date Range</label>
                    <select id="date-range" class="block w-full px-3 py-2 border border-gray-300 rounded-md leading-5 bg-white focus:outline-none focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                        <option value="upcoming">Upcoming</option>
                        <option value="past">Past</option>
                        <option value="this-week">This Week</option>
                        <option value="this-month">This Month</option>
                        <option value="custom">Custom Range</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Sessions List -->
        <div class="card">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date & Time</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Duration</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Participants</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Notes</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($sessions as $session)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ date('M d, Y', strtotime($session->date)) }}
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ date('h:i A', strtotime($session->start_time)) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">
                                    {{ $session->duration }} minutes
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        @if($session->status == 'scheduled') bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100
                                        @elseif($session->status == 'completed') bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100
                                        @else bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100 @endif">
                                        {{ ucfirst($session->status) }}
                                    </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">{{ $session->participants_count ?? 0 }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 dark:text-white truncate max-w-xs">
                                    {{ $session->notes ?? 'No notes' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <button onclick="viewSession({{ $session->id }})" class="text-primary-600 hover:text-primary-900 dark:text-primary-400 dark:hover:text-primary-300" title="View">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button onclick="editSession({{ $session->id }})" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button onclick="confirmCancelSession({{ $session->id }})" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300" title="Cancel">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                                No sessions found. <button onclick="showSessionModal()" class="text-primary-600 hover:text-primary-900 dark:text-primary-400 dark:hover:text-primary-300">Schedule one</button>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-4 py-3 flex items-center justify-between border-t border-gray-200 dark:border-gray-700 sm:px-6">
                {{ $sessions->links() }}
            </div>
        </div>
    </main>

    <!-- Schedule Session Modal -->
    <div id="sessionModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="w-full max-w-2xl mx-4 bg-white dark:bg-gray-900 rounded-lg shadow-lg p-6 space-y-6">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Schedule New Session</h2>
            <form id="sessionForm" class="space-y-5">
                <div>
                    <label for="date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date</label>
                    <input type="date" id="date" name="date" required class="w-full px-4 py-2 mt-1 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:ring-primary-500 focus:border-primary-500">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="start_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Start Time</label>
                        <input type="time" id="start_time" name="start_time" required class="px-4 py-2 w-full mt-1 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:ring-primary-500 focus:border-primary-500">
                    </div>
                    <div>
                        <label for="end_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300">End Time</label>
                        <input type="time" id="end_time" name="end_time" required class="w-full px-4 py-2  mt-1 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:ring-primary-500 focus:border-primary-500">
                    </div>
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                    <select id="status" name="status" required class="w-full px-4 py-2  mt-1 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:ring-primary-500 focus:border-primary-500">
                        <option value="scheduled">Scheduled</option>
                        <option value="cancelled">Cancelled</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="creator_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Creator Type</label>
                        <input type="text" id="creator_type" name="creator_type" class="px-4 py-2 w-full mt-1 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:ring-primary-500 focus:border-primary-500">
                    </div>
                    <div>
                        <label for="creator_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Creator ID</label>
                        <input type="number" id="creator_id" name="creator_id" class="px-4 py-2 w-full mt-1 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:ring-primary-500 focus:border-primary-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="specific_group" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Specific Group</label>
                        <input type="text" id="specific_group" name="specific_group" class="px-4 py-2 w-full mt-1 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:ring-primary-500 focus:border-primary-500">
                    </div>
                    <div>
                        <label for="specific_group_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Specific Group ID</label>
                        <input type="number" id="specific_group_id" name="specific_group_id" class="px-4 py-2 w-full mt-1 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:ring-primary-500 focus:border-primary-500">
                    </div>
                </div>

                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Notes</label>
                    <textarea id="notes" name="notes" rows="3" class="px-4 py-2 w-full mt-1 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:ring-primary-500 focus:border-primary-500"></textarea>
                </div>

                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" onclick="hideSessionModal()" class="px-4 py-2 rounded-md bg-gray-300 text-gray-900 hover:bg-gray-400 dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 rounded-md bg-primary-600 text-white hover:bg-primary-700 dark:bg-primary-700 dark:hover:bg-primary-600">
                        Schedule
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Cancel Session Modal -->
    <div id="cancelSessionModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="w-full max-w-lg mx-4 bg-white dark:bg-gray-900 rounded-lg shadow-lg p-6 space-y-4">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Cancel Session</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Are you sure you want to cancel this session? This action cannot be undone.</p>

            <div>
                <label for="cancel_reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Reason for cancellation</label>
                <textarea id="cancel_reason" name="cancel_reason" rows="3" class="w-full mt-1 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:ring-primary-500 focus:border-primary-500"></textarea>
            </div>

            <div class="flex justify-end space-x-3 pt-4">
                <button type="button" onclick="hideCancelSessionModal()" class="px-4 py-2 rounded-md bg-gray-300 text-gray-900 hover:bg-gray-400 dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600">
                    Keep Session
                </button>
                <button type="button" onclick="cancelSession()" class="px-4 py-2 rounded-md bg-red-600 text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                    Cancel Session
                </button>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        // Modal functions
        function showSessionModal() {
            document.getElementById('sessionModal').classList.remove('hidden');
        }

        function hideSessionModal() {
            document.getElementById('sessionModal').classList.add('hidden');
        }

        function showCancelSessionModal() {
            document.getElementById('cancelSessionModal').classList.remove('hidden');
        }

        function hideCancelSessionModal() {
            document.getElementById('cancelSessionModal').classList.add('hidden');
        }

        // Form handling
        document.getElementById('sessionForm').addEventListener('submit', function(e) {
            e.preventDefault();
            // Handle form submission
            hideSessionModal();
            // Show success message
            alert('Session scheduled successfully!');
        });

        // Search functionality
        document.getElementById('search').addEventListener('input', function(e) {
            // Implement search logic
            console.log('Searching:', e.target.value);
        });

        // Filter handling
        document.getElementById('status').addEventListener('change', function(e) {
            // Implement status filter
            console.log('Status filter:', e.target.value);
        });

        document.getElementById('date-range').addEventListener('change', function(e) {
            // Implement date range filter
            console.log('Date range filter:', e.target.value);
        });
    </script>
@endsection
