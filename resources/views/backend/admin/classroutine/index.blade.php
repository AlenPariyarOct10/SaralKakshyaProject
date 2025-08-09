@extends("backend.layout.admin-dashboard-layout")

@section("title")
    Class Routines
@endsection

@section('content')
    <div class="container mx-auto p-4 overflow-scroll">
        <h1 class="text-2xl font-semibold mb-4 dark:text-white">Class Routines</h1>

        <!-- Add Routine Button -->
        <div class="mb-4">
            <button id="addRoutineBtn" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                Add Routine
            </button>
        </div>

        <!-- Routine Form Card -->
        <div id="routineFormCard" class="overflow-scroll bg-white shadow-md rounded-md p-4 mb-4 hidden dark:bg-gray-800">
            <h2 id="formTitle" class="text-lg font-semibold mb-2 dark:text-white">Add Class Routine</h2>
            <form id="routineForm">
                @csrf
                <input type="hidden" id="routineId">

                <div class="mb-4">
                    <label for="departmentId" class="block text-gray-700 text-sm font-bold mb-2 dark:text-gray-300">Department:</label>
                    <select id="departmentId" name="department_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">Select Department</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                        @endforeach
                    </select>
                    <p id="departmentIdError" class="text-red-500 text-xs italic mt-1 hidden"></p>
                </div>

                <div class="mb-4">
                    <label for="mappingId" class="block text-gray-700 text-sm font-bold mb-2 dark:text-gray-300">Teacher - Subject:</label>
                    <select id="mappingId" name="mapping_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">Select Department First</option>
                    </select>
                    <p id="mappingIdError" class="text-red-500 text-xs italic mt-1 hidden"></p>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2 dark:text-gray-300">Days and Time:</label>
                    <table class="table-auto w-full">
                        <thead>
                        <tr>
                            <th class="px-4 py-2 text-left dark:text-white">Day</th>
                            <th class="px-4 py-2 text-left dark:text-white">Start Time</th>
                            <th class="px-4 py-2 text-left dark:text-white">End Time</th>
                            <th class="px-4 py-2 text-left dark:text-white">Notes</th>
                            <th class="px-4 py-2 text-left dark:text-white">Actions</th>
                        </tr>
                        </thead>
                        <tbody id="daysTableBody">
                        <!-- Days will be populated here -->
                        </tbody>
                    </table>
                    <div id="addSlotContainer" class="mt-3">
                        <button type="button" id="addSlotBtn" class="bg-blue-500 hover:bg-blue-700 text-white text-sm py-1 px-3 rounded">
                            <i class="fas fa-plus mr-1"></i> Add Time Slot
                        </button>
                    </div>
                    <p id="daysError" class="text-red-500 text-xs italic mt-1 hidden"></p>
                </div>

                <div class="flex items-center justify-between">
                    <button id="saveBtn" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                        Save Routine
                    </button>
                    <button id="cancelBtn" class="bg-gray-400 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="button">
                        Cancel
                    </button>
                </div>
            </form>
        </div>

        <!-- Search and Filter -->
        <div class="flex flex-wrap justify-between items-center mb-4 gap-2">
            <div class="flex flex-wrap items-center gap-2">
                <div>
                    <label for="departmentFilter" class="mr-2 dark:text-white">Department:</label>
                    <select id="departmentFilter" class="shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">All Departments</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="programFilter" class="mr-2 dark:text-white">Program:</label>
                    <select id="programFilter" class="shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">All Programs</option>
                    </select>
                </div>
            </div>
            <div class="flex items-center">
                <input type="text" id="searchInput" placeholder="Search..." class="shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <button id="searchBtn" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-2">Search</button>
            </div>
        </div>

        <!-- Routines Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full leading-normal">
                <thead>
                <tr>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider dark:bg-gray-700 dark:text-gray-400 dark:border-gray-600">
                        Department
                    </th>

                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider dark:bg-gray-700 dark:text-gray-400 dark:border-gray-600">
                        Teacher
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider dark:bg-gray-700 dark:text-gray-400 dark:border-gray-600">
                        Subject
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider dark:bg-gray-700 dark:text-gray-400 dark:border-gray-600">
                        Day
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider dark:bg-gray-700 dark:text-gray-400 dark:border-gray-600">
                        Start Time
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider dark:bg-gray-700 dark:text-gray-400 dark:border-gray-600">
                        End Time
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider dark:bg-gray-700 dark:text-gray-400 dark:border-gray-600">
                        Notes
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider dark:bg-gray-700 dark:text-gray-400 dark:border-gray-600">
                        Actions
                    </th>
                </tr>
                </thead>
                <tbody id="routinesTableBody">
                <!-- Routines will be populated here -->
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4 flex justify-between items-center">
            <button id="prevPageBtn" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded disabled:opacity-50 disabled:cursor-not-allowed">
                Previous
            </button>
            <div id="paginationInfo" class="text-sm text-gray-700 dark:text-gray-300"></div>
            <button id="nextPageBtn" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded disabled:opacity-50 disabled:cursor-not-allowed">
                Next
            </button>
        </div>
    </div>

    <script>
        const addRoutineBtn = document.getElementById('addRoutineBtn');
        const routineFormCard = document.getElementById('routineFormCard');
        const routineForm = document.getElementById('routineForm');
        const routineId = document.getElementById('routineId');
        const departmentId = document.getElementById('departmentId');
        const mappingId = document.getElementById('mappingId');
        const daysTableBody = document.getElementById('daysTableBody');
        const addSlotBtn = document.getElementById('addSlotBtn');
        const saveBtn = document.getElementById('saveBtn');
        const cancelBtn = document.getElementById('cancelBtn');
        const formTitle = document.getElementById('formTitle');

        const departmentIdError = document.getElementById('departmentIdError');
        const mappingIdError = document.getElementById('mappingIdError');
        const daysError = document.getElementById('daysError');

        const routinesTableBody = document.getElementById('routinesTableBody');
        const searchInput = document.getElementById('searchInput');
        const searchBtn = document.getElementById('searchBtn');
        const departmentFilter = document.getElementById('departmentFilter');
        const programFilter = document.getElementById('programFilter');

        const prevPageBtn = document.getElementById('prevPageBtn');
        const nextPageBtn = document.getElementById('nextPageBtn');
        const paginationInfo = document.getElementById('paginationInfo');

        let routines = [];
        let mappings = [];
        let programs = [];
        let availableDays = [];
        let currentPage = 1;
        let totalPages = 1;
        let isLoading = false;
        let isEditMode = false;

        const daysOfWeek = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

        // Helper function to format time for input
        function formatTimeForInput(timeString) {
            if (!timeString) return '';
            return timeString.slice(0, 5); // Returns HH:MM
        }

        // Helper to format time for display
        function formatTimeForDisplay(timeString) {
            if (!timeString) return '';

            try {
                // If already in 12-hour format
                if (timeString.includes('AM') || timeString.includes('PM')) {
                    return timeString;
                }

                // Convert 24-hour format to 12-hour format
                const time = new Date(`1970-01-01T${timeString}`);
                if (isNaN(time)) return timeString;

                return time.toLocaleTimeString('en-US', {
                    hour: 'numeric',
                    minute: '2-digit',
                    hour12: true
                });
            } catch (error) {
                return timeString;
            }
        }

        function populateDaysTable(availableSlots = []) {
            daysTableBody.innerHTML = '';

            // Store available days for later use
            availableDays = availableSlots.map(slot => slot.day_of_week?.toLowerCase() || slot.day?.toLowerCase() || '');

            // If no available slots, show a message
            if (availableSlots.length === 0) {
                const row = document.createElement('tr');
                row.innerHTML = `
                <td colspan="5" class="px-4 py-4 text-center text-gray-500">
                    No available time slots found for this teacher-subject combination.
                </td>
            `;
                daysTableBody.appendChild(row);
                return;
            }

            // Group slots by day for easier processing
            const slotsByDay = {};
            availableSlots.forEach(slot => {
                const day = slot.day_of_week || slot.day || '';
                if (day) {
                    const dayLower = day.toLowerCase();
                    if (!slotsByDay[dayLower]) {
                        slotsByDay[dayLower] = [];
                    }
                    slotsByDay[dayLower].push(slot);
                }
            });

            daysOfWeek.forEach(day => {
                const dayLower = day.toLowerCase();
                const daySlots = slotsByDay[dayLower] || [];

                if (daySlots.length > 0) {
                    // Use the first slot for this day
                    const daySlot = daySlots[0];

                    addDayRow(day, {
                        start_time: daySlot.start_time,
                        end_time: daySlot.end_time,
                        notes: daySlot.notes || daySlot.note || '',
                        isAvailable: true
                    });
                }
            });
        }

        function addDayRow(day, data = {}, isNewSlot = false) {
            const row = document.createElement('tr');
            row.className = 'day-row';
            row.dataset.day = day;

            // Check if this day is available
            const isAvailable = data.isAvailable !== false && availableDays.includes(day.toLowerCase());

            // Set default values
            const startTime = data.start_time || '';
            const endTime = data.end_time || '';
            const notes = data.notes || '';

            // Create day selection dropdown for new slots
            let dayCell;
            if (isNewSlot) {
                dayCell = `
                <td class="px-4 py-2 whitespace-nowrap">
                    <select class="day-select form-input" onchange="checkDayAvailability(this)">
                        <option value="">Select Day</option>
                        ${daysOfWeek.map(d => `<option value="${d}" ${d === day ? 'selected' : ''}>${d}</option>`).join('')}
                    </select>
                </td>
            `;
            } else {
                dayCell = `
                <td class="px-4 py-2 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                    ${day}
                </td>
            `;
            }

            row.innerHTML = `
            ${dayCell}
            <td class="px-4 py-2 whitespace-nowrap">
                <input type="time" name="startTimes[]" data-day="${day}"
                    class="day-start-time form-input"
                    value="${formatTimeForInput(startTime)}"
                    onchange="validateTimeSlot(this)">
                <div class="time-error text-red-500 text-xs mt-1 hidden"></div>
            </td>
            <td class="px-4 py-2 whitespace-nowrap">
                <input type="time" name="endTimes[]" data-day="${day}"
                    class="day-end-time form-input"
                    value="${formatTimeForInput(endTime)}"
                    onchange="validateTimeSlot(this)">
                <div class="time-error text-red-500 text-xs mt-1 hidden"></div>
            </td>
            <td class="px-4 py-2 whitespace-nowrap">
                <input type="text" name="dayNotes[]" data-day="${day}"
                    class="day-note form-input"
                    placeholder="Notes for ${day}"
                    value="${notes}">
            </td>
            <td class="px-4 py-2 whitespace-nowrap">
                <div class="flex items-center space-x-2">
                    <button type="button" class="text-red-500 hover:text-red-700" onclick="removeTimeSlot(this)">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </td>
        `;

            // Add warning for unavailable days
            if (!isAvailable) {
                const warningDiv = document.createElement('div');
                warningDiv.className = 'text-yellow-500 text-xs mt-1';
                warningDiv.innerHTML = '<i class="fas fa-exclamation-triangle mr-1"></i> Teacher has not marked this day as available';

                // Insert after the first cell
                const firstCell = row.querySelector('td');
                firstCell.appendChild(warningDiv);
            }

            daysTableBody.appendChild(row);

            // Validate the time slot
            const startTimeInput = row.querySelector('.day-start-time');
            if (startTimeInput) {
                validateTimeSlot(startTimeInput);
            }
        }

        function checkDayAvailability(select) {
            const day = select.value;
            const row = select.closest('.day-row');

            // Remove any existing warning
            const existingWarning = select.parentNode.querySelector('.text-yellow-500');
            if (existingWarning) {
                existingWarning.remove();
            }

            // Update the row's day attribute
            row.dataset.day = day;

            // Update all inputs in the row with the new day
            row.querySelectorAll('input[data-day]').forEach(input => {
                input.dataset.day = day;
            });

            // Check if day is available
            if (day && !availableDays.includes(day.toLowerCase())) {
                const warningDiv = document.createElement('div');
                warningDiv.className = 'text-yellow-500 text-xs mt-1';
                warningDiv.innerHTML = '<i class="fas fa-exclamation-triangle mr-1"></i> Teacher has not marked this day as available';
                select.parentNode.appendChild(warningDiv);
            }
        }

        function removeTimeSlot(button) {
            const row = button.closest('.day-row');
            row.remove();
        }

        function validateTimeSlot(input) {
            const row = input.closest('.day-row');
            const startTimeInput = row.querySelector('.day-start-time');
            const endTimeInput = row.querySelector('.day-end-time');
            const errorElement = input.nextElementSibling;

            if (!startTimeInput || !endTimeInput) return;

            const start = startTimeInput.value;
            const end = endTimeInput.value;

            if (start && end) {
                if (start >= end) {
                    errorElement.textContent = 'End time must be after start time';
                    errorElement.classList.remove('hidden');
                    endTimeInput.classList.add('border-red-500');
                } else {
                    errorElement.classList.add('hidden');
                    endTimeInput.classList.remove('border-red-500');
                }
            }
        }

        async function loadMappingsByDepartment() {
            const deptId = departmentId.value;
            if (!deptId) {
                mappingId.innerHTML = '<option value="">Select Department First</option>';
                return;
            }

            mappingId.innerHTML = '<option value="">Loading...</option>';
            mappingId.disabled = true;

            try {
                const response = await fetch(`/admin/department/${deptId}/mappings`);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();

                // Check if data is an array (direct response) or has a data property
                const mappingsData = Array.isArray(data) ? data : (data.data || []);

                if (mappingsData.length > 0) {
                    mappings = mappingsData;

                    // Map the mappings to the format needed for the select
                    const mappingOptions = mappings.map(m => ({
                        id: m.id,
                        name: `${m.teacher.fname} ${m.teacher.lname} - ${m.subject.name}`
                    }));

                    populateSelect(mappingId, mappingOptions, 'Select Teacher-Subject');
                } else {
                    mappingId.innerHTML = '<option value="">No mappings found</option>';
                }
            } catch (error) {
                console.error("Error loading mappings:", error);
                mappingId.innerHTML = '<option value="">Error loading mappings</option>';
            } finally {
                mappingId.disabled = false;
            }
        }

        async function loadProgramsByDepartment(deptId) {
            if (!deptId) {
                programFilter.innerHTML = '<option value="">All Programs</option>';
                return;
            }

            programFilter.innerHTML = '<option value="">Loading...</option>';
            programFilter.disabled = true;

            try {
                const response = await fetch(`/admin/department/get_department_programs?department_id=${deptId}`);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();

                // Check if data is an array or has a data property
                const programsData = Array.isArray(data) ? data : (data.data || []);

                if (programsData.length > 0) {
                    programs = programsData;

                    // Map the programs to the format needed for the select
                    const programOptions = programs.map(p => ({
                        id: p.id,
                        name: p.name
                    }));

                    populateSelect(programFilter, programOptions, 'All Programs');
                } else {
                    programFilter.innerHTML = '<option value="">No programs found</option>';
                }
            } catch (error) {
                console.error("Error loading programs:", error);
                programFilter.innerHTML = '<option value="">Error loading programs</option>';
            } finally {
                programFilter.disabled = false;
            }
        }

        function loadTiming() {
            const selectedMappingId = mappingId.value;
            if (!selectedMappingId) {
                populateDaysTable([]); // Clear table if no mapping selected
                return;
            }

            // Show loading indicator in the days table
            daysTableBody.innerHTML = '<tr><td colspan="5" class="text-center py-4">Loading available time slots...</td></tr>';

            fetch(`/admin/mapping/${selectedMappingId}/timing`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.status === 'success' && data.available_slots && data.available_slots.length > 0) {
                        populateDaysTable(data.available_slots);
                    } else {
                        populateDaysTable([]);
                        console.log("No available time slots found");
                    }
                })
                .catch(error => {
                    console.error('Error loading timing:', error);
                    daysTableBody.innerHTML = '<tr><td colspan="5" class="text-center py-4 text-red-500">Error loading time slots. Please try again.</td></tr>';
                });
        }

        async function handleFormSubmit(e) {
            e.preventDefault();
            hideAllErrors();

            // Disable the save button to prevent double submission
            saveBtn.disabled = true;
            saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Saving...';

            const payload = {
                department_id: departmentId.value,
                subject_teacher_mappings_id: mappingId.value,
                day_schedules: []
            };

            let isValid = true;

            // Validate required fields
            if (!payload.department_id) {
                departmentIdError.textContent = 'Department is required.';
                departmentIdError.classList.remove('hidden');
                isValid = false;
            }

            if (!payload.subject_teacher_mappings_id) {
                mappingIdError.textContent = 'Teacher-Subject is required.';
                mappingIdError.classList.remove('hidden');
                isValid = false;
            }

            // Collect day data from the form
            document.querySelectorAll('.day-row').forEach(row => {
                let day;

                // Check if this is a new slot with a day select dropdown
                const daySelect = row.querySelector('.day-select');
                if (daySelect) {
                    day = daySelect.value;
                } else {
                    day = row.dataset.day;
                }

                if (!day) return; // Skip rows without a day selected

                const startTimeInput = row.querySelector('.day-start-time');
                const endTimeInput = row.querySelector('.day-end-time');
                const noteInput = row.querySelector('.day-note');

                if (!startTimeInput || !endTimeInput) return;

                const start = startTimeInput.value;
                const end = endTimeInput.value;
                const note = noteInput ? noteInput.value : '';

                // Validate time inputs
                if (start && end) {
                    // Validate that end time is after start time
                    if (start >= end) {
                        const errorElement = endTimeInput.nextElementSibling;
                        errorElement.textContent = 'End time must be after start time';
                        errorElement.classList.remove('hidden');
                        endTimeInput.classList.add('border-red-500');
                        isValid = false;
                    } else {
                        payload.day_schedules.push({
                            day,
                            start_time: start,
                            end_time: end,
                            note: note
                        });
                    }
                }
            });

            if (payload.day_schedules.length === 0) {
                daysError.textContent = 'At least one valid day schedule is required.';
                daysError.classList.remove('hidden');
                isValid = false;
            }

            if (!isValid) {
                saveBtn.disabled = false;
                saveBtn.innerHTML = isEditMode ? 'Update Routine' : 'Save Routine';
                return;
            }

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
                    showSweetAlert('Success', data.message || 'Routine saved successfully', 'success');
                    hideForm();
                    loadRoutines();
                } else {
                    if (data.errors) {
                        showValidationErrors(data.errors);
                    } else {
                        showSweetAlert('Error', data.message || 'Something went wrong', 'error');
                    }
                }
            } catch (error) {
                console.error("Routine save error:", error);
                showSweetAlert('Error', 'Failed to save routine', 'error');
            } finally {
                saveBtn.disabled = false;
                saveBtn.innerHTML = isEditMode ? 'Update Routine' : 'Save Routine';
            }
        }

        async function editRoutine(id) {
            try {
                // Show loading indicator
                Swal.fire({
                    title: 'Loading...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                const response = await fetch(`/admin/routines/${id}`);
                const data = await response.json();

                Swal.close(); // Close loading indicator

                if (data.status === 'success') {
                    const routine = data.data;
                    isEditMode = true;
                    formTitle.textContent = 'Edit Class Routine';
                    saveBtn.textContent = 'Update Routine';

                    // Reset form first
                    resetForm();

                    // Set form values
                    routineId.value = routine.id;

                    // Set department and wait for it to load
                    departmentId.value = routine.department.id;
                    await loadMappingsByDepartment();

                    // Set mapping and wait for it to load
                    setTimeout(() => {
                        // Find the correct mapping ID based on subject and teacher
                        const mappingToSelect = mappings.find(m =>
                            m.subject.id === routine.subject.id &&
                            m.teacher.id === routine.teacher.id
                        );

                        if (mappingToSelect) {
                            mappingId.value = mappingToSelect.id;

                            // Load timing data for this mapping
                            loadTiming();

                            // After timing is loaded, set the values for this specific routine
                            setTimeout(() => {
                                // Find the day row for this routine
                                const dayRow = document.querySelector(`.day-row[data-day="${routine.day}"]`);
                                if (dayRow) {
                                    const startTimeInput = dayRow.querySelector('.day-start-time');
                                    const endTimeInput = dayRow.querySelector('.day-end-time');
                                    const noteInput = dayRow.querySelector('.day-note');

                                    if (startTimeInput) startTimeInput.value = formatTimeForInput(routine.start_time);
                                    if (endTimeInput) endTimeInput.value = formatTimeForInput(routine.end_time);
                                    if (noteInput) noteInput.value = routine.note || '';
                                }
                            }, 500);
                        }
                    }, 500);

                    routineFormCard.classList.remove('hidden');
                    routineFormCard.scrollIntoView({ behavior: 'smooth', block: 'start' });
                } else {
                    showSweetAlert('Error', data.message || 'Failed to load routine', 'error');
                }
            } catch (error) {
                Swal.close();
                console.error("Error loading routine for edit:", error);
                showSweetAlert('Error', 'Failed to load routine details', 'error');
            }
        }

        function updateTable(routines) {
            routinesTableBody.innerHTML = '';
            if (!routines || routines.length === 0) {
                routinesTableBody.innerHTML = '<tr><td colspan="9" class="text-center py-4 text-gray-500">No routines found.</td></tr>';
                return;
            }

            routines.forEach(routine => {
                const row = document.createElement('tr');
                row.innerHTML = `
                <td class="table-cell">${routine.department?.name || 'N/A'}</td>
                <td class="table-cell">${routine.teacher?.fname || ''} ${routine.teacher?.lname || ''}</td>
                <td class="table-cell">${routine.subject?.name || 'N/A'}</td>
                <td class="table-cell">${routine.day || 'N/A'}</td>
                <td class="table-cell">${formatTimeForDisplay(routine.start_time)}</td>
                <td class="table-cell">${formatTimeForDisplay(routine.end_time)}</td>
                <td class="table-cell">${routine.notes || routine.note || '-'}</td>
                <td class="table-cell">
                    <div class="flex items-center space-x-2">
                        <button onclick="editRoutine(${routine.id})" class="p-1.5 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-full">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="confirmDelete(${routine.id})" class="p-1.5 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 hover:bg-red-50 dark:hover:bg-blue-900/20 rounded-full">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                </td>
            `;
                routinesTableBody.appendChild(row);
            });
        }

        async function loadRoutines() {
            if (isLoading) return;
            isLoading = true;

            // Show loading indicator
            routinesTableBody.innerHTML = '<tr><td colspan="9" class="text-center py-4">Loading routines...</td></tr>';

            const query = new URLSearchParams({
                page: currentPage,
                search: searchInput.value || '',
                department_id: departmentFilter.value || '',
                program_id: programFilter.value || '',
            });

            try {
                const response = await fetch(`/admin/routines?${query.toString()}`);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();
                if (data.status === 'success') {
                    routines = data.data;
                    totalPages = data.meta.last_page || 1;
                    currentPage = data.meta.current_page || 1;
                    updateTable(data.data);
                    updatePagination(data.meta);
                } else {
                    routinesTableBody.innerHTML = '<tr><td colspan="9" class="text-center py-4 text-red-500">Failed to load routines</td></tr>';
                    console.error("API Error:", data.message);
                }
            } catch (error) {
                console.error("Error loading routines:", error);
                routinesTableBody.innerHTML = '<tr><td colspan="9" class="text-center py-4 text-red-500">Error loading routines. Please try again.</td></tr>';
            } finally {
                isLoading = false;
            }
        }

        function updatePagination(meta) {
            currentPage = meta.current_page;
            totalPages = meta.last_page;
            paginationInfo.textContent = `Page ${currentPage} of ${totalPages}`;
            prevPageBtn.disabled = currentPage <= 1;
            nextPageBtn.disabled = currentPage >= totalPages;
        }

        function populateSelect(selectElement, data, defaultOptionText = 'Select an option') {
            selectElement.innerHTML = '';
            const defaultOption = document.createElement('option');
            defaultOption.value = '';
            defaultOption.textContent = defaultOptionText;
            selectElement.appendChild(defaultOption);

            data.forEach(item => {
                const option = document.createElement('option');
                option.value = item.id;
                option.textContent = item.name;
                selectElement.appendChild(option);
            });
        }

        function showForm() {
            routineFormCard.classList.remove('hidden');
            routineFormCard.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }

        function hideForm() {
            routineFormCard.classList.add('hidden');
        }

        function resetForm() {
            isEditMode = false;
            formTitle.textContent = 'Add Class Routine';
            saveBtn.textContent = 'Save Routine';
            routineForm.reset();
            routineId.value = '';
            departmentId.value = '';
            mappingId.innerHTML = '<option value="">Select Department First</option>';
            populateDaysTable([]);
            hideAllErrors();
        }

        function hideAllErrors() {
            departmentIdError.classList.add('hidden');
            mappingIdError.classList.add('hidden');
            daysError.classList.add('hidden');

            document.querySelectorAll('.time-error').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('.day-end-time').forEach(el => el.classList.remove('border-red-500'));
        }

        function showValidationErrors(errors) {
            for (const field in errors) {
                const errorElement = document.getElementById(`${field}Error`);
                if (errorElement) {
                    errorElement.textContent = errors[field][0];
                    errorElement.classList.remove('hidden');
                }
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

        function confirmDelete(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteRoutine(id);
                }
            });
        }

        async function deleteRoutine(id) {
            try {
                const response = await fetch(`/admin/routines/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                const data = await response.json();

                if (data.status === 'success') {
                    showSweetAlert('Deleted!', data.message || 'Routine has been deleted.', 'success');
                    loadRoutines();
                } else {
                    showSweetAlert('Error', data.message || 'Failed to delete routine', 'error');
                }
            } catch (error) {
                console.error("Error deleting routine:", error);
                showSweetAlert('Error', 'Failed to delete routine', 'error');
            }
        }

        // Add a new time slot
        function addNewTimeSlot() {
            // Create a new row with day selection dropdown
            addDayRow('', {}, true);
        }

        // Event Listeners
        addRoutineBtn.addEventListener('click', showForm);
        cancelBtn.addEventListener('click', () => {
            hideForm();
            resetForm();
        });
        routineForm.addEventListener('submit', handleFormSubmit);
        departmentId.addEventListener('change', loadMappingsByDepartment);
        mappingId.addEventListener('change', loadTiming);
        searchBtn.addEventListener('click', loadRoutines);
        departmentFilter.addEventListener('change', () => {
            loadProgramsByDepartment(departmentFilter.value);
            loadRoutines();
        });
        programFilter.addEventListener('change', loadRoutines);
        searchInput.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                loadRoutines();
            }
        });
        addSlotBtn.addEventListener('click', addNewTimeSlot);

        prevPageBtn.addEventListener('click', () => {
            if (currentPage > 1) {
                currentPage--;
                loadRoutines();
            }
        });

        nextPageBtn.addEventListener('click', () => {
            if (currentPage < totalPages) {
                currentPage++;
                loadRoutines();
            }
        });

        // Make functions globally accessible
        window.validateTimeSlot = validateTimeSlot;
        window.removeTimeSlot = removeTimeSlot;
        window.checkDayAvailability = checkDayAvailability;
        window.editRoutine = editRoutine;
        window.confirmDelete = confirmDelete;

        // Initial Load
        loadRoutines();
        loadProgramsByDepartment(departmentFilter.value);
    </script>
@endsection


