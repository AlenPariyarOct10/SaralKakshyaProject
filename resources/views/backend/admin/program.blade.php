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
    <main class="p-4 md:p-6 flex-grow overflow-y-auto">
        <!-- Action Bar -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
            <div class="flex items-center">
                <div class="relative">
                    <input type="text" id="searchPrograms" placeholder="Search programs..." class="form-input pl-10 pr-4 py-2">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                </div>
                <div class="ml-2">
                    <select id="departmentFilter" class="form-input py-2">
                        <option value="">All Departments</option>
                        <option value="Computer Science">Computer Science</option>
                        <option value="Engineering">Engineering</option>
                        <option value="Business">Business</option>
                        <option value="Arts">Arts</option>
                        <option value="Science">Science</option>
                    </select>
                </div>
            </div>

            <button id="addProgramBtn" class="btn-primary flex items-center">
                <i class="fas fa-plus mr-2"></i> Add New Program
            </button>
        </div>

        <!-- Programs Table -->
        <div class="card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th scope="col" class="table-header">Program Name</th>
                        <th scope="col" class="table-header">Department</th>
                        <th scope="col" class="table-header">Total Batches</th>
                        <th scope="col" class="table-header">Total Semesters</th>
                        <th scope="col" class="table-header">Duration (Years)</th>
                        <th scope="col" class="table-header">Status</th>
                        <th scope="col" class="table-header">Actions</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700" id="programsTableBody">
                    <tr>
                        <td class="table-cell font-medium">Bachelor of Computer Science</td>
                        <td class="table-cell">Computer Science</td>
                        <td class="table-cell">4</td>
                        <td class="table-cell">8</td>
                        <td class="table-cell">4</td>
                        <td class="table-cell">
                            <span class="badge bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">Active</span>
                        </td>
                        <td class="table-cell">
                            <div class="flex items-center space-x-2">
                                <button class="edit-program-btn p-1 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300" data-id="1">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="delete-program-btn p-1 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300" data-id="1">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="table-cell font-medium">Master of Business Administration</td>
                        <td class="table-cell">Business</td>
                        <td class="table-cell">2</td>
                        <td class="table-cell">4</td>
                        <td class="table-cell">2</td>
                        <td class="table-cell">
                            <span class="badge bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">Active</span>
                        </td>
                        <td class="table-cell">
                            <div class="flex items-center space-x-2">
                                <button class="edit-program-btn p-1 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300" data-id="2">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="delete-program-btn p-1 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300" data-id="2">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="table-cell font-medium">Bachelor of Mechanical Engineering</td>
                        <td class="table-cell">Engineering</td>
                        <td class="table-cell">4</td>
                        <td class="table-cell">8</td>
                        <td class="table-cell">4</td>
                        <td class="table-cell">
                            <span class="badge bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">Active</span>
                        </td>
                        <td class="table-cell">
                            <div class="flex items-center space-x-2">
                                <button class="edit-program-btn p-1 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300" data-id="3">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="delete-program-btn p-1 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300" data-id="3">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="table-cell font-medium">Bachelor of Fine Arts</td>
                        <td class="table-cell">Arts</td>
                        <td class="table-cell">3</td>
                        <td class="table-cell">6</td>
                        <td class="table-cell">3</td>
                        <td class="table-cell">
                            <span class="badge bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100">Under Review</span>
                        </td>
                        <td class="table-cell">
                            <div class="flex items-center space-x-2">
                                <button class="edit-program-btn p-1 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300" data-id="4">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="delete-program-btn p-1 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300" data-id="4">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="table-cell font-medium">PhD in Physics</td>
                        <td class="table-cell">Science</td>
                        <td class="table-cell">1</td>
                        <td class="table-cell">6</td>
                        <td class="table-cell">3</td>
                        <td class="table-cell">
                            <span class="badge bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">Inactive</span>
                        </td>
                        <td class="table-cell">
                            <div class="flex items-center space-x-2">
                                <button class="edit-program-btn p-1 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300" data-id="5">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="delete-program-btn p-1 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300" data-id="5">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 bg-white dark:bg-gray-800 border-t dark:border-gray-700 flex items-center justify-between">
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    Showing <span class="font-medium text-gray-700 dark:text-gray-300">1</span> to <span class="font-medium text-gray-700 dark:text-gray-300">5</span> of <span class="font-medium text-gray-700 dark:text-gray-300">12</span> programs
                </div>
                <div class="flex items-center space-x-2">
                    <button class="p-2 rounded-md text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 disabled:opacity-50" disabled>
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="p-2 rounded-md bg-primary-50 dark:bg-gray-700 text-primary-600 dark:text-primary-400">1</button>
                    <button class="p-2 rounded-md text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700">2</button>
                    <button class="p-2 rounded-md text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700">3</button>
                    <button class="p-2 rounded-md text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </main>
