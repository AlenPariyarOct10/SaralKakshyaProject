@extends("backend.layout.student-dashboard-layout")

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
@section("title", "Profile")
@section('content')
    <!-- Main Content Area -->
    <!-- Main Content Area - Made scrollable -->
    <main class="p-4 md:p-6 flex-1 overflow-y-auto">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Profile Information -->
            <div class="md:col-span-1">
                <div class="card">
                    <div class="flex flex-col items-center">
                        <div class="relative mb-4">
                            <img src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($user->fname . ' ' . $user->lname) . '&background=0D8ABC&color=fff' }}" alt="Profile" class="w-32 h-32 rounded-full">
                            <button id="openProfilePictureModal" class="absolute bottom-0 right-0 p-2 bg-primary-600 text-white rounded-full hover:bg-primary-700">
                                <i class="fas fa-camera"></i>
                            </button>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800 dark:text-white">{{$user->fname}} {{$user->lname}}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Student</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                         @if ($user->roll_number)
                            {{ "ID:". $user->roll_number }}
                        @else
                            <div class="bg-red-100 border border-red-400 dark:bg-red-400 text-red-700 dark:text-red-100 rounded-xl px-3 relative">
                                <span>Not Registered</span>
                            </div>
                            @endif
                        </p>

                        <div class="w-full mt-6 space-y-2">
                            <div class="flex items-center">
                                <i class="fas fa-envelope text-gray-500 dark:text-gray-400 w-6"></i>
                                <span class="text-sm text-gray-700 dark:text-gray-300 ml-2">{{$user->email}}</span>
                            </div>
                            @if($user->phone)
                                <div class="flex items-center">
                                    <i class="fas fa-phone text-gray-500 dark:text-gray-400 w-6"></i>
                                    <span class="text-sm text-gray-700 dark:text-gray-300 ml-2">{{$user->phone}}</span>
                                </div>
                            @endif
                            @if($user->address)
                                <div class="flex items-center">
                                    <i class="fas fa-map-marker-alt text-gray-500 dark:text-gray-400 w-6"></i>
                                    <span class="text-sm text-gray-700 dark:text-gray-300 ml-2">{{$user->address}}</span>
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
            </div>

            <!-- Profile Details -->
            <div class="md:col-span-2">
                <div class="card mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Personal Information</h3>
                        <button id="openPersonalInfoModal" class="text-primary-600 hover:text-primary-800">
                            <i class="fas fa-edit"></i>
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Full Name</label>
                            <p class="text-gray-800 dark:text-white">{{$user->fname}} {{$user->lname}}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Date of Birth</label>
                            <p class="text-gray-800 dark:text-white">{{($user->dob)?$user->dob->format('M d, Y'):"Not Set"}}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Email</label>
                            <p class="text-gray-800 dark:text-white">{{($user->email)?$user->email:"Not Set"}}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Phone</label>
                            <p class="text-gray-800 dark:text-white">{{($user->phone)?$user->phone:"Not Set"}}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Address</label>
                            <p class="text-gray-800 dark:text-white">{{($user->address)?$user->address:"Not Set"}}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Parents Name</label>
                            <p class="text-gray-800 dark:text-white">{{($user->guardian_name)?$user->guardian_name:"Not Set"}}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Parents Phone</label>
                            <p class="text-gray-800 dark:text-white">{{($user->guardian_phone)?$user->guardian_phone:"Not Set"}}</p>
                        </div>
                    </div>
                </div>

                <div class="card mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Academic Information</h3>
                        <button id="openAcademicInfoModal" class="text-primary-600 hover:text-primary-800">
                            <i class="fas fa-edit"></i>
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Program</label>
                            <p class="text-gray-800 dark:text-white">{{$program->program->name}}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Department</label>
                            <p class="text-gray-800 dark:text-white">{{$program->program->department->name}}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Batch</label>
                            <p class="text-gray-800 dark:text-white">{{$program->batch->batch}}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Semester</label>
                            <p class="text-gray-800 dark:text-white">{{$program->batch->semester}}</p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('styles')
    <style type="text/tailwindcss">
        .hidden {
            display: none !important;  /* Or visibility: hidden; */
        }
        @layer utilities {
            .btn-primary {
                @apply px-6 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-colors;
            }
            .card {
                @apply bg-white dark:bg-gray-800 rounded-lg shadow-md p-6;
            }
            .sidebar-item {
                @apply flex items-center gap-3 px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md transition-colors;
            }
            .sidebar-item.active {
                @apply bg-primary-50 dark:bg-gray-700 text-primary-600 dark:text-primary-400 font-medium;
            }
            .form-input {
                @apply w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-800 dark:border-gray-700 dark:text-white;
            }
            .modal {
                @apply fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50 transition-opacity;
            }
            .modal-content {
                @apply bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full max-h-[90vh] overflow-y-auto;
            }
            .modal-header {
                @apply flex items-center justify-between p-4 border-b dark:border-gray-700;
            }
            .modal-body {
                @apply p-4;
            }
            .modal-footer {
                @apply flex justify-end p-4 border-t dark:border-gray-700 gap-2;
            }
        }
    </style>
