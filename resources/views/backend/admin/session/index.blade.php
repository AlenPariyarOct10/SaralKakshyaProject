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
    <!-- Include FullCalendar CSS -->
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css' rel='stylesheet' />

    <!-- Main Content Area -->
    <main class="scrollable-content p-4 md:p-6">
        <!-- Session Management Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Session Management</h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Manage all sessions for {{$institute->name}}</p>
            </div>
            <div class="mt-4 md:mt-0 flex flex-wrap gap-3">
                <button onclick="toggleView()" class="btn-secondary" id="viewToggleBtn">
                    <i class="fas fa-table mr-2"></i> Table View
                </button>
                <button onclick="addAllSaturdaysAsHoliday()" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md transition-colors">
                    <i class="fas fa-calendar-times mr-2"></i> Add All Saturdays as Holiday
                </button>
                <button onclick="showSessionModal()" class="btn-primary">
                    <i class="fas fa-plus mr-2"></i> Schedule New Sessions
                </button>
            </div>
        </div>

        <!-- Color Legend -->
        <div class="card mb-6">
            <div class="p-4">
                <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Session Types</h3>
                <div class="flex flex-wrap gap-4">
                    <div class="flex items-center">
                        <div class="w-4 h-4 rounded mr-2" style="background-color: #3b82f6;"></div>
                        <span class="text-sm text-gray-600 dark:text-gray-400">Class</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 rounded mr-2" style="background-color: #ef4444;"></div>
                        <span class="text-sm text-gray-600 dark:text-gray-400">Holiday</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 rounded mr-2" style="background-color: #f59e0b;"></div>
                        <span class="text-sm text-gray-600 dark:text-gray-400">Exam</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 rounded mr-2" style="background-color: #10b981;"></div>
                        <span class="text-sm text-gray-600 dark:text-gray-400">Event</span>
                    </div>
                </div>
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
                    <label for="statusFilter" class="sr-only">Status</label>
                    <select id="statusFilter" class="block w-full px-3 py-2 border border-gray-300 rounded-md leading-5 bg-white focus:outline-none focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                        <option value="">All Types</option>
                        <option value="class">Class</option>
                        <option value="holiday">Holiday</option>
                        <option value="exam">Exam</option>
                        <option value="event">Event</option>
                    </select>
                </div>
                <div class="w-full md:w-48">
                    <label for="date-range" class="sr-only">Date Range</label>
                    <select id="date-range" class="block w-full px-3 py-2 border border-gray-300 rounded-md leading-5 bg-white focus:outline-none focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                        <option value="month">Month View</option>
                        <option value="week">Week View</option>
                        <option value="day">Day View</option>
                        <option value="list">List View</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Main Calendar View -->
        <div class="card" id="calendarView">
            <div class="p-4">
                <div id="mainCalendar" class="bg-white dark:bg-gray-800 rounded-lg"></div>
            </div>
        </div>

        <!-- Table View (Hidden by default) -->
        <div class="card hidden" id="tableView">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date & Time</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
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
                                    {{ date('h:i A', strtotime($session->start_time)) }} - {{ date('h:i A', strtotime($session->end_time)) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 rounded mr-2" style="background-color: {{
                                        $session->status === 'class' ? '#3b82f6' :
                                        ($session->status === 'holiday' ? '#ef4444' :
                                        ($session->status === 'exam' ? '#f59e0b' :
                                        ($session->status === 'event' ? '#10b981' : '#6b7280')))
                                    }};"></div>
                                    <span class="text-sm text-gray-900 dark:text-white">
                                        {{ ucfirst($session->status ?? 'class') }}
                                    </span>
                                </div>
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
                                    <button onclick="confirmDeleteSession({{ $session->id }})" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                                No sessions found. <button onclick="showSessionModal()" class="text-primary-600 hover:text-primary-900 dark:text-primary-400 dark:hover:text-primary-300">Schedule one</button>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Schedule Session Modal -->
    <div id="sessionModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="w-full max-w-6xl mx-4 bg-white dark:bg-gray-900 rounded-lg shadow-lg p-6 space-y-6 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Schedule New Sessions</h2>
                <button onclick="hideSessionModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Calendar Section -->
                <div class="lg:col-span-2">
                    <div class="mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Select Dates</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Click on dates to select multiple days for your sessions</p>
                    </div>
                    <div id="schedulingCalendar" class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700"></div>
                </div>

                <!-- Session Details Section -->
                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Session Details</h3>

                        <!-- Selected Dates Display -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Selected Dates</label>
                            <div id="selectedDates" class="min-h-[60px] p-3 border border-gray-300 dark:border-gray-600 rounded-md bg-gray-50 dark:bg-gray-800">
                                <p class="text-sm text-gray-500 dark:text-gray-400">No dates selected</p>
                            </div>
                        </div>

                        <!-- Time Settings -->
                        <div class="grid grid-cols-2 gap-3 mb-4">
                            <div>
                                <label for="startTime" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Start Time</label>
                                <input type="time" id="startTime" value="09:00" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-md focus:ring-primary-500 focus:border-primary-500">
                            </div>
                            <div>
                                <label for="endTime" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">End Time</label>
                                <input type="time" id="endTime" value="10:00" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-md focus:ring-primary-500 focus:border-primary-500">
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="mb-4">
                            <label for="sessionStatus" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                            <select id="sessionStatus" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-md focus:ring-primary-500 focus:border-primary-500">
                                <option value="class">Class</option>
                                <option value="holiday">Holiday</option>
                                <option value="exam">Exam</option>
                                <option value="event">Event</option>
                            </select>
                        </div>

                        <!-- Notes -->
                        <div class="mb-6">
                            <label for="sessionNotes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Notes</label>
                            <textarea id="sessionNotes" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-md focus:ring-primary-500 focus:border-primary-500" placeholder="Add any additional notes..."></textarea>
                        </div>

                        <!-- Action Buttons -->
                        <div class="space-y-3">
                            <button onclick="saveSessions()" class="w-full px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 disabled:opacity-50 disabled:cursor-not-allowed" id="saveButton" disabled>
                                <i class="fas fa-save mr-2"></i>Save Sessions
                            </button>
                            <button onclick="clearSelection()" class="w-full px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600">
                                <i class="fas fa-eraser mr-2"></i>Clear Selection
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Session Modal -->
    <div id="editSessionModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="w-full max-w-lg mx-4 bg-white dark:bg-gray-900 rounded-lg shadow-lg p-6 space-y-4">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Edit Session</h2>
                <button onclick="hideEditSessionModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <form id="editSessionForm" class="space-y-4">
                <div>
                    <label for="editDate" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date</label>
                    <input type="date" id="editDate" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-md focus:ring-primary-500 focus:border-primary-500">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="editStartTime" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Start Time</label>
                        <input type="time" id="editStartTime" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-md focus:ring-primary-500 focus:border-primary-500">
                    </div>
                    <div>
                        <label for="editEndTime" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">End Time</label>
                        <input type="time" id="editEndTime" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-md focus:ring-primary-500 focus:border-primary-500">
                    </div>
                </div>

                <div>
                    <label for="editStatus" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                    <select id="editStatus" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-md focus:ring-primary-500 focus:border-primary-500">
                        <option value="class">Class</option>
                        <option value="holiday">Holiday</option>
                        <option value="exam">Exam</option>
                        <option value="event">Event</option>
                    </select>
                </div>

                <div>
                    <label for="editNotes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Notes</label>
                    <textarea id="editNotes" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-md focus:ring-primary-500 focus:border-primary-500"></textarea>
                </div>

                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" onclick="hideEditSessionModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                        <i class="fas fa-save mr-2"></i>Update Session
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Session Details Modal (View Only) -->
    <div id="sessionDetailsModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="w-full max-w-lg mx-4 bg-white dark:bg-gray-900 rounded-lg shadow-lg p-6 space-y-4">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Session Details</h2>
                <button onclick="hideSessionDetailsModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <div id="sessionDetailsContent">
                <!-- Session details will be populated here -->
            </div>

            <div class="flex justify-end space-x-3 pt-4">
                <button onclick="editCurrentSession()" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                    <i class="fas fa-edit mr-2"></i>Edit
                </button>
                <button onclick="confirmDeleteSession(currentSessionId)" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:ring-2 focus:ring-red-500 focus:ring-opacity-50 transition-colors">
                    <i class="fas fa-trash mr-2"></i>Delete
                </button>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteSessionModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="w-full max-w-lg mx-4 bg-white dark:bg-gray-900 rounded-lg shadow-lg p-6 space-y-4">
            <div class="flex items-center space-x-3">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 rounded-full bg-red-100 dark:bg-red-900 flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400"></i>
                    </div>
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Delete Session</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Are you sure you want to delete this session? This action cannot be undone.</p>
                </div>
            </div>

            <div id="deleteSessionDetails" class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg border-l-4 border-red-500">
                <!-- Session details will be populated here -->
            </div>

            <div class="flex justify-end space-x-3 pt-4">
                <button type="button" onclick="hideDeleteSessionModal()" class="px-4 py-2 rounded-md bg-gray-300 text-gray-900 hover:bg-gray-400 dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600 transition-colors">
                    Cancel
                </button>
                <button type="button" onclick="deleteSession()" class="px-4 py-2 rounded-md bg-red-600 text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 transition-colors">
                    <i class="fas fa-trash mr-2"></i>Delete Session
                </button>
            </div>
        </div>
    </div>



    <!-- Add Saturdays as Holiday Modal -->
    <div id="saturdayHolidayModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="w-full max-w-lg mx-4 bg-white dark:bg-gray-900 rounded-lg shadow-lg p-6 space-y-4 max-h-[90vh] overflow-y-auto">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Add All Saturdays as Holiday</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Select the date range to mark all Saturdays as holidays.</p>

            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="saturdayStartDate" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Start Date</label>
                        <input type="date" id="saturdayStartDate" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-md focus:ring-primary-500 focus:border-primary-500">
                    </div>
                    <div>
                        <label for="saturdayEndDate" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">End Date</label>
                        <input type="date" id="saturdayEndDate" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-md focus:ring-primary-500 focus:border-primary-500">
                    </div>
                </div>

                <div>
                    <label for="saturdayNotes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Notes</label>
                    <input type="text" id="saturdayNotes" value="Saturday Holiday" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-md focus:ring-primary-500 focus:border-primary-500">
                </div>

                <div id="saturdayPreview" class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg hidden max-h-40 overflow-y-auto">
                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Preview:</h4>
                    <div id="saturdayList" class="text-sm text-gray-600 dark:text-gray-400"></div>
                </div>
            </div>

            <div class="flex justify-end space-x-3 pt-4">
                <button type="button" onclick="hideSaturdayHolidayModal()" class="px-4 py-2 rounded-md bg-gray-300 text-gray-900 hover:bg-gray-400 dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600">
                    Cancel
                </button>
                <button type="button" onclick="previewSaturdays()" class="px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700">
                    <i class="fas fa-eye mr-2"></i>Preview
                </button>
                <button type="button" onclick="addSaturdaysAsHoliday()" class="px-4 py-2 rounded-md bg-red-600 text-white hover:bg-red-700" id="addSaturdaysBtn" disabled>
                    <i class="fas fa-calendar-times mr-2"></i>Add Holidays
                </button>
            </div>
        </div>
    </div>


@endsection

@section('scripts')
    <!-- Include FullCalendar JS -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>

    <script>
        let mainCalendar;
        let schedulingCalendar;
        let selectedDates = [];
        let currentView = 'calendar';
        let currentSessionId = null;
        let editingSessionId = null;

        // Sample session data - replace with your actual data from Laravel
        const sessionsData = [
                @foreach($sessions as $session)
            {
                id: '{{ $session->id }}',
                title: '{{ ucfirst($session->status ?? "Session") }}',
                start: '{{ \Carbon\Carbon::parse($session->date)->format("Y-m-d") }}T{{ $session->start_time }}',
                end: '{{ \Carbon\Carbon::parse($session->date)->format("Y-m-d") }}T{{ $session->end_time }}',
                backgroundColor: getEventColor('{{ $session->status ?? "class" }}'),
                borderColor: getEventColor('{{ $session->status ?? "class" }}'),
                extendedProps: {
                    status: '{{ $session->status ?? "class" }}',
                    notes: {!! json_encode($session->notes ?? "") !!}
                }
            }@if(!$loop->last),@endif
            @endforeach
        ];

        console.log(sessionsData);

        function getEventColor(status) {
            switch( (status.toLowerCase()) ) {
                case 'class': return '#3b82f6';      // Blue
                case 'holiday': return '#ef4444';    // Red
                case 'exam': return '#f59e0b';       // Amber
                case 'event': return '#10b981';      // Green
                default: return '#6b7280';           // Gray
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            initializeMainCalendar();
            initializeSchedulingCalendar();
            setDefaultDates();
        });

        function setDefaultDates() {
            const today = new Date();
            const startOfYear = new Date(today.getFullYear(), 0, 1);
            const endOfYear = new Date(today.getFullYear(), 11, 31);

            document.getElementById('saturdayStartDate').value = startOfYear.toISOString().split('T')[0];
            document.getElementById('saturdayEndDate').value = endOfYear.toISOString().split('T')[0];
        }

        function initializeMainCalendar() {
            const calendarEl = document.getElementById('mainCalendar');

            mainCalendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                height: 'auto',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                },
                events: sessionsData,
                eventClick: function(info) {
                    showSessionDetails(info.event);
                },
                eventMouseEnter: function(info) {
                    info.el.style.opacity = '0.8';
                },
                eventMouseLeave: function(info) {
                    info.el.style.opacity = '1';
                },
                dayMaxEvents: 3,
                moreLinkClick: 'popover',
                eventDisplay: 'block'
            });

            mainCalendar.render();
            addCalendarStyles();
        }

        function initializeSchedulingCalendar() {
            const calendarEl = document.getElementById('schedulingCalendar');

            schedulingCalendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                height: 'auto',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,listWeek'
                },
                selectable: true,
                selectMirror: true,
                dayMaxEvents: true,
                weekends: true,

                dateClick: function(info) {
                    const dateStr = info.dateStr;

                    if (selectedDates.includes(dateStr)) {
                        selectedDates = selectedDates.filter(date => date !== dateStr);
                        info.dayEl.classList.remove('selected-date');
                    } else {
                        selectedDates.push(dateStr);
                        info.dayEl.classList.add('selected-date');
                    }

                    updateSelectedDatesDisplay();
                    updateSaveButton();
                }
            });

            schedulingCalendar.render();
        }

        function addCalendarStyles() {
            const style = document.createElement('style');
            style.textContent = `
                .selected-date {
                    background-color: #3b82f6 !important;
                    color: white !important;
                }
                .fc-day-today {
                    background-color: #fef3c7 !important;
                }

                .dark .fc-day-today {
                    background-color: #451a03 !important;
                }
                .fc-theme-standard td, .fc-theme-standard th {
                    border-color: #e5e7eb;
                }
                .dark .fc-theme-standard td, .dark .fc-theme-standard th {
                    border-color: #374151;
                }
                .dark .fc-daygrid-day-number {
                    color: #d1d5db;
                }
                .dark .fc-col-header-cell-cushion {
                    color: #d1d5db;
                }
                .dark .fc-toolbar-title {
                    color: #f9fafb;
                }
                .dark .fc-button {
                    background-color: #374151;
                    border-color: #4b5563;
                    color: #f9fafb;
                }
                .dark .fc-button:hover {
                    background-color: #4b5563;
                }
                .fc-event {
                    cursor: pointer;
                    transition: opacity 0.2s;
                }
                .fc-event:hover {
                    opacity: 0.8 !important;
                }
            `;
            document.head.appendChild(style);
        }

        // Saturday Holiday Functions
        function addAllSaturdaysAsHoliday() {
            document.getElementById('saturdayHolidayModal').classList.remove('hidden');
        }

        function hideSaturdayHolidayModal() {
            document.getElementById('saturdayHolidayModal').classList.add('hidden');
            document.getElementById('saturdayPreview').classList.add('hidden');
            document.getElementById('addSaturdaysBtn').disabled = true;
        }

        function previewSaturdays() {
            const startDate = new Date(document.getElementById('saturdayStartDate').value);
            const endDate = new Date(document.getElementById('saturdayEndDate').value);

            if (!startDate || !endDate || startDate > endDate) {
                alert('Please select valid start and end dates.');
                return;
            }

            const saturdays = [];
            const current = new Date(startDate);

            while (current <= endDate) {
                if (current.getDay() === 6) { // Saturday
                    saturdays.push(new Date(current));
                }
                current.setDate(current.getDate() + 1);
            }

            const saturdayList = document.getElementById('saturdayList');
            saturdayList.innerHTML = saturdays.map(date =>
                `<div class="mb-1">${date.toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })}</div>`
            ).join('');

            document.getElementById('saturdayPreview').classList.remove('hidden');
            document.getElementById('addSaturdaysBtn').disabled = saturdays.length === 0;
        }

        function addSaturdaysAsHoliday() {
            const startDate = new Date(document.getElementById('saturdayStartDate').value);
            const endDate = new Date(document.getElementById('saturdayEndDate').value);
            const notes = document.getElementById('saturdayNotes').value;

            const saturdays = [];
            const current = new Date(startDate);

            while (current <= endDate) {
                if (current.getDay() === 6) {
                    saturdays.push(current.toISOString().split('T')[0]);
                }
                current.setDate(current.getDate() + 1);
            }

            const holidayData = {
                dates: saturdays,
                status: 'holiday',
                start_time: '00:00',
                end_time: '23:59',
                notes: notes,
                _token: '{{ csrf_token() }}'
            };

            // API call to create Saturday holidays
            fetch('/admin/sessions/bulk-create', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(holidayData)
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(`Successfully added ${saturdays.length} Saturday holidays!`);
                        hideSaturdayHolidayModal();
                        location.reload();
                    } else {
                        alert('Error creating Saturday holidays: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while creating Saturday holidays.');
                });
        }

        // Session Details Functions (View Only)
        function showSessionDetails(event) {
            currentSessionId = event.id;
            const props = event.extendedProps;

            const content = `
                <div class="space-y-4">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">${event.title}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">${event.start.toLocaleDateString()} at ${event.start.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" style="background-color: ${event.backgroundColor}20; color: ${event.backgroundColor};">
                                ${props.status.charAt(0).toUpperCase() + props.status.slice(1)}
                            </span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Duration</label>
                            <p class="text-sm text-gray-900 dark:text-white">${event.start.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})} - ${event.end.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</p>
                        </div>
                    </div>

                    ${props.notes ? `
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Notes</label>
                        <p class="text-sm text-gray-900 dark:text-white">${props.notes}</p>
                    </div>
                    ` : ''}
                </div>
            `;

            document.getElementById('sessionDetailsContent').innerHTML = content;
            document.getElementById('sessionDetailsModal').classList.remove('hidden');
        }

        function hideSessionDetailsModal() {
            document.getElementById('sessionDetailsModal').classList.add('hidden');
        }

        function editCurrentSession() {
            if (currentSessionId) {
                editSession(currentSessionId);
                hideSessionDetailsModal();
            }
        }

        // Edit Session Functions
        function editSession(id) {
            editingSessionId = id;
            const event = mainCalendar.getEventById(id);

            if (event) {
                const props = event.extendedProps;

                document.getElementById('editDate').value = event.start.toISOString().split('T')[0];
                document.getElementById('editStatus').value = props.status;
                document.getElementById('editStartTime').value = event.start.toTimeString().slice(0, 5);
                document.getElementById('editEndTime').value = event.end.toTimeString().slice(0, 5);
                document.getElementById('editNotes').value = props.notes || '';

                document.getElementById('editSessionModal').classList.remove('hidden');
            }
        }

        function hideEditSessionModal() {
            document.getElementById('editSessionModal').classList.add('hidden');
            editingSessionId = null;
        }

        document.getElementById('editSessionForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = {
                id: editingSessionId,
                date: document.getElementById('editDate').value,
                status: document.getElementById('editStatus').value,
                start_time: document.getElementById('editStartTime').value,
                end_time: document.getElementById('editEndTime').value,
                notes: document.getElementById('editNotes').value,
                _token: '{{ csrf_token() }}'
            };

            // API call to update session
            fetch(`/admin/sessions/${editingSessionId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(formData)
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Session updated successfully!');
                        hideEditSessionModal();
                        location.reload();
                    } else {
                        alert('Error updating session: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while updating the session.');
                });
        });

        // Delete Functions
        function confirmDeleteSession(id) {
            currentSessionId = id;
            const event = mainCalendar.getEventById(id);

            console.log("deleting"+id);

            if (event) {
                const props = event.extendedProps;
                const details = `
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="font-medium text-gray-700 dark:text-gray-300">Date:</span>
                            <span class="text-gray-900 dark:text-white">${event.start.toLocaleDateString()}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium text-gray-700 dark:text-gray-300">Time:</span>
                            <span class="text-gray-900 dark:text-white">${event.start.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})} - ${event.end.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium text-gray-700 dark:text-gray-300">Status:</span>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium" style="background-color: ${event.backgroundColor}20; color: ${event.backgroundColor};">
                                ${props.status.charAt(0).toUpperCase() + props.status.slice(1)}
                            </span>
                        </div>
                        ${props.notes ? `
                        <div class="flex justify-between">
                            <span class="font-medium text-gray-700 dark:text-gray-300">Notes:</span>
                            <span class="text-gray-900 dark:text-white text-right max-w-xs truncate">${props.notes}</span>
                        </div>
                        ` : ''}
                    </div>
                `;

                document.getElementById('deleteSessionDetails').innerHTML = details;
                document.getElementById('deleteSessionModal').classList.remove('hidden');

                // Hide details modal if it's open
                if (document.getElementById('sessionDetailsModal').classList.contains('hidden') === false) {
                    hideSessionDetailsModal();
                }
            }
        }

        function hideDeleteSessionModal() {
            document.getElementById('deleteSessionModal').classList.add('hidden');
        }

        function deleteSession() {
            console.log("h", currentSessionId);
            if (!currentSessionId) return;

            // Show loading state
            const deleteBtn = document.querySelector('#deleteSessionModal button[onclick="deleteSession()"]');
            const originalText = deleteBtn.innerHTML;
            deleteBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Deleting...';
            deleteBtn.disabled = true;

            // API call to delete session
            fetch(`/admin/sessions/${currentSessionId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    _token: '{{ csrf_token() }}'
                })
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.status === "success") {
                        Swal.fire({
                            toast: true,
                            position: 'bottom-end',
                            icon: 'success',
                            title: 'Session deleted successfully!',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            background: '#f8f9fa',
                            iconColor: '#28a745',
                            color: '#343a40'
                        });
                        hideDeleteSessionModal();
                        location.reload();
                    } else {
                        throw new Error(data.message || 'Unknown error occurred');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        toast: true,
                        position: 'bottom-end',
                        icon: 'error',
                        title: 'Error deleting session',
                        text: error.message,
                        showConfirmButton: false,
                        timer: 5000,
                        timerProgressBar: true,
                        background: '#f8f9fa',
                        iconColor: '#dc3545',
                        color: '#343a40'
                    });

                    // Reset button state if needed
                    const deleteBtn = document.querySelector('#deleteSessionModal button[onclick="deleteSession()"]');
                    if (deleteBtn) {
                        deleteBtn.innerHTML = '<i class="fas fa-trash mr-2"></i>Delete Session';
                        deleteBtn.disabled = false;
                    }
                });
        }

        // Toggle View Function
        function toggleView() {
            const calendarView = document.getElementById('calendarView');
            const tableView = document.getElementById('tableView');
            const toggleBtn = document.getElementById('viewToggleBtn');

            if (currentView === 'calendar') {
                calendarView.classList.add('hidden');
                tableView.classList.remove('hidden');
                toggleBtn.innerHTML = '<i class="fas fa-calendar mr-2"></i> Calendar View';
                currentView = 'table';
            } else {
                calendarView.classList.remove('hidden');
                tableView.classList.add('hidden');
                toggleBtn.innerHTML = '<i class="fas fa-table mr-2"></i> Table View';
                currentView = 'calendar';
                setTimeout(() => mainCalendar.render(), 100);
            }
        }

        // Selected Dates Functions
        function updateSelectedDatesDisplay() {
            const container = document.getElementById('selectedDates');

            if (selectedDates.length === 0) {
                container.innerHTML = '<p class="text-sm text-gray-500 dark:text-gray-400">No dates selected</p>';
            } else {
                const sortedDates = selectedDates.sort();
                const dateElements = sortedDates.map(date => {
                    const formattedDate = new Date(date).toLocaleDateString('en-US', {
                        weekday: 'short',
                        month: 'short',
                        day: 'numeric'
                    });
                    return `
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100 mr-2 mb-2">
                            ${formattedDate}
                            <button onclick="removeDate('${date}')" class="ml-1 text-blue-600 hover:text-blue-800 dark:text-blue-300 dark:hover:text-blue-100">
                                <i class="fas fa-times text-xs"></i>
                            </button>
                        </span>
                    `;
                }).join('');

                container.innerHTML = `
                    <div class="flex flex-wrap">
                        ${dateElements}
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">${selectedDates.length} date(s) selected</p>
                `;
            }
        }

        function removeDate(dateStr) {
            selectedDates = selectedDates.filter(date => date !== dateStr);

            const dayEl = schedulingCalendar.el.querySelector(`[data-date="${dateStr}"]`);
            if (dayEl) {
                dayEl.classList.remove('selected-date');
            }

            updateSelectedDatesDisplay();
            updateSaveButton();
        }

        function updateSaveButton() {
            const saveButton = document.getElementById('saveButton');
            saveButton.disabled = selectedDates.length === 0;
        }

        function clearSelection() {
            selectedDates = [];

            const selectedElements = schedulingCalendar.el.querySelectorAll('.selected-date');
            selectedElements.forEach(el => el.classList.remove('selected-date'));

            updateSelectedDatesDisplay();
            updateSaveButton();
        }

        // Session Creation Function
        function saveSessions() {
            if (selectedDates.length === 0) {
                alert('Please select at least one date.');
                return;
            }

            const startTime = document.getElementById('startTime').value;
            const endTime = document.getElementById('endTime').value;
            const status = document.getElementById('sessionStatus').value;
            const notes = document.getElementById('sessionNotes').value;

            if (startTime >= endTime) {
                alert('End time must be after start time.');
                return;
            }

            const sessionsData = {
                dates: selectedDates,
                start_time: startTime,
                end_time: endTime,
                status: status,
                notes: notes,
                _token: '{{ csrf_token() }}'
            };

            fetch('/admin/sessions/bulk-create', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(sessionsData)
            })
                .then(response => response.json())
                .then(data => {
                    if (data) {
                        alert('Sessions created successfully!');
                        hideSessionModal();
                        location.reload();
                    } else {
                        alert('Error creating sessions: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while saving sessions.');
                });
        }

        // Modal Control Functions
        function showSessionModal() {
            document.getElementById('sessionModal').classList.remove('hidden');
            if (schedulingCalendar) {
                setTimeout(() => schedulingCalendar.render(), 100);
            }
        }

        function hideSessionModal() {
            document.getElementById('sessionModal').classList.add('hidden');
            clearSelection();
            document.getElementById('startTime').value = '09:00';
            document.getElementById('endTime').value = '10:00';
            document.getElementById('sessionStatus').value = 'class';
            document.getElementById('sessionNotes').value = '';
        }

        function viewSession(id) {
            const event = mainCalendar.getEventById(id);
            if (event) {
                showSessionDetails(event);
            }
        }

        // Filter handling
        document.getElementById('statusFilter').addEventListener('change', function(e) {
            const filterValue = e.target.value;

            if (filterValue === '') {
                mainCalendar.removeAllEventSources();
                mainCalendar.addEventSource(sessionsData);
            } else {
                const filteredEvents = sessionsData.filter(event => event.extendedProps.status === filterValue);
                mainCalendar.removeAllEventSources();
                mainCalendar.addEventSource(filteredEvents);
            }
        });

        document.getElementById('date-range').addEventListener('change', function(e) {
            const view = e.target.value;
            switch(view) {
                case 'month':
                    mainCalendar.changeView('dayGridMonth');
                    break;
                case 'week':
                    mainCalendar.changeView('timeGridWeek');
                    break;
                case 'day':
                    mainCalendar.changeView('timeGridDay');
                    break;
                case 'list':
                    mainCalendar.changeView('listWeek');
                    break;
            }
        });

        document.getElementById('search').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();

            if (searchTerm === '') {
                mainCalendar.removeAllEventSources();
                mainCalendar.addEventSource(sessionsData);
            } else {
                const filteredEvents = sessionsData.filter(event =>
                    event.title.toLowerCase().includes(searchTerm) ||
                    event.extendedProps.notes.toLowerCase().includes(searchTerm)
                );
                mainCalendar.removeAllEventSources();
                mainCalendar.addEventSource(filteredEvents);
            }
        });
    </script>
@endsection
