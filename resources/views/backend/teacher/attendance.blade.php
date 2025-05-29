@extends("backend.layout.teacher-dashboard-layout")

@section('content')
    <!-- Main Content Area -->
    <main class="scrollable-content p-4 md:p-6">
        <!-- Attendance Management -->
        <div class="card mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4 md:mb-0">Mark Attendance</h3>

                <div class="flex flex-col md:flex-row gap-4">
                    <div class="relative">
                        <select id="subjectSelect" class="w-full md:w-48 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                            <option value="">Select Subject</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" data-batch-id="{{ $subject->batch_id }}">
                                    {{ $subject->name }} ({{ $subject->code }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="relative">
                        <input type="date" id="attendanceDate" class="w-full md:w-48 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                    </div>

                    <button id="loadStudents" class="btn-primary">
                        Load Students
                    </button>
                </div>
            </div>

            <div id="studentsContainer" class="hidden">
                <div class="mb-4 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-md">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="font-medium text-blue-800 dark:text-blue-200" id="selectedSubjectName"></h4>
                            <p class="text-sm text-blue-600 dark:text-blue-300" id="selectedBatchInfo"></p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-blue-600 dark:text-blue-300">Total Students: <span id="totalStudents">0</span></p>
                            <p class="text-sm text-blue-600 dark:text-blue-300">Date: <span id="selectedDate"></span></p>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <input id="selectAll" type="checkbox" class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                    <label for="selectAll" class="ml-2">Student</label>
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Roll Number</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Notes</th>
                        </tr>
                        </thead>
                        <tbody id="studentsTableBody" class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        <!-- Students will be loaded here dynamically -->
                        </tbody>
                    </table>
                </div>

                <div class="mt-6 flex flex-col md:flex-row gap-4 justify-end">
                    <button id="markAllPresent" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors">
                        Mark All Present
                    </button>
                    <button id="saveAttendance" class="btn-primary">
                        Save Attendance
                    </button>
                </div>
            </div>

            <div id="noSubjectSelected" class="text-center py-8 text-gray-500 dark:text-gray-400">
                <p>Please select a subject and date to load students</p>
            </div>
        </div>

        <!-- Attendance History -->
        <div class="card">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Attendance History</h3>
                <div class="flex gap-2">
                    <select id="historySubject" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                        <option value="">All Subjects</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                        @endforeach
                    </select>
                    <button id="loadHistory" class="btn-primary">Load History</button>
                </div>
            </div>

            <div id="attendanceHistory" class="overflow-x-auto">
                <!-- Attendance history will be loaded here -->
                <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                    <p>Select a subject to view attendance history</p>
                </div>
            </div>
        </div>
    </main>
@endsection

@section("scripts")
    <script>
        let currentStudents = [];
        let currentSubjectId = null;
        let currentBatchId = null;

        // Set today's date as default
        const attendanceDate = document.getElementById('attendanceDate');
        const today = new Date().toISOString().split('T')[0];
        attendanceDate.value = today;

        // Load Students Button
        document.getElementById('loadStudents').addEventListener('click', function() {
            const subjectId = document.getElementById('subjectSelect').value;
            const date = document.getElementById('attendanceDate').value;

            if (!subjectId || !date) {
                Toast.fire({
                    icon: 'warning',
                    title: 'Please select both subject and date'
                });
                return;
            }

            loadStudents(subjectId, date);
        });

        // Load students for selected subject
        function loadStudents(subjectId, date) {
            const loadingHtml = `
                <tr>
                    <td colspan="4" class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center">
                            <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-primary-600"></div>
                            <span class="ml-2">Loading students...</span>
                        </div>
                    </td>
                </tr>
            `;
            document.getElementById('studentsTableBody').innerHTML = loadingHtml;
            document.getElementById('studentsContainer').classList.remove('hidden');
            document.getElementById('noSubjectSelected').classList.add('hidden');

            fetch(`/teacher/attendance/students/${subjectId}?date=${date}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        currentStudents = data.students;
                        currentSubjectId = subjectId;
                        currentBatchId = data.subject.batch_id;
                        renderStudents(data.students, data.subject, data.existingAttendance);
                        updateSubjectInfo(data.subject, data.students.length, date);
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: data.message || 'Failed to load students'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Toast.fire({
                        icon: 'error',
                        title: 'Failed to load students'
                    });
                });
        }

        // Render students table
        function renderStudents(students, subject, existingAttendance = {}) {
            const tbody = document.getElementById('studentsTableBody');

            if (students.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                            No students found for this subject
                        </td>
                    </tr>
                `;
                return;
            }

            const studentsHtml = students.map(student => {
                const attendance = existingAttendance[student.id] || {};
                const status = attendance.status || 'present';
                const notes = attendance.remarks || '';

                return `
                    <tr data-student-id="${student.id}">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <input type="checkbox" class="student-checkbox h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded" ${status === 'present' ? 'checked' : ''}>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-800 dark:text-white">${student.full_name}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">${student.email}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500 dark:text-gray-400">${student.roll_number || 'N/A'}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <select class="attendance-status px-2 py-1 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                                <option value="present" ${status === 'present' ? 'selected' : ''}>Present</option>
                                <option value="absent" ${status === 'absent' ? 'selected' : ''}>Absent</option>
                                <option value="late" ${status === 'late' ? 'selected' : ''}>Late</option>
                                <option value="excused" ${status === 'excused' ? 'selected' : ''}>Excused</option>
                            </select>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <input type="text" class="attendance-notes px-2 py-1 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-800 dark:border-gray-700 dark:text-white w-full" placeholder="Add notes..." value="${notes}">
                        </td>
                    </tr>
                `;
            }).join('');

            tbody.innerHTML = studentsHtml;

            // Add event listeners for status changes
            addStatusChangeListeners();
        }

        // Add event listeners for status changes
        function addStatusChangeListeners() {
            document.querySelectorAll('.attendance-status').forEach(select => {
                select.addEventListener('change', function() {
                    const row = this.closest('tr');
                    const checkbox = row.querySelector('.student-checkbox');

                    if (this.value === 'present') {
                        checkbox.checked = true;
                    } else {
                        checkbox.checked = false;
                    }
                });
            });

            document.querySelectorAll('.student-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const row = this.closest('tr');
                    const statusSelect = row.querySelector('.attendance-status');

                    if (this.checked) {
                        statusSelect.value = 'present';
                    } else {
                        statusSelect.value = 'absent';
                    }
                });
            });
        }

        // Update subject info display
        function updateSubjectInfo(subject, studentCount, date) {
            document.getElementById('selectedSubjectName').textContent = `${subject.name} (${subject.code})`;
            document.getElementById('selectedBatchInfo').textContent = `Batch: ${subject.batch.name} | Program: ${subject.program.name}`;
            document.getElementById('totalStudents').textContent = studentCount;
            document.getElementById('selectedDate').textContent = new Date(date).toLocaleDateString();
        }

        // Select All Checkbox
        document.getElementById('selectAll').addEventListener('change', function() {
            const studentCheckboxes = document.querySelectorAll('.student-checkbox');
            const attendanceStatuses = document.querySelectorAll('.attendance-status');

            studentCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });

            attendanceStatuses.forEach(select => {
                select.value = this.checked ? 'present' : 'absent';
            });
        });

        // Mark All Present Button
        document.getElementById('markAllPresent').addEventListener('click', function() {
            const studentCheckboxes = document.querySelectorAll('.student-checkbox');
            const attendanceStatuses = document.querySelectorAll('.attendance-status');

            studentCheckboxes.forEach(checkbox => {
                checkbox.checked = true;
            });

            attendanceStatuses.forEach(select => {
                select.value = 'present';
            });

            document.getElementById('selectAll').checked = true;
        });

        // Save Attendance Button
        document.getElementById('saveAttendance').addEventListener('click', function() {
            if (!currentSubjectId) {
                Toast.fire({
                    icon: 'warning',
                    title: 'Please select a subject first'
                });
                return;
            }

            const attendanceData = [];
            const rows = document.querySelectorAll('#studentsTableBody tr[data-student-id]');

            rows.forEach(row => {
                const studentId = row.getAttribute('data-student-id');
                const status = row.querySelector('.attendance-status').value;
                const notes = row.querySelector('.attendance-notes').value;

                attendanceData.push({
                    student_id: studentId,
                    status: status,
                    remarks: notes
                });
            });

            const data = {
                subject_id: currentSubjectId,
                date: document.getElementById('attendanceDate').value,
                attendance: attendanceData,
                _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            };

            fetch('/teacher/attendance/store', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': data._token
                },
                body: JSON.stringify(data)
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Toast.fire({
                            icon: 'success',
                            title: 'Attendance saved successfully!'
                        });
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: data.message || 'Failed to save attendance'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Toast.fire({
                        icon: 'error',
                        title: 'Failed to save attendance'
                    });
                });
        });

        // Load History Button
        document.getElementById('loadHistory').addEventListener('click', function() {
            const subjectId = document.getElementById('historySubject').value;
            loadAttendanceHistory(subjectId);
        });

        // Load attendance history
        function loadAttendanceHistory(subjectId = '') {
            const historyContainer = document.getElementById('attendanceHistory');

            historyContainer.innerHTML = `
                <div class="text-center py-8">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary-600 mx-auto"></div>
                    <p class="mt-2 text-gray-500">Loading attendance history...</p>
                </div>
            `;

            const url = subjectId ? `/teacher/attendance/history?subject_id=${subjectId}` : '/teacher/attendance/history';

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        renderAttendanceHistory(data.history);
                    } else {
                        historyContainer.innerHTML = `
                            <div class="text-center py-8 text-gray-500">
                                <p>${data.message || 'No attendance history found'}</p>
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    historyContainer.innerHTML = `
                        <div class="text-center py-8 text-red-500">
                            <p>Failed to load attendance history</p>
                        </div>
                    `;
                });
        }

        // Render attendance history
        function renderAttendanceHistory(history) {
            const historyContainer = document.getElementById('attendanceHistory');

            if (history.length === 0) {
                historyContainer.innerHTML = `
                    <div class="text-center py-8 text-gray-500">
                        <p>No attendance records found</p>
                    </div>
                `;
                return;
            }

            const historyHtml = `
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Subject</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total Students</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Present</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Absent</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Attendance Rate</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        ${history.map(record => `
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    ${new Date(record.date).toLocaleDateString()}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    ${record.subject_name}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    ${record.total_students}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600">
                                    ${record.present_count}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600">
                                    ${record.absent_count}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    <div class="flex items-center">
                                        <div class="w-16 bg-gray-200 rounded-full h-2 mr-2">
                                            <div class="bg-green-500 h-2 rounded-full" style="width: ${record.attendance_rate}%"></div>
                                        </div>
                                        <span>${record.attendance_rate}%</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button onclick="editAttendance('${record.date}', ${record.subject_id})" class="text-primary-600 hover:text-primary-900 mr-2">Edit</button>
                                    <button onclick="viewAttendanceDetails('${record.date}', ${record.subject_id})" class="text-blue-600 hover:text-blue-900">View</button>
                                </td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            `;

            historyContainer.innerHTML = historyHtml;
        }

        // Edit attendance function
        function editAttendance(date, subjectId) {
            document.getElementById('subjectSelect').value = subjectId;
            document.getElementById('attendanceDate').value = date;
            loadStudents(subjectId, date);

            // Scroll to top
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        // View attendance details function
        function viewAttendanceDetails(date, subjectId) {
            window.open(`/teacher/attendance/details?date=${date}&subject_id=${subjectId}`, '_blank');
        }

        // Load initial history
        loadAttendanceHistory();
    </script>
@endsection