@endpush

@section('modals')
    <!-- Edit Profile Modal -->
    <div id="editProfileModal" class="modal hidden">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Edit Profile</h3>
                <button class="closeModal text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="editProfileForm">
                    <div class="space-y-4">
                        <div>
                            <label for="fullName" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Full Name</label>
                            <input type="text" id="fullName" name="fullName" value="{{$user->fname}} {{$user->lname}}" class="form-input">
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                            <input type="email" id="email" name="email" value="{{$user->email}}" class="form-input">
                        </div>
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phone</label>
                            <input type="tel" id="phone" name="phone" value="{{$user->phone}}" class="form-input">
                        </div>
                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Address</label>
                            <textarea id="address" name="address" rows="2" class="form-input">{{$user->address}}</textarea>
                        </div>
                        <div>
                            <label for="emergencyContact" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Emergency Contact</label>
                            <input type="text" id="emergencyContact" name="emergencyContact" value="Jane Doe (+1 555-987-6543)" class="form-input">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="closeModal px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600">
                    Cancel
                </button>
                <button class="btn-primary">
                    Save Changes
                </button>
            </div>
        </div>
    </div>

    <!-- Personal Information Modal -->
    <div id="personalInfoModal" class="modal hidden">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Edit Personal Information</h3>
                <button class="closeModal text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <form  id="personalInfoForm">
                    <div class="space-y-4">
                        <div class="flex space-x-4">
                            <div class="flex-1">
                                <label for="firstNamePersonal" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">First Name</label>
                                <input type="text" id="firstNamePersonal" name="fname" value="{{$user->fname}}" class="form-input">
                            </div>
                            <div class="flex-1">
                                <label for="lastNamePersonal" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Last Name</label>
                                <input type="text" id="lastNamePersonal" name="lname" value="{{$user->lname}}" class="form-input">
                            </div>
                        </div>
                        <div>
                            <label for="dob" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date of Birth</label>
                            <input type="date" id="dob" name="dob" value="{{$user->dob->format('M Y, d')}}" class="form-input">
                        </div>
                        <div>
                            <label for="emailPersonal" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                            <input type="email" id="emailPersonal" name="email" value="{{$user->email}}" class="form-input">
                        </div>
                        <div>
                            <label for="phonePersonal" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phone</label>
                            <input type="tel" id="phonePersonal" name="phone" value="{{$user->phone}}" class="form-input">
                        </div>
                        <div>
                            <label for="addressPersonal" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Address</label>
                            <textarea id="addressPersonal" name="address" rows="2" class="form-input">{{$user->address}}</textarea>
                        </div>
                        <div>
                            <label for="emergencyContactPersonal" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Parents Name</label>
                            <input type="text" id="emergencyContactPersonal" name="guardian_name" value="{{$user->guardian_name}}" class="form-input">
                        </div>
                        <div>
                            <label for="emergencyContactPersonal" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Parents Phone</label>
                            <input type="text" id="emergencyContactPersonal" name="guardian_phone" value="{{$user->guardian_phone}}" class="form-input">
                        </div>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button class="closeModal px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600">
                    Cancel
                </button>
                <button class="btn-primary">
                    Save Changes
                </button>
            </div>
        </div>
    </div>

    <!-- Academic Information Modal -->
    <div id="academicInfoModal" class="modal hidden">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Edit Academic Information</h3>
                <button class="closeModal text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="academicInfoForm">
                    <div class="space-y-4">
                        <div>
                            <label for="studentId" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Student ID</label>
                            <input type="text" id="studentId" name="studentId" value="STU001" class="form-input" readonly>
                        </div>
                        <div>
                            <label for="program" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Program</label>
                            <input type="text" id="program" name="program" value="Bachelor of Science" class="form-input">
                        </div>
                        <div>
                            <label for="major" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Major</label>
                            <input type="text" id="major" name="major" value="Computer Science" class="form-input">
                        </div>
                        <div>
                            <label for="year" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Year</label>
                            <select id="year" name="year" class="form-input">
                                <option value="1">1st Year</option>
                                <option value="2">2nd Year</option>
                                <option value="3" selected>3rd Year</option>
                                <option value="4">4th Year</option>
                            </select>
                        </div>
                        <div>
                            <label for="gpa" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">GPA</label>
                            <input type="text" id="gpa" name="gpa" value="3.8/4.0" class="form-input" readonly>
                        </div>
                        <div>
                            <label for="advisor" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Advisor</label>
                            <input type="text" id="advisor" name="advisor" value="Prof. David Wilson" class="form-input">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="closeModal px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600">
                    Cancel
                </button>
                <button class="btn-primary">
                    Save Changes
                </button>
            </div>
        </div>
    </div>

    <!-- Profile Picture Modal -->
    <div id="profilePictureModal" class="modal hidden">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Change Profile Picture</h3>
                <button class="closeModal text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="profilePictureForm" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div class="modal-body">
                    <div class="flex flex-col items-center space-y-4">
                        <!-- Profile Picture Preview -->
                        <div class="relative w-32 h-32 rounded-full overflow-hidden bg-gray-200 dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600">
                            <img id="previewImage"
                                 src="{{ Auth::user()->profile_picture ? asset('storage/profile_pictures/' . Auth::user()->profile_picture) : 'https://ui-avatars.com/api/?name=' . Auth::user()->fname . '+' . Auth::user()->lname . '&background=0D8ABC&color=fff&size=128' }}"
                                 alt="Profile Preview"
                                 class="w-full h-full object-cover">
                        </div>

                        <!-- File Upload Section -->
                        <div class="w-full text-center text-white">
                            <label for="profilePictureUpload" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Upload a new profile picture
                            </label>
                            <input type="file" id="profilePictureUpload" name="profile_picture" accept="image/*" required>
                        </div>

                        <!-- Upload Restrictions -->
                        <div class="w-full text-center">
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                Supported formats: <span class="font-semibold">JPG, PNG, GIF</span>. Max size: <span class="font-semibold">5MB</span>.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer flex justify-end space-x-2">
                    <button type="button" class="closeModal px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700">
                        Upload Picture
                    </button>
                </div>
            </form>

        </div>
    </div>
