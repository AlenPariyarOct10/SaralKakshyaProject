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
        <!-- Attendance Management -->
        <div class="card mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4 md:mb-0">Mark Attendance</h3>

                <div class="flex flex-col md:flex-row gap-4">
                    <div class="relative">
                        <select id="classSelect" class="w-full md:w-48 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                            <option value="">Select Class</option>
                            <option value="math">Mathematics</option>
                            <option value="physics">Physics</option>
                            <option value="cs">Computer Science</option>
                        </select>
                    </div>

                    <div class="relative">
                        <input type="date" id="attendanceDate" class="w-full md:w-48 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-800 dark:border-gray-700 dark:text-white">
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
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">ID</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Notes</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <input type="checkbox" class="student-checkbox h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-800 dark:text-white">Alice Johnson</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">alice.j@example.com</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500 dark:text-gray-400">STU001</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <select class="attendance-status px-2 py-1 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                                <option value="present">Present</option>
                                <option value="absent">Absent</option>
                                <option value="late">Late</option>
                                <option value="excused">Excused</option>
                            </select>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <input type="text" class="px-2 py-1 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-800 dark:border-gray-700 dark:text-white w-full" placeholder="Add notes...">
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <input type="checkbox" class="student-checkbox h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-800 dark:text-white">Bob Smith</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">bob.s@example.com</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500 dark:text-gray-400">STU002</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <select class="attendance-status px-2 py-1 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                                <option value="present">Present</option>
                                <option value="absent">Absent</option>
                                <option value="late">Late</option>
                                <option value="excused">Excused</option>
                            </select>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <input type="text" class="px-2 py-1 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-800 dark:border-gray-700 dark:text-white w-full" placeholder="Add notes...">
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <input type="checkbox" class="student-checkbox h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-800 dark:text-white">Charlie Davis</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">charlie.d@example.com</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500 dark:text-gray-400">STU003</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <select class="attendance-status px-2 py-1 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                                <option value="present">Present</option>
                                <option value="absent">Absent</option>
                                <option value="late">Late</option>
                                <option value="excused">Excused</option>
                            </select>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <input type="text" class="px-2 py-1 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-800 dark:border-gray-700 dark:text-white w-full" placeholder="Add notes...">
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <input type="checkbox" class="student-checkbox h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-800 dark:text-white">Diana Evans</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">diana.e@example.com</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500 dark:text-gray-400">STU004</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <select class="attendance-status px-2 py-1 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                                <option value="present">Present</option>
                                <option value="absent">Absent</option>
                                <option value="late">Late</option>
                                <option value="excused">Excused</option>
                            </select>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <input type="text" class="px-2 py-1 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-800 dark:border-gray-700 dark:text-white w-full" placeholder="Add notes...">
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <input type="checkbox" class="student-checkbox h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-800 dark:text-white">Ethan Foster</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">ethan.f@example.com</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500 dark:text-gray-400">STU005</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <select class="attendance-status px-2 py-1 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                                <option value="present">Present</option>
                                <option value="absent">Absent</option>
                                <option value="late">Late</option>
                                <option value="excused">Excused</option>
                            </select>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <input type="text" class="px-2 py-1 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-800 dark:border-gray-700 dark:text-white w-full" placeholder="Add notes...">
                        </td>
                    </tr>
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

        <!-- Attendance Reports -->
        <div class="card">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Attendance Reports</h3>

                <button id="generateReport" class="btn-primary">
                    Generate Report
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Overall Attendance -->
                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-md">
                    <h4 class="text-sm font-medium text-gray-800 dark:text-white mb-2">Overall Attendance</h4>
                    <div class="flex items-center">
                        <div class="w-16 h-16 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center mr-4">
                            <span class="text-xl font-bold text-blue-600 dark:text-blue-300">92%</span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Class Average</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Last 30 days</p>
                        </div>
                    </div>
                </div>

                <!-- Attendance by Status -->
                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-md">
                    <h4 class="text-sm font-medium text-gray-800 dark:text-white mb-2">Attendance by Status</h4>
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-500 dark:text-gray-400">Present</span>
                            <div class="w-3/4 bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                                <div class="bg-green-500 h-2 rounded-full" style="width: 85%"></div>
                            </div>
                            <span class="text-xs font-medium text-gray-800 dark:text-white">85%</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-500 dark:text-gray-400">Absent</span>
                            <div class="w-3/4 bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                                <div class="bg-red-500 h-2 rounded-full" style="width: 8%"></div>
                            </div>
                            <span class="text-xs font-medium text-gray-800 dark:text-white">8%</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-500 dark:text-gray-400">Late</span>
                            <div class="w-3/4 bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                                <div class="bg-yellow-500 h-2 rounded-full" style="width: 5%"></div>
                            </div>
                            <span class="text-xs font-medium text-gray-800 dark:text-white">5%</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-500 dark:text-gray-400">Excused</span>
                            <div class="w-3/4 bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                                <div class="bg-blue-500 h-2 rounded-full" style="width: 2%"></div>
                            </div>
                            <span class="text-xs font-medium text-gray-800 dark:text-white">2%</span>
                        </div>
                    </div>
                </div>

                <!-- Recent Reports -->
                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-md">
                    <h4 class="text-sm font-medium text-gray-800 dark:text-white mb-2">Recent Reports</h4>
                    <div class="space-y-2">
                        <a href="#" class="block text-xs text-primary-600 hover:underline">
                            <i class="fas fa-file-pdf mr-1"></i> Mathematics - May 2023.pdf
                        </a>
                        <a href="#" class="block text-xs text-primary-600 hover:underline">
                            <i class="fas fa-file-excel mr-1"></i> Physics - April 2023.xlsx
                        </a>
                        <a href="#" class="block text-xs text-primary-600 hover:underline">
                            <i class="fas fa-file-pdf mr-1"></i> Computer Science - March 2023.pdf
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
@section("scripts")
    <script>


        // Select All Checkbox
        const selectAll = document.getElementById('selectAll');
        const studentCheckboxes = document.querySelectorAll('.student-checkbox');

        selectAll.addEventListener('change', () => {
            studentCheckboxes.forEach(checkbox => {
                checkbox.checked = selectAll.checked;
            });
        });

        // Mark All Present Button
        const markAllPresent = document.getElementById('markAllPresent');
        const attendanceStatuses = document.querySelectorAll('.attendance-status');

        markAllPresent.addEventListener('click', () => {
            attendanceStatuses.forEach(select => {
                select.value = 'present';
            });
            studentCheckboxes.forEach(checkbox => {
                checkbox.checked = true;
            });
            selectAll.checked = true;
        });

        // Save Attendance Button
        const saveAttendance = document.getElementById('saveAttendance');

        saveAttendance.addEventListener('click', () => {
            console.log("helo");
            Toast.fire({
                icon: 'success',
                title: 'Success',
            });
        });

        // Generate Report Button
        const generateReport = document.getElementById('generateReport');

        generateReport.addEventListener('click', () => {
            // In a real app, you would generate a report based on the attendance data
            alert('Report generated successfully!');
        });

        // Set today's date as default
        const attendanceDate = document.getElementById('attendanceDate');
        const today = new Date().toISOString().split('T')[0];
        attendanceDate.value = today;
    </script>
@endsection
