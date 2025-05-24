@extends("backend.layout.superadmin-dashboard-layout")

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

@section("title")
    Profile
@endsection

@section('content')
    <!-- Main Content Area -->
    <main class="p-4 md:p-6 flex-1 overflow-y-auto">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Profile Information -->
            <div class="md:col-span-1">
                <div class="card">
                    <div class="flex flex-col items-center">
                        <div class="relative mb-4">
                            <img src="{{ $user->profile_picture ? asset($user->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($user->fname . ' ' . $user->lname) . '&background=0D8ABC&color=fff' }}" alt="Profile" class="w-32 h-32 rounded-full object-cover">
                            <button type="button" id="openProfilePictureModal" class="absolute bottom-0 right-0 p-2 bg-primary-600 text-white rounded-full hover:bg-primary-700">
                                <i class="fas fa-camera"></i>
                            </button>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800 dark:text-white">{{$user->fname}} {{$user->lname}}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Super Admin</p>
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
                        <!-- Change Password Button -->
                        <div class="mt-6 w-full">
                            <button type="button" id="openChangePasswordModal" class="w-full py-2 px-4 bg-primary-600 text-white rounded-md hover:bg-primary-700 transition-colors">
                                <i class="fas fa-key mr-2"></i> Change Password
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Details -->
            <div class="md:col-span-2">
                <div class="card mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Personal Information</h3>
                        <button type="button" id="openPersonalInfoModal" class="text-primary-600 hover:text-primary-800">
                            <i class="fas fa-edit"></i>
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Full Name</label>
                            <p class="text-gray-800 dark:text-white">{{$user->fname}} {{$user->lname}}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Email</label>
                            <p class="text-gray-800 dark:text-white">{{($user->email) ? $user->email : "Not Set"}}</p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </main>
    <div id="personalInfoModal" class="modal hidden">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Edit Personal Information</h3>
                <button type="button" class="closeModal text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="personalInfoForm" method="POST" action="{{ route('superadmin.profile.update') }}">
                    @csrf
                    <div id="personalInfoMessage" class="success-message hidden"></div>
                    <div class="space-y-4">
                        <div class="flex space-x-4">
                            <div class="flex-1">
                                <label for="firstNamePersonal" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">First Name</label>
                                <input type="text" id="firstNamePersonal" name="fname" value="{{$user->fname}}" class="form-input">
                                <span class="form-error" id="fname-error"></span>
                            </div>
                            <div class="flex-1">
                                <label for="lastNamePersonal" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Last Name</label>
                                <input type="text" id="lastNamePersonal" name="lname" value="{{$user->lname}}" class="form-input">
                                <span class="form-error" id="lname-error"></span>
                            </div>
                        </div>
                        <div>
                            <label for="emailPersonal" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                            <input type="email" id="emailPersonal" name="email" value="{{$user->email}}" class="form-input">
                            <span class="form-error" id="email-error"></span>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="closeModal px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600">
                    Cancel
                </button>
                <button type="button" id="savePersonalInfo" class="btn-primary">
                    Save Changes
                </button>
            </div>
        </div>
    </div>

    <!-- Change Password Modal -->
    <div id="changePasswordModal" class="modal hidden">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Change Password</h3>
                <button type="button" class="closeModal text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="changePasswordForm" method="POST" action="{{ route('superadmin.profile.change-password') }}">
                    @csrf
                    <div id="passwordMessage" class="success-message hidden"></div>
                    <div class="space-y-4">
                        <div>
                            <label for="currentPassword" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Current Password</label>
                            <input type="password" id="currentPassword" name="current_password" class="form-input" required>
                            <span class="form-error" id="current_password-error"></span>
                        </div>
                        <div>
                            <label for="newPassword" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">New Password</label>
                            <input type="password" id="newPassword" name="password" class="form-input" required>
                            <div class="password-strength">
                                <div id="passwordStrengthMeter" class="password-strength-meter"></div>
                            </div>
                            <span class="form-error" id="password-error"></span>
                        </div>
                        <div>
                            <label for="confirmPassword" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Confirm New Password</label>
                            <input type="password" id="confirmPassword" name="password_confirmation" class="form-input" required>
                            <span class="form-error" id="password_confirmation-error"></span>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="closeModal px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600">
                    Cancel
                </button>
                <button type="button" id="savePassword" class="btn-primary">
                    Update Password
                </button>
            </div>
        </div>
    </div>

    <!-- Profile Picture Modal -->
    <div id="profilePictureModal" class="modal hidden">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Change Profile Picture</h3>
                <button type="button" class="closeModal text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="profilePictureForm" method="POST" action="{{ route('superadmin.profile.update-picture') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div class="modal-body">
                    <div id="pictureMessage" class="success-message hidden"></div>
                    <div class="flex flex-col items-center space-y-4">
                        <!-- Profile Picture Preview -->
                        <div class="relative w-32 h-32 rounded-full overflow-hidden bg-gray-200 dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600">
                            <img id="previewImage"
                                 src="{{ $user->profile_picture ? asset($user->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($user->fname . ' ' . $user->lname) . '&background=0D8ABC&color=fff&size=128' }}"
                                 alt="Profile Preview"
                                 class="w-full h-full object-cover">
                        </div>

                        <!-- File Upload Section -->
                        <div class="w-full text-center">
                            <label for="profilePictureUpload" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Upload a new profile picture
                            </label>
                            <input type="file" id="profilePictureUpload" name="profile_picture" accept="image/*" required>
                            <span class="form-error" id="profile_picture-error"></span>
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
                    <button type="button" id="uploadPicture" class="px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700">
                        Upload Picture
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('styles')
    <style type="text/tailwindcss">
        .hidden {
            display: none !important;
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
            .form-error {
                @apply text-red-500 text-sm mt-1;
            }
            .success-message {
                @apply text-green-500 text-sm mt-2 text-center;
            }
            .password-strength {
                @apply h-2 mt-1 rounded-full overflow-hidden;
            }
            .password-strength-meter {
                @apply h-full transition-all duration-300;
            }
            .strength-weak {
                @apply bg-red-500 w-1/4;
            }
            .strength-fair {
                @apply bg-yellow-500 w-2/4;
            }
            .strength-good {
                @apply bg-blue-500 w-3/4;
            }
            .strength-strong {
                @apply bg-green-500 w-full;
            }
        }
    </style>
@endpush





@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Modal elements
            const modals = {
                personalInfo: document.getElementById('personalInfoModal'),
                profilePicture: document.getElementById('profilePictureModal'),
                changePassword: document.getElementById('changePasswordModal')
            };

            // Open modal buttons
            document.getElementById('openPersonalInfoModal')?.addEventListener('click', function() {
                openModal('personalInfo');
            });

            document.getElementById('openProfilePictureModal')?.addEventListener('click', function() {
                openModal('profilePicture');
            });

            document.getElementById('openChangePasswordModal')?.addEventListener('click', function() {
                openModal('changePassword');
            });

            // Close modal buttons
            document.querySelectorAll('.closeModal').forEach(button => {
                button.addEventListener('click', closeAllModals);
            });

            // Close modals when clicking outside
            window.addEventListener('click', function(e) {
                Object.entries(modals).forEach(([key, modal]) => {
                    if (modal && e.target === modal) {
                        closeAllModals();
                    }
                });
            });

            // Profile picture preview
            const profilePictureUpload = document.getElementById('profilePictureUpload');
            const previewImage = document.getElementById('previewImage');

            if (profilePictureUpload && previewImage) {
                profilePictureUpload.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            previewImage.src = e.target.result;
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }

            // Password strength meter
            const newPasswordInput = document.getElementById('newPassword');
            const passwordStrengthMeter = document.getElementById('passwordStrengthMeter');

            if (newPasswordInput && passwordStrengthMeter) {
                newPasswordInput.addEventListener('input', function() {
                    const password = this.value;
                    const strength = checkPasswordStrength(password);

                    // Remove all classes
                    passwordStrengthMeter.classList.remove('strength-weak', 'strength-fair', 'strength-good', 'strength-strong');

                    // Add appropriate class based on strength
                    if (password.length > 0) {
                        if (strength < 2) {
                            passwordStrengthMeter.classList.add('strength-weak');
                        } else if (strength < 3) {
                            passwordStrengthMeter.classList.add('strength-fair');
                        } else if (strength < 4) {
                            passwordStrengthMeter.classList.add('strength-good');
                        } else {
                            passwordStrengthMeter.classList.add('strength-strong');
                        }
                    }
                });
            }

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
                    if (modal) {
                        modal.classList.add('hidden');
                    }
                });
                document.body.style.overflow = ''; // Restore scrolling

                // Clear error messages
                document.querySelectorAll('.form-error').forEach(error => {
                    error.textContent = '';
                });

                // Hide success messages
                document.querySelectorAll('.success-message').forEach(message => {
                    message.classList.add('hidden');
                    message.textContent = '';
                });
            }

            // Check password strength
            function checkPasswordStrength(password) {
                let strength = 0;

                // Length check
                if (password.length >= 8) strength += 1;

                // Contains lowercase
                if (/[a-z]/.test(password)) strength += 1;

                // Contains uppercase
                if (/[A-Z]/.test(password)) strength += 1;

                // Contains number
                if (/[0-9]/.test(password)) strength += 1;

                // Contains special character
                if (/[^a-zA-Z0-9]/.test(password)) strength += 1;

                return strength;
            }

            // Handle form submissions with AJAX

            // Personal Information Form
            const savePersonalInfoBtn = document.getElementById('savePersonalInfo');
            if (savePersonalInfoBtn) {
                savePersonalInfoBtn.addEventListener('click', async function() {
                    const form = document.getElementById('personalInfoForm');
                    const formData = new FormData(form);

                    try {
                        const response = await fetch(form.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                            }
                        });

                        const result = await response.json();

                        if (result.success) {
                            // Show success message
                            const messageDiv = document.getElementById('personalInfoMessage');
                            messageDiv.textContent = result.message || 'Profile updated successfully!';
                            messageDiv.classList.remove('hidden');

                            // Update the displayed information on the page after a short delay
                            setTimeout(() => {
                                location.reload();
                            }, 1500);
                        } else {
                            // Handle validation errors
                            if (result.errors) {
                                Object.keys(result.errors).forEach(field => {
                                    const errorElement = document.getElementById(`${field}-error`);
                                    if (errorElement) {
                                        errorElement.textContent = result.errors[field][0];
                                    }
                                });
                            }
                        }
                    } catch (error) {
                        console.error('Error updating profile:', error);
                    }
                });
            }

            // Change Password Form
            const savePasswordBtn = document.getElementById('savePassword');
            if (savePasswordBtn) {
                savePasswordBtn.addEventListener('click', async function() {
                    const form = document.getElementById('changePasswordForm');
                    const formData = new FormData(form);

                    try {
                        const response = await fetch(form.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                            }
                        });

                        const result = await response.json();

                        if (result.success) {
                            // Show success message
                            const messageDiv = document.getElementById('passwordMessage');
                            messageDiv.textContent = result.message || 'Password updated successfully!';
                            messageDiv.classList.remove('hidden');

                            // Clear the form
                            form.reset();

                            // Reset password strength meter
                            const passwordStrengthMeter = document.getElementById('passwordStrengthMeter');
                            if (passwordStrengthMeter) {
                                passwordStrengthMeter.classList.remove('strength-weak', 'strength-fair', 'strength-good', 'strength-strong');
                            }

                            // Close the modal after a delay
                            setTimeout(() => {
                                closeAllModals();
                            }, 2000);
                        } else {
                            // Handle validation errors
                            if (result.errors) {
                                Object.keys(result.errors).forEach(field => {
                                    const errorElement = document.getElementById(`${field}-error`);
                                    if (errorElement) {
                                        errorElement.textContent = result.errors[field][0];
                                    }
                                });
                            }
                        }
                    } catch (error) {
                        console.error('Error updating password:', error);
                    }
                });
            }

            // Profile Picture Upload
            const uploadPictureBtn = document.getElementById('uploadPicture');
            if (uploadPictureBtn) {
                uploadPictureBtn.addEventListener('click', async function() {
                    const form = document.getElementById('profilePictureForm');
                    const formData = new FormData(form);

                    try {
                        const response = await fetch(form.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                            }
                        });

                        const result = await response.json();

                        console.log(result);

                        if (result.success) {
                            // Show success message

                            const messageDiv = document.getElementById('pictureMessage');
                            messageDiv.textContent = result.message || 'Profile picture updated successfully!';
                            messageDiv.classList.remove('hidden');

                            // Update the displayed profile picture after a short delay
                            setTimeout(() => {
                                location.reload();
                            }, 1500);
                        } else {
                            // Handle validation errors
                            if (result.errors) {
                                Object.keys(result.errors).forEach(field => {
                                    const errorElement = document.getElementById(`${field}-error`);
                                    if (errorElement) {
                                        errorElement.textContent = result.errors[field][0];
                                    }
                                });
                            }
                        }
                    } catch (error) {
                        console.error('Error updating profile picture:', error);
                    }
                });
            }
        });
    </script>
@endsection