@endsection

@section("scripts")
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const form = document.getElementById("profilePictureForm");
            const fileInput = document.getElementById("profilePictureUpload");
            const previewImage = document.getElementById("previewImage");

            // Handle form submission
            form.addEventListener("submit", async function (e) {
                e.preventDefault(); // Prevent default form submission

                let formData = new FormData();
                formData.append("profile_picture", fileInput.files[0]);

                try {
                    let response = await fetch("{{ route('student.update_profile_picture') }}", {
                        method: "POST",
                        body: formData,
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        }
                    });

                    let data = await response.json();

                    if (data.status === "success") {
                        // Update the profile picture preview
                        previewImage.src = data.profile_picture;

                        // Show success message
                        alert(data.message);
                    } else {
                        alert("Failed to update profile picture.");
                    }
                } catch (error) {
                    console.error("Error uploading:", error);
                    alert("An error occurred while uploading.");
                }
            });

            // Preview the selected image before uploading
            fileInput.addEventListener("change", function (event) {
                let reader = new FileReader();
                reader.onload = function (e) {
                    previewImage.src = e.target.result;
                };
                reader.readAsDataURL(event.target.files[0]);
            });
        });
    </script>
@endsection
@section('js')

    document.addEventListener('DOMContentLoaded', function () {
    // Modal elements
    const modals = {
    editProfile: document.getElementById('editProfileModal'),
    personalInfo: document.getElementById('personalInfoModal'),
    academicInfo: document.getElementById('academicInfoModal'),
    profilePicture: document.getElementById('profilePictureModal')
    };

    // Open modal buttons
    document.getElementById('openEditProfileModal')?.addEventListener('click', () => openModal('editProfile'));
    document.getElementById('openPersonalInfoModal')?.addEventListener('click', () => openModal('personalInfo'));
    document.getElementById('openAcademicInfoModal')?.addEventListener('click', () => openModal('academicInfo'));
    document.getElementById('openProfilePictureModal')?.addEventListener('click', () => openModal('profilePicture'));

    // Close modal buttons
    document.querySelectorAll('.closeModal').forEach(button => {
    button.addEventListener('click', closeAllModals);
    });

    // Close modals when clicking outside
    window.addEventListener('click', (e) => {
    Object.values(modals).forEach(modal => {
    if (e.target === modal) {
    closeAllModals();
    }
    });
    });

    // Profile picture preview
    const profilePictureUpload = document.getElementById('profilePictureUpload');
    const previewImage = document.getElementById('previewImage');

    profilePictureUpload?.addEventListener('change', (e) => {
    const file = e.target.files[0];
    if (file) {
    const reader = new FileReader();
    reader.onload = (e) => {
    previewImage.src = e.target.result;
    };
    reader.readAsDataURL(file);
    }
    });

    // Functions to handle modals
    function openModal(modalName) {
    closeAllModals();
    if (modals[modalName]) {
    modals[modalName].classList.remove('hidden');
    document.body.style.overflow = 'hidden'; // Prevent scrolling when modal is open
    }
    }

    function closeAllModals() {
    Object.values(modals).forEach(modal => {
    modal.classList.add('hidden');
    });
    document.body.style.overflow = ''; // Restore scrolling
    }
    });
@endsection

