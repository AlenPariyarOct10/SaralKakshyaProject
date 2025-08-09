@php use Illuminate\Support\Facades\Auth; @endphp
@extends('backend.layout.student-dashboard-layout')
@php
    $user = Auth::user();
    // Use real data passed from controller
    $attendancePercentage = $attendancePercentage ?? 0;
    $daysPresent = $daysPresent ?? 0;
    $daysAbsent = $daysAbsent ?? 0;
    $daysLate = $daysLate ?? 0;
    $daysExcused = $daysExcused ?? 0;
    $totalClassDays = $totalClassDays ?? 0;
    $currentMonth = isset($startDate) ? $startDate->format('F Y') : \Carbon\Carbon::now()->format('F Y');
@endphp
@section('username', $user->fname . ' ' . $user->lname)
@section("title", "Attendance")

@section('content')
    <div class="scrollable-content p-6 bg-gray-50 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto">
            <!-- Page Title and Actions -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">My Attendance</h1>
                <div class="mt-4 md:mt-0">
                    <a href="{{route('student.attendance.setup.index')}}" class="cursor-pointer px-3 py-2 mr-2 rounded-md bg-primary-500 text-white hover:bg-primary-600">
                        Setup Attendance
                    </a>
                </div>
            </div>

            <!-- Attendance Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Overall Attendance -->
                <div class="card">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-700 dark:text-gray-300">Overall Attendance</h3>
                        <span class="text-xs px-2 py-1 rounded-full bg-opacity-10 {{ $attendancePercentage >= 75 ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }}">
                            {{ $attendancePercentage >= 75 ? 'Good Standing' : 'Needs Improvement' }}
                        </span>
                    </div>
                    <div class="relative pt-1">
                        <div class="overflow-hidden h-6 mb-4 text-xs flex rounded bg-gray-200 dark:bg-gray-700">
                            <div style="width:{{ $attendancePercentage }}%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center {{ $attendancePercentage >= 75 ? 'bg-green-500' : 'bg-red-500' }}">
                                <span class="font-bold">{{ $attendancePercentage }}%</span>
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Total Class Days</p>
                        <p class="text-2xl font-bold text-blue-500" id="totalClassDaysCard">{{ $totalClassDays }}</p>
                    </div>
                </div>

                <!-- Present Days -->
                <div class="card">
                    <div class="text-center">
                        <div class="w-16 h-16 mx-auto mb-4 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                            <i class="fas fa-check text-2xl text-green-500"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-700 dark:text-gray-300 mb-2">Present</h3>
                        <p class="text-3xl font-bold text-green-500" id="daysPresentCard">{{ $daysPresent + $daysLate }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400" id="presentPercentageCard">
                            {{ $totalClassDays > 0 ? round((($daysPresent + $daysLate) / $totalClassDays) * 100, 1) : 0 }}% of total
                        </p>
                        @if($daysLate > 0)
                            <p class="text-xs text-yellow-600 dark:text-yellow-400 mt-1">
                                ({{ $daysLate }} late arrivals)
                            </p>
                        @endif
                    </div>
                </div>

                <!-- Absent Days -->
                <div class="card">
                    <div class="text-center">
                        <div class="w-16 h-16 mx-auto mb-4 bg-red-100 dark:bg-red-900 rounded-full flex items-center justify-center">
                            <i class="fas fa-times text-2xl text-red-500"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-700 dark:text-gray-300 mb-2">Absent</h3>
                        <p class="text-3xl font-bold text-red-500" id="daysAbsentCard">{{ $daysAbsent }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400" id="absentPercentageCard">
                            {{ $totalClassDays > 0 ? round(($daysAbsent / $totalClassDays) * 100, 1) : 0 }}% of total
                        </p>
                        @if($daysExcused > 0)
                            <p class="text-xs text-purple-600 dark:text-purple-400 mt-1">
                                ({{ $daysExcused }} excused)
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Monthly Trend Chart -->
            <div class="card mb-8">
                <h3 class="text-lg font-medium text-gray-700 dark:text-gray-300 mb-4">Monthly Attendance Trend</h3>
                <div class="h-64">
                    <canvas id="monthlyTrendChart"></canvas>
                </div>
            </div>

            <!-- Calendar View -->
            <div class="card mb-8">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-medium text-gray-700 dark:text-gray-300">Attendance Calendar</h3>
                    <div class="flex space-x-4">
                        <button id="prevMonth" class="p-1 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <span id="currentMonth" class="text-sm font-medium">{{ $currentMonth }}</span>
                        <button id="nextMonth" class="p-1 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-7 gap-2 text-center mb-2">
                    <div class="text-xs font-medium text-gray-500">Sun</div>
                    <div class="text-xs font-medium text-gray-500">Mon</div>
                    <div class="text-xs font-medium text-gray-500">Tue</div>
                    <div class="text-xs font-medium text-gray-500">Wed</div>
                    <div class="text-xs font-medium text-gray-500">Thu</div>
                    <div class="text-xs font-medium text-gray-500">Fri</div>
                    <div class="text-xs font-medium text-gray-500">Sat</div>
                </div>

                <div id="calendarGrid" class="grid grid-cols-7 gap-2">
                    <!-- Calendar days will be populated by JavaScript -->
                </div>

                <div class="flex items-center justify-center mt-6 space-x-6 flex-wrap">
                    <div class="flex items-center mb-2">
                        <div class="w-4 h-4 rounded-full bg-green-500 mr-2"></div>
                        <span class="text-xs text-gray-500 dark:text-gray-400">Present</span>
                    </div>
                    <div class="flex items-center mb-2">
                        <div class="w-4 h-4 rounded-full bg-red-500 mr-2"></div>
                        <span class="text-xs text-gray-500 dark:text-gray-400">Absent</span>
                    </div>
                    <div class="flex items-center mb-2">
                        <div class="w-4 h-4 rounded-full bg-blue-500 mr-2"></div>
                        <span class="text-xs text-gray-500 dark:text-gray-400">Holiday</span>
                    </div>
                    <div class="flex items-center mb-2">
                        <div class="w-4 h-4 rounded-full bg-gray-300 dark:bg-gray-600 mr-2"></div>
                        <span class="text-xs text-gray-500 dark:text-gray-400">No Class</span>
                    </div>
                    <div class="flex items-center mb-2">
                        <div class="w-4 h-4 rounded-full bg-orange-500 mr-2"></div>
                        <span class="text-xs text-gray-500 dark:text-gray-400">Auto Absent</span>
                    </div>
                </div>
            </div>

            <!-- Recent Attendance Records -->
            <div class="card">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-700 dark:text-gray-300">Recent Attendance Records</h3>
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        Showing last 15 records
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Time</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Method</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Remarks</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                        @php
                            // Combine actual attendance records with auto-absent records for display
                            $allRecords = collect();

                            // Add actual attendance records
                            foreach($attendanceRecords->take(15) as $record) {
                                $allRecords->push($record);
                            }

                            // Add auto-absent records for class sessions without attendance
                            $attendanceByDate = $attendanceRecords->keyBy(function($item) {
                                return \Carbon\Carbon::parse($item->date)->format('Y-m-d');
                            });

                            foreach($classSessions->take(15) as $session) {
                                $sessionDate = \Carbon\Carbon::parse($session->date)->format('Y-m-d');
                                if (!isset($attendanceByDate[$sessionDate])) {
                                    $autoAbsent = (object) [
                                        'date' => $session->date,
                                        'status' => 'absent',
                                        'attended_at' => null,
                                        'method' => 'auto',
                                        'remarks' => 'Auto-marked absent (no attendance record)',
                                        'is_auto' => true
                                    ];
                                    $allRecords->push($autoAbsent);
                                }
                            }

                            $allRecords = $allRecords->sortByDesc('date')->take(15);
                        @endphp

                        @forelse($allRecords as $record)
                            <tr class="{{ isset($record->is_auto) && $record->is_auto ? 'bg-orange-50 dark:bg-orange-900/20' : '' }}">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    {{ \Carbon\Carbon::parse($record->date)->format('M d, Y') }}
                                    @if(isset($record->is_auto) && $record->is_auto)
                                        <span class="ml-2 text-xs text-orange-600 dark:text-orange-400">(Auto)</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            @if($record->status === 'present') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                                            @elseif($record->status === 'absent')
                                                @if(isset($record->is_auto) && $record->is_auto)
                                                    bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-300
                                                @else
                                                    bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300
                                                @endif
                                            @elseif($record->status === 'late') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300
                                            @elseif($record->status === 'excused') bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300
                                            @else bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300
                                            @endif">
                                            {{ ucfirst($record->status) }}
                                        </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    {{ $record->attended_at ? \Carbon\Carbon::parse($record->attended_at)->format('h:i A') : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    {{ ucfirst($record->method ?? 'manual') }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $record->remarks ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                    No attendance records found.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Pass data from PHP to JavaScript
        const calendarData = @json($calendarData ?? []);
        const monthlyTrendData = @json($monthlyTrend ?? ['months' => [], 'data' => []]);
        let currentDate = new Date();

        // Store current attendance stats
        let currentStats = {
            attendancePercentage: {{ $attendancePercentage }},
            daysPresent: {{ $daysPresent }},
            daysAbsent: {{ $daysAbsent }},
            daysLate: {{ $daysLate }},
            daysExcused: {{ $daysExcused }},
            totalClassDays: {{ $totalClassDays }}
        };

        // Monthly trend chart data
        const monthlyData = {
            labels: monthlyTrendData.months,
            datasets: [{
                label: 'Attendance %',
                data: monthlyTrendData.data,
                borderColor: '#0ea5e9',
                backgroundColor: 'rgba(14, 165, 233, 0.2)',
                tension: 0.3,
                fill: true
            }]
        };

        // Initialize charts
        document.addEventListener('DOMContentLoaded', function() {
            // Monthly trend chart
            const monthlyChart = new Chart(
                document.getElementById('monthlyTrendChart'),
                {
                    type: 'line',
                    data: monthlyData,
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 100,
                                ticks: {
                                    callback: function(value) {
                                        return value + '%';
                                    }
                                }
                            }
                        }
                    }
                }
            );

            // Initialize calendar
            generateCalendar();

            // Calendar navigation
            document.getElementById('prevMonth').addEventListener('click', function() {
                currentDate.setMonth(currentDate.getMonth() - 1);
                loadMonthData();
            });

            document.getElementById('nextMonth').addEventListener('click', function() {
                currentDate.setMonth(currentDate.getMonth() + 1);
                loadMonthData();
            });

            // Period selector
            document.getElementById('periodSelector').addEventListener('change', function() {
                const period = this.value;
                const now = new Date();

                switch(period) {
                    case 'current':
                        currentDate = new Date(now.getFullYear(), now.getMonth(), 1);
                        break;
                    case 'previous':
                        currentDate = new Date(now.getFullYear(), now.getMonth() - 1, 1);
                        break;
                    case 'semester':
                        // You can implement semester logic here
                        currentDate = new Date(now.getFullYear(), now.getMonth(), 1);
                        break;
                }
                loadMonthData();
            });
        });

        // Load month data via AJAX
        function loadMonthData() {
            const month = currentDate.getMonth() + 1;
            const year = currentDate.getFullYear();

            fetch(`{{ route('student.attendance.monthly') }}?month=${month}&year=${year}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('currentMonth').textContent = data.monthName;
                    generateCalendar(data.calendarData, data.daysInMonth, data.firstDayOfWeek);

                    // Update statistics
                    if (data.attendanceAnalysis) {
                        updateAttendanceStats(data.attendanceAnalysis);
                    }
                })
                .catch(error => {
                    console.error('Error loading month data:', error);
                });
        }

        // Update attendance statistics
        function updateAttendanceStats(stats) {
            // Update the cards with new data
            document.getElementById('totalClassDaysCard').textContent = stats.totalClassDays;
            document.getElementById('daysPresentCard').textContent = stats.daysPresent + stats.daysLate;
            document.getElementById('daysAbsentCard').textContent = stats.daysAbsent;

            // Update percentages
            const presentPercentage = stats.totalClassDays > 0 ?
                Math.round(((stats.daysPresent + stats.daysLate) / stats.totalClassDays) * 100 * 10) / 10 : 0;
            const absentPercentage = stats.totalClassDays > 0 ?
                Math.round((stats.daysAbsent / stats.totalClassDays) * 100 * 10) / 10 : 0;

            document.getElementById('presentPercentageCard').textContent = presentPercentage + '% of total';
            document.getElementById('absentPercentageCard').textContent = absentPercentage + '% of total';

            // Update overall attendance percentage bar
            const overallPercentage = stats.attendancePercentage;
            const progressBar = document.querySelector('[style*="width:"]');
            if (progressBar) {
                progressBar.style.width = overallPercentage + '%';
                progressBar.querySelector('span').textContent = overallPercentage + '%';

                // Update color based on percentage
                if (overallPercentage >= 75) {
                    progressBar.className = progressBar.className.replace('bg-red-500', 'bg-green-500');
                } else {
                    progressBar.className = progressBar.className.replace('bg-green-500', 'bg-red-500');
                }
            }

            // Update status badge
            const statusBadge = document.querySelector('[class*="bg-green-100"], [class*="bg-red-100"]');
            if (statusBadge) {
                if (overallPercentage >= 75) {
                    statusBadge.className = 'text-xs px-2 py-1 rounded-full bg-opacity-10 bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300';
                    statusBadge.textContent = 'Good Standing';
                } else {
                    statusBadge.className = 'text-xs px-2 py-1 rounded-full bg-opacity-10 bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300';
                    statusBadge.textContent = 'Needs Improvement';
                }
            }
        }

        // Calendar generation
        function generateCalendar(data = calendarData, daysInMonth = null, firstDayOffset = null) {
            const calendarGrid = document.getElementById('calendarGrid');

            if (!daysInMonth) {
                daysInMonth = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0).getDate();
            }

            if (firstDayOffset === null) {
                firstDayOffset = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1).getDay();
            }

            // Clear calendar
            calendarGrid.innerHTML = '';

            // Add empty cells for days before the first day of month
            for (let i = 0; i < firstDayOffset; i++) {
                const emptyDay = document.createElement('div');
                emptyDay.className = 'h-10';
                calendarGrid.appendChild(emptyDay);
            }

            // Today's date
            const today = new Date();

            // Add days of the month
            for (let day = 1; day <= daysInMonth; day++) {
                const dateObj = new Date(currentDate.getFullYear(), currentDate.getMonth(), day);
                let dayData = data[day] || { status: null, type: 'no_class' };

                // Prevent showing absent/auto_absent for future days
                if (dateObj > today && (dayData.status === 'absent' || dayData.status === 'auto_absent')) {
                    dayData.status = null;
                }

                const dayCell = document.createElement('div');

                let statusClass = '';
                let status = '';

                switch(dayData.status) {
                    case 'present':
                        statusClass = 'bg-green-500 text-white';
                        status = 'Present';
                        break;
                    case 'absent':
                        if (dayData.type === 'auto_absent') {
                            statusClass = 'bg-orange-500 text-white';
                            status = 'Auto Absent';
                        } else {
                            statusClass = 'bg-red-500 text-white';
                            status = 'Absent';
                        }
                        break;
                    case 'late':
                        statusClass = 'bg-yellow-500 text-white';
                        status = 'Late';
                        break;
                    case 'excused':
                        statusClass = 'bg-purple-500 text-white';
                        status = 'Excused';
                        break;
                    case 'holiday':
                        statusClass = 'bg-blue-500 text-white';
                        status = 'Holiday';
                        break;
                    default:
                        if (dayData.hasClass) {
                            statusClass = 'bg-gray-400 text-white border-2 border-dashed border-gray-600';
                            status = 'Class Scheduled';
                        } else {
                            statusClass = 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300';
                            status = 'No Class';
                        }
                }

                dayCell.className = `h-10 flex items-center justify-center rounded-full cursor-pointer ${statusClass} hover:opacity-80 transition-all`;
                dayCell.textContent = day;
                dayCell.setAttribute('data-status', status);

                // Add tooltip behavior
                if (status && status !== 'No Class') {
                    dayCell.addEventListener('mouseenter', function(e) {
                        const tooltip = document.createElement('div');
                        tooltip.className = 'absolute z-10 px-2 py-1 text-xs bg-gray-800 text-white rounded pointer-events-none whitespace-nowrap';

                        let tooltipText = status;
                        if (dayData.notes) {
                            tooltipText += ': ' + dayData.notes;
                        }
                        if (dayData.session_notes) {
                            tooltipText += ' - ' + dayData.session_notes;
                        }
                        if (dayData.start_time && dayData.end_time) {
                            const startTime = new Date(dayData.start_time).toLocaleTimeString('en-US', {hour: '2-digit', minute:'2-digit'});
                            const endTime = new Date(dayData.end_time).toLocaleTimeString('en-US', {hour: '2-digit', minute:'2-digit'});
                            tooltipText += ` (${startTime} - ${endTime})`;
                        }

                        tooltip.textContent = tooltipText;
                        tooltip.style.bottom = '100%';
                        tooltip.style.left = '50%';
                        tooltip.style.transform = 'translateX(-50%)';
                        tooltip.id = 'day-tooltip';
                        this.style.position = 'relative';
                        this.appendChild(tooltip);
                    });

                    dayCell.addEventListener('mouseleave', function() {
                        const tooltip = document.getElementById('day-tooltip');
                        if (tooltip) {
                            tooltip.remove();
                        }
                    });
                }

                calendarGrid.appendChild(dayCell);
            }
        }
    </script>
@endsection
