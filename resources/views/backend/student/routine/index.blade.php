@php use Illuminate\Support\Facades\Auth;
 use Carbon\Carbon;
@endphp
@extends('backend.layout.student-dashboard-layout')
@php $user = Auth::user(); @endphp
@section('username', $user->fname . ' ' . $user->lname)
@section("title", "Routine")
@section('styles')
    <style>
        @media print {
            .no-print {
                display: none !important;
            }

            .card {
                box-shadow: none !important;
                border: 1px solid #e5e7eb !important;
            }

            body {
                background: white !important;
            }

            #cardView {
                display: none !important;
            }
        }

        /* Custom scrollbar for horizontal scroll */
        .overflow-x-auto::-webkit-scrollbar {
            height: 6px;
        }

        .overflow-x-auto::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }

        .overflow-x-auto::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }

        .overflow-x-auto::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        /* Ensure table cells don't break */
        table td, table th {
            white-space: nowrap;
        }

        /* Mobile optimizations */
        @media (max-width: 768px) {
            .min-w-full {
                min-width: 800px;
            }
        }
    </style>
@endsection

@section('content')
    <div class="scrollable-content p-4 md:p-6 bg-gray-50 dark:bg-gray-900">
        <div class="max-w-full mx-auto">
            <!-- Page Header -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                <div>
                    <h1 class="text-xl md:text-2xl font-bold text-gray-800 dark:text-white">Class Routine</h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        @if($user->batch)
                            {{ $user->batch->program->name ?? 'N/A' }} - Semester {{ $user->batch->semester ?? 'N/A' }}
                        @else
                            No batch assigned
                        @endif
                    </p>
                </div>
                <div class="mt-4 md:mt-0 flex flex-col sm:flex-row gap-2">
                    <button id="printRoutine" class="px-3 py-2 text-sm bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                        <i class="fas fa-print mr-2"></i>Print
                    </button>
                    <button id="downloadRoutine" class="px-3 py-2 text-sm bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">
                        <i class="fas fa-download mr-2"></i>PDF
                    </button>
                </div>
            </div>

            <x-show-success-failure-badge/>

            <!-- Routine Statistics Cards -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-6 mb-6 md:mb-8">
                <!-- Total Classes Card -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-3 md:p-6">
                    <div class="flex items-center justify-between">
                        <div class="min-w-0 flex-1">
                            <p class="text-xs md:text-sm font-medium text-gray-600 dark:text-gray-400 truncate">Total Classes</p>
                            <p class="text-lg md:text-2xl font-bold text-gray-900 dark:text-white">{{ $routineStats['total_classes'] ?? 0 }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Per week</p>
                        </div>
                        <div class="w-8 h-8 md:w-10 md:h-10 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-calendar-alt text-blue-600 dark:text-blue-400 text-sm md:text-base"></i>
                        </div>
                    </div>
                </div>

                <!-- Today's Classes Card -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-3 md:p-6">
                    <div class="flex items-center justify-between">
                        <div class="min-w-0 flex-1">
                            <p class="text-xs md:text-sm font-medium text-gray-600 dark:text-gray-400 truncate">Today's Classes</p>
                            <p class="text-lg md:text-2xl font-bold text-green-600 dark:text-green-400">{{ $routineStats['today_classes'] ?? 0 }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ now()->format('D') }}</p>
                        </div>
                        <div class="w-8 h-8 md:w-10 md:h-10 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-clock text-green-600 dark:text-green-400 text-sm md:text-base"></i>
                        </div>
                    </div>
                </div>

                <!-- Total Subjects Card -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-3 md:p-6">
                    <div class="flex items-center justify-between">
                        <div class="min-w-0 flex-1">
                            <p class="text-xs md:text-sm font-medium text-gray-600 dark:text-gray-400 truncate">Total Subjects</p>
                            <p class="text-lg md:text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $routineStats['total_subjects'] ?? 0 }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">This semester</p>
                        </div>
                        <div class="w-8 h-8 md:w-10 md:h-10 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-book text-purple-600 dark:text-purple-400 text-sm md:text-base"></i>
                        </div>
                    </div>
                </div>

                <!-- Next Class Card -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-3 md:p-6">
                    <div class="flex items-center justify-between">
                        <div class="min-w-0 flex-1">
                            <p class="text-xs md:text-sm font-medium text-gray-600 dark:text-gray-400 truncate">Next Class</p>
                            @if($nextClass)
                                <p class="text-sm md:text-lg font-bold text-orange-600 dark:text-orange-400 truncate">{{ $nextClass['time'] }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 truncate">{{ $nextClass['subject'] }}</p>
                            @else
                                <p class="text-sm md:text-lg font-bold text-gray-500 dark:text-gray-400">No class</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Today</p>
                            @endif
                        </div>
                        <div class="w-8 h-8 md:w-10 md:h-10 bg-orange-100 dark:bg-orange-900 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-play text-orange-600 dark:text-orange-400 text-sm md:text-base"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Current Day Highlight -->
            <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg p-4 md:p-6 mb-6 md:mb-8 text-white">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <div class="mb-3 sm:mb-0">
                        <h3 class="text-base md:text-lg font-semibold">{{ now()->format('l, F j, Y') }}</h3>
                        <p class="text-blue-100 mt-1 text-sm">
                            @if($todayClasses->count() > 0)
                                You have {{ $todayClasses->count() }} {{ $todayClasses->count() == 1 ? 'class' : 'classes' }} today
                            @else
                                No classes scheduled for today
                            @endif
                        </p>
                    </div>
                    <div class="text-left sm:text-right">
                        <div class="text-xl md:text-2xl font-bold">{{ now()->format('g:i A') }}</div>
                        <div class="text-blue-100 text-sm">Current Time</div>
                    </div>
                </div>
            </div>

            <!-- Mobile View Toggle -->
            <div class="lg:hidden mb-4">
                <div class="flex space-x-2">
                    <button id="tableViewBtn" class="px-3 py-2 text-sm bg-blue-600 text-white rounded-md">Table View</button>
                    <button id="cardViewBtn" class="px-3 py-2 text-sm bg-gray-600 text-white rounded-md">Card View</button>
                </div>
            </div>

            <!-- Weekly Routine Table (Desktop) -->
            <div id="tableView" class="card mb-6 md:mb-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 md:mb-6">
                    <h3 class="text-base md:text-lg font-medium text-gray-700 dark:text-gray-300 mb-3 sm:mb-0">Weekly Schedule</h3>
                    <div class="flex flex-wrap items-center gap-2 md:gap-4">
                        <div class="flex items-center space-x-1 md:space-x-2">
                            <div class="w-2 h-2 md:w-3 md:h-3 bg-blue-500 rounded-full"></div>
                            <span class="text-xs text-gray-600 dark:text-gray-400">Current</span>
                        </div>
                        <div class="flex items-center space-x-1 md:space-x-2">
                            <div class="w-2 h-2 md:w-3 md:h-3 bg-green-500 rounded-full"></div>
                            <span class="text-xs text-gray-600 dark:text-gray-400">Upcoming</span>
                        </div>
                        <div class="flex items-center space-x-1 md:space-x-2">
                            <div class="w-2 h-2 md:w-3 md:h-3 bg-gray-300 rounded-full"></div>
                            <span class="text-xs text-gray-600 dark:text-gray-400">Completed</span>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto -mx-4 md:mx-0">
                    <div class="min-w-full inline-block align-middle">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700" style="min-width: 800px;">
                            <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th class="px-2 md:px-6 py-2 md:py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-24 md:w-32">Time</th>
                                @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                                    <th class="px-1 md:px-3 py-2 md:py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider {{ now()->format('l') == $day ? 'bg-blue-50 dark:bg-blue-900' : '' }}">
                                        <div class="flex flex-col">
                                            <span class="hidden sm:inline">{{ $day }}</span>
                                            <span class="sm:hidden">{{ substr($day, 0, 3) }}</span>
                                            @if(now()->format('l') == $day)
                                                <span class="mt-1 px-1 py-0.5 text-xs bg-blue-500 text-white rounded-full">Today</span>
                                            @endif
                                        </div>
                                    </th>
                                @endforeach
                            </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($timeSlots as $timeSlot)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-2 md:px-6 py-2 md:py-4 text-xs md:text-sm font-medium text-gray-900 dark:text-white">
                                        <div class="whitespace-nowrap">{{ $timeSlot }}</div>
                                    </td>
                                    @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                                        <td class="px-1 md:px-3 py-2 md:py-4 {{ now()->format('l') == $day ? 'bg-blue-50 dark:bg-blue-900' : '' }}">
                                            @php
                                                $class = $routine->where('day', $day)->where('time_slot', $timeSlot)->first();
                                                $isCurrentClass = false;
                                                $isUpcoming = false;
                                                $isCompleted = false;

                                                if ($class && now()->format('l') == $day) {
                                                    $currentTime = now()->format('H:i');
                                                    $classStart = Carbon::parse($class->start_time)->format('H:i');
                                                    $classEnd = Carbon::parse($class->end_time)->format('H:i');

                                                    if ($currentTime >= $classStart && $currentTime <= $classEnd) {
                                                        $isCurrentClass = true;
                                                    } elseif ($currentTime < $classStart) {
                                                        $isUpcoming = true;
                                                    } else {
                                                        $isCompleted = true;
                                                    }
                                                }
                                            @endphp

                                            @if($class)
                                                <div class="p-1 md:p-2 rounded-md text-xs {{ $isCurrentClass ? 'bg-blue-100 border-l-2 md:border-l-4 border-blue-500 dark:bg-blue-900' : ($isUpcoming ? 'bg-green-100 border-l-2 md:border-l-4 border-green-500 dark:bg-green-900' : ($isCompleted ? 'bg-gray-100 border-l-2 md:border-l-4 border-gray-400 dark:bg-gray-700' : 'bg-gray-50 dark:bg-gray-700')) }}">
                                                    <div class="font-semibold text-gray-800 dark:text-white truncate" title="{{ $class->subject_name }}">
                                                        {{ Str::limit($class->subject_name, 15) }}
                                                    </div>
                                                    <div class="text-gray-600 dark:text-gray-400 mt-1 truncate" title="{{ $class->teacher_name }}">
                                                        {{ Str::limit($class->teacher_name, 12) }}
                                                    </div>
                                                    <div class="text-gray-500 dark:text-gray-500 mt-1 hidden md:block">
                                                        {{ Carbon::parse($class->start_time)->format('g:i A') }} - {{ Carbon::parse($class->end_time)->format('g:i A') }}
                                                    </div>
                                                    @if($class->notes)
                                                        <div class="text-gray-500 dark:text-gray-500 mt-1 italic truncate hidden md:block" title="{{ $class->notes }}">
                                                            {{ Str::limit($class->notes, 20) }}
                                                        </div>
                                                    @endif
                                                    @if($isCurrentClass)
                                                        <div class="mt-1 md:mt-2">
                                                            <span class="px-1 md:px-2 py-0.5 md:py-1 text-xs bg-blue-500 text-white rounded-full">
                                                                <i class="fas fa-circle mr-1"></i><span class="hidden md:inline">Live</span>
                                                            </span>
                                                        </div>
                                                    @endif
                                                </div>
                                            @else
                                                <div class="text-center text-gray-400 dark:text-gray-600 text-xs py-2">
                                                    <i class="fas fa-minus"></i>
                                                </div>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Mobile Card View -->
            <div id="cardView" class="lg:hidden hidden mb-6 md:mb-8">
                <div class="card">
                    <h3 class="text-lg font-medium text-gray-700 dark:text-gray-300 mb-4">Weekly Schedule</h3>
                    <div class="space-y-4">
                        @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-3 {{ now()->format('l') == $day ? 'bg-blue-50 dark:bg-blue-900 border-blue-300' : '' }}">
                                <h4 class="font-semibold text-gray-800 dark:text-white mb-2 flex items-center">
                                    {{ $day }}
                                    @if(now()->format('l') == $day)
                                        <span class="ml-2 px-2 py-1 text-xs bg-blue-500 text-white rounded-full">Today</span>
                                    @endif
                                </h4>
                                <div class="space-y-2">
                                    @php
                                        $dayClasses = $routine->where('day', $day)->sortBy('start_time');
                                    @endphp
                                    @forelse($dayClasses as $class)
                                        @php
                                            $isCurrentClass = false;
                                            $isUpcoming = false;
                                            $isCompleted = false;

                                            if (now()->format('l') == $day) {
                                                $currentTime = now()->format('H:i');
                                                $classStart = Carbon::parse($class->start_time)->format('H:i');
                                                $classEnd = Carbon::parse($class->end_time)->format('H:i');

                                                if ($currentTime >= $classStart && $currentTime <= $classEnd) {
                                                    $isCurrentClass = true;
                                                } elseif ($currentTime < $classStart) {
                                                    $isUpcoming = true;
                                                } else {
                                                    $isCompleted = true;
                                                }
                                            }
                                        @endphp
                                        <div class="p-3 rounded-md {{ $isCurrentClass ? 'bg-blue-100 border border-blue-500 dark:bg-blue-800' : ($isUpcoming ? 'bg-green-100 border border-green-500 dark:bg-green-800' : ($isCompleted ? 'bg-gray-100 border border-gray-400 dark:bg-gray-700' : 'bg-gray-50 dark:bg-gray-700')) }}">
                                            <div class="flex justify-between items-start">
                                                <div class="flex-1">
                                                    <div class="font-semibold text-gray-800 dark:text-white">{{ $class->subject_name }}</div>
                                                    <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $class->teacher_name }}</div>
                                                    <div class="text-sm text-gray-500 dark:text-gray-500 mt-1">
                                                        {{ Carbon::parse($class->start_time)->format('g:i A') }} - {{ Carbon::parse($class->end_time)->format('g:i A') }}
                                                    </div>
                                                    @if($class->notes)
                                                        <div class="text-sm text-gray-500 dark:text-gray-500 mt-1 italic">{{ $class->notes }}</div>
                                                    @endif
                                                </div>
                                                @if($isCurrentClass)
                                                    <span class="px-2 py-1 text-xs bg-blue-500 text-white rounded-full">Live</span>
                                                @endif
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-center text-gray-500 dark:text-gray-400 py-4">
                                            <i class="fas fa-calendar-times text-2xl mb-2"></i>
                                            <p class="text-sm">No classes scheduled</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Today's Detailed Schedule -->
            @if($todayClasses->count() > 0)
                <div class="card mb-6 md:mb-8">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 md:mb-6">
                        <h3 class="text-base md:text-lg font-medium text-gray-700 dark:text-gray-300 mb-2 sm:mb-0">Today's Detailed Schedule</h3>
                        <span class="px-3 py-1 text-sm bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded-full">
                            {{ $todayClasses->count() }} {{ $todayClasses->count() == 1 ? 'Class' : 'Classes' }}
                        </span>
                    </div>

                    <div class="space-y-3 md:space-y-4">
                        @foreach($todayClasses as $class)
                            @php
                                $currentTime = now()->format('H:i');
                                $classStart = Carbon::parse($class->start_time)->format('H:i');
                                $classEnd = Carbon::parse($class->end_time)->format('H:i');
                                $isCurrentClass = $currentTime >= $classStart && $currentTime <= $classEnd;
                                $isUpcoming = $currentTime < $classStart;
                                $isCompleted = $currentTime > $classEnd;
                            @endphp

                            <div class="flex items-center p-3 md:p-4 rounded-lg border {{ $isCurrentClass ? 'border-blue-500 bg-blue-50 dark:bg-blue-900' : ($isUpcoming ? 'border-green-500 bg-green-50 dark:bg-green-900' : 'border-gray-200 bg-gray-50 dark:bg-gray-700') }}">
                                <div class="flex-shrink-0 mr-3 md:mr-4">
                                    <div class="w-10 h-10 md:w-12 md:h-12 rounded-full flex items-center justify-center {{ $isCurrentClass ? 'bg-blue-500' : ($isUpcoming ? 'bg-green-500' : 'bg-gray-400') }} text-white">
                                        @if($isCurrentClass)
                                            <i class="fas fa-play text-sm md:text-base"></i>
                                        @elseif($isUpcoming)
                                            <i class="fas fa-clock text-sm md:text-base"></i>
                                        @else
                                            <i class="fas fa-check text-sm md:text-base"></i>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex-1 min-w-0">
                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                                        <h4 class="text-base md:text-lg font-semibold text-gray-800 dark:text-white truncate">{{ $class->subject_name }}</h4>
                                        <span class="mt-1 sm:mt-0 px-2 md:px-3 py-1 text-xs md:text-sm rounded-full {{ $isCurrentClass ? 'bg-blue-500 text-white' : ($isUpcoming ? 'bg-green-500 text-white' : 'bg-gray-400 text-white') }}">
                                            {{ $isCurrentClass ? 'Live Now' : ($isUpcoming ? 'Upcoming' : 'Completed') }}
                                        </span>
                                    </div>
                                    <p class="text-sm md:text-base text-gray-600 dark:text-gray-400 mt-1">
                                        <i class="fas fa-user mr-2"></i>{{ $class->teacher_name }}
                                    </p>
                                    <div class="flex flex-col sm:flex-row sm:items-center text-sm md:text-base text-gray-600 dark:text-gray-400 mt-1">
                                        <span class="mr-0 sm:mr-4">
                                            <i class="fas fa-clock mr-2"></i>{{ Carbon::parse($class->start_time)->format('g:i A') }} - {{ Carbon::parse($class->end_time)->format('g:i A') }}
                                        </span>
                                        <span class="mt-1 sm:mt-0">
                                            <i class="fas fa-hourglass-half mr-2"></i>{{ Carbon::parse($class->start_time)->diffInMinutes(Carbon::parse($class->end_time)) }} minutes
                                        </span>
                                    </div>
                                    @if($class->notes)
                                        <p class="text-sm text-gray-500 dark:text-gray-500 mt-2 italic">
                                            <i class="fas fa-sticky-note mr-2"></i>{{ $class->notes }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Subject-wise Schedule Summary -->
            <div class="card">
                <div class="flex items-center justify-between mb-4 md:mb-6">
                    <h3 class="text-base md:text-lg font-medium text-gray-700 dark:text-gray-300">Subject-wise Schedule Summary</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
                    @foreach($subjectSummary as $subject)
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3 md:p-4">
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="font-semibold text-gray-800 dark:text-white text-sm md:text-base truncate">{{ $subject['name'] }}</h4>
                                <span class="px-2 py-1 text-xs bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded-full whitespace-nowrap">
                                    {{ $subject['total_classes'] }} classes/week
                                </span>
                            </div>
                            <div class="space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600 dark:text-gray-400">Teacher:</span>
                                    <span class="text-gray-800 dark:text-white truncate ml-2">{{ $subject['teacher'] }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600 dark:text-gray-400">Total Hours:</span>
                                    <span class="text-gray-800 dark:text-white">{{ $subject['total_hours'] }} hrs/week</span>
                                </div>
                                <div class="mt-3">
                                    <div class="text-xs text-gray-600 dark:text-gray-400 mb-1">Class Days:</div>
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($subject['days'] as $day)
                                            <span class="px-2 py-1 text-xs bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-300 rounded">
                                                {{ substr($day, 0, 3) }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile view toggle
            const tableViewBtn = document.getElementById('tableViewBtn');
            const cardViewBtn = document.getElementById('cardViewBtn');
            const tableView = document.getElementById('tableView');
            const cardView = document.getElementById('cardView');

            if (tableViewBtn && cardViewBtn) {
                tableViewBtn.addEventListener('click', function() {
                    tableView.classList.remove('hidden');
                    cardView.classList.add('hidden');
                    tableViewBtn.classList.remove('bg-gray-600');
                    tableViewBtn.classList.add('bg-blue-600');
                    cardViewBtn.classList.remove('bg-blue-600');
                    cardViewBtn.classList.add('bg-gray-600');
                });

                cardViewBtn.addEventListener('click', function() {
                    tableView.classList.add('hidden');
                    cardView.classList.remove('hidden');
                    cardViewBtn.classList.remove('bg-gray-600');
                    cardViewBtn.classList.add('bg-blue-600');
                    tableViewBtn.classList.remove('bg-blue-600');
                    tableViewBtn.classList.add('bg-gray-600');
                });
            }

            // Print functionality
            const printBtn = document.getElementById('printRoutine');
            if (printBtn) {
                printBtn.addEventListener('click', function() {
                    window.print();
                });
            }

            // Download PDF functionality
            const downloadBtn = document.getElementById('downloadRoutine');
            if (downloadBtn) {
                downloadBtn.addEventListener('click', function() {
                    window.location.href = "{{ route('student.routine.download-pdf') }}";
                });
            }

            // Auto-refresh current time
            function updateCurrentTime() {
                const now = new Date();
                const timeString = now.toLocaleTimeString('en-US', {
                    hour: 'numeric',
                    minute: '2-digit',
                    hour12: true
                });

                const timeElements = document.querySelectorAll('.current-time');
                timeElements.forEach(element => {
                    element.textContent = timeString;
                });
            }

            // Initial time update
            updateCurrentTime();

            // Update time every minute
            setInterval(updateCurrentTime, 60000);

            // Responsive table handling
            function handleResponsiveTable() {
                const table = document.querySelector('table');
                const container = document.querySelector('.overflow-x-auto');

                if (table && container) {
                    if (window.innerWidth < 768) {
                        // Mobile: ensure horizontal scroll
                        container.style.overflowX = 'auto';
                    } else {
                        // Desktop: normal behavior
                        container.style.overflowX = 'auto';
                    }
                }
            }

            // Initial call and resize listener
            handleResponsiveTable();
            window.addEventListener('resize', handleResponsiveTable);
        });
    </script>
@endsection
