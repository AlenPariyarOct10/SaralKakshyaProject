@php use App\Models\SystemSetting; @endphp
@extends('backend.layout.superadmin-dashboard-layout')

@php
    $system = SystemSetting::first();
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

        @component('components.super-admin.landing-page-sections-manager')@endcomponent
            @if (session('success'))
                <div class="bg-green-100 border border-green-500 text-green-700 px-4 py-3 mb-3 rounded relative" role="alert">
                    <strong class="font-bold">Success!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
        <!-- General Settings -->
        <div class="card mb-6">
            @component('components.login.validation-summary') @endcomponent

            <div class="space-y-6">
                <form action="{{ route('superadmin.setting.contact.update') }}" method="POST" class="space-y-6">
                    @csrf
                    @method("PUT")
                <div class="flex flex-col">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Contact Information</h3>
                    <!-- Phone -->
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Address</label>
                        <input required name="address" type="text" id="address" value="{{$system->address}}"
                               class="bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                    </div>
                    <!-- Phone -->
                    <div>
                        <label for="phone-number"
                               class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phone
                            number</label>
                        <input required name="phone" type="number" id="phone-number" value="{{$system->phone}}"
                               class="bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                    </div>
                    <!-- Email -->
                    <div>
                        <label for="email"
                               class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                        <input required name="email" type="text" id="email" value="{{$system->email}}"
                               class="bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                    </div>
                    <!-- Facebook -->
                    <div>
                        <label for="facebook-url"
                               class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Facebook
                            URL</label>
                        <input name="facebook" type="text" id="facebook-url" value="{{$system->facebook}}"
                               class="bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                    </div>
                    <!-- Instagram -->
                    <div>
                        <label for="instagram-url"
                               class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Instagram
                            URL</label>
                        <input name="instagram" type="text" id="instagram-url" value="{{$system->instagram}}"
                               class="bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                    </div>
                    <!-- Twitter -->
                    <div>
                        <label for="twitter-url"
                               class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Twitter
                            URL</label>
                        <input name="twitter" type="text" id="twitter-url" value="{{$system->twitter}}"
                               class="bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                    </div>
                    <!-- Save Button -->
                    <div class="flex justify-end mt-4">
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
