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
            <div class="mt-4 md:mt-0 flex flex-wrap gap-3">
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
                        <span class="text-sm text-gray-600 dark:text-gray-400">📚 Class</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 rounded mr-2" style="background-color: #ef4444;"></div>
                        <span class="text-sm text-gray-600 dark:text-gray-400">🏖️ Holiday</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 rounded mr-2" style="background-color: #f59e0b;"></div>
                        <span class="text-sm text-gray-600 dark:text-gray-400">📝 Exam</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 rounded mr-2" style="background-color: #10b981;"></div>
                        <span class="text-sm text-gray-600 dark:text-gray-400">🎉 Event</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters and Search -->
        <div class="card mb-6">
            <div class="p-4">
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
                        <label for="monthFilter" class="sr-only">Month Filter</label>
                        <select id="monthFilter" class="block w-full px-3 py-2 border border-gray-300 rounded-md leading-5 bg-white focus:outline-none focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                            <option value="">All Months</option>
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ date('n') == $i ? 'selected' : '' }}>
                                    {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="w-full md:w-48">
                        <label for="yearFilter" class="sr-only">Year Filter</label>
                        <select id="yearFilter" class="block w-full px-3 py-2 border border-gray-300 rounded-md leading-5 bg-white focus:outline-none focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                            <option value="">All Years</option>
                            @for($year = date('Y') - 1; $year <= date('Y') + 2; $year++)
                                <option value="{{ $year }}" {{ date('Y') == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endfor
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sessions Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                                <span class="text-white text-sm">📚</span>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Classes</dt>
                                <dd class="text-lg font-medium text-gray-900 dark:text-white" id="classCount">
                                    {{ $sessions->where('status', 'class')->count() }}
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-red-500 rounded-md flex items-center justify-center">
                                <span class="text-white text-sm">🏖️</span>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Holidays</dt>
                                <dd class="text-lg font-medium text-gray-900 dark:text-white" id="holidayCount">
                                    {{ $sessions->where('status', 'holiday')->count() }}
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                                <span class="text-white text-sm">📝</span>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Exams</dt>
                                <dd class="text-lg font-medium text-gray-900 dark:text-white" id="examCount">
                                    {{ $sessions->where('status', 'exam')->count() }}
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                                <span class="text-white text-sm">🎉</span>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Events</dt>
                                <dd class="text-lg font-medium text-gray-900 dark:text-white" id="eventCount">
                                    {{ $sessions->where('status', 'event')->count() }}
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sessions Table -->
        <div class="card">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">All Sessions</h3>
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        Total: <span id="totalCount">{{ $sessions->count() }}</span> sessions
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            <button onclick="sortTable('date')" class="flex items-center hover:text-gray-700 dark:hover:text-gray-100">
                                Date & Time
                                <i class="fas fa-sort ml-1"></i>
                            </button>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            <button onclick="sortTable('status')" class="flex items-center hover:text-gray-700 dark:hover:text-gray-100">
                                Status
                                <i class="fas fa-sort ml-1"></i>
                            </button>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Notes</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700" id="sessionsTableBody">
                    @forelse($sessions->sortBy('date') as $session)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors session-row"
                            data-status="{{ $session->status ?? 'class' }}"
                            data-date="{{ $session->date }}"
                            data-month="{{ date('n', strtotime($session->date)) }}"
                            data-year="{{ date('Y', strtotime($session->date)) }}"
                            data-search="{{ strtolower(($session->notes ?? '') . ' ' . ($session->status ?? 'class')) }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ date('M d, Y', strtotime($session->date)) }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ date('l', strtotime($session->date)) }} •
                                            {{ date('h:i A', strtotime($session->start_time)) }} - {{ date('h:i A', strtotime($session->end_time)) }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 rounded-full mr-3" style="background-color: {{
                                        $session->status === 'class' ? '#3b82f6' :
                                        ($session->status === 'holiday' ? '#ef4444' :
                                        ($session->status === 'exam' ? '#f59e0b' :
                                        ($session->status === 'event' ? '#10b981' : '#6b7280')))
                                    }};"></div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" style="
                                        background-color: {{
                                            $session->status === 'class' ? '#dbeafe' :
                                            ($session->status === 'holiday' ? '#fee2e2' :
                                            ($session->status === 'exam' ? '#fef3c7' :
                                            ($session->status === 'event' ? '#d1fae5' : '#f3f4f6')))
                                        }};
                                        color: {{
                                            $session->status === 'class' ? '#1e40af' :
                                            ($session->status === 'holiday' ? '#dc2626' :
                                            ($session->status === 'exam' ? '#d97706' :
                                            ($session->status === 'event' ? '#059669' : '#374151')))
                                        }};
                                    ">
                                        @switch($session->status ?? 'class')
                                            @case('class') 📚 Class @break
                                            @case('holiday') 🏖️ Holiday @break
                                            @case('exam') 📝 Exam @break
                                            @case('event') 🎉 Event @break
                                            @default 📅 Session
                                        @endswitch
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 dark:text-white">
                                    @if($session->notes)
                                        <div class="max-w-xs truncate" title="{{ $session->notes }}">
                                            {{ $session->notes }}
                                        </div>
                                    @else
                                        <span class="text-gray-400 italic">No notes</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <button onclick="viewSession({{ $session->id }})" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 p-1 rounded" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button onclick="editSession({{ $session->id }})" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 p-1 rounded" title="Edit Session">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button onclick="confirmDeleteSession({{ $session->id }})" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 p-1 rounded" title="Delete Session">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr id="noSessionsRow">
                            <td colspan="4" class="px-6 py-12 text-center">
                                <div class="text-gray-500 dark:text-gray-400">
                                    <i class="fas fa-calendar-alt text-4xl mb-4"></i>
                                    <p class="text-lg font-medium mb-2">No sessions found</p>
                                    <p class="text-sm mb-4">Get started by scheduling your first session</p>
                                    <button onclick="showSessionModal()" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                        <i class="fas fa-plus mr-2"></i>
                                        Schedule Session
                                    </button>
                                </div>
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
        <div class="w-full max-w-4xl mx-4 bg-white dark:bg-gray-900 rounded-lg shadow-lg p-6 space-y-6 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Schedule New Sessions</h2>
                <button onclick="hideSessionModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Date Selection Section -->
                <div>
                    <div class="mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Select Date Range</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Choose start and end dates for your sessions</p>
                    </div>

                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="startDate" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Start Date</label>
                                <input type="date" id="startDate" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-md focus:ring-primary-500 focus:border-primary-500">
                            </div>
                            <div>
                                <label for="endDate" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">End Date</label>
                                <input type="date" id="endDate" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-md focus:ring-primary-500 focus:border-primary-500">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Days of Week</label>
                            <div class="grid grid-cols-4 gap-2">
                                <label class="flex items-center">
                                    <input type="checkbox" value="1" class="dayCheckbox mr-2"> Mon
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" value="2" class="dayCheckbox mr-2"> Tue
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" value="3" class="dayCheckbox mr-2"> Wed
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" value="4" class="dayCheckbox mr-2"> Thu
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" value="5" class="dayCheckbox mr-2"> Fri
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" value="6" class="dayCheckbox mr-2"> Sat
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" value="0" class="dayCheckbox mr-2"> Sun
                                </label>
                            </div>
                        </div>

                        <div id="selectedDatesPreview" class="p-3 bg-gray-50 dark:bg-gray-800 rounded-md min-h-[60px]">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Select dates to see preview</p>
                        </div>
                    </div>
                </div>

                <!-- Session Details Section -->
                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Session Details</h3>

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
                                <option value="class">📚 Class</option>
                                <option value="holiday">🏖️ Holiday</option>
                                <option value="exam">📝 Exam</option>
                                <option value="event">🎉 Event</option>
                            </select>
                        </div>

                        <!-- Notes -->
                        <div class="mb-6">
                            <label for="sessionNotes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Notes</label>
                            <textarea id="sessionNotes" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-md focus:ring-primary-500 focus:border-primary-500" placeholder="Add any additional notes..."></textarea>
                        </div>

                        <!-- Action Buttons -->
                        <div class="space-y-3">
                            <button onclick="previewSessions()" class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <i class="fas fa-eye mr-2"></i>Preview Sessions
                            </button>
                            <button onclick="saveSessions()" class="w-full px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 disabled:opacity-50 disabled:cursor-not-allowed" id="saveButton" disabled>
                                <i class="fas fa-save mr-2"></i>Save Sessions
                            </button>
                            <button onclick="clearSessionForm()" class="w-full px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600">
                                <i class="fas fa-eraser mr-2"></i>Clear Form
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
                        <option value="class">📚 Class</option>
                        <option value="holiday">🏖️ Holiday</option>
                        <option value="exam">📝 Exam</option>
                        <option value="event">🎉 Event</option>
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
    <script>
        let currentSessionId = null;
        let editingSessionId = null;
        let selectedDates = [];
        let sortDirection = 'asc';
        let sortColumn = 'date';

        // Sessions data from Laravel
        const sessionsData = [
                @foreach($sessions as $session)
            {
                id: {{ $session->id }},
                date: '{{ $session->date }}',
                start_time: '{{ $session->start_time }}',
                end_time: '{{ $session->end_time }}',
                status: '{{ $session->status ?? "class" }}',
                notes: {!! json_encode($session->notes ?? '') !!}
            }@if(!$loop->last),@endif
            @endforeach
        ];

        document.addEventListener('DOMContentLoaded', function() {
            setDefaultDates();
            initializeFilters();
        });

        function setDefaultDates() {
            const today = new Date();
            const startOfYear = new Date(today.getFullYear(), 0, 1);
            const endOfYear = new Date(today.getFullYear(), 11, 31);

            document.getElementById('saturdayStartDate').value = startOfYear.toISOString().split('T')[0];
            document.getElementById('saturdayEndDate').value = endOfYear.toISOString().split('T')[0];
            document.getElementById('startDate').value = today.toISOString().split('T')[0];
            document.getElementById('endDate').value = new Date(today.getTime() + 7 * 24 * 60 * 60 * 1000).toISOString().split('T')[0];
        }

        function initializeFilters() {
            // Search filter
            document.getElementById('search').addEventListener('input', function(e) {
                filterSessions();
            });

            // Status filter
            document.getElementById('statusFilter').addEventListener('change', function(e) {
                filterSessions();
            });

            // Month filter
            document.getElementById('monthFilter').addEventListener('change', function(e) {
                filterSessions();
            });

            // Year filter
            document.getElementById('yearFilter').addEventListener('change', function(e) {
                filterSessions();
            });

            // Date range inputs for session creation
            document.getElementById('startDate').addEventListener('change', previewSessions);
            document.getElementById('endDate').addEventListener('change', previewSessions);
            document.querySelectorAll('.dayCheckbox').forEach(checkbox => {
                checkbox.addEventListener('change', previewSessions);
            });
        }

        function filterSessions() {
            const searchTerm = document.getElementById('search').value.toLowerCase();
            const statusFilter = document.getElementById('statusFilter').value;
            const monthFilter = document.getElementById('monthFilter').value;
            const yearFilter = document.getElementById('yearFilter').value;

            const rows = document.querySelectorAll('.session-row');
            let visibleCount = 0;

            rows.forEach(row => {
                const status = row.dataset.status;
                const date = row.dataset.date;
                const month = row.dataset.month;
                const year = row.dataset.year;
                const searchText = row.dataset.search;

                let show = true;

                // Search filter
                if (searchTerm && !searchText.includes(searchTerm)) {
                    show = false;
                }

                // Status filter
                if (statusFilter && status !== statusFilter) {
                    show = false;
                }

                // Month filter
                if (monthFilter && month != monthFilter) {
                    show = false;
                }

                // Year filter
                if (yearFilter && year != yearFilter) {
                    show = false;
                }

                row.style.display = show ? '' : 'none';
                if (show) visibleCount++;
            });

            // Update total count
            document.getElementById('totalCount').textContent = visibleCount;

            // Show/hide no results message
            const noSessionsRow = document.getElementById('noSessionsRow');
            if (noSessionsRow) {
                noSessionsRow.style.display = visibleCount === 0 ? '' : 'none';
            }
        }

        function sortTable(column) {
            const tbody = document.getElementById('sessionsTableBody');
            const rows = Array.from(tbody.querySelectorAll('.session-row'));

            if (sortColumn === column) {
                sortDirection = sortDirection === 'asc' ? 'desc' : 'asc';
            } else {
                sortColumn = column;
                sortDirection = 'asc';
            }

            rows.sort((a, b) => {
                let aValue, bValue;

                if (column === 'date') {
                    aValue = new Date(a.dataset.date);
                    bValue = new Date(b.dataset.date);
                } else if (column === 'status') {
                    aValue = a.dataset.status;
                    bValue = b.dataset.status;
                }

                if (aValue < bValue) return sortDirection === 'asc' ? -1 : 1;
                if (aValue > bValue) return sortDirection === 'asc' ? 1 : -1;
                return 0;
            });

            // Clear tbody and append sorted rows
            tbody.innerHTML = '';
            rows.forEach(row => tbody.appendChild(row));

            // Add no sessions row if needed
            if (rows.length === 0) {
                const noSessionsRow = document.createElement('tr');
                noSessionsRow.id = 'noSessionsRow';
                noSessionsRow.innerHTML = `
                    <td colspan="4" class="px-6 py-12 text-center">
                        <div class="text-gray-500 dark:text-gray-400">
                            <i class="fas fa-calendar-alt text-4xl mb-4"></i>
                            <p class="text-lg font-medium mb-2">No sessions found</p>
                            <p class="text-sm mb-4">Get started by scheduling your first session</p>
                            <button onclick="showSessionModal()" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                <i class="fas fa-plus mr-2"></i>
                                Schedule Session
                            </button>
                        </div>
                    </td>
                `;
                tbody.appendChild(noSessionsRow);
            }
        }

        function previewSessions() {
            const startDate = new Date(document.getElementById('startDate').value);
            const endDate = new Date(document.getElementById('endDate').value);
            const selectedDays = Array.from(document.querySelectorAll('.dayCheckbox:checked')).map(cb => parseInt(cb.value));

            if (!startDate || !endDate || selectedDays.length === 0) {
                document.getElementById('selectedDatesPreview').innerHTML = '<p class="text-sm text-gray-500 dark:text-gray-400">Select dates and days to see preview</p>';
                document.getElementById('saveButton').disabled = true;
                return;
            }

            selectedDates = [];
            const current = new Date(startDate);

            while (current <= endDate) {
                if (selectedDays.includes(current.getDay())) {
                    selectedDates.push(current.toISOString().split('T')[0]);
                }
                current.setDate(current.getDate() + 1);
            }

            if (selectedDates.length === 0) {
                document.getElementById('selectedDatesPreview').innerHTML = '<p class="text-sm text-gray-500 dark:text-gray-400">No dates match the selected criteria</p>';
                document.getElementById('saveButton').disabled = true;
                return;
            }

            const preview = selectedDates.slice(0, 10).map(date => {
                const formattedDate = new Date(date).toLocaleDateString('en-US', {
                    weekday: 'short',
                    month: 'short',
                    day: 'numeric'
                });
                return `<span class="inline-block bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100 px-2 py-1 rounded text-xs mr-2 mb-2">${formattedDate}</span>`;
            }).join('');

            const moreText = selectedDates.length > 10 ? `<p class="text-xs text-gray-500 mt-2">...and ${selectedDates.length - 10} more dates</p>` : '';

            document.getElementById('selectedDatesPreview').innerHTML = `
                <div class="flex flex-wrap">${preview}</div>
                <p class="text-xs text-gray-600 dark:text-gray-400 mt-2">${selectedDates.length} session(s) will be created</p>
                ${moreText}
            `;

            document.getElementById('saveButton').disabled = false;
        }

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

            const saveBtn = document.getElementById('saveButton');
            const originalText = saveBtn.innerHTML;
            saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Saving...';
            saveBtn.disabled = true;

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
                    if (data.success) {
                        alert('Sessions created successfully!');
                        hideSessionModal();
                        location.reload();
                    } else {
                        alert('Error creating sessions: ' + (data.message || 'Unknown error'));
                        saveBtn.innerHTML = originalText;
                        saveBtn.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while saving sessions.');
                    saveBtn.innerHTML = originalText;
                    saveBtn.disabled = false;
                });
        }

        function clearSessionForm() {
            document.getElementById('startDate').value = new Date().toISOString().split('T')[0];
            document.getElementById('endDate').value = new Date(Date.now() + 7 * 24 * 60 * 60 * 1000).toISOString().split('T')[0];
            document.getElementById('startTime').value = '09:00';
            document.getElementById('endTime').value = '10:00';
            document.getElementById('sessionStatus').value = 'class';
            document.getElementById('sessionNotes').value = '';
            document.querySelectorAll('.dayCheckbox').forEach(cb => cb.checked = false);
            document.getElementById('selectedDatesPreview').innerHTML = '<p class="text-sm text-gray-500 dark:text-gray-400">Select dates to see preview</p>';
            document.getElementById('saveButton').disabled = true;
            selectedDates = [];
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
                if (current.getDay() === 6) {
                    saturdays.push(new Date(current));
                }
                current.setDate(current.getDate() + 1);
            }

            const saturdayList = document.getElementById('saturdayList');
            saturdayList.innerHTML = saturdays.map(date =>
                `<div class="mb-1 flex items-center">
                    <span class="text-red-500 mr-2">🏖️</span>
                    ${date.toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })}
                </div>`
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

            const addBtn = document.getElementById('addSaturdaysBtn');
            const originalText = addBtn.innerHTML;
            addBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Adding Holidays...';
            addBtn.disabled = true;

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
                        alert('Error creating Saturday holidays: ' + (data.message || 'Unknown error'));
                        addBtn.innerHTML = originalText;
                        addBtn.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while creating Saturday holidays.');
                    addBtn.innerHTML = originalText;
                    addBtn.disabled = false;
                });
        }

        // Session Management Functions
        function viewSession(id) {
            const session = sessionsData.find(s => s.id == id);
            if (!session) {
                alert('Session not found');
                return;
            }

            currentSessionId = id;
            const statusIcons = {
                'class': '📚',
                'holiday': '🏖️',
                'exam': '📝',
                'event': '🎉'
            };

            const statusColors = {
                'class': '#3b82f6',
                'holiday': '#ef4444',
                'exam': '#f59e0b',
                'event': '#10b981'
            };

            const content = `
                <div class="space-y-4">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white flex items-center">
                            <span class="mr-2">${statusIcons[session.status] || '📅'}</span>
                            ${session.status.charAt(0).toUpperCase() + session.status.slice(1)} Session
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            ${new Date(session.date).toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })}
                        </p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                  style="background-color: ${statusColors[session.status]}20; color: ${statusColors[session.status]};">
                                ${statusIcons[session.status]} ${session.status.charAt(0).toUpperCase() + session.status.slice(1)}
                            </span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Time</label>
                            <p class="text-sm text-gray-900 dark:text-white">
                                ${session.start_time} - ${session.end_time}
                            </p>
                        </div>
                    </div>

                    ${session.notes ? `
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Notes</label>
                        <p class="text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-800 p-2 rounded">${session.notes}</p>
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

        function editSession(id) {
            const session = sessionsData.find(s => s.id == id);
            if (!session) {
                alert('Session not found');
                return;
            }

            editingSessionId = id;
            document.getElementById('editDate').value = session.date;
            document.getElementById('editStatus').value = session.status;
            document.getElementById('editStartTime').value = session.start_time;
            document.getElementById('editEndTime').value = session.end_time;
            document.getElementById('editNotes').value = session.notes || '';

            document.getElementById('editSessionModal').classList.remove('hidden');
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
                        alert('Error updating session: ' + (data.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while updating the session.');
                });
        });

        function confirmDeleteSession(id) {
            const session = sessionsData.find(s => s.id == id);
            if (!session) {
                alert('Session not found');
                return;
            }

            currentSessionId = id;
            const statusIcons = {
                'class': '📚',
                'holiday': '🏖️',
                'exam': '📝',
                'event': '🎉'
            };

            const statusColors = {
                'class': '#3b82f6',
                'holiday': '#ef4444',
                'exam': '#f59e0b',
                'event': '#10b981'
            };

            const details = `
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="font-medium text-gray-700 dark:text-gray-300">Date:</span>
                        <span class="text-gray-900 dark:text-white">${new Date(session.date).toLocaleDateString()}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium text-gray-700 dark:text-gray-300">Time:</span>
                        <span class="text-gray-900 dark:text-white">${session.start_time} - ${session.end_time}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium text-gray-700 dark:text-gray-300">Status:</span>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium"
                              style="background-color: ${statusColors[session.status]}20; color: ${statusColors[session.status]};">
                            ${statusIcons[session.status]} ${session.status.charAt(0).toUpperCase() + session.status.slice(1)}
                        </span>
                    </div>
                    ${session.notes ? `
                    <div class="flex justify-between">
                        <span class="font-medium text-gray-700 dark:text-gray-300">Notes:</span>
                        <span class="text-gray-900 dark:text-white text-right max-w-xs truncate">${session.notes}</span>
                    </div>
                    ` : ''}
                </div>
            `;

            document.getElementById('deleteSessionDetails').innerHTML = details;
            document.getElementById('deleteSessionModal').classList.remove('hidden');

            if (!document.getElementById('sessionDetailsModal').classList.contains('hidden')) {
                hideSessionDetailsModal();
            }
        }

        function hideDeleteSessionModal() {
            document.getElementById('deleteSessionModal').classList.add('hidden');
        }

        function deleteSession() {
            if (!currentSessionId) return;

            const deleteBtn = document.querySelector('#deleteSessionModal button[onclick="deleteSession()"]');
            const originalText = deleteBtn.innerHTML;
            deleteBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Deleting...';
            deleteBtn.disabled = true;

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
                    if (data.status === "success" || data.success) {
                        alert('Session deleted successfully!');
                        hideDeleteSessionModal();
                        location.reload();
                    } else {
                        throw new Error(data.message || 'Unknown error occurred');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error deleting session: ' + error.message);
                    deleteBtn.innerHTML = originalText;
                    deleteBtn.disabled = false;
                });
        }

        // Modal Control Functions
        function showSessionModal() {
            document.getElementById('sessionModal').classList.remove('hidden');
        }

        function hideSessionModal() {
            document.getElementById('sessionModal').classList.add('hidden');
            clearSessionForm();
        }
    </script>
@endsection