@endsection

@section("modals")
    <!-- Add/Edit Program Modal -->
    <div id="programModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
        <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 id="modalTitle" class="text-xl font-semibold text-gray-800 dark:text-white">Add New Program</h3>
                    <button id="closeModal" class="p-1 rounded-md text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form id="programForm">
                    <input type="hidden" id="programId" value="">

                    <div class="mb-4">
                        <label for="programName" class="form-label">Program Name</label>
                        <input type="text" id="programName" class="form-input" placeholder="Enter program name" required>
                    </div>

                    <div class="mb-4">
                        <label for="department" class="form-label">Department</label>
                        <select id="department" class="form-input" required>
                            <option value="">Select Department</option>
                            <option value="Computer Science">Computer Science</option>
                            <option value="Engineering">Engineering</option>
                            <option value="Business">Business</option>
                            <option value="Arts">Arts</option>
                            <option value="Science">Science</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div>
                            <label for="totalBatches" class="form-label">Total Batches</label>
                            <input type="number" id="totalBatches" class="form-input" min="1" placeholder="Batches" required>
                        </div>

                        <div>
                            <label for="totalSemesters" class="form-label">Total Semesters</label>
                            <input type="number" id="totalSemesters" class="form-input" min="1" placeholder="Semesters" required>
                        </div>

                        <div>
                            <label for="durationYears" class="form-label">Duration (Years)</label>
                            <input type="number" id="durationYears" class="form-input" min="1" placeholder="Years" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="programStatus" class="form-label">Status</label>
                        <select id="programStatus" class="form-input" required>
                            <option value="active">Active</option>
                            <option value="review">Under Review</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="programDescription" class="form-label">Description (Optional)</label>
                        <textarea id="programDescription" class="form-input" rows="3" placeholder="Enter program description"></textarea>
                    </div>

                    <div class="flex justify-end space-x-2 mt-6">
                        <button type="button" id="cancelBtn" class="btn-secondary">Cancel</button>
                        <button type="submit" id="saveBtn" class="btn-primary">Save Program</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
        <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full mx-4">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-white">Confirm Deletion</h3>
                    <button id="closeDeleteModal" class="p-1 rounded-md text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <p class="text-gray-600 dark:text-gray-400 mb-6">Are you sure you want to delete this program? This action cannot be undone.</p>

                <div class="flex justify-end space-x-2">
                    <button id="cancelDeleteBtn" class="btn-secondary">Cancel</button>
                    <button id="confirmDeleteBtn" class="btn-danger">Delete</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section("scripts")
    <script>
      

        // Program Modal
        const programModal = document.getElementById('programModal');
        const addProgramBtn = document.getElementById('addProgramBtn');
        const closeModal = document.getElementById('closeModal');
        const cancelBtn = document.getElementById('cancelBtn');
        const modalTitle = document.getElementById('modalTitle');
        const programForm = document.getElementById('programForm');
        const programId = document.getElementById('programId');

        // Delete Modal
        const deleteModal = document.getElementById('deleteModal');
        const closeDeleteModal = document.getElementById('closeDeleteModal');
        const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
        const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');

        // Open Add Program Modal
        addProgramBtn.addEventListener('click', () => {
            modalTitle.textContent = 'Add New Program';
            programId.value = '';
            programForm.reset();
            programModal.classList.remove('hidden');
        });

        // Close Program Modal
        closeModal.addEventListener('click', () => {
            programModal.classList.add('hidden');
        });

        cancelBtn.addEventListener('click', () => {
            programModal.classList.add('hidden');
        });

        // Close Delete Modal
        closeDeleteModal.addEventListener('click', () => {
            deleteModal.classList.add('hidden');
        });

        cancelDeleteBtn.addEventListener('click', () => {
            deleteModal.classList.add('hidden');
        });

        // Edit Program
        const editButtons = document.querySelectorAll('.edit-program-btn');

        editButtons.forEach(button => {
            button.addEventListener('click', () => {
                const id = button.getAttribute('data-id');
                modalTitle.textContent = 'Edit Program';
                programId.value = id;

                // In a real application, you would fetch the program data from the server
                // For this demo, we'll just populate with dummy data
                const row = button.closest('tr');
                const programName = row.querySelector('td:first-child').textContent;
                const department = row.querySelector('td:nth-child(2)').textContent;
                const totalBatches = row.querySelector('td:nth-child(3)').textContent;
                const totalSemesters = row.querySelector('td:nth-child(4)').textContent;
                const durationYears = row.querySelector('td:nth-child(5)').textContent;
                const status = row.querySelector('td:nth-child(6) span').textContent;

                document.getElementById('programName').value = programName;
                document.getElementById('department').value = department;
                document.getElementById('totalBatches').value = totalBatches;
                document.getElementById('totalSemesters').value = totalSemesters;
                document.getElementById('durationYears').value = durationYears;

                // Set status based on badge text
                if (status === 'Active') {
                    document.getElementById('programStatus').value = 'active';
                } else if (status === 'Under Review') {
                    document.getElementById('programStatus').value = 'review';
                } else {
                    document.getElementById('programStatus').value = 'inactive';
                }

                programModal.classList.remove('hidden');
            });
        });

        // Delete Program
        const deleteButtons = document.querySelectorAll('.delete-program-btn');
        let programToDelete = null;

        deleteButtons.forEach(button => {
            button.addEventListener('click', () => {
                programToDelete = button.getAttribute('data-id');
                deleteModal.classList.remove('hidden');
            });
        });

        confirmDeleteBtn.addEventListener('click', () => {
            if (programToDelete) {
                // In a real application, you would send a delete request to the server
                // For this demo, we'll just remove the row from the table
                const row = document.querySelector(`.delete-program-btn[data-id="${programToDelete}"]`).closest('tr');
                row.remove();

                // Show a success message (in a real app, you might use a toast notification)
                alert('Program deleted successfully');

                deleteModal.classList.add('hidden');
                programToDelete = null;
            }
        });

        // Form Submission
        programForm.addEventListener('submit', (e) => {
            e.preventDefault();

            // In a real application, you would send the form data to the server
            // For this demo, we'll just show a success message
            const isEditing = programId.value !== '';
            const message = isEditing ? 'Program updated successfully' : 'Program added successfully';

            alert(message);

            // Close the modal
            programModal.classList.add('hidden');

            // If adding a new program, you would typically refresh the table or add a new row
            // For this demo, we'll just reload the page after a short delay
            if (!isEditing) {
                setTimeout(() => {
                    location.reload();
                }, 1000);
            }
        });

        // Search and Filter
        const searchInput = document.getElementById('searchPrograms');
        const departmentFilter = document.getElementById('departmentFilter');

        function filterPrograms() {
            const searchTerm = searchInput.value.toLowerCase();
            const department = departmentFilter.value;

            const rows = document.querySelectorAll('#programsTableBody tr');

            rows.forEach(row => {
                const programName = row.querySelector('td:first-child').textContent.toLowerCase();
                const programDepartment = row.querySelector('td:nth-child(2)').textContent;

                const matchesSearch = programName.includes(searchTerm);
                const matchesDepartment = department === '' || programDepartment === department;

                if (matchesSearch && matchesDepartment) {
                    row.classList.remove('hidden');
                } else {
                    row.classList.add('hidden');
                }
            });
        }

        searchInput.addEventListener('input', filterPrograms);
        departmentFilter.addEventListener('change', filterPrograms);
    </script>
@endsection
