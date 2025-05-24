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
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" id="searchTeachers" placeholder="Search teachers..." class="form-input pl-10 pr-4 py-2">
                </div>
            </div>
            <div class="flex justify-end">
                <a id="exportBtn" href="{{route('admin.teacher.download.excel')}}" class="btn-secondary flex items-center justify-center mr-2">
                    <i class="fas fa-download mr-2"></i> Export
                </a>
                <a href="{{route('admin.teacher.unapproved.index')}}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition shadow-sm">
                    <i class="fa-solid fa-hourglass-end mr-2"></i> Pending Approval
                    <span class="ml-2 bg-green-500 text-white text-xs font-semibold px-2 py-1 rounded-full">
                        {{$pendingCount}}
                    </span>
                </a>
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
                        <th scope="col" class="table-header">Status</th>
                        <th scope="col" class="table-header">Actions</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($teachers as $teacher)
                        <tr id="teacher-row-{{$teacher->id}}">
                            <td class="table-cell">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 flex-shrink-0">
                                        <img class="h-10 w-10 rounded-full object-cover"
                                             src="{{ ($teacher->profile_picture) ? asset('storage/' . $teacher->profile_picture) : 'https://ui-avatars.com/api/?name='.urlencode($teacher->fname.' '.$teacher->lname) }}"
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
                            <td class="table-cell">{{ ($teacher->phone)?$teacher->phone:'Not given' }}</td>
                            <td class="table-cell">
                                @if($teacher->status=="active")
                                    <span id="status-badge-{{$teacher->id}}" class="badge bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                        Active
                                    </span>
                                @else
                                    <span id="status-badge-{{$teacher->id}}" class="badge bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100">
                                        Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="table-cell">
                                <div class="flex items-center space-x-2">
                                    <button class="view-profile-btn p-1.5 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-full"
                                            data-id="{{ $teacher->id }}"
                                            title="View Profile"
                                            aria-label="View teacher profile">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    @if($teacher->status=="active")
                                        <button class="block-teacher-btn p-1.5 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-full"
                                                data-id="{{ $teacher->id }}"
                                                title="Block Teacher"
                                                aria-label="Block teacher">
                                            <i class="fas fa-ban"></i>
                                        </button>
                                    @else
                                        <button class="unblock-teacher-btn p-1.5 text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300 hover:bg-green-50 dark:hover:bg-green-900/20 rounded-full"
                                                data-id="{{ $teacher->id }}"
                                                title="Activate Teacher"
                                                aria-label="Activate teacher">
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
        <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-4xl w-full mx-4 max-h-[90vh] overflow-hidden">
            <div class="p-6 overflow-y-auto max-h-[80vh]">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-semibold text-gray-800 dark:text-white">Teacher Profile</h3>
                    <button id="closeProfileModal" class="p-1 rounded-md text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700"
                            aria-label="Close profile modal">
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
                                <p class="text-sm text-gray-500 dark:text-gray-400">Address</p>
                                <p id="teacherAddress" class="font-medium"></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Joining Date</p>
                                <p id="teacherJoiningDate" class="font-medium"></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Status</p>
                                <p id="teacherStatus" class="font-medium"></p>
                            </div>
                            <!-- Optional: Department -->
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Department</p>
                                <p id="teacherDepartment" class="font-medium"></p>
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
                                <p class="text-sm text-gray-500 dark:text-gray-400">Account Status</p>
                                <p id="teacherAccountStatus" class="font-medium"></p>
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
                    <button id="closeBlockModal" class="p-1 rounded-md text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700"
                            aria-label="Close confirmation modal">
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
        // Toast Notification Setup
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        // DOM Elements
        const teacherProfileModal = document.getElementById('teacherProfileModal');
        const blockConfirmModal = document.getElementById('blockConfirmModal');
        const closeProfileModal = document.getElementById('closeProfileModal');
        const closeBlockModal = document.getElementById('closeBlockModal');
        const cancelBlockBtn = document.getElementById('cancelBlockBtn');
        const searchInput = document.getElementById('searchTeachers');

        // View Profile
        document.querySelectorAll('.view-profile-btn').forEach(button => {
            button.addEventListener('click', async () => {
                const teacherId = button.getAttribute('data-id');

                // Show loading state
                button.disabled = true;
                button.innerHTML = `
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Loading...
                `;

                try {
                    const response = await fetch(`/admin/api/teacher/${teacherId}`);

                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }

                    const data = await response.json();

                    // Validate response structure
                    if (data.status !== 'success' || !data.data || !data.data.teacher) {
                        throw new Error('Invalid response structure');
                    }

                    const teacher = data.data.teacher;

                    // Populate modal with teacher data
                    document.getElementById('teacherProfilePicture').src =
                        teacher.profile_picture
                            ? `/storage/${teacher.profile_picture}`
                            : `https://ui-avatars.com/api/?name=${encodeURIComponent(
                                `${teacher.fname} ${teacher.lname}`
                            )}&background=random`;

                    document.getElementById('teacherName').textContent =
                        `${teacher.fname} ${teacher.lname}`;
                    document.getElementById('teacherEmail').textContent =
                        teacher.email || 'Not provided';
                    document.getElementById('teacherPhone').textContent =
                        teacher.phone || 'Not provided';
                    document.getElementById('teacherAddress').textContent =
                        teacher.address || 'Not provided';
                    document.getElementById('teacherJoiningDate').textContent =
                        teacher.created_at ? new Date(teacher.created_at).toLocaleDateString() : 'Unknown';
                    document.getElementById('teacherStatus').textContent =
                        teacher.status || 'Unknown';
                    document.getElementById('teacherQualification').textContent =
                        teacher.qualification || 'Not provided';
                    document.getElementById('teacherSpecialization').textContent =
                        teacher.specialization || 'Not specified';
                    document.getElementById('teacherExperience').textContent =
                        teacher.experience || 'Not specified';
                    document.getElementById('teacherAccountStatus').textContent =
                        teacher.status === 'active' ? 'Active' : 'Inactive';

                    // Handle department if available
                    if (teacher.department) {
                        document.getElementById('teacherDepartment').textContent =
                            teacher.department.name || 'Not assigned';
                    } else {
                        document.getElementById('teacherDepartment').textContent = 'Not assigned';
                    }

                    // Show modal
                    teacherProfileModal.classList.remove('hidden');
                    document.body.classList.add('overflow-hidden');

                } catch (error) {
                    console.error('Error fetching teacher data:', error);
                    Toast.fire({
                        icon: 'error',
                        title: 'Error loading profile: ' + (error.message || 'Unknown error')
                    });
                } finally {
                    // Reset button state
                    button.disabled = false;
                    button.innerHTML = '<i class="fas fa-eye"></i>';
                }
            });
        });

        // Block/Unblock Teacher
        document.querySelectorAll('.block-teacher-btn, .unblock-teacher-btn').forEach(button => {
            button.addEventListener('click', () => {
                const teacherId = button.getAttribute('data-id');
                const isBlocking = button.classList.contains('block-teacher-btn');

                document.getElementById('blockModalTitle').textContent =
                    isBlocking ? 'Block Teacher' : 'Unblock Teacher';
                document.getElementById('blockModalMessage').textContent = isBlocking
                    ? 'Are you sure you want to block this teacher? They will not be able to access the system.'
                    : 'Are you sure you want to unblock this teacher? They will regain access to the system.';

                document.getElementById('confirmBlockBtn').textContent = isBlocking ? 'Block' : 'Unblock';
                document.getElementById('confirmBlockBtn').onclick = async () => {
                    try {
                        const response = await fetch(`/admin/teacher/status/${teacherId}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({})
                        });

                        const data = await response.json();

                        if (response.ok && data.status) {
                            // Update UI
                            const badge = document.getElementById(`status-badge-${teacherId}`);
                            const newStatus = badge.textContent.trim() === "Active" ? "Inactive" : "Active";

                            badge.textContent = newStatus;
                            badge.classList.toggle('bg-green-100', newStatus === "Active");
                            badge.classList.toggle('text-green-800', newStatus === "Active");
                            badge.classList.toggle('dark:bg-green-800', newStatus === "Active");
                            badge.classList.toggle('dark:text-green-100', newStatus === "Active");
                            badge.classList.toggle('bg-red-100', newStatus === "Inactive");
                            badge.classList.toggle('text-red-800', newStatus === "Inactive");
                            badge.classList.toggle('dark:bg-red-800', newStatus === "Inactive");
                            badge.classList.toggle('dark:text-red-100', newStatus === "Inactive");

                            blockConfirmModal.classList.add('hidden');

                            Toast.fire({
                                icon: 'success',
                                title: 'Status updated successfully',
                            });
                        } else {
                            throw new Error(data.message || 'Failed to update status');
                        }
                    } catch (error) {
                        console.error("Status update failed:", error);
                        blockConfirmModal.classList.add('hidden');
                        Toast.fire({
                            icon: 'error',
                            title: 'Failed to update status',
                        });
                    }
                };

                blockConfirmModal.classList.remove('hidden');
            });
        });

        // Close modals
        closeProfileModal.addEventListener('click', () => {
            teacherProfileModal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
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

            document.querySelectorAll('tbody tr').forEach(row => {
                const name = row.querySelector('td:first-child').textContent.toLowerCase();
                const email = row.querySelector('td:nth-child(2)').textContent.toLowerCase();

                const matchesSearch = name.includes(searchTerm) || email.includes(searchTerm);

                row.classList.toggle('hidden', !matchesSearch);
            });
        }

        searchInput.addEventListener('input', filterTeachers);
    </script>
@endsection
