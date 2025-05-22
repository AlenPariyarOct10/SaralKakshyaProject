@php use Illuminate\Support\Facades\Auth; @endphp
@extends('backend.layout.student-dashboard-layout')
@php $user = Auth::user(); $attendancePercentage = 90;  $daysPresent = 90; $daysAbsent = 90;  $courseAttendance=90; $courseAttendance = [];  $attendanceRecords = []; $totalRecords = "a";  @endphp
@section('username', $user->fname . ' ' . $user->lname)

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
                    <select id="periodSelector" class="bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 rounded-md px-4 py-2 pr-8 focus:outline-none focus:ring-2 focus:ring-primary-500">
                        <option value="current">Current Semester</option>
                        <option value="previous">Previous Semester</option>
                        <option value="all">All Time</option>
                    </select>
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
                    <div class="grid grid-cols-2 gap-4 mt-4">
                        <div class="text-center">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Present</p>
                            <p class="text-2xl font-bold text-green-500">{{ $daysPresent }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">days</p>
                        </div>
                        <div class="text-center">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Absent</p>
                            <p class="text-2xl font-bold text-red-500">{{ $daysAbsent }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">days</p>
                        </div>
                    </div>
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
                        <span id="currentMonth" class="text-sm font-medium">September 2023</span>
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

                <div class="flex items-center justify-center mt-6 space-x-6">
                    <div class="flex items-center">
                        <div class="w-4 h-4 rounded-full bg-green-500 mr-2"></div>
                        <span class="text-xs text-gray-500 dark:text-gray-400">Present</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 rounded-full bg-red-500 mr-2"></div>
                        <span class="text-xs text-gray-500 dark:text-gray-400">Absent</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 rounded-full bg-gray-300 dark:bg-gray-600 mr-2"></div>
                        <span class="text-xs text-gray-500 dark:text-gray-400">No Class</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Sample data for the charts
        const monthlyData = {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'Attendance %',
                data: [85, 90, 78, 95, 88, 92, 91],
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
        });

        // Calendar generation
        function generateCalendar() {
            const calendarGrid = document.getElementById('calendarGrid');
            const daysInMonth = 30; // Simplified for example
            const firstDayOffset = 2; // Tuesday as first day (0 for Sunday, 1 for Monday, etc.)

            // Clear calendar
            calendarGrid.innerHTML = '';

            // Add empty cells for days before the first day of month
            for (let i = 0; i < firstDayOffset; i++) {
                const emptyDay = document.createElement('div');
                emptyDay.className = 'h-10';
                calendarGrid.appendChild(emptyDay);
            }

            // Sample attendance data (1: present, 0: absent, null: no class)
            const attendanceData = {
                1: 1, 2: 1, 3: 0, 4: 1, 5: null,
                8: 1, 9: 1, 10: 1, 11: 1, 12: null,
                15: 0, 16: 1, 17: 1, 18: 1, 19: null,
                22: 1, 23: 0, 24: 1, 25: 1, 26: null,
                29: 1, 30: 1
            };

            // Add days of the month
            for (let day = 1; day <= daysInMonth; day++) {
                const dayCell = document.createElement('div');

                // Determine attendance status for this day
                let status = '';
                let statusClass = '';

                if (day in attendanceData) {
                    if (attendanceData[day] === 1) {
                        status = 'Present';
                        statusClass = 'bg-green-500 text-white';
                    } else if (attendanceData[day] === 0) {
                        status = 'Absent';
                        statusClass = 'bg-red-500 text-white';
                    } else {
                        status = 'No Class';
                        statusClass = 'bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-300';
                    }
                } else {
                    statusClass = 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300';
                }

                dayCell.className = `h-10 flex items-center justify-center rounded-full cursor-pointer ${statusClass} hover:opacity-80 transition-all`;
                dayCell.textContent = day;
                dayCell.setAttribute('data-status', status);

                // Add tooltip behavior
                dayCell.addEventListener('mouseenter', function(e) {
                    if (status) {
                        const tooltip = document.createElement('div');
                        tooltip.className = 'absolute z-10 px-2 py-1 text-xs bg-gray-800 text-white rounded pointer-events-none';
                        tooltip.textContent = status;
                        tooltip.style.bottom = '100%';
                        tooltip.style.left = '50%';
                        tooltip.style.transform = 'translateX(-50%)';
                        tooltip.id = 'day-tooltip';
                        this.style.position = 'relative';
                        this.appendChild(tooltip);
                    }
                });

                dayCell.addEventListener('mouseleave', function() {
                    const tooltip = document.getElementById('day-tooltip');
                    if (tooltip) {
                        tooltip.remove();
                    }
                });

                calendarGrid.appendChild(dayCell);
            }
        }
    </script>
@endsection
