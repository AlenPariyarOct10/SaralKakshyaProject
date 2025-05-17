@php use Illuminate\Support\Facades\Auth; @endphp
@extends("backend.layout.admin-dashboard-layout")

@php
    $user = Auth::user();
@endphp

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

@push("styles")
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                            950: '#082f49',
                        },
                    }
                }
            }
        }
    </script>
    <style type="text/tailwindcss">
        @layer utilities {
            .btn-primary {
                @apply px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-colors duration-200 font-medium text-sm;
            }
            .btn-secondary {
                @apply px-4 py-2 bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-200 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300 dark:focus:ring-gray-600 focus:ring-offset-2 transition-colors duration-200 font-medium text-sm;
            }
            .btn-danger {
                @apply px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors duration-200 font-medium text-sm;
            }
            .btn-success {
                @apply px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors duration-200 font-medium text-sm;
            }
            .card {
                @apply bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden;
            }
            .form-input {
                @apply w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:text-white text-sm;
            }
            .form-select {
                @apply w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:text-white text-sm bg-white dark:bg-gray-700 pr-10;
            }
            .form-label {
                @apply block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1;
            }
            .table-header {
                @apply px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider;
            }
            .table-cell {
                @apply px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200;
            }
        }
        .border-red-500 {
            border-color: #ef4444 !important;
        }
    </style>
@endpush

@section("title")
    Manage Class Routines
@endsection

@section('content')
    <main class="scrollable-content p-4 md:p-6">
        <!-- Page Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Class Routine Management</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Create and manage class schedules</p>
        </div>

        <!-- Action Bar -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
            <!-- Search & Filter -->
            <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                <!-- Search -->
                <div class="relative w-full max-w-md">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input
                        type="text"
                        id="searchInput"
                        placeholder="Search routines..."
                        class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                    >
                </div>

                <!-- Department Filter -->
                <div class="relative w-full max-w-md">
                    <select
                        id="departmentFilter"
                        class="form-select"
                    >
                        <option value="">All Departments</option>
                    </select>
                </div>
            </div>

            <!-- Add Routine Button -->
            <div class="flex justify-end">
                <button
                    id="addRoutineBtn"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition shadow-sm">
                    <i class="fas fa-plus mr-2"></i> New Routine
                </button>
            </div>
        </div>

        <!-- Routine Form Card (Hidden by default) -->
        <div id="routineFormCard" class="card mb-6 hidden">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white" id="formTitle">New Class Routine</h2>
                    <button id="closeFormBtn" class="text-gray-400 hover:text-gray-500">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form id="routineForm">
                    <input type="hidden" id="routineId" value="">

                    <!-- Department and Subject Teacher Selection -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="departmentId" class="form-label">Department <span class="text-red-500">*</span></label>
                            <select id="departmentId" name="departmentId" class="form-select" required>
                                <option value="">Select Department</option>
                            </select>
                            <div id="departmentIdError" class="text-red-500 text-xs mt-1 hidden"></div>
                        </div>

                        <div>
                            <label for="mappingId" class="form-label">Teacher-Subject <span class="text-red-500">*</span></label>
                            <select id="mappingId" name="mappingId" class="form-select" required>
                                <option value="">Select Teacher-Subject</option>
                            </select>
                            <div id="mappingIdError" class="text-red-500 text-xs mt-1 hidden"></div>
                        </div>
                    </div>
                    <!-- Days Table -->
                    <div class="mb-4">
                        <label class="form-label">Schedule by Day <span class="text-red-500">*</span></label>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Day</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Start Time</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">End Time</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Notes</th>
                                </tr>
                                </thead>
                                <tbody id="daysTableBody" class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                <!-- Days will be populated here -->
                                </tbody>
                            </table>
                        </div>
                        <div id="daysError" class="text-red-500 text-xs mt-1 hidden"></div>
                    </div>

                    <div class="flex justify-end gap-2">
                        <button type="button" id="cancelBtn" class="btn-secondary">Cancel</button>
                        <button type="submit" id="saveBtn" class="btn-primary">Save Routine</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Routines Table -->
        <div class="card">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th scope="col" class="table-header">Department</th>
                        <th scope="col" class="table-header">Teacher</th>
                        <th scope="col" class="table-header">Subject</th>
                        <th scope="col" class="table-header">Day</th>
                        <th scope="col" class="table-header">Start Time</th>
                        <th scope="col" class="table-header">End Time</th>
                        <th scope="col" class="table-header">Notes</th>
                        <th scope="col" class="table-header">Actions</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700" id="routinesTableBody">
                    <!-- Table content will be loaded via JavaScript -->
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 bg-white dark:bg-gray-800 border-t dark:border-gray-700 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-sm text-gray-500 dark:text-gray-400" id="paginationInfo">
                    Showing 0-0 of 0 routines
                </div>
                <div class="flex items-center space-x-1" id="paginationControls">
                    <!-- Pagination controls will be loaded via JavaScript -->
                </div>
            </div>
        </div>
    </main>
