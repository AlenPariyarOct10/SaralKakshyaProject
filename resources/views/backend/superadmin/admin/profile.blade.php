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
    Admin Profile - {{$admin->fname}} {{$admin->lname}}
@endsection

@section('content')
    <!-- Main Content Area -->
    <main class="p-4 md:p-6 flex-1 overflow-y-auto">
        <!-- Header with Back Button -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center space-x-4">
                <a href="{{ route('superadmin.admin-management') }}" class="flex items-center text-primary-600 hover:text-primary-800 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Admins
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Admin Profile</h1>
                    <p class="text-gray-600 dark:text-gray-400">View admin account details and information</p>
                </div>
            </div>

        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Profile Information -->
            <div class="md:col-span-1">
                <div class="card">
                    <div class="flex flex-col items-center">
                        <div class="relative mb-4">
                            <img src="{{ $admin->profile_picture ? asset('/storage/'.$admin->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($admin->fname . ' ' . $admin->lname) . '&background=0D8ABC&color=fff' }}"
                                 alt="Profile" class="w-32 h-32 rounded-full object-cover border-4 border-gray-200 dark:border-gray-600">
                            <div class="absolute bottom-2 right-2">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                    {{ $admin->is_approved ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                    <i class="fas fa-circle mr-1 text-xs"></i>
                                    {{ ($admin->is_approved ? "Active" : "Blocked")}}
                                </span>
                            </div>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800 dark:text-white">{{$admin->fname}} {{$admin->lname}}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Administrator</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{$admin->institute->name}}</p>

                        <!-- Contact Information -->
                        <div class="w-full mt-6 space-y-3">
                            <div class="flex items-center">
                                <i class="fas fa-envelope text-gray-500 dark:text-gray-400 w-6"></i>
                                <span class="text-sm text-gray-700 dark:text-gray-300 ml-2">{{$admin->email}}</span>
                            </div>
                            @if($admin->phone)
                                <div class="flex items-center">
                                    <i class="fas fa-phone text-gray-500 dark:text-gray-400 w-6"></i>
                                    <span class="text-sm text-gray-700 dark:text-gray-300 ml-2">{{$admin->phone}}</span>
                                </div>
                            @endif
                            @if($admin->address)
                                <div class="flex items-center">
                                    <i class="fas fa-map-marker-alt text-gray-500 dark:text-gray-400 w-6"></i>
                                    <span class="text-sm text-gray-700 dark:text-gray-300 ml-2">{{$admin->address}}</span>
                                </div>
                            @endif
                            <div class="flex items-center">
                                <i class="fas fa-calendar text-gray-500 dark:text-gray-400 w-6"></i>
                                <span class="text-sm text-gray-700 dark:text-gray-300 ml-2">Joined {{ $admin->created_at->format('M d, Y') }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-clock text-gray-500 dark:text-gray-400 w-6"></i>
                                <span class="text-sm text-gray-700 dark:text-gray-300 ml-2">Last updated {{ $admin->updated_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Profile Details -->
            <div class="md:col-span-2">
                <!-- Personal Information -->
                <div class="card mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Personal Information</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">First Name</label>
                            <p class="text-gray-800 dark:text-white">{{$admin->fname}}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Last Name</label>
                            <p class="text-gray-800 dark:text-white">{{$admin->lname}}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Email Address</label>
                            <p class="text-gray-800 dark:text-white">{{$admin->email}}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Phone Number</label>
                            <p class="text-gray-800 dark:text-white">{{$admin->phone ?? 'Not provided'}}</p>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Address</label>
                            <p class="text-gray-800 dark:text-white">{{$admin->address ?? 'Not provided'}}</p>
                        </div>
                    </div>
                </div>
                <!-- Institute Information -->
                <div class="card mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Institute Information</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Name</label>
                            <p class="text-gray-800 dark:text-white">{{$admin->institute->name}}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Email</label>
                            <p class="text-gray-800 dark:text-white">{{$admin->institute->email}}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Address</label>
                            <p class="text-gray-800 dark:text-white">{{$admin->institute->address}}</p>
                        </div>

                    </div>
                </div>

                <!-- Account Information -->
                <div class="card mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Account Information</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Account Status</label>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                {{ $admin->is_approved ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                {{ ($admin->is_approved? "Active": "Blocked")}}
                            </span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Role</label>
                            <p class="text-gray-800 dark:text-white">Administrator</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Account Created</label>
                            <p class="text-gray-800 dark:text-white">{{ $admin->created_at->format('F d, Y \a\t g:i A') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Last Updated</label>
                            <p class="text-gray-800 dark:text-white">{{ $admin->updated_at->format('F d, Y \a\t g:i A') }}</p>
                        </div>
                        @if($admin->email_verified_at)
                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Email Verified</label>
                                <p class="text-gray-800 dark:text-white">{{ $admin->email_verified_at->format('F d, Y \a\t g:i A') }}</p>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </main>


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
        }
    </style>
@endpush

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Modal elements
            const modals = {
                editAdmin: document.getElementById('editAdminModal'),
                resetPassword: document.getElementById('resetPasswordModal')
            };

            // Open modal buttons
            document.getElementById('editAdminBtn')?.addEventListener('click', function() {
                openModal('editAdmin');
            });

            document.getElementById('resetPasswordBtn')?.addEventListener('click', function() {
                openModal('resetPassword');
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

            // Profile picture preview for edit modal
            const editProfilePicture = document.getElementById('editProfilePicture');
            const editProfilePreview = document.getElementById('editProfilePreview');

            if (editProfilePicture && editProfilePreview) {
                editProfilePicture.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            editProfilePreview.src = e.target.result;
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }



        });
    </script>
@endsection
