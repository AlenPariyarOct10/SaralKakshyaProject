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
                    <input type="text" id="searchTeachers" placeholder="Search teachers..." class="form-input pl-12 pr-4 py-2">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-4">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                </div>
                <div>
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

        </div>

        <!-- Teachers Table -->
        <div class="card">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th scope="col" class="table-header">Name</th>
                        <th scope="col" class="table-header">Email</th>
                        <th scope="col" class="table-header">Phone</th>
                        <th scope="col" class="table-header">Department</th>
                        <th scope="col" class="table-header">Status</th>
                        <th scope="col" class="table-header">Actions</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($teachers as $teacher)
                        <tr>
                            <td class="table-cell">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 flex-shrink-0">
                                        <img class="h-10 w-10 rounded-full object-cover"
                                             src="{{ $teacher->profile_picture ?? 'https://ui-avatars.com/api/?name='.urlencode($teacher->fname.' '.$teacher->lname) }}"
                                             alt="{{ $teacher->fname }} {{ $teacher->lname }}">
                                    </div>
                                    <div class="ml-4">
                                        <div class="font-medium text-gray-900 dark:text-white">
                                            {{ $teacher->fname }} {{ $teacher->lname }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="table-cell">{{ $teacher->email }}</td>
                            <td class="table-cell">{{ $teacher->phone }}</td>
                            <td class="table-cell">{{ $teacher->department }}</td>
                            <td class="table-cell">
                                @if($teacher->status)
                                    <span class="badge bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                        Active
                                    </span>
                                @else
                                    <span class="badge bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100">
                                        Blocked
                                    </span>
                                @endif
                            </td>
                            <td class="table-cell">
                                <div class="flex items-center space-x-2">
                                    <button class="view-profile-btn p-1.5 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-full"
                                            data-id="{{ $teacher->id }}"
                                            title="View Profile">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    @if($teacher->status)
                                        <button class="block-teacher-btn p-1.5 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-full"
                                                data-id="{{ $teacher->id }}"
                                                title="Block Teacher">
                                            <i class="fas fa-ban"></i>
                                        </button>
                                    @else
                                        <button class="unblock-teacher-btn p-1.5 text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300 hover:bg-green-50 dark:hover:bg-green-900/20 rounded-full"
                                                data-id="{{ $teacher->id }}"
                                                title="Unblock Teacher">
                                            <i class="fas fa-check-circle"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 bg-white dark:bg-gray-800 border-t dark:border-gray-700">
{{--                {{ $teachers->links() }}--}}
            </div>
        </div>
    </main>
@endsection

@section('modals')
    <!-- Teacher Profile Modal -->
    <div id="teacherProfileModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
        <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-4xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-semibold text-gray-800 dark:text-white">Teacher Profile</h3>
                    <button id="closeProfileModal" class="p-1 rounded-md text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Personal Information -->
                    <div class="space-y-4">
                        <h4 class="text-lg font-medium text-gray-800 dark:text-white">Personal Information</h4>
                        <div class="flex items-center space-x-4">
                            <img id="teacherProfilePicture" class="h-20 w-20 rounded-full object-cover" src="" alt="">
                            <div>
                                <p id="teacherName" class="text-xl font-medium text-gray-800 dark:text-white"></p>
                                <p id="teacherEmail" class="text-gray-600 dark:text-gray-400"></p>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Phone</p>
                                <p id="teacherPhone" class="font-medium"></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Department</p>
                                <p id="teacherDepartment" class="font-medium"></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Address</p>
                                <p id="teacherAddress" class="font-medium"></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Joining Date</p>
                                <p id="teacherJoiningDate" class="font-medium"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Academic Information -->
                    <div class="space-y-4">
                        <h4 class="text-lg font-medium text-gray-800 dark:text-white">Academic Information</h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Qualification</p>
                                <p id="teacherQualification" class="font-medium"></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Specialization</p>
                                <p id="teacherSpecialization" class="font-medium"></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Experience</p>
                                <p id="teacherExperience" class="font-medium"></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Status</p>
                                <p id="teacherStatus" class="font-medium"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Block/Unblock Confirmation Modal -->
    <div id="blockConfirmModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
        <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full mx-4">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 id="blockModalTitle" class="text-xl font-semibold text-gray-800 dark:text-white">Confirm Action</h3>
                    <button id="closeBlockModal" class="p-1 rounded-md text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <p id="blockModalMessage" class="text-gray-600 dark:text-gray-400 mb-6"></p>

                <div class="flex justify-end space-x-2">
                    <button id="cancelBlockBtn" class="btn-secondary">Cancel</button>
                    <button id="confirmBlockBtn" class="btn-danger">Confirm</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // DOM Elements
        const teacherProfileModal = document.getElementById('teacherProfileModal');
        const blockConfirmModal = document.getElementById('blockConfirmModal');
        const closeProfileModal = document.getElementById('closeProfileModal');
        const closeBlockModal = document.getElementById('closeBlockModal');
        const cancelBlockBtn = document.getElementById('cancelBlockBtn');
        const searchInput = document.getElementById('searchTeachers');
        const departmentFilter = document.getElementById('departmentFilter');

        // View Profile
        document.querySelectorAll('.view-profile-btn').forEach(button => {
            button.addEventListener('click', async () => {
                const teacherId = button.getAttribute('data-id');
                try {
                    const response = await fetch(`/api/teachers/${teacherId}`);
                    const teacher = await response.json();

                    // Populate modal with teacher data
                    document.getElementById('teacherProfilePicture').src = teacher.profile_picture;
                    document.getElementById('teacherName').textContent = `${teacher.fname} ${teacher.lname}`;
                    document.getElementById('teacherEmail').textContent = teacher.email;
                    document.getElementById('teacherPhone').textContent = teacher.phone;
                    document.getElementById('teacherDepartment').textContent = teacher.department;
                    document.getElementById('teacherAddress').textContent = teacher.address;
                    document.getElementById('teacherJoiningDate').textContent = new Date(teacher.created_at).toLocaleDateString();
                    document.getElementById('teacherStatus').textContent = teacher.status;

                    teacherProfileModal.classList.remove('hidden');
                } catch (error) {
                    console.error('Error fetching teacher data:', error);
                    alert('Failed to load teacher profile');
                }
            });
        });

        // Block/Unblock Teacher
        document.querySelectorAll('.block-teacher-btn, .unblock-teacher-btn').forEach(button => {
            button.addEventListener('click', () => {
                const teacherId = button.getAttribute('data-id');
                const isBlocking = button.classList.contains('block-teacher-btn');

                document.getElementById('blockModalTitle').textContent = isBlocking ? 'Block Teacher' : 'Unblock Teacher';
                document.getElementById('blockModalMessage').textContent = isBlocking
                    ? 'Are you sure you want to block this teacher? They will not be able to access the system.'
                    : 'Are you sure you want to unblock this teacher? They will regain access to the system.';

                document.getElementById('confirmBlockBtn').textContent = isBlocking ? 'Block' : 'Unblock';
                document.getElementById('confirmBlockBtn').onclick = async () => {
                    try {
                        const response = await fetch(`/api/teachers/${teacherId}/toggle-status`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        });

                        if (response.ok) {
                            location.reload();
                        } else {
                            throw new Error('Failed to update status');
                        }
                    } catch (error) {
                        console.error('Error updating teacher status:', error);
                        alert('Failed to update teacher status');
                    }
                };

                blockConfirmModal.classList.remove('hidden');
            });
        });

        // Close modals
        closeProfileModal.addEventListener('click', () => {
            teacherProfileModal.classList.add('hidden');
        });

        closeBlockModal.addEventListener('click', () => {
            blockConfirmModal.classList.add('hidden');
        });

        cancelBlockBtn.addEventListener('click', () => {
            blockConfirmModal.classList.add('hidden');
        });

        // Search and Filter
        function filterTeachers() {
            const searchTerm = searchInput.value.toLowerCase();
            const department = departmentFilter.value;

            document.querySelectorAll('tbody tr').forEach(row => {
                const name = row.querySelector('td:first-child').textContent.toLowerCase();
                const teacherDepartment = row.querySelector('td:nth-child(4)').textContent;

                const matchesSearch = name.includes(searchTerm);
                const matchesDepartment = department === '' || teacherDepartment === department;

                row.classList.toggle('hidden', !(matchesSearch && matchesDepartment));
            });
        }

        searchInput.addEventListener('input', filterTeachers);
        departmentFilter.addEventListener('change', filterTeachers);
    </script>
@endsection
