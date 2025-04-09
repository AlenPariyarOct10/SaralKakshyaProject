@extends('backend.layout.superadmin-dashboard-layout')

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


        <!-- Attendance Management -->
        <div class="card mb-6">

            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4 md:mb-0">Testimonial</h3>

                <div class="flex flex-col md:flex-row gap-4">
                    <div class="relative">
                        <button data-mode="new" id="addTestimonialBtn" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors">
                            Add New
                        </button>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-max border-collapse divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th scope="col" class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            User Name
                        </th>
                        <th scope="col" class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Stars</th>
                        <th scope="col" class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Description</th>
                        <th scope="col" class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Designation</th>
                        <th scope="col" class="px-4 md:px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Action</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($allTestimonials as $row)
                        <tr class="text-sm">
                            <td class="px-4 md:px-6 py-4 whitespace-nowrap">
                                <span class="font-medium text-gray-800 dark:text-white">{{$row->user_name}}</span>
                            </td>
                            <td class="px-4 md:px-6 py-4 whitespace-nowrap">
                                <span class="text-gray-500 dark:text-gray-400">{{$row->stars}}</span>
                            </td>
                            <td class="px-4 md:px-6 py-4">
                                <span class="block max-w-xs md:max-w-sm text-gray-500 dark:text-gray-400 break-words">{{$row->description}}</span>
                            </td>
                            <td class="px-4 md:px-6 py-4 whitespace-nowrap">
                                <span class="text-gray-500 dark:text-gray-400">{{$row->designation}}</span>
                            </td>
                            <td class="px-4 md:px-6 py-4">
                                <div class="flex flex-col sm:flex-row gap-2 justify-end">
                                    <button data-mode="edit" data-id="{{$row->id}}"
                                            class="editTestimonialBtn px-2 py-1 text-xs sm:text-sm bg-green-600 text-white rounded hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 transition">
                                        Edit
                                    </button>
                                    <button data-id="{{$row->id}}"
                                            class="deleteTestimonialBtn px-2 py-1 text-xs sm:text-sm bg-red-600 text-white rounded hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 transition">
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-4 md:px-6 py-4 text-center text-gray-800 dark:text-white" colspan="5">
                                No Items Found
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>


        </div>

    </main>
@endsection

