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
            .section-title {
                @apply text-lg font-semibold text-gray-900 dark:text-white mb-4;
            }
            .info-label {
                @apply text-sm text-gray-500 dark:text-gray-400;
            }
            .info-value {
                @apply text-sm font-medium text-gray-900 dark:text-white;
            }
            .content-wrapper {
                @apply h-[calc(100vh-64px)] overflow-y-auto;
            }
            .error-message {
                @apply text-red-500 text-xs mt-1;
            }
        }
    </style>
@endpush

@section('content')
    <main class="p-4 md:p-6 h-screen overflow-y-auto">
        <!-- Profile Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">My Profile</h1>
            <p class="text-gray-600 dark:text-gray-400">Manage your account settings and preferences</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Profile Information -->
            <div class="lg:col-span-2">
                <div class="card p-6">
                    <h2 class="section-title">Profile Information</h2>
                    <form id="profileForm" class="space-y-6" action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Profile Picture -->
                        <div class="flex items-center space-x-6">
                            <div class="shrink-0 relative">
                                <img id="profileImagePreview" class="h-16 w-16 object-cover rounded-full"
                                     src="{{ $user->profile_picture ? asset('storage/'.$user->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($user->fname . ' ' . $user->lname) . '&background=random' }}"
                                     alt="Profile picture">
                                <div id="uploadSpinner" class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50 rounded-full hidden">
                                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </div>
                            </div>
                            <label class="block">
                                <span class="btn-secondary cursor-pointer">Change Photo</span>
                                <input type="file" id="profileImage" name="profile_picture" class="hidden" accept="image/*">
                                <div id="profileImageError" class="error-message"></div>
                            </label>
                        </div>

                        <!-- Name Fields -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="form-label" for="fname">First Name</label>
                                <input type="text" id="fname" name="fname" class="form-input" value="{{ old('fname', $user->fname) }}" required>
                                <div id="fnameError" class="error-message"></div>
                            </div>
                            <div>
                                <label class="form-label" for="lname">Last Name</label>
                                <input type="text" id="lname" name="lname" class="form-input" value="{{ old('lname', $user->lname) }}" required>
                                <div id="lnameError" class="error-message"></div>
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="form-label" for="email">Email Address</label>
                                <input type="email" id="email" name="email" class="form-input" value="{{ old('email', $user->email) }}" required>
                                <div id="emailError" class="error-message"></div>
                            </div>
                            <div>
                                <label class="form-label" for="phone">Phone Number</label>
                                <input type="tel" id="phone" name="phone" class="form-input" value="{{ old('phone', $user->phone) }}" required>
                                <div id="phoneError" class="error-message"></div>
                            </div>
                        </div>

                        <!-- Address -->
                        <div>
                            <label class="form-label" for="address">Address</label>
                            <textarea id="address" name="address" rows="3" class="form-input">{{ old('address', $user->address) }}</textarea>
                            <div id="addressError" class="error-message"></div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="btn-primary" id="submitProfile">
                                <span id="submitProfileText">Save Changes</span>
                                <span id="submitProfileSpinner" class="hidden ml-2">
                                    <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Account Settings -->
            <div class="lg:col-span-1">
                <div class="card p-6">
                    <h2 class="section-title">Account Settings</h2>

                    <!-- Change Password -->
                    <form id="passwordForm" class="space-y-4" action="{{ route('admin.profile.changePassword') }}" method="POST">
                        @method("PUT")
                        @csrf
                        <div>
                            <label class="form-label" for="currentPassword">Current Password</label>
                            <input type="password" id="currentPassword" name="current_password" class="form-input" required>
                            <div id="currentPasswordError" class="error-message"></div>
                        </div>
                        <div>
                            <label class="form-label" for="newPassword">New Password</label>
                            <input type="password" id="newPassword" name="password" class="form-input" required>
                            <div id="newPasswordError" class="error-message"></div>
                        </div>
                        <div>
                            <label class="form-label" for="confirmPassword">Confirm New Password</label>
                            <input type="password" id="confirmPassword" name="password_confirmation" class="form-input" required>
                            <div id="confirmPasswordError" class="error-message"></div>
                        </div>
                        <button type="submit" class="btn-primary w-full" id="submitPassword">
                            <span id="submitPasswordText">Update Password</span>
                            <span id="submitPasswordSpinner" class="hidden ml-2">
                                <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </span>
                        </button>
                    </form>

                    <hr class="my-6 border-gray-200 dark:border-gray-700">

                </div>
            </div>
        </div>
    </main>
@endsection

