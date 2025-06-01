@extends("backend.layout.teacher-dashboard-layout")

@section('content')
    <!-- Main Content Area -->
    <main class="scrollable-content p-4 md:p-6">
        <!-- Student Viewer -->
        <div class="card mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4 md:mb-0">View Students Attendance</h3>

                <div class="flex flex-col md:flex-row gap-4">
                    <div class="relative">
                        <input type="date" id="viewDate" class="w-full md:w-48 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                    </div>

                    <button id="loadStudents" class="btn-primary">
                        View Attendance
                    </button>
                </div>
            </div>

            <div id="studentsContainer" class="hidden">
                <div class="mb-4 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-md">
                    <div class="flex items-center justify-between">
                        <div class="text-right">
                            <p class="text-sm text-blue-600 dark:text-blue-300">Total Records: <span id="totalStudents">0</span></p>
                            <p class="text-sm text-blue-600 dark:text-blue-300">Date: <span id="selectedDate"></span></p>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Student ID</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Method</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Attended At</th>
                        </tr>
                        </thead>
                        <tbody id="studentsTableBody" class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        <!-- Attendance records will be loaded here dynamically -->
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="noDateSelected" class="text-center py-8 text-gray-500 dark:text-gray-400">
                <p>Please select a date to view attendance records</p>
            </div>
        </div>
    </main>
@endsection

@section("scripts")
    <script>
        // Set today's date as default
        const viewDate = document.getElementById('viewDate');
        const today = new Date().toISOString().split('T')[0];
        viewDate.value = today;

        // Load Students Button
        document.getElementById('loadStudents').addEventListener('click', function() {
            const date = document.getElementById('viewDate').value;

            if (!date) {
                Toast.fire({
                    icon: 'warning',
                    title: 'Please select a date'
                });
                return;
            }


            loadAttendanceRecords(date);
        });

        // Load attendance records for selected date
        function loadAttendanceRecords(date) {
            const loadingHtml = `
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center">
                            <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-primary-600"></div>
                            <span class="ml-2">Loading attendance records...</span>
                        </div>
                    </td>
                </tr>
            `;
            document.getElementById('studentsTableBody').innerHTML = loadingHtml;
            document.getElementById('studentsContainer').classList.remove('hidden');
            document.getElementById('noDateSelected').classList.add('hidden');

            fetch(`/teacher/attendance/students/`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Filter attendance records by selected date
                        const filteredAttendance = data.attendances.filter(attendance => {
                            return attendance.date === date;
                        });

                        console.log(filteredAttendance);

                        renderAttendanceRecords(filteredAttendance, date);
                        document.getElementById('totalStudents').textContent = filteredAttendance.length;
                        document.getElementById('selectedDate').textContent = new Date(date).toLocaleDateString();
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: data.message || 'Failed to load attendance records'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Toast.fire({
                        icon: 'error',
                        title: 'Failed to load attendance records'
                    });
                });
        }

        // Render attendance records table
        function renderAttendanceRecords(attendanceRecords, date) {
            const tbody = document.getElementById('studentsTableBody');

            if (attendanceRecords.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            No attendance records found for this date
                        </td>
                    </tr>
                `;
                return;
            }

            const attendanceHtml = attendanceRecords.map(record => {
                return `
                    <tr data-attendance-id="${record.id}">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-800 dark:text-white">${record.attendee_id}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500 dark:text-gray-400">${record.date}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ${getStatusColor(record.status)}">
                                ${record.status || 'N/A'}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500 dark:text-gray-400">${record.method || 'N/A'}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500 dark:text-gray-400">${formatDateTime(record.attended_at)}</div>
                        </td>
                    </tr>
                `;
            }).join('');

            tbody.innerHTML = attendanceHtml;
        }

        // Helper function to get status color classes
        function getStatusColor(status) {
            switch(status) {
                case 'present':
                    return 'bg-green-100 text-green-800';
                case 'absent':
                    return 'bg-red-100 text-red-800';
                case 'late':
                    return 'bg-yellow-100 text-yellow-800';
                case 'excused':
                    return 'bg-blue-100 text-blue-800';
                default:
                    return 'bg-gray-100 text-gray-800';
            }
        }

        // Helper function to format datetime
        function formatDateTime(dateTimeString) {
            if (!dateTimeString) return 'N/A';
            const date = new Date(dateTimeString);
            return date.toLocaleString();
        }
    </script>
@endsection