@section("modals")

    <div id="deleteModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
        <div class="absolute inset-0 bg-black bg-opacity-50 backdrop-blur-sm" id="deleteModalOverlay"></div>
        <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full mx-4">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-white">Confirm Deletion</h3>
                    <button id="closeDeleteModal" class="p-1 rounded-md text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200" aria-label="Close modal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <p class="text-gray-600 dark:text-gray-400 mb-6">Are you sure you want to delete this testimonial? This action cannot be undone.</p>

                <div class="flex justify-end space-x-3">
                    <button id="cancelDeleteBtn" class="btn-secondary">Cancel</button>
                    <button id="confirmDeleteBtn" class="btn-danger">Delete</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Add Testimonial Modal -->
    <div id="addTestimonialModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
        <div class="absolute inset-0 bg-black bg-opacity-50 backdrop-blur-sm" id="addModalOverlay"></div>
        <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-white">Add Testimonial</h3>
                    <button id="closeAddModal" class="p-1 rounded-md text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200" aria-label="Close modal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <form action="{{route("admin.testimonial.store")}}" method="post" id="addTestimonialForm" enctype="multipart/form-data">
                    @csrf
                    <!-- Profile Picture Upload -->
                    <div class="mb-6">
                        <label for="addProfilePicture" class="form-label">Profile Picture</label>
                        <div class="flex items-center space-x-4">
                            <div id="addProfilePreview" class="w-20 h-20 bg-gray-200 rounded-full flex items-center justify-center overflow-hidden">
                                <span id="addPreviewPlaceholder" class="text-gray-400">No image</span>
                                <img id="addImagePreview" class="hidden w-full h-full object-cover" alt="Profile preview">
                            </div>
                            <div class="flex-1">
                                <input type="file" id="addProfilePicture" name="profilePicture"
                                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                                       accept="image/*">
                                <p class="mt-1 text-xs text-gray-500">JPG, PNG or GIF (Max. 2MB)</p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="addUserName" class="form-label">User Name</label>
                        <input type="text" id="addUserName" name="userName" class="form-input" placeholder="Enter user name" required>
                    </div>

                    <div class="mb-4">
                        <label for="addStars" class="form-label">Stars</label>
                        <select id="addStars" name="stars" class="form-input" required>
                            <option value="">Select Rating</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="addDesignation" class="form-label">Designation</label>
                        <input type="text" id="addDesignation" name="designation" class="form-input" placeholder="Enter designation" required>
                    </div>

                    <div class="mb-4">
                        <label for="addRank" class="form-label">Rank</label>
                        <input type="number" min="0" id="addRank" name="rank" class="form-input" placeholder="Enter rank" required>
                    </div>

                    <div class="mb-4">
                        <label for="addTestimonialStatus" class="form-label">Status</label>
                        <select id="addTestimonialStatus" name="testimonialStatus" class="form-input" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="addTestimonialDescription" class="form-label">Description</label>
                        <textarea id="addTestimonialDescription" name="testimonialDescription" class="form-input" rows="3" placeholder="Enter testimonial description" required></textarea>
                    </div>

                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" id="addCancelBtn" class="btn-secondary">Cancel</button>
                        <button type="submit" id="addSaveBtn" class="btn-primary">Save Testimonial</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Testimonial Modal -->
    <div id="editTestimonialModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
        <div class="absolute inset-0 bg-black bg-opacity-50 backdrop-blur-sm" id="editModalOverlay"></div>
        <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 id="editModalTitle" class="text-xl font-semibold text-gray-800 dark:text-white">Edit Testimonial</h3>
                    <button id="closeEditModal" class="p-1 rounded-md text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200" aria-label="Close modal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <form action="{{route('admin.testimonial.update')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="editTestimonialId" name="testimonialId" value="">

                    <!-- Profile Picture Upload -->
                    <div class="mb-6">
                        <label for="editProfilePicture" class="form-label">Profile Picture</label>
                        <div class="flex items-center space-x-4">
                            <div id="editProfilePreview" class="w-20 h-20 bg-gray-200 rounded-full flex items-center justify-center overflow-hidden">
                                <span id="editPreviewPlaceholder" class="text-gray-400">No image</span>
                                <img id="editImagePreview" class="hidden w-full h-full object-cover" alt="Profile preview">
                            </div>
                            <div class="flex-1">
                                <input type="file" id="editProfilePicture" name="profilePicture"
                                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                                       accept="image/*">
                                <p class="mt-1 text-xs text-gray-500">JPG, PNG or GIF (Max. 2MB)</p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="editUserName" class="form-label">User Name</label>
                        <input type="text" id="editUserName" name="userName" class="form-input" placeholder="Enter user name" required>
                    </div>

                    <div class="mb-4">
                        <label for="editStars" class="form-label">Stars</label>
                        <select id="editStars" name="stars" class="form-input" required>
                            <option value="">Select Rating</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="editDesignation" class="form-label">Designation</label>
                        <input type="text" id="editDesignation" name="designation" class="form-input" placeholder="Enter designation" required>
                    </div>

                    <div class="mb-4">
                        <label for="editRank" class="form-label">Rank</label>
                        <input type="number" min="0" id="editRank" name="rank" class="form-input" placeholder="Enter rank" required>
                    </div>

                    <div class="mb-4">
                        <label for="editTestimonialStatus" class="form-label">Status</label>
                        <select id="editTestimonialStatus" name="testimonialStatus" class="form-input" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="editTestimonialDescription" class="form-label">Description</label>
                        <textarea id="editTestimonialDescription" name="testimonialDescription" class="form-input" rows="3" placeholder="Enter testimonial description" required></textarea>
                    </div>

                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" id="editCancelBtn" class="btn-secondary">Cancel</button>
                        <button type="submit" id="editSaveBtn" class="btn-primary">Save Testimonial</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
        <div class="absolute inset-0 bg-black bg-opacity-50 backdrop-blur-sm" id="deleteModalOverlay"></div>
        <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full mx-4">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-white">Confirm Deletion</h3>
                    <button id="closeDeleteModal" class="p-1 rounded-md text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200" aria-label="Close modal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <p class="text-gray-600 dark:text-gray-400 mb-6">Are you sure you want to delete this testimonial? This action cannot be undone.</p>

                <div class="flex justify-end space-x-3">
                    <button id="cancelDeleteBtn" class="btn-secondary">Cancel</button>
                    <button wire:click="deleteItem()" id="confirmDeleteBtn" class="btn-danger">Delete</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section("scripts")

    <script>
        console.log("ready");
        document.addEventListener('DOMContentLoaded', function() {


            // DOM Elements - Add Modal
            const addTestimonialModal = document.getElementById('addTestimonialModal');
            const addTestimonialBtn = document.getElementById('addTestimonialBtn');
            const closeAddModal = document.getElementById('closeAddModal');
            const addCancelBtn = document.getElementById('addCancelBtn');
            const addTestimonialForm = document.getElementById('addTestimonialForm');
            const addProfilePicture = document.getElementById('addProfilePicture');
            const addImagePreview = document.getElementById('addImagePreview');
            const addPreviewPlaceholder = document.getElementById('addPreviewPlaceholder');

            // DOM Elements - Edit Modal
            const editTestimonialModal = document.getElementById('editTestimonialModal');
            const editTestimonialBtns = document.querySelectorAll('.editTestimonialBtn');
            const closeEditModal = document.getElementById('closeEditModal');
            const editCancelBtn = document.getElementById('editCancelBtn');
            const editTestimonialForm = document.getElementById('editTestimonialForm');
            const editProfilePicture = document.getElementById('editProfilePicture');
            const editImagePreview = document.getElementById('editImagePreview');
            const editPreviewPlaceholder = document.getElementById('editPreviewPlaceholder');
            const editTestimonialId = document.getElementById('editTestimonialId');

            // DOM Elements - Delete Modal
            const deleteModal = document.getElementById('deleteModal');
            const deleteTestimonialBtns = document.querySelectorAll('.deleteTestimonialBtn');
            const closeDeleteModal = document.getElementById('closeDeleteModal');
            const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
            const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');

            let testimonialToDelete = null;

            // Add Testimonial Modal
            if (addTestimonialBtn) {
                addTestimonialBtn.addEventListener('click', () => {
                    addTestimonialModal.classList.remove('hidden');
                });
            }

            if (closeAddModal) {
                closeAddModal.addEventListener('click', () => {
                    addTestimonialModal.classList.add('hidden');
                    addTestimonialForm.reset();
                    addImagePreview.classList.add('hidden');
                    addPreviewPlaceholder.classList.remove('hidden');
                });
            }

            if (addCancelBtn) {
                addCancelBtn.addEventListener('click', () => {
                    addTestimonialModal.classList.add('hidden');
                    addTestimonialForm.reset();
                    addImagePreview.classList.add('hidden');
                    addPreviewPlaceholder.classList.remove('hidden');
                });
            }



            // Add Profile Picture Preview
            if (addProfilePicture) {
                addProfilePicture.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(event) {
                            addImagePreview.src = event.target.result;
                            addImagePreview.classList.remove('hidden');
                            addPreviewPlaceholder.classList.add('hidden');
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }


            // Edit Testimonial Modal
            editTestimonialBtns.forEach(button => {
                button.addEventListener('click', () => {
                    const testimonialId = button.getAttribute('data-id');
                    editTestimonialId.value = testimonialId;

                    // In a real application, you would fetch the testimonial data here
                    // and populate the form fields
                    console.log(`Editing testimonial with ID: ${testimonialId}`);


                    fetch(`/superadmin/testimonial/get_testimonial/${testimonialId}`, {
                        method: "GET",
                        headers: {
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                            "Content-Type": "application/json",
                        },
                    })
                        .then(response => response.json())  // Automatically parses the JSON response
                        .then(data => {

                            console.log(data);
                            document.getElementById('editUserName').value = data.user_name;
                            document.getElementById('editStars').value = data.stars;
                            document.getElementById('editDesignation').value = data.designation;
                            document.getElementById('editRank').value = data.rank;
                            document.getElementById('editTestimonialStatus').value = data.status;
                            document.getElementById('editTestimonialDescription').value = data.description;
                            if(data.profile_picture)
                            {
                                document.getElementById('editImagePreview').src = "/storage/"+data.profile_picture;
                                document.getElementById('editImagePreview').classList.remove("hidden");
                                document.getElementById('editPreviewPlaceholder').classList.add("hidden");
                            }

                        })
                        .catch(error => {
                            console.error('Error:', error);  // Log any errors
                        });

                    editTestimonialModal.classList.remove('hidden');
                });
            });

            if (closeEditModal) {
                closeEditModal.addEventListener('click', () => {
                    editTestimonialModal.classList.add('hidden');
                    editTestimonialForm.reset();
                    editImagePreview.classList.add('hidden');
                    editPreviewPlaceholder.classList.remove('hidden');
                });
            }

            if (editCancelBtn) {
                editCancelBtn.addEventListener('click', () => {
                    editTestimonialModal.classList.add('hidden');
                    editTestimonialForm.reset();
                    editImagePreview.classList.add('hidden');
                    editPreviewPlaceholder.classList.remove('hidden');
                });
            }

            // Edit Profile Picture Preview
            if (editProfilePicture) {
                editProfilePicture.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(event) {
                            editImagePreview.src = event.target.result;
                            editImagePreview.classList.remove('hidden');
                            editPreviewPlaceholder.classList.add('hidden');
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }

            // Delete Testimonial Modal
            deleteTestimonialBtns.forEach(button => {
                button.addEventListener('click', () => {
                    testimonialToDelete = button.getAttribute('data-id');
                    console.log(`Preparing to delete testimonial with ID: ${testimonialToDelete}`);
                    deleteModal.classList.remove('hidden');
                });
            });

            if (closeDeleteModal) {
                closeDeleteModal.addEventListener('click', () => {
                    deleteModal.classList.add('hidden');
                    testimonialToDelete = null;
                });
            }

            if (cancelDeleteBtn) {
                cancelDeleteBtn.addEventListener('click', () => {
                    deleteModal.classList.add('hidden');
                    testimonialToDelete = null;
                });
            }

            // Handle confirm delete
            if (confirmDeleteBtn) {
                confirmDeleteBtn.addEventListener("click", () => {
                    if (testimonialToDelete) {
                        fetch(`/superadmin/testimonial/${testimonialToDelete}`, {
                            method: "DELETE",
                            headers: {
                                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                                "Content-Type": "application/json",
                            },
                        })
                            .then((response) => response.json())
                            .then((data) => {
                                if (data.success) {

                                    const row = document.querySelector(`.deleteTestimonialBtn[data-id="${testimonialToDelete}"]`).closest("tr");
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
                                testimonialToDelete = null;
                            })
                            .catch((error) => {
                                console.error("Error:", error.toString());
                            });
                    }
                });
            }

        });
    </script>
@endsection

