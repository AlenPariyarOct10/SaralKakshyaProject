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
            .card {
                @apply bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden;
            }
            .sidebar-item {
                @apply flex items-center gap-3 px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md transition-colors duration-200;
            }
            .sidebar-item.active {
                @apply bg-primary-50 dark:bg-gray-700 text-primary-600 dark:text-primary-400 font-medium;
            }
            .form-input {
                @apply w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:text-white text-sm;
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
            .badge {
                @apply px-2.5 py-1 text-xs font-medium rounded-full;
            }
            .dropdown-item {
                @apply block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-150;
            }
        }
    </style>
@endpush

@section('content')

    <!-- Main Content Area -->
    <main class="scrollable-content p-4 md:p-6">



        <!-- Action Bar -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="relative">
                    <input type="text" id="searchdepartments" placeholder="Search departments..."
                           class="form-input rounded-lg pl-10 pr-4 py-2 focus:ring-2 ">
                </div>

            </div>

            <button id="adddepartmentBtn" class="btn-primary flex items-center justify-center">
                <i class="fas fa-plus mr-2"></i> Add New Departments
            </button>
        </div>

        <!-- departments Table -->
        <div class="card">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th scope="col" class="table-header">Department Name</th>
                        <th scope="col" class="table-header">Status</th>
                        <th scope="col" class="table-header">Actions</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700" id="departmentsTableBody">
                    @forelse($allDepartments as $department)
                    <tr>
                        <td class="table-cell font-medium">{{$department->name}}</td>
                        @if($department->status=="active")
                            <td class="table-cell">
                                <span class="badge bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">Active</span>
                            </td>
                        @endif
                        @if($department->status=="inactive")
                            <td class="table-cell">
                                <span class="badge bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">Inactive</span>
                            </td>
                        @endif
                        <td class="table-cell">
                            <div class="flex items-center space-x-2">
                                <button class="edit-department-btn p-1.5 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-full" data-id="{{$department->id}}" aria-label="Edit department">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="delete-department-btn p-1.5 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-full" data-id="{{$department->id}}" aria-label="Delete department">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </td>

                    </tr>
                    @empty
                        <tr>
                            <td class="table-cell font-medium text-center" colspan="4">No records found</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
{{--            <div class="px-6 py-4 bg-white dark:bg-gray-800 border-t dark:border-gray-700 flex flex-col sm:flex-row items-center justify-between gap-4">--}}
{{--                <div class="text-sm text-gray-500 dark:text-gray-400">--}}
{{--                    Showing <span class="font-medium text-gray-700 dark:text-gray-300">1</span> to <span class="font-medium text-gray-700 dark:text-gray-300">5</span> of <span class="font-medium text-gray-700 dark:text-gray-300">12</span> departments--}}
{{--                </div>--}}
{{--                <div class="flex items-center space-x-1">--}}
{{--                    <button class="p-2 rounded-md text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 disabled:opacity-50 disabled:cursor-not-allowed" disabled aria-label="Previous page">--}}
{{--                        <i class="fas fa-chevron-left"></i>--}}
{{--                    </button>--}}
{{--                    <button class="p-2 rounded-md bg-primary-50 dark:bg-gray-700 text-primary-600 dark:text-primary-400" aria-label="Page 1">1</button>--}}
{{--                    <button class="p-2 rounded-md text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700" aria-label="Page 2">2</button>--}}
{{--                    <button class="p-2 rounded-md text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700" aria-label="Page 3">3</button>--}}
{{--                    <button class="p-2 rounded-md text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700" aria-label="Next page">--}}
{{--                        <i class="fas fa-chevron-right"></i>--}}
{{--                    </button>--}}
{{--                </div>--}}
{{--            </div>--}}
        </div>
    </main>
@endsection