@endsection

@section("scripts")
    <script>
        // Global variables
        let currentPage = 1;
        let totalPages = 1;
        let departments = [];
        let mappings = [];
        let routines = [];
        let isEditMode = false;
        let itemsPerPage = 10;
        let isLoading = false;

        // Days of week
        const daysOfWeek = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

        // DOM elements
        const searchInput = document.getElementById('searchInput');
        const departmentFilter = document.getElementById('departmentFilter');
        const routineFormCard = document.getElementById('routineFormCard');
        const routineForm = document.getElementById('routineForm');
        const addRoutineBtn = document.getElementById('addRoutineBtn');
        const closeFormBtn = document.getElementById('closeFormBtn');
        const cancelBtn = document.getElementById('cancelBtn');
        const formTitle = document.getElementById('formTitle');
        const saveBtn = document.getElementById('saveBtn');
        const routinesTableBody = document.getElementById('routinesTableBody');
        const paginationInfo = document.getElementById('paginationInfo');
        const paginationControls = document.getElementById('paginationControls');

        // Form fields
        const routineId = document.getElementById('routineId');
        const departmentId = document.getElementById('departmentId');
        const mappingId = document.getElementById('mappingId');
        const daysTableBody = document.getElementById('daysTableBody');
        const daysError = document.getElementById('daysError');

        // Error elements
        const departmentIdError = document.getElementById('departmentIdError');
        const mappingIdError = document.getElementById('mappingIdError');

        // Initialize the page
        document.addEventListener('DOMContentLoaded', function() {
            loadDepartments();
            loadRoutines();
            setupEventListeners();
        });

        // Populate days table
        function populateDaysTable(availableSlots = []) {
            daysTableBody.innerHTML = '';

            daysOfWeek.forEach(day => {
                // Find if this day has available slots
                const daySlot = availableSlots.find(slot => slot.day_of_week === day.toLowerCase());

                if(daySlot) {
                    const row = document.createElement('tr');
                    row.className = 'day-row';
                    row.dataset.day = day;

                    row.innerHTML = `
                        <td class="px-4 py-2 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                            ${day}
                        </td>
                        <td class="px-4 py-2 whitespace-nowrap">
                            <input type="time" name="startTimes[]" data-day="${day}"
                                class="day-start-time form-input"
                                value="${daySlot ? formatTimeForInput(daySlot.start_time) : ''}"
                                min="${formatTimeForInput(daySlot.start_time)}"
                                max="${formatTimeForInput(daySlot.end_time)}"
                                onchange="validateTimeSlot(this)">
                            <div class="time-error text-red-500 text-xs mt-1 hidden"></div>
                        </td>
                        <td class="px-4 py-2 whitespace-nowrap">
                            <input type="time" name="endTimes[]" data-day="${day}"
                                class="day-end-time form-input"
                                value="${daySlot ? formatTimeForInput(daySlot.end_time) : ''}"
                                min="${formatTimeForInput(daySlot.start_time)}"
                                max="${formatTimeForInput(daySlot.end_time)}"
                                onchange="validateTimeSlot(this)">
                            <div class="time-error text-red-500 text-xs mt-1 hidden"></div>
                        </td>
                        <td class="px-4 py-2 whitespace-nowrap">
                            <input type="text" name="dayNotes[]" data-day="${day}"
                                class="day-note form-input"
                                placeholder="Notes for ${day}"
                                value="">
                        </td>
                    `;

                    daysTableBody.appendChild(row);
                }
            });
        }

        function validateTimeSlot(input) {
            const dayRow = input.closest('.day-row');
            const day = dayRow.dataset.day;
            const startTimeInput = dayRow.querySelector('.day-start-time');
            const endTimeInput = dayRow.querySelector('.day-end-time');
            const errorElements = dayRow.querySelectorAll('.time-error');

            // Reset error states
            errorElements.forEach(el => {
                el.textContent = '';
                el.classList.add('hidden');
            });
            input.classList.remove('border-red-500');

            const minTime = input.min;
            const maxTime = input.max;
            const selectedTime = input.value;

            // Check if time is outside available slot
            if (selectedTime && (selectedTime < minTime || selectedTime > maxTime)) {
                input.classList.add('border-red-500');
                const errorElement = input.nextElementSibling;
                errorElement.textContent = `Time must be between ${formatTimeForDisplay(minTime)} and ${formatTimeForDisplay(maxTime)}`;
                errorElement.classList.remove('hidden');
                return false;
            }

            // Check if end time is before start time
            if (startTimeInput.value && endTimeInput.value && startTimeInput.value >= endTimeInput.value) {
                endTimeInput.classList.add('border-red-500');
                const errorElement = endTimeInput.nextElementSibling;
                errorElement.textContent = 'End time must be after start time';
                errorElement.classList.remove('hidden');
                return false;
            }

            return true;
        }
        window.validateTimeSlot = validateTimeSlot;

        // Helper to format time for input field
        function formatTimeForInput(timeString) {
            if (!timeString) return '';
            // If already in HH:MM format
            if (timeString.match(/^\d{2}:\d{2}$/)) return timeString;

            // Parse other formats
            const time = new Date(`1970-01-01T${timeString}`);
            if (isNaN(time)) return '';

            return time.toLocaleTimeString('en-US', {
                hour12: false,
                hour: '2-digit',
                minute: '2-digit'
            }).slice(0, 5);
        }

        // Helper to format time for display
        function formatTimeForDisplay(timeString) {
            if (!timeString) return '';
            const time = new Date(`1970-01-01T${timeString}`);
            if (isNaN(time)) return timeString;

            return time.toLocaleTimeString('en-US', {
                hour: 'numeric',
                minute: '2-digit',
                hour12: true
            });
        }

        // Event listeners setup
        function setupEventListeners() {
            addRoutineBtn.addEventListener('click', showAddForm);
            closeFormBtn.addEventListener('click', hideForm);
            cancelBtn.addEventListener('click', hideForm);
            departmentId.addEventListener('change', loadMappingsByDepartment);
            routineForm.addEventListener('submit', handleFormSubmit);
            searchInput.addEventListener('input', debounce(loadRoutines, 300));
            departmentFilter.addEventListener('change', loadRoutines);
            mappingId.addEventListener('change', loadTiming);
            mappingId.addEventListener('change', loadDays);
        }

        function loadDays() {
            const selectedMappingId = mappingId.value;
            if (!selectedMappingId) return;

            // Show loading indicator
            Swal.fire({
                title: 'Loading...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Fetch routines filtered by the selected mapping ID
            fetch(`/admin/routines?subject_teacher_mapping_id=${selectedMappingId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log("data", data);
                    Swal.close(); // Close loading indicator

                    if (data.status === 'success') {
                        if (data.data && data.data.length > 0) {
                            // Extract unique days already scheduled for this mapping
                            const scheduledDays = [...new Set(data.data.map(routine => routine.day))];
                            console.log("Scheduled days for this mapping:", scheduledDays);

                            // Disable these days in the date picker
                            disableDaysInDatePicker(scheduledDays);

                            // Show warning if current selection is in scheduled days
                            checkCurrentSelection(scheduledDays);
                        } else {
                            console.log("No routines found for this mapping");
                            // Enable all days if no schedules exist
                            enableAllDays();
                        }
                    } else {
                        throw new Error(data.message || 'Failed to fetch routines');
                    }
                })
                .catch(error => {
                    Swal.close(); // Close loading indicator
                    console.error('Error loading routine days:', error);
                    showSweetAlert('Error', 'Failed to load scheduled days', 'error');
                });
        }

        // Disable specific days in date picker
        function disableDaysInDatePicker(scheduledDays) {
            // Assuming you're using flatpickr (adjust for other date pickers)
            if (window.datePicker) {
                window.datePicker.set('disable', [
                    function(date) {
                        // Convert date to day name (e.g., "Monday")
                        const dayName = date.toLocaleDateString('en-US', { weekday: 'long' });
                        return scheduledDays.includes(dayName);
                    }
                ]);
            }

            // For jQuery UI Datepicker
            if (typeof $ !== 'undefined' && $.fn.datepicker) {
                $('#day-picker').datepicker('option', 'beforeShowDay', function(date) {
                    const dayName = date.toLocaleDateString('en-US', { weekday: 'long' });
                    const isScheduled = scheduledDays.includes(dayName);
                    return [!isScheduled, isScheduled ? 'disabled-day' : ''];
                });
            }
        }

        // Enable all days in date picker
        function enableAllDays() {
            if (window.datePicker) {
                window.datePicker.set('disable', []);
            }

            if (typeof $ !== 'undefined' && $.fn.datepicker) {
                $('#day-picker').datepicker('option', 'beforeShowDay', function(date) {
                    return [true, ''];
                });
            }
        }

        // Check if current selection is in scheduled days
        function checkCurrentSelection(scheduledDays) {
            const daySelect = document.getElementById('day-select');
            if (daySelect && daySelect.value && scheduledDays.includes(daySelect.value)) {
                showSweetAlert(
                    'Warning',
                    'This day is already scheduled for the selected teacher/subject combination',
                    'warning'
                );
            }
        }

        function showSweetAlert(title, message, icon) {
            Swal.fire({
                title: title,
                text: message,
                icon: icon,
                confirmButtonText: 'OK'
            });
        }

        // Load timing based on selected mapping
        function loadTiming() {
            const selectedMappingId = mappingId.value;
            if (!selectedMappingId) {
                populateDaysTable([]); // Clear table if no mapping selected
                return;
            }

            fetch(`/admin/mapping/${selectedMappingId}/timing`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success' && data.available_slots) {
                        populateDaysTable(data.available_slots);
                    } else {
                        populateDaysTable([]);
                        console.log("No available time slots found");
                    }
                })
                .catch(error => {
                    console.error('Error loading timing:', error);
                    populateDaysTable([]);
                });
        }

        // Load mappings based on selected department
        async function loadMappingsByDepartment() {
            const deptId = departmentId.value;
            mappingId.innerHTML = '<option value="">Loading...</option>';
            try {
                const response = await fetch(`/admin/mappings?department_id=${deptId}`);
                const data = await response.json();
                if (data.status === 'success') {
                    mappings = data.data;
                    populateSelect(mappingId, mappings.map(m => ({
                        id: m.id,
                        name: `${m.teacher.name} - ${m.subject.name}`
                    })), 'Select Teacher-Subject');
                }
            } catch (error) {
                console.error("Error loading mappings:", error);
            }
        }


        // Show add form
        function showAddForm() {
            resetForm();
            isEditMode = false;
            formTitle.textContent = 'New Class Routine';
            routineFormCard.classList.remove('hidden');
        }

        function updateTable(routines) {
            routinesTableBody.innerHTML = '';
            if (!routines.length) {
                routinesTableBody.innerHTML = '<tr><td colspan="8" class="text-center py-4 text-gray-500">No routines found.</td></tr>';
                return;
            }

            routines.forEach(routine => {
                const row = document.createElement('tr');
                row.innerHTML = `
                <td class="table-cell">${routine.department.name}</td>
                <td class="table-cell">${routine.teacher.name}</td>
                <td class="table-cell">${routine.subject.name}</td>
                <td class="table-cell">${routine.day}</td>
                <td class="table-cell">${formatTimeForDisplay(routine.start_time)}</td>
                <td class="table-cell">${formatTimeForDisplay(routine.end_time)}</td>
                <td class="table-cell">${routine.notes || '-'}</td>
                <td class="table-cell">
                    <button class="btn-sm btn-primary mr-2" onclick="editRoutine(${routine.id})"><i class="fas fa-edit"></i></button>
                    <button class="btn-sm btn-danger" onclick="deleteRoutine(${routine.id})"><i class="fas fa-trash"></i></button>
                </td>
            `;
                routinesTableBody.appendChild(row);
            });
        }

        function updatePagination(meta) {
            paginationInfo.textContent = `Showing ${meta.from ?? 0}-${meta.to ?? 0} of ${meta.total} routines`;
            paginationControls.innerHTML = '';

            for (let i = 1; i <= meta.last_page; i++) {
                const btn = document.createElement('button');
                btn.textContent = i;
                btn.className = `px-3 py-1 rounded ${i === meta.current_page ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-800'}`;
                btn.addEventListener('click', () => {
                    currentPage = i;
                    loadRoutines();
                });
                paginationControls.appendChild(btn);
            }
        }

        // Show edit form
        function showEditForm(id) {
            resetForm();
            isEditMode = true;
            formTitle.textContent = 'Edit Class Routine';
            saveBtn.textContent = 'Update Routine';

            const routine = routines.find(r => r.id === id);
            if (!routine) return;

            routineId.value = routine.id;
            departmentId.value = routine.department_id;

            // Load mappings for the department and then set the mapping
            loadMappingsByDepartment();
            setTimeout(() => {
                mappingId.value = routine.mapping_id;
                // Load existing schedule days
                if (routine.days) {
                    populateDaysTable(routine.days);
                }
            }, 300);

            routineFormCard.classList.remove('hidden');
            routineFormCard.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }

        // Hide form
        function hideForm() {
            routineFormCard.classList.add('hidden');
        }

        // Reset form
        function resetForm() {
            routineForm.reset();
            routineId.value = '';
            departmentIdError.classList.add('hidden');
            mappingIdError.classList.add('hidden');
            daysError.classList.add('hidden');
            daysTableBody.innerHTML = '';
        }

        // Hide all error messages
        function hideAllErrors() {
            departmentIdError.classList.add('hidden');
            mappingIdError.classList.add('hidden');
            daysError.classList.add('hidden');

            // Clear all time errors
            document.querySelectorAll('.time-error').forEach(el => {
                el.textContent = '';
                el.classList.add('hidden');
            });

            // Remove red borders
            document.querySelectorAll('.border-red-500').forEach(el => {
                el.classList.remove('border-red-500');
            });
        }

        // Show validation errors
        function showValidationErrors(errors) {
            hideAllErrors();

            if (errors.departmentId) {
                departmentIdError.textContent = errors.departmentId;
                departmentIdError.classList.remove('hidden');
            }

            if (errors.mappingId) {
                mappingIdError.textContent = errors.mappingId;
                mappingIdError.classList.remove('hidden');
            }

            if (errors.days) {
                daysError.textContent = errors.days;
                daysError.classList.remove('hidden');
            }

            // Handle day-specific errors
            Object.keys(errors).forEach(key => {
                if (key.startsWith('day_')) {
                    const day = key.replace('day_', '');
                    const dayRow = document.querySelector(`.day-row[data-day="${day}"]`);
                    if (dayRow) {
                        const errorElement = dayRow.querySelector('.time-error');
                        if (errorElement) {
                            errorElement.textContent = errors[key];
                            errorElement.classList.remove('hidden');
                        }
                    }
                }
            });
        }

        // Handle form submission
        async function handleFormSubmit(e) {
            e.preventDefault();

            const payload = {
                id: routineId.value,
                department_id: departmentId.value,
                mapping_id: mappingId.value,
                days: [],
            };

            let isValid = true;

            document.querySelectorAll('.day-row').forEach(row => {
                const day = row.dataset.day;
                const start = row.querySelector('.day-start-time').value;
                const end = row.querySelector('.day-end-time').value;
                const note = row.querySelector('.day-note').value;

                if (start && end) {
                    payload.days.push({
                        day, start_time: start, end_time: end, notes: note
                    });
                }
            });

            if (!payload.department_id) {
                departmentIdError.textContent = 'Department is required.';
                departmentIdError.classList.remove('hidden');
                isValid = false;
            }

            if (!payload.mapping_id) {
                mappingIdError.textContent = 'Teacher-Subject is required.';
                mappingIdError.classList.remove('hidden');
                isValid = false;
            }

            if (payload.days.length === 0) {
                daysError.textContent = 'At least one valid day schedule is required.';
                daysError.classList.remove('hidden');
                isValid = false;
            }

            if (!isValid) return;

            try {
                const url = isEditMode ? `/admin/routines/${payload.id}` : `/admin/routines`;
                const method = isEditMode ? 'PUT' : 'POST';

                const response = await fetch(url, {
                    method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(payload)
                });

                const data = await response.json();

                if (data.status === 'success') {
                    showSweetAlert('Success', data.message, 'success');
                    hideForm();
                    loadRoutines();
                } else {
                    showSweetAlert('Error', data.message || 'Something went wrong', 'error');
                }
            } catch (error) {
                console.error("Routine save error:", error);
                showSweetAlert('Error', 'Failed to save routine', 'error');
            }
        }


        // Create a new routine
        async function createRoutine(data) {
            try {
                saveBtn.disabled = true;
                saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Saving...';

                const response = await fetch('/admin/routine-planner', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();
                console.log('API Response:', result); // Debug log

                if (!response.ok) {
                    throw new Error(result.message || 'Failed to create routine');
                }

                if (result.status === 'success') {
                    hideForm();
                    loadRoutines();
                    Toast.fire({
                        icon: 'success',
                        title: 'Routine created successfully'
                    });
                } else {
                    if (result.errors) {
                        showValidationErrors(result.errors);
                    }
                    throw new Error(result.message || 'Failed to create routine');
                }
            } catch (error) {
                console.error('Error creating routine:', error);
                Toast.fire({
                    icon: 'error',
                    title: error.message || 'An error occurred while creating routine'
                });
            } finally {
                saveBtn.disabled = false;
                saveBtn.innerHTML = 'Save Routine';
            }
        }

        // Update an existing routine
        async function updateRoutine(id, data) {
            saveBtn.disabled = true;
            saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Updating...';

            try {
                const response = await fetch(`/admin/routines/${id}`, {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (result.status === 'success') {
                    hideForm();
                    loadRoutines();
                    Toast.fire({
                        icon: 'success',
                        title: 'Routine updated successfully'
                    });
                } else {
                    if (result.errors) {
                        showValidationErrors(result.errors);
                    }
                    Toast.fire({
                        icon: 'error',
                        title: result.message || 'Failed to update routine'
                    });
                }
            } catch (error) {
                Toast.fire({
                    icon: 'error',
                    title: 'An error occurred'
                });
            } finally {
                saveBtn.disabled = false;
                saveBtn.innerHTML = 'Update Routine';
            }
        }

        // Delete a routine
        function confirmDelete(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'This action cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteRoutine(id);
                }
            });
        }

        async function deleteRoutine(id) {
            const confirmed = await Swal.fire({
                title: 'Are you sure?',
                text: 'This will permanently delete the routine.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e3342f',
                confirmButtonText: 'Yes, delete it!'
            });

            if (!confirmed.isConfirmed) return;

            try {
                const response = await fetch(`/admin/routines/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                const data = await response.json();
                if (data.status === 'success') {
                    showSweetAlert('Deleted!', data.message, 'success');
                    loadRoutines();
                } else {
                    showSweetAlert('Error', data.message || 'Failed to delete', 'error');
                }
            } catch (error) {
                console.error("Error deleting routine:", error);
            }
        }

        // Load departments
        async function loadDepartments() {
            try {
                const response = await fetch('/admin/department/getAllDepartments');
                const data = await response.json();
                if (data) {

                    departments = data;
                    console.log(departments);
                    populateSelect(departmentId, departments, 'Select Department');
                    populateSelect(departmentFilter, [{ id: '', name: 'All Departments' }, ...departments]);
                }
            } catch (error) {
                console.error("Error loading departments:", error);
            }
        }

        // Render department options
        function renderDepartmentOptions() {
            const options = '<option value="">Select Department</option>' +
                departments.map(dept => `<option value="${dept.id}">${dept.name}</option>`).join('');

            departmentId.innerHTML = options;
            departmentFilter.innerHTML = '<option value="">All Departments</option>' +
                departments.map(dept => `<option value="${dept.id}">${dept.name}</option>`).join('');
        }

        // Load routines
        async function loadRoutines() {
            if (isLoading) return;
            isLoading = true;

            const query = new URLSearchParams({
                page: currentPage,
                search: searchInput.value,
                department: departmentFilter.value,
            });

            try {
                const response = await fetch(`/admin/routines?${query.toString()}`);
                const data = await response.json();
                if (data.status === 'success') {
                    routines = data.data;
                    totalPages = data.meta.last_page;
                    currentPage = data.meta.current_page;
                    updateTable(data.data);
                    updatePagination(data.meta);
                }
            } catch (error) {
                console.error("Error loading routines:", error);
            } finally {
                isLoading = false;
            }
        }

        // Render routines table
        function renderRoutines() {
            if (!Array.isArray(routines) || routines.length === 0) {
                routinesTableBody.innerHTML = `
            <tr>
                <td colspan="8" class="table-cell text-center py-8 text-gray-500 dark:text-gray-400">
                    No routines found
                </td>
            </tr>
        `;
                return;
            }

            let html = '';

            // Group routines by mapping_id
            const groupedRoutines = routines.reduce((groups, routine) => {
                const key = routine?.mapping_id || 'unknown';
                if (!groups[key]) {
                    groups[key] = {
                        department: routine?.department || null,
                        mapping: routine?.mapping || null,
                        schedules: []
                    };
                }
                groups[key].schedules.push(routine);
                return groups;
            }, {});

            // Process each group
            Object.values(groupedRoutines).forEach(group => {
                if (!group || !Array.isArray(group.schedules) || group.schedules.length === 0) return;
                console.log("group", group);
                const departmentName = group.department?.name || 'Unknown Department';
                const teacher = group.mapping?.teacher || {};
                const teacherName = `${teacher.fname || ''} ${teacher.lname || ''}`.trim() || 'Unknown Teacher';
                const subjectName = group.mapping?.subject?.name || 'Unknown Subject';

                group.schedules.forEach(schedule => {
                    if (!schedule) return;

                    const days = Array.isArray(schedule.days)
                        ? schedule.days
                        : [{
                            day: schedule.day,
                            start_time: schedule.start_time,
                            end_time: schedule.end_time,
                            note: schedule.note
                        }];

                    days.forEach(day => {
                        if (!day) return;

                        html += `
                    <tr>
                        <td class="table-cell">${departmentName}</td>
                        <td class="table-cell">${teacherName}</td>
                        <td class="table-cell">${subjectName}</td>
                        <td class="table-cell">${day.day || '-'}</td>
                        <td class="table-cell">${formatTime(day.start_time)}</td>
                        <td class="table-cell">${formatTime(day.end_time)}</td>
                        <td class="table-cell">${day.note || '-'}</td>
                        <td class="table-cell">
                            <div class="flex items-center space-x-2">
                                <button onclick="showEditForm(${schedule.id})"
                                        class="p-1.5 text-blue-600 hover:text-blue-800 dark:text-blue-400
                                               dark:hover:text-blue-300 hover:bg-blue-50 dark:hover:bg-blue-900/20
                                               rounded-full">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="confirmDelete(${schedule.id})"
                                        class="p-1.5 text-red-600 hover:text-red-800 dark:text-red-400
                                               dark:hover:text-red-300 hover:bg-red-50 dark:hover:bg-red-900/20
                                               rounded-full">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
                    });
                });
            });

            routinesTableBody.innerHTML = html;
        }

        async function editRoutine(id) {
            try {
                const response = await fetch(`/admin/routines/${id}`);
                const data = await response.json();

                if (data.status === 'success') {
                    const routine = data.data;
                    isEditMode = true;
                    showAddForm();
                    formTitle.textContent = 'Edit Routine';
                    routineId.value = routine.id;
                    departmentId.value = routine.department_id;

                    await loadMappingsByDepartment();
                    mappingId.value = routine.subject_teacher_mapping_id;

                    populateDaysTable([routine]); // assuming single day per routine
                }
            } catch (error) {
                console.error("Error loading routine for edit:", error);
            }
        }

        function populateSelect(selectElement, items, defaultText = 'Select') {
            selectElement.innerHTML = `<option value="">${defaultText}</option>`;
            items.forEach(item => {
                const option = document.createElement('option');
                option.value = item.id;
                option.textContent = item.name;
                selectElement.appendChild(option);
            });
        }

        // Helper function to format time display
        function formatTime(timeString) {
            if (!timeString) return '-';
            try {
                const time = new Date(`1970-01-01T${timeString}`);
                if (isNaN(time)) return timeString;

                return time.toLocaleTimeString('en-US', {
                    hour: 'numeric',
                    minute: '2-digit',
                    hour12: true
                });
            } catch (e) {
                return timeString;
            }
        }

        // Render pagination
        function renderPagination(meta) {
            if (!meta) return;

            paginationInfo.textContent = `Showing ${meta.from || 0} to ${meta.to || 0} of ${meta.total} routines`;
            totalPages = meta.last_page || 1;

            let html = '';

            // Previous button
            html += `
                <button onclick="changePage(${meta.current_page - 1})"
                        class="p-2 rounded-md text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700"
                        ${meta.current_page <= 1 ? 'disabled' : ''}>
                    <i class="fas fa-chevron-left"></i>
                </button>
            `;

            // Page numbers
            for (let i = 1; i <= meta.last_page; i++) {
                if (
                    i === 1 ||
                    i === meta.last_page ||
                    (i >= meta.current_page - 2 && i <= meta.current_page + 2)
                ) {
                    html += `
                        <button onclick="changePage(${i})"
                                class="p-2 rounded-md ${i === meta.current_page
                        ? 'bg-primary-50 dark:bg-gray-700 text-primary-600 dark:text-primary-400'
                        : 'text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700'}">
                            ${i}
                        </button>
                    `;
                } else if (
                    i === meta.current_page - 3 ||
                    i === meta.current_page + 3
                ) {
                    html += '<span class="p-2 text-gray-500">...</span>';
                }
            }

            // Next button
            html += `
                <button onclick="changePage(${meta.current_page + 1})"
                        class="p-2 rounded-md text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700"
                        ${meta.current_page >= meta.last_page ? 'disabled' : ''}>
                    <i class="fas fa-chevron-right"></i>
                </button>
            `;

            paginationControls.innerHTML = html;
        }

        // Change page
        function changePage(page) {
            if (page < 1 || page > totalPages) return;
            currentPage = page;
            loadRoutines();
        }

        // Utility function: Debounce
        function debounce(func, delay) {
            let timeout;
            return function () {
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(this, arguments), delay);
            };
        }

        // Make functions accessible globally
        window.showEditForm = showEditForm;
        window.confirmDelete = confirmDelete;
        window.changePage = changePage;
    </script>
@endsection
