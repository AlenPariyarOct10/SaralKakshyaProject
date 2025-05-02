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
                    <form id="profileForm" class="space-y-6">
                        <!-- Profile Picture -->
                        <div class="flex items-center space-x-6">
                            <div class="shrink-0">
                                <img class="h-16 w-16 object-cover rounded-full"
                                     src="{{ asset($user->profile_picture) ?? 'https://ui-avatars.com/api/?name='.urlencode($user->fname.' '.$user->lname) }}"
                                     alt="Profile picture">
                            </div>
                            <label class="block">
                                <span class="btn-secondary">Change Photo</span>
                                <input type="file" class="hidden" accept="image/*">
                            </label>
                        </div>

                        <!-- Name Fields -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="form-label" for="fname">First Name</label>
                                <input type="text" id="fname" name="fname" class="form-input" value="{{ $user->fname }}" required>
                            </div>
                            <div>
                                <label class="form-label" for="lname">Last Name</label>
                                <input type="text" id="lname" name="lname" class="form-input" value="{{ $user->lname }}" required>
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="form-label" for="email">Email Address</label>
                                <input type="email" id="email" name="email" class="form-input" value="{{ $user->email }}" required>
                            </div>
                            <div>
                                <label class="form-label" for="phone">Phone Number</label>
                                <input type="tel" id="phone" name="phone" class="form-input" value="{{ $user->phone }}" required>
                            </div>
                        </div>

                        <!-- Address -->
                        <div>
                            <label class="form-label" for="address">Address</label>
                            <textarea id="address" name="address" rows="3" class="form-input">{{ $user->address }}</textarea>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>

            </div>

            <!-- Account Settings -->
            <div class="lg:col-span-1">
                <div class="card p-6">
                    <h2 class="section-title">Account Settings</h2>

                    <!-- Change Password -->
                    <form id="passwordForm" class="space-y-4">
                        <div>
                            <label class="form-label" for="currentPassword">Current Password</label>
                            <input type="password" id="currentPassword" name="currentPassword" class="form-input" required>
                        </div>
                        <div>
                            <label class="form-label" for="newPassword">New Password</label>
                            <input type="password" id="newPassword" name="newPassword" class="form-input" required>
                        </div>
                        <div>
                            <label class="form-label" for="confirmPassword">Confirm New Password</label>
                            <input type="password" id="confirmPassword" name="confirmPassword" class="form-input" required>
                        </div>
                        <button type="submit" class="btn-primary w-full">Update Password</button>
                    </form>

                    <hr class="my-6 border-gray-200 dark:border-gray-700">

                    <!-- Notification Settings -->
                    <div class="space-y-4">
                        <h3 class="text-sm font-medium text-gray-900 dark:text-white">Notification Settings</h3>
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input type="checkbox" class="form-checkbox" checked>
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Email Notifications</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="form-checkbox" checked>
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">SMS Notifications</span>
                            </label>
                        </div>
                    </div>

                    <hr class="my-6 border-gray-200 dark:border-gray-700">

                    <!-- Danger Zone -->
                    <div>
                        <h3 class="text-sm font-medium text-red-600 dark:text-red-400">Danger Zone</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Once you delete your account, there is no going back.</p>
                        <button type="button" class="mt-4 btn-danger w-full">Delete Account</button>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('scripts')
    <script>
        // Profile Form Submission
        document.getElementById('profileForm').addEventListener('submit', async (e) => {
            e.preventDefault();

            try {
                // In a real application, you would send the form data to the server
                // For this demo, we'll just show a success message
                alert('Profile updated successfully');
            } catch (error) {
                console.error('Error updating profile:', error);
                alert('Failed to update profile');
            }
        });

        // Password Form Submission
        document.getElementById('passwordForm').addEventListener('submit', async (e) => {
            e.preventDefault();

            const newPassword = document.getElementById('newPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;

            if (newPassword !== confirmPassword) {
                alert('New passwords do not match');
                return;
            }

            try {
                // In a real application, you would send the form data to the server
                // For this demo, we'll just show a success message
                alert('Password updated successfully');
                e.target.reset();
            } catch (error) {
                console.error('Error updating password:', error);
                alert('Failed to update password');
            }
        });

        // Profile Picture Upload
        document.querySelector('input[type="file"]').addEventListener('change', async (e) => {
            const file = e.target.files[0];
            if (file) {
                try {
                    // In a real application, you would upload the file to the server
                    // For this demo, we'll just show a success message
                    alert('Profile picture updated successfully');
                } catch (error) {
                    console.error('Error uploading profile picture:', error);
                    alert('Failed to upload profile picture');
                }
            }
        });
    </script>
@endsection