@section('scripts')
    <script>
        // Profile Image Preview
        document.getElementById('profileImage').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('profileImagePreview');
            const errorElement = document.getElementById('profileImageError');

            // Reset error
            errorElement.textContent = '';

            if (file) {
                // Validate file type
                const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                if (!validTypes.includes(file.type)) {
                    errorElement.textContent = 'Please select a valid image file (JPEG, PNG, GIF, WEBP)';
                    return;
                }

                // Validate file size (max 2MB)
                if (file.size > 2 * 1024 * 1024) {
                    errorElement.textContent = 'Image size must be less than 2MB';
                    return;
                }

                // Create preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });

        // Profile Form Submission with AJAX
        document.getElementById('profileForm').addEventListener('submit', async (e) => {
            e.preventDefault();

            const form = e.target;
            const formData = new FormData(form);
            const submitButton = document.getElementById('submitProfile');
            const submitText = document.getElementById('submitProfileText');
            const spinner = document.getElementById('submitProfileSpinner');

            // Clear previous errors
            document.querySelectorAll('.error-message').forEach(el => el.textContent = '');

            // Show loading state
            submitButton.disabled = true;
            submitText.textContent = 'Saving...';
            spinner.classList.remove('hidden');

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: formData
                });

                const data = await response.json();

                if (!response.ok) {
                    // Handle validation errors
                    if (data.errors) {
                        Object.entries(data.errors).forEach(([field, messages]) => {
                            const errorElement = document.getElementById(`${field}Error`);
                            if (errorElement) {
                                errorElement.textContent = messages[0];
                            }
                        });
                    } else {
                        throw new Error(data.message || 'Failed to update profile');
                    }
                } else {
                    // Success
                    alert('Profile updated successfully');
                    if (data.profile_picture) {
                        document.getElementById('profileImagePreview').src = data.profile_picture;
                    }
                }
            } catch (error) {
                console.error('Error updating profile:', error);
                alert(error.message || 'Failed to update profile');
            } finally {
                // Reset button state
                submitButton.disabled = false;
                submitText.textContent = 'Save Changes';
                spinner.classList.add('hidden');
            }
        });

        // Password Form Submission with AJAX
        document.getElementById('passwordForm').addEventListener('submit', async (e) => {
            e.preventDefault();

            const form = e.target;
            const formData = new FormData(form);
            const submitButton = document.getElementById('submitPassword');
            const submitText = document.getElementById('submitPasswordText');
            const spinner = document.getElementById('submitPasswordSpinner');

            // Clear previous errors
            document.querySelectorAll('.error-message').forEach(el => el.textContent = '');

            // Validate password match
            const newPassword = document.getElementById('newPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;

            if (newPassword.trim() === '' || confirmPassword.trim() === '') {
                document.getElementById('newPasswordError').textContent = 'Password cannot be empty';
                document.getElementById('confirmPasswordError').textContent = 'Password cannot be empty';
                return;
            }

            if (newPassword !== confirmPassword) {
                document.getElementById('confirmPasswordError').textContent = 'Passwords do not match';
                return;
            }

            // Show loading state
            submitButton.disabled = true;
            submitText.textContent = 'Updating...';
            spinner.classList.remove('hidden');

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: formData
                });

                const data = await response.json();

                if (!response.ok) {
                    // Handle validation errors
                    if (data.errors) {
                        let errorMessages = [];
                        Object.entries(data.errors).forEach(([field, messages]) => {
                            const errorElement = document.getElementById(`${field}Error`);
                            if (errorElement) {
                                errorElement.textContent = messages[0];
                            }
                            // Collect error messages for SweetAlert
                            errorMessages.push(messages[0]);
                        });

                        // Show error messages in SweetAlert
                        Swal.fire({
                            icon: 'error',
                            title: 'Password update failed',
                            html: errorMessages.join('<br>'), // Display all errors in the toast
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 5000,
                            timerProgressBar: true
                        });
                    } else {
                        throw new Error(data.message || 'Failed to update password');
                    }
                } else {
                    // Success Toast
                    Swal.fire({
                        icon: 'success',
                        title: 'Password updated successfully',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                    });
                    form.reset();
                }
            } catch (error) {
                // Error Toast
                Swal.fire({
                    icon: 'error',
                    title: 'An error occurred',
                    text: error.message || 'Failed to update password!',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 5000,
                    timerProgressBar: true
                });
                console.error('Error updating password:', error);
            } finally {
                // Reset button state
                submitButton.disabled = false;
                submitText.textContent = 'Update Password';
                spinner.classList.add('hidden');
            }
        });


    </script>
@endsection
