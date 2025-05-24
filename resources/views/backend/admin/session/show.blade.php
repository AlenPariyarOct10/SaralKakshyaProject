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

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.0/main.min.css" rel="stylesheet">
    <style>
        .fc-event {
            cursor: pointer;
        }
        .fc-event:hover {
            opacity: 0.9;
        }
        .fc-daygrid-day.fc-day-today {
            background-color: rgba(59, 130, 246, 0.1);
        }
        .session-pending {
            background-color: #FEF3C7;
            border-color: #F59E0B;
        }
        .session-completed {
            background-color: #D1FAE5;
            border-color: #10B981;
        }
        .session-cancelled {
            background-color: #FEE2E2;
            border-color: #EF4444;
        }
        .tab-active {
            border-bottom: 2px solid #3B82F6;
            color: #3B82F6;
        }
        .session-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
    </style>
@endsection

@section('content')
    <!-- Main Content Area -->
    <main class="scrollable-content p-4 md:p-6">
        <!-- Institute Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <div class="flex items-center">
                @if($institute->logo)
                    <img src="{{ asset('storage/' . $institute->logo) }}" alt="{{ $institute->name }}" class="h-16 w-16 rounded-full object-cover mr-4">
                @else
                    <div class="h-16 w-16 rounded-full bg-primary-100 dark:bg-primary-800 flex items-center justify-center mr-4">
                        <span class="text-primary-600 dark:text-primary-300 text-2xl font-bold">{{ substr($institute->name, 0, 1) }}</span>
                    </div>
                @endif
                <div>
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-white">{{ $institute->name }}</h1>
                    <div class="flex items-center mt-1">
                        @if($institute->status == 'active')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100 mr-2">
                                Active
                            </span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100 mr-2">
                                Inactive
                            </span>
                        @endif
                        <span class="text-sm text-gray-600 dark:text-gray-400">{{ $institute->type }}</span>
                    </div>
                </div>
            </div>
            <div class="mt-4 md:mt-0 flex flex-wrap gap-2">
                <a href="{{ route('admin.institute.edit', $institute->id) }}" class="btn-primary">
                    <i class="fas fa-edit mr-2"></i> Edit Institute
                </a>
                <button onclick="showSessionModal()" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors">
                    <i class="fas fa-plus mr-2"></i> New Session
                </button>
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:bg-gray-600">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                    <div x-show="open" @click.away="open = false" class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 z-10">
                        <div class="py-1">
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
                                <i class="fas fa-download mr-2"></i> Export Sessions
                            </a>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
                                <i class="fas fa-envelope mr-2"></i> Email Institute
                            </a>
                            <a href="#" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100 dark:text-red-400 dark:hover:bg-gray-700" onclick="confirmDelete({{ $institute->id }})">
                                <i class="fas fa-trash mr-2"></i> Delete Institute
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Institute Information and Calendar -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Institute Information Card -->
            <div class="card">
                <h2 class="text-lg font-medium text-gray-800 dark:text-white mb-4">Institute Information</h2>

                <div class="space-y-4">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Contact Information</h3>
                        <div class="mt-2 text-sm text-gray-800 dark:text-white">
                            <div class="flex items-start mb-1">
                                <i class="fas fa-envelope w-5 text-gray-400 mt-1"></i>
                                <span class="ml-2">{{ $institute->email }}</span>
                            </div>
                            <div class="flex items-start mb-1">
                                <i class="fas fa-phone w-5 text-gray-400 mt-1"></i>
                                <span class="ml-2">{{ $institute->phone }}</span>
                            </div>
                            <div class="flex items-start mb-1">
                                <i class="fas fa-globe w-5 text-gray-400 mt-1"></i>
                                <span class="ml-2">
                                    @if($institute->website)
                                        <a href="{{ $institute->website }}" target="_blank" class="text-primary-600 hover:underline dark:text-primary-400">{{ $institute->website }}</a>
                                    @else
                                        Not provided
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Address</h3>
                        <div class="mt-2 text-sm text-gray-800 dark:text-white">
                            <p>{{ $institute->address_line1 }}</p>
                            @if($institute->address_line2)
                                <p>{{ $institute->address_line2 }}</p>
                            @endif
                            <p>{{ $institute->city }}, {{ $institute->state }} {{ $institute->postal_code }}</p>
                            <p>{{ $institute->country }}</p>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Primary Contact</h3>
                        <div class="mt-2 text-sm text-gray-800 dark:text-white">
                            <p class="font-medium">{{ $institute->contact_name }}</p>
                            @if($institute->contact_position)
                                <p class="text-gray-500 dark:text-gray-400">{{ $institute->contact_position }}</p>
                            @endif
                            <div class="flex items-start mt-1">
                                <i class="fas fa-envelope w-5 text-gray-400 mt-1"></i>
                                <span class="ml-2">{{ $institute->contact_email }}</span>
                            </div>
                            <div class="flex items-start mt-1">
                                <i class="fas fa-phone w-5 text-gray-400 mt-1"></i>
                                <span class="ml-2">{{ $institute->contact_phone }}</span>
                            </div>
                        </div>
                    </div>

                    @if($institute->description)
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Description</h3>
                            <div class="mt-2 text-sm text-gray-800 dark:text-white">
                                <p>{{ $institute->description }}</p>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Statistics</h3>
                    <div class="mt-2 grid grid-cols-2 gap-4">
                        <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-md">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Total Sessions</span>
                            <p class="text-xl font-semibold text-gray-800 dark:text-white">{{ $sessions_count ?? 0 }}</p>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-md">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Upcoming</span>
                            <p class="text-xl font-semibold text-gray-800 dark:text-white">{{ $upcoming_sessions_count ?? 0 }}</p>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-md">
                            <span class="text-sm text-gray-500 dark:text-gray-400">This Month</span>
                            <p class="text-xl font-semibold text-gray-800 dark:text-white">{{ $this_month_sessions_count ?? 0 }}</p>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-md">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Completed</span>
                            <p class="text-xl font-semibold text-gray-800 dark:text-white">{{ $completed_sessions_count ?? 0 }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Calendar and Sessions -->
            <div class="lg:col-span-2">
                <!-- Tabs -->
                <div class="card mb-6">
                    <div class="border-b border-gray-200 dark:border-gray-700 mb-4">
                        <nav class="-mb-px flex space-x-6" aria-label="Tabs">
                            <button onclick="switchTab('calendar')" id="calendar-tab" class="pb-3 px-1 text-sm font-medium tab-active">
                                <i class="fas fa-calendar-alt mr-2"></i> Calendar
                            </button>
                            <button onclick="switchTab('upcoming')" id="upcoming-tab" class="pb-3 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300">
                                <i class="fas fa-clock mr-2"></i> Upcoming Sessions
                            </button>
                            <button onclick="switchTab('past')" id="past-tab" class="pb-3 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300">
                                <i class="fas fa-history mr-2"></i> Past Sessions
                            </button>
                        </nav>
                    </div>

                    <!-- Calendar Tab -->
                    <div id="calendar-content" class="tab-content">
                        <div id="calendar"></div>
                    </div>

                    <!-- Upcoming Sessions Tab -->
                    <div id="upcoming-content" class="tab-content hidden">
                        <div class="space-y-4">
                            @forelse($upcoming_sessions as $session)
                                <div class="session-card bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-all duration-200">
                                    <div class="flex justify-between">
                                        <div>
                                            <h3 class="font-medium text-gray-800 dark:text-white">{{ date('l, F j, Y', strtotime($session->date)) }}</h3>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ date('g:i A', strtotime($session->start_time)) }} - {{ date('g:i A', strtotime($session->end_time)) }}</p>
                                        </div>
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $session->status == 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100' : 'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100' }}">
                                            {{ ucfirst($session->status) }}
                                        </span>
                                    </div>
                                    @if($session->notes)
                                        <div class="mt-2">
                                            <p class="text-sm text-gray-600 dark:text-gray-300">{{ $session->notes }}</p>
                                        </div>
                                    @endif
                                    <div class="mt-3 flex justify-end space-x-2">
                                        <button onclick="viewSession({{ $session->id }})" class="text-xs px-2 py-1 bg-gray-100 text-gray-800 rounded hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
                                            <i class="fas fa-eye mr-1"></i> View
                                        </button>
                                        <button onclick="editSession({{ $session->id }})" class="text-xs px-2 py-1 bg-blue-100 text-blue-800 rounded hover:bg-blue-200 dark:bg-blue-700 dark:text-blue-200 dark:hover:bg-blue-600">
                                            <i class="fas fa-edit mr-1"></i> Edit
                                        </button>
                                        <button onclick="confirmCancelSession({{ $session->id }})" class="text-xs px-2 py-1 bg-red-100 text-red-800 rounded hover:bg-red-200 dark:bg-red-700 dark:text-red-200 dark:hover:bg-red-600">
                                            <i class="fas fa-times mr-1"></i> Cancel
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-6">
                                    <div class="text-gray-400 dark:text-gray-500 mb-2"><i class="fas fa-calendar-times text-3xl"></i></div>
                                    <h3 class="text-lg font-medium text-gray-800 dark:text-gray-200">No upcoming sessions</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Schedule a new session with this institute</p>
                                    <button onclick="showSessionModal()" class="mt-3 px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-colors">
                                        <i class="fas fa-plus mr-2"></i> New Session
                                    </button>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Past Sessions Tab -->
                    <div id="past-content" class="tab-content hidden">
                        <div class="space-y-4">
                            @forelse($past_sessions as $session)
                                <div class="session-card bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-all duration-200">
                                    <div class="flex justify-between">
                                        <div>
                                            <h3 class="font-medium text-gray-800 dark:text-white">{{ date('l, F j, Y', strtotime($session->date)) }}</h3>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ date('g:i A', strtotime($session->start_time)) }} - {{ date('g:i A', strtotime($session->end_time)) }}</p>
                                        </div>
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $session->status == 'completed' ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' : 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100' }}">
                                            {{ ucfirst($session->status) }}
                                        </span>
                                    </div>
                                    @if($session->notes)
                                        <div class="mt-2">
                                            <p class="text-sm text-gray-600 dark:text-gray-300">{{ $session->notes }}</p>
                                        </div>
                                    @endif
                                    <div class="mt-3 flex justify-end">
                                        <button onclick="viewSession({{ $session->id }})" class="text-xs px-2 py-1 bg-gray-100 text-gray-800 rounded hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
                                            <i class="fas fa-eye mr-1"></i> View Details
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-6">
                                    <div class="text-gray-400 dark:text-gray-500 mb-2"><i class="fas fa-history text-3xl"></i></div>
                                    <h3 class="text-lg font-medium text-gray-800 dark:text-gray-200">No past sessions</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">There are no previous sessions with this institute</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Recent Notes or Activity -->
                <div class="card">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-medium text-gray-800 dark:text-white">Recent Activity</h2>
                        <a href="#" class="text-sm text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300">View all</a>
                    </div>

                    <div class="flow-root">
                        <ul role="list" class="-mb-8">
                            <li>
                                <div class="relative pb-8">
                                    <span class="absolute top-5 left-5 -ml-px h-full w-0.5 bg-gray-200 dark:bg-gray-700" aria-hidden="true"></span>
                                    <div class="relative flex items-start space-x-3">
                                        <div class="relative">
                                            <div class="h-10 w-10 rounded-full bg-gray-400 flex items-center justify-center ring-8 ring-white dark:ring-gray-800">
                                                <i class="fas fa-calendar-plus text-white"></i>
                                            </div>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div>
                                                <div class="text-sm">
                                                    <a href="#" class="font-medium text-gray-900 dark:text-white">New session scheduled</a>
                                                </div>
                                                <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">Today at 2:30 PM</p>
                                            </div>
                                            <div class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                                                <p>Session scheduled for May 15, 2023 from 10:00 AM to 12:00 PM.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>

                            <li>
                                <div class="relative pb-8">
                                    <span class="absolute top-5 left-5 -ml-px h-full w-0.5 bg-gray-200 dark:bg-gray-700" aria-hidden="true"></span>
                                    <div class="relative flex items-start space-x-3">
                                        <div class="relative">
                                            <div class="h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white dark:ring-gray-800">
                                                <i class="fas fa-edit text-white"></i>
                                            </div>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div>
                                                <div class="text-sm">
                                                    <a href="#" class="font-medium text-gray-900 dark:text-white">Institute information updated</a>
                                                </div>
                                                <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">Yesterday at 11:45 AM</p>
                                            </div>
                                            <div class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                                                <p>Contact information was updated.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>

                            <li>
                                <div class="relative pb-8">
                                    <div class="relative flex items-start space-x-3">
                                        <div class="relative">
                                            <div class="h-10 w-10 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white dark:ring-gray-800">
                                                <i class="fas fa-check text-white"></i>
                                            </div>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div>
                                                <div class="text-sm">
                                                    <a href="#" class="font-medium text-gray-900 dark:text-white">Session completed</a>
                                                </div>
                                                <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">3 days ago</p>
                                            </div>
                                            <div class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                                                <p>A session was marked as completed. Notes were added to the session.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- New Session Modal -->
    <div id="sessionModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden" style="z-index: 50;">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800 max-w-md">
            <div class="flex justify-between items-center border-b pb-3 mb-4 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white" id="modalTitle">Schedule New Session</h3>
                <button type="button" onclick="hideSessionModal()" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="sessionForm" method="POST" action="{{ route('admin.institute.sessions.store', $institute->id) }}">
                @csrf
                <input type="hidden" id="session_id" name="session_id">
                <div class="space-y-4">
                    <!-- Date -->
                    <div>
                        <label for="date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date <span class="text-red-600">*</span></label>
                        <input type="date" id="date" name="date" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>

                    <!-- Start Time -->
                    <div>
                        <label for="start_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Start Time <span class="text-red-600">*</span></label>
                        <input type="time" id="start_time" name="start_time" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>

                    <!-- End Time -->
                    <div>
                        <label for="end_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300">End Time <span class="text-red-600">*</span></label>
                        <input type="time" id="end_time" name="end_time" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                        <select id="status" name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="pending">Pending</option>
                            <option value="confirmed">Confirmed</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>

                    <!-- Notes -->
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Notes</label>
                        <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"></textarea>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" onclick="hideSessionModal()" class="px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-md shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300 dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-primary-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 dark:bg-primary-700 dark:hover:bg-primary-600">
                        <span id="saveButtonText">Schedule Session</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Session View Modal -->
    <div id="viewSessionModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden" style="z-index: 50;">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800 max-w-md">
            <div class="flex justify-between items-center border-b pb-3 mb-4 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Session Details</h3>
                <button type="button" onclick="hideViewSessionModal()" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="sessionDetails" class="space-y-4">
                <!-- Session details will be loaded here -->
            </div>
            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" onclick="hideViewSessionModal()" class="px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-md shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300 dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600">
                    Close
                </button>
                <button id="editButton" type="button" class="px-4 py-2 bg-primary-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 dark:bg-primary-700 dark:hover:bg-primary-600">
                    Edit
                </button>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden" style="z-index: 50;">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800 max-w-md">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-800">
                    <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-300 text-xl"></i>
                </div>
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mt-4">Delete Institute</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Are you sure you want to delete this institute? This action cannot be undone and will delete all associated sessions.
                    </p>
                </div>
                <div class="flex justify-center gap-4 mt-4">
                    <button id="cancelDelete" onclick="hideDeleteModal()" class="px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-md shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300 dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600">
                        Cancel
                    </button>
                    <form id="deleteForm" method="POST" action="{{ route('admin.institute.destroy', $institute->id) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 dark:bg-red-700 dark:hover:bg-red-600">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Cancel Session Modal -->
    <div id="cancelSessionModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden" style="z-index: 50;">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800 max-w-md">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 dark:bg-yellow-800">
                    <i class="fas fa-calendar-times text-yellow-600 dark:text-yellow-300 text-xl"></i>
                </div>
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mt-4">Cancel Session</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Are you sure you want to cancel this session? You can provide a reason for cancellation.
                    </p>
                    <div class="mt-4">
                        <label for="cancel_reason" class="block text-sm font-medium text-left text-gray-700 dark:text-gray-300">Cancellation Reason</label>
                        <textarea id="cancel_reason" name="cancel_reason" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"></textarea>
                    </div>
                </div>
                <div class="flex justify-center gap-4 mt-4">
                    <button onclick="hideCancelSessionModal()" class="px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-md shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300 dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600">
                        Keep Session
                    </button>
                    <form id="cancelSessionForm" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="cancelled">
                        <input type="hidden" id="cancel_notes" name="notes">
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 dark:bg-red-700 dark:hover:bg-red-600">
                            Cancel Session
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.0/main.min.js"></script>
    <script>
        // Initialize FullCalendar
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: [
                    // These would typically be loaded from your backend
                        @foreach($all_sessions as $session)
                    {
                        id: '{{ $session->id }}',
                        title: '{{ addslashes($institute->name) }} Session',
                        start: '{{ $session->date }}T{{ $session->start_time }}',
                        end: '{{ $session->date }}T{{ $session->end_time }}',
                        classNames: ['session-{{ $session->status }}'],
                        extendedProps: {
                            status: '{{ $session->status }}',
                            notes: '{{ addslashes($session->notes) }}'
                        }
                    },
                    @endforeach
                ],
                eventClick: function(info) {
                    viewSession(info.event.id);
                },
                eventTimeFormat: {
                    hour: 'numeric',
                    minute: '2-digit',
                    meridiem: 'short'
                },
                dayMaxEvents: true,
                themeSystem: 'standard',
                height: 'auto'
            });

            calendar.render();

            // Add legends for calendar events
            const legendHtml = `
            <div class="flex flex-wrap items-center justify-end gap-3 text-xs mt-2">
                <div class="flex items-center">
                    <span class="w-3 h-3 inline-block rounded-full bg-yellow-300 mr-1"></span>
                    <span>Pending</span>
                </div>
                <div class="flex items-center">
                    <span class="w-3 h-3 inline-block rounded-full bg-blue-400 mr-1"></span>
                    <span>Confirmed</span>
                </div>
                <div class="flex items-center">
                    <span class="w-3 h-3 inline-block rounded-full bg-green-400 mr-1"></span>
                    <span>Completed</span>
                </div>
                <div class="flex items-center">
                    <span class="w-3 h-3 inline-block rounded-full bg-red-400 mr-1"></span>
                    <span>Cancelled</span>
                </div>
            </div>
        `;

            document.querySelector('.fc-toolbar-chunk:last-child').insertAdjacentHTML('afterend', legendHtml);
        });

        // Tab switching
        function switchTab(tabName) {
            // Hide all tab content
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });

            // Show selected tab content
            document.getElementById(`${tabName}-content`).classList.remove('hidden');

            // Update active tab styling
            document.querySelectorAll('button[id$="-tab"]').forEach(tab => {
                tab.classList.remove('tab-active');
                tab.classList.add('text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300', 'dark:text-gray-400', 'dark:hover:text-gray-300');
            });

            document.getElementById(`${tabName}-tab`).classList.add('tab-active');
            document.getElementById(`${tabName}-tab`).classList.remove('text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300', 'dark:text-gray-400', 'dark:hover:text-gray-300');
        }

        // Session Modal Functions
        function showSessionModal() {
            // Reset form
            document.getElementById('sessionForm').reset();
            document.getElementById('session_id').value = '';
            document.getElementById('modalTitle').textContent = 'Schedule New Session';
            document.getElementById('saveButtonText').textContent = 'Schedule Session';

            // Set default date to today
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('date').value = today;

            // Show modal
            document.getElementById('sessionModal').classList.remove('hidden');
        }

        function hideSessionModal() {
            document.getElementById('sessionModal').classList.add('hidden');
        }

        function editSession(id) {
            // Fetch session data and populate form
            // This would typically be an AJAX call to your backend

            document.getElementById('session_id').value = id;
            document.getElementById('modalTitle').textContent = 'Edit Session';
            document.getElementById('saveButtonText').textContent = 'Update Session';

            // For demo purposes, we'll just set some dummy data
            // In a real app, you would fetch this data from your backend
            const dummyData = {
                date: '2023-05-15',
                start_time: '10:00',
                end_time: '12:00',
                status: 'confirmed',
                notes: 'Sample notes for this session.'
            };

            document.getElementById('date').value = dummyData.date;
            document.getElementById('start_time').value = dummyData.start_time;
            document.getElementById('end_time').value = dummyData.end_time;
            document.getElementById('status').value = dummyData.status;
            document.getElementById('notes').value = dummyData.notes;

            // Show modal
            document.getElementById('sessionModal').classList.remove('hidden');
        }

        function viewSession(id) {
            // Fetch session data and display in modal
            // This would typically be an AJAX call to your backend

            // For demo purposes, we'll just set some dummy data
            // In a real app, you would fetch this data from your backend
            const dummyData = {
                id: id,
                date: 'May 15, 2023',
                time: '10:00 AM - 12:00 PM',
                status: 'Confirmed',
                notes: 'Sample notes for this session.',
                created_by: 'Admin User',
                created_at: 'May 10, 2023'
            };

            // Build HTML for session details
            const html = `
            <div class="space-y-3">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Date</p>
                    <p class="font-medium text-gray-900 dark:text-white">${dummyData.date}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Time</p>
                    <p class="font-medium text-gray-900 dark:text-white">${dummyData.time}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Status</p>
                    <p class="font-medium text-gray-900 dark:text-white">${dummyData.status}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Notes</p>
                    <p class="font-medium text-gray-900 dark:text-white">${dummyData.notes || 'No notes'}</p>
                </div>
                <div class="pt-3 border-t border-gray-200 dark:border-gray-700">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Created by ${dummyData.created_by} on ${dummyData.created_at}</p>
                </div>
            </div>
        `;

            // Update modal content
            document.getElementById('sessionDetails').innerHTML = html;

            // Set up edit button
            document.getElementById('editButton').onclick = function() {
                hideViewSessionModal();
                editSession(id);
            };

            // Show modal
            document.getElementById('viewSessionModal').classList.remove('hidden');
        }

        function hideViewSessionModal() {
            document.getElementById('viewSessionModal').classList.add('hidden');
        }

        // Delete confirmation functions
        function confirmDelete(id) {
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function hideDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }

        // Cancel session functions
        function confirmCancelSession(id) {
            // Set up form action
            document.getElementById('cancelSessionForm').action = `/admin/institute/sessions/${id}`;
            document.getElementById('cancelSessionModal').classList.remove('hidden');
        }

        function hideCancelSessionModal() {
            document.getElementById('cancelSessionModal').classList.add('hidden');
        }

        // Handle cancel session form submission
        document.getElementById('cancelSessionForm').addEventListener('submit', function(e) {
            e.preventDefault();

            // Get cancellation reason and set it to notes
            const reason = document.getElementById('cancel_reason').value;
            document.getElementById('cancel_notes').value = `Cancelled: ${reason}`;

            // Submit form
            this.submit();
        });

        // Form submission handling
        document.getElementById('sessionForm').addEventListener('submit', function(e) {
            e.preventDefault();

            // Validate form
            const startTime = document.getElementById('start_time').value;
            const endTime = document.getElementById('end_time').value;

            if (endTime <= startTime) {
                alert('End time must be after start time');
                return;
            }

            // Submit form
            this.submit();
        });
    </script>
@endsection
