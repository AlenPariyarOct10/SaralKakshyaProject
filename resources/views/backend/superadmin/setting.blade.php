@extends('backend.layout.superadmin-dashboard-layout')

@php
    $system = \App\Models\SystemSetting::first();
@endphp

@push('styles')
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
                        },
                    }
                }
            }
        }
    </script>
    <style type="text/tailwindcss">
        @layer utilities {
            .btn-primary {
                @apply px-6 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-colors;
            }

            .btn-success {
                @apply px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors;
            }

            .btn-light-green {
                @apply px-6 py-2 bg-green-400 text-white rounded-md hover:bg-green-400 focus:outline-none focus:ring-2 focus:ring-green-300 focus:ring-offset-2 transition-colors;
            }
            .btn-secondary {
                @apply px-6 py-2 bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-200 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300 dark:focus:ring-gray-600 focus:ring-offset-2 transition-colors;
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
            .scrollable-content {
                @apply overflow-y-auto;
                height: calc(100vh - 64px); /* Adjust based on header height */
            }
            .badge {
                @apply px-2 py-1 text-xs font-medium rounded-full;
            }
            .badge-success {
                @apply bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100;
            }
            .badge-warning {
                @apply bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100;
            }
            .badge-danger {
                @apply bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100;
            }
            .badge-info {
                @apply bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100;
            }
            .tab {
                @apply px-4 py-2 text-sm font-medium rounded-t-lg;
            }
            .tab.active {
                @apply bg-white dark:bg-gray-800 text-primary-600 dark:text-primary-400 border-b-2 border-primary-600 dark:border-primary-400;
            }
            .tab:not(.active) {
                @apply text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300;
            }
            .modal {
                @apply fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50 transition-opacity;
            }
            .modal-content {
                @apply bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full max-h-[90vh] overflow-y-auto;
            }
            .form-input {
                @apply bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5;
            }
            .form-label {
                @apply block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1;
            }
            .form-error {
                @apply text-xs text-red-500 mt-1;
            }
        }
    </style>
@endpush

@section('content')

    <!-- Main Content Area - Made Scrollable -->
    <main class="scrollable-content p-4 md:p-6">
        @if (session('success'))
            <div class="bg-green-100 border border-green-500 text-green-700 px-4 py-3 mb-3 rounded relative" role="alert">
                <strong class="font-bold">Success!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-500 text-red-700 px-4 py-3 mb-3 rounded relative" role="alert">
                <strong class="font-bold">Error!</strong>
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif
        @component('components.super-admin.landing-page-sections-manager')@endcomponent
        <!-- General Settings -->
        <div class="card mb-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">General Settings</h3>
            <form action="{{route('superadmin.general.update')}}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="space-y-6">
                <div class="flex">
                    <!-- System Logo -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">System Logo</label>
                        <div class="flex items-center space-x-4">
                            <!-- Logo Preview -->
                            <img id="logo-preview" src="{{ ($system->logo) ? asset($system->logo) : 'https://ui-avatars.com/api/?name=SKS&background=0D8ABC&color=fff&size=64' }}" alt="System Logo" class="w-16 rounded-md">

                            <!-- Styled Label to Trigger File Input -->
                            <label for="logo-chooser" class="btn-secondary cursor-pointer">
                                <i class="fas fa-upload mr-2"></i> Upload New Logo
                            </label>

                            <!-- File Input -->
                            <input type="file" id="logo-chooser" name="logo" class="hidden" accept="image/*">
                        </div>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Recommended size: 200x200 pixels. Max file size: 2MB.</p>
                    </div>
                    <!-- System Favicon -->
                    <div class="ml-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">System Favicon</label>
                        <div class="flex items-center space-x-4">
                            <img id="favicon-preview" src="{{($system->favicon)?asset($system->favicon):'https://ui-avatars.com/api/?name=SKS&background=0D8ABC&color=fff&size=64'}}" alt="System Logo" class="w-16 h-16 rounded-md">
                            <label for="favicon-chooser" class="btn-secondary cursor-pointer">
                                <i class="fas fa-upload mr-2"></i> Upload New Favicon
                            </label>
                            <input type="file" id="favicon-chooser" name="favicon" class="hidden" accept="image/*">

                        </div>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Recommended size: 200x200 pixels. Max file size: 2MB.</p>
                    </div>
                </div>
                <!-- System Name -->
                <div>
                    <label for="system-name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">System Name</label>
                    <input name="name" type="text" id="name" value="{{$system->name}}" class="bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">This name will be displayed throughout the system.</p>
                </div>
                <!-- System Description -->
                <div>
                    <label for="system-description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">System Description</label>
                    <input name="description" type="text" id="description" value="{{$system->description}}" class="bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                </div>
                <!-- Long Description -->
                <div>
                    <label for="system-description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Long System Description</label>
                    <input name="long_description" type="text" id="system-description" value="{{$system->long_description}}" class="bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                </div>
                <!-- Save Button -->
                <div class="flex justify-end">
                    <button class="btn-primary">
                        <i class="fas fa-save mr-2"></i> Save Changes
                    </button>
                </div>
            </div>
            </form>
        </div>



        <!-- Footer -->
        @component('components.backend.dashboard-footer')
        @endcomponent
    </main>
@endsection

@section('scripts')
    <script>
        // Image preview for logo
        document.getElementById('logo-chooser').addEventListener('change', function(event) {
            console.log("clicked");
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('logo-preview').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });

        // Image preview for favicon
        document.getElementById('favicon-chooser').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('favicon-preview').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
@endsection