@section("modals")
    <!-- Add Department Modal -->
    <div id="departmentModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
        <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 id="modalTitle" class="text-xl font-semibold text-gray-800 dark:text-white">Add New Department</h3>
                    <button id="closeModal" class="p-1 rounded-md text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form action="{{route("admin.department.store")}}" method="POST" id="departmentForm">
                    <input type="hidden" id="departmentId" value="">
                    @csrf
                    <div class="mb-4">
                        <label for="departmentName" class="form-label">Department Name</label>
                        <input type="text" name="name" id="departmentName" class="form-input" placeholder="Enter department name" required>
                    </div>


                    <div class="mb-4">
                        <label for="departmentStatus" class="form-label">Status</label>
                        <select id="departmentStatus" name="status" class="form-input" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="departmentDescription" class="form-label">Description (Optional)</label>
                        <textarea id="departmentDescription" name="description" class="form-input" rows="3" placeholder="Enter department description"></textarea>
                    </div>

                    <div class="flex justify-end space-x-2 mt-6">
                        <button type="button" id="cancelBtn" class="btn-secondary">Cancel</button>
                        <button type="submit" id="saveBtn" class="btn-primary">Save department</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Department Modal -->
    <div id="editDepartmentModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
        <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3  class="text-xl font-semibold text-gray-800 dark:text-white">Edit Department</h3>
                    <button id="closeEditModal" class="p-1 rounded-md text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form action="{{ route('admin.department.update') }}" method="POST" id="departmentForm">
                    @method("PUT")
                    @csrf

                    <!-- Hidden input to store department ID -->
                    <input type="hidden" name="id" id="editDepartmentId" value="">

                    <div class="mb-4">
                        <label for="editDepartmentName" class="form-label">Department Name</label>
                        <input type="text" name="name" id="editDepartmentName" class="form-input" placeholder="Enter department name" required>
                    </div>

                    <div class="mb-4">
                        <label for="editDepartmentStatus" class="form-label">Status</label>
                        <select id="editDepartmentStatus" name="status" class="form-input" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="editDepartmentDescription" class="form-label">Description (Optional)</label>
                        <textarea id="editDepartmentDescription" name="description" class="form-input" rows="3" placeholder="Enter department description"></textarea>
                    </div>

                    <div class="flex justify-end space-x-2 mt-6">
                        <button type="button" id="editCancelBtn" class="btn-secondary">Cancel</button>
                        <button type="submit" id="saveBtn" class="btn-primary">Save department</button>
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

                <p class="text-gray-600 dark:text-gray-400 mb-6">Are you sure you want to delete this department? This action cannot be undone.</p>

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




        // department Modal
        const departmentModal = document.getElementById('departmentModal');
        const adddepartmentBtn = document.getElementById('adddepartmentBtn');
        const closeModal = document.getElementById('closeModal');
        const cancelBtn = document.getElementById('cancelBtn');
        const modalTitle = document.getElementById('modalTitle');
        const departmentForm = document.getElementById('departmentForm');
        const departmentId = document.getElementById('departmentId');
        const editDepartmentModal = document.getElementById('editDepartmentModal');
        const editCancelButton = document.getElementById('editCancelBtn');
        const editCloseButton = document.getElementById('closeEditModal');

        // Delete Modal
        const deleteModal = document.getElementById('deleteModal');
        const closeDeleteModal = document.getElementById('closeDeleteModal');
        const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
        const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');

        editCancelButton.addEventListener('click', ()=>{
            editDepartmentModal.classList.add("hidden");
        })
        editCloseButton.addEventListener('click', ()=>{
            editDepartmentModal.classList.add("hidden");
        })

        // Open Add department Modal
        adddepartmentBtn.addEventListener('click', () => {
            modalTitle.textContent = 'Add New Department';
            departmentId.value = '';
            departmentForm.reset();
            departmentModal.classList.remove('hidden');
        });

        // Close department Modal
        closeModal.addEventListener('click', () => {
            departmentModal.classList.add('hidden');
            departmentModal.classList.add('cancelBtn');
        });

        cancelBtn.addEventListener('click', () => {
            departmentModal.classList.add('hidden');
            editDepartmentModal.classList.add('hidden');
        });

        // Close Delete Modal
        closeDeleteModal.addEventListener('click', () => {
            deleteModal.classList.add('hidden');
        });

        cancelDeleteBtn.addEventListener('click', () => {
            deleteModal.classList.add('hidden');
        });

        // Edit department
        console.log(document.querySelectorAll('.delete-department-btn'));
        const editButtons = document.querySelectorAll('.edit-department-btn');

        console.log("All Edit", editButtons);

        editButtons.forEach(button => {
            button.addEventListener('click', () => {
                const id = button.getAttribute('data-id');
                departmentId.value = id;

                fetch(`/admin/department/get_department/${id}`, {
                    method: "GET",
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                        "Content-Type": "application/json",
                    },
                })
                    .then(response => response.json())  // Automatically parses the JSON response
                    .then(data => {

                        console.log("alldata",data);

                        document.getElementById('editDepartmentId').value = data.id;
                        document.getElementById('editDepartmentName').value = data.name;
                        document.getElementById('editDepartmentStatus').value = data.status;
                        document.getElementById('editDepartmentDescription').value = data.description;


                    })
                    .catch(error => {
                        console.error('Error:', error.toString());  // Log any errors
                    });

                editDepartmentModal.classList.remove('hidden');
            });
        });

        // Delete department
        const deleteButtons = document.querySelectorAll('.delete-department-btn');
        let departmentToDelete = null;

        deleteButtons.forEach(button => {
            button.addEventListener('click', () => {
                departmentToDelete = button.getAttribute('data-id');
                deleteModal.classList.remove('hidden');
            });
        });

        confirmDeleteBtn.addEventListener('click', () => {
            if (departmentToDelete) {
                fetch(`/admin/department/${departmentToDelete}`, {
                    method: "DELETE",
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                        "Content-Type": "application/json",
                    },
                })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {

                            const row = document.querySelector(`.delete-department-btn[data-id="${departmentToDelete}"]`).closest("tr");
                            if (row) {
                                row.remove();
                            }

                            Toast.fire({
                                icon: 'success',
                                title: 'Deleted',
                            })

                        } else {
                            alert("Failed to delete testimonial.");
                        }

                        // Hide the modal
                        deleteModal.classList.add("hidden");
                        departmentToDelete = null;
                    })
                    .catch((error) => {
                        console.error("Error:", error.toString());
                    });
            }
        });



        // Search and Filter
        const searchInput = document.getElementById('searchdepartments');
        const departmentFilter = document.getElementById('departmentFilter');

        function filterdepartments() {
            const searchTerm = searchInput.value.toLowerCase();
            const department = departmentFilter.value;

            const rows = document.querySelectorAll('#departmentsTableBody tr');

            rows.forEach(row => {
                const departmentName = row.querySelector('td:first-child').textContent.toLowerCase();
                const departmentDepartment = row.querySelector('td:nth-child(2)').textContent;

                const matchesSearch = departmentName.includes(searchTerm);
                const matchesDepartment = department === '' || departmentDepartment === department;

                if (matchesSearch && matchesDepartment) {
                    row.classList.remove('hidden');
                } else {
                    row.classList.add('hidden');
                }
            });
        }

        searchInput.addEventListener('input', filterdepartments);
        // departmentFilter.addEventListener('change', filterdepartments);
    </script>
@endsection
