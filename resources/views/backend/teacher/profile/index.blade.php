@php use Illuminate\Support\Facades\Auth; @endphp
@extends("backend.layout.teacher-dashboard-layout")

@php
    $user = Auth::user();
@endphp

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
    {{$user?->profile_picture}}
@endsection

@section('content')
    <div class="min-h-screen bg-gray-50 overflow-auto pb-20">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 py-8">

            {{-- Page Header --}}
            <div class="mb-8">
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800">My Profile</h1>
                <p class="text-gray-600 mt-1">Manage your personal information and account settings</p>
            </div>

            {{-- Profile Card --}}
            <div class="bg-white rounded-xl shadow-sm overflow-hidden transition-all duration-300 hover:shadow-md">
                {{-- Profile Header --}}
                <div class="relative bg-gradient-to-r from-blue-700 to-indigo-600 text-white p-6 md:p-8">
                    <div class="flex flex-col md:flex-row items-start md:items-center gap-6">
                        <div class="relative group">
                            <div class="w-24 h-24 md:w-32 md:h-32 rounded-full overflow-hidden border-4 border-white/80 shadow-lg transition-all duration-300 group-hover:border-white">
                                @if($teacher?->profile_picture)
                                    <img src="{{ asset('storage/' . $teacher->profile_picture) }}"
                                         alt="{{ $teacher->fname }} {{ $teacher->lname }}"
                                         class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-blue-500 to-indigo-500 flex items-center justify-center">
                                        <span class="text-2xl font-bold text-white/90">
                                            {{ strtoupper(substr($teacher->fname, 0, 1) . substr($teacher->lname, 0, 1)) }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                            <div class="absolute -bottom-2 right-0 transform translate-y-1/2">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold inline-flex items-center gap-1.5 shadow-sm
                                    {{ $teacher->status === 'active' ? 'bg-emerald-100 text-emerald-800' :
                                       ($teacher->status === 'inactive' ? 'bg-red-100 text-red-800' :
                                        'bg-amber-100 text-amber-800') }}">
                                    <span class="h-2 w-2 rounded-full
                                        {{ $teacher->status === 'active' ? 'bg-emerald-500' :
                                           ($teacher->status === 'inactive' ? 'bg-red-500' :
                                            'bg-amber-500') }}"></span>
                                    {{ ucfirst(str_replace('_', ' ', $teacher->status)) }}
                                </span>
                            </div>
                        </div>

                        <div class="flex-1 z-10">
                            <h1 class="text-3xl font-bold mb-1">{{ $teacher->fname }} {{ $teacher->lname }}</h1>

                            <div class="flex flex-wrap gap-x-6 gap-y-3 mt-3 text-white/90">
                                <div class="flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 opacity-80" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                    {{ $teacher->subject }}
                                </div>
                                <div class="flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 opacity-80" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    {{ $teacher->email }}
                                </div>
                                <div class="flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 opacity-80" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                    {{ $teacher->phone }}
                                </div>
                                <div class="flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 opacity-80" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    Joined {{ $teacher->created_at->format('F Y') }}
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Decorative Element --}}
                    <div class="absolute right-0 bottom-0 opacity-10 transform translate-x-1/4 translate-y-1/4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="240" height="240" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                    </div>
                </div>

                {{-- Profile Edit Form --}}
                <div class="p-6 md:p-8">
                    <div class="mb-6">
                        <h2 class="text-xl font-bold text-gray-800">Edit Profile Information</h2>
                        <p class="text-gray-600 text-sm mt-1">Update your personal details below</p>
                    </div>

                    <form action="{{ route('teacher.profile.update', $teacher) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-6">
                            {{-- Personal Information Section --}}
                            <div class="md:col-span-2">
                                <h3 class="text-md font-semibold text-gray-700 pb-2 border-b border-gray-200 mb-4">Personal Information</h3>
                            </div>

                            {{-- First Name --}}
                            <div>
                                <label for="fname" class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                                <input type="text" name="fname" id="fname" value="{{ old('fname', $teacher->fname) }}"
                                       class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors">
                                @error('fname') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Last Name --}}
                            <div>
                                <label for="lname" class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                                <input type="text" name="lname" id="lname" value="{{ old('lname', $teacher->lname) }}"
                                       class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors">
                                @error('lname') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Gender --}}
                            <div>
                                <label for="gender" class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
                                <select name="gender" id="gender"
                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors">
                                    <option value="male" {{ old('gender', $teacher->gender) === 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender', $teacher->gender) === 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ old('gender', $teacher->gender) === 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('gender') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Date of Birth --}}
                            <div>
                                <label for="dob" class="block text-sm font-medium text-gray-700 mb-1">Date of Birth</label>
                                <input type="date" name="dob" id="dob" value="{{ old('dob', $teacher->dob) }}"
                                       class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors">
                                @error('dob') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Contact Information Section --}}
                            <div class="md:col-span-2 pt-4">
                                <h3 class="text-md font-semibold text-gray-700 pb-2 border-b border-gray-200 mb-4">Contact Information</h3>
                            </div>

                            {{-- Phone --}}
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                                <input type="text" name="phone" id="phone" value="{{ old('phone', $teacher->phone) }}"
                                       class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors">
                                @error('phone') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Professional Information Section --}}
                            <div class="md:col-span-2 pt-4">
                                <h3 class="text-md font-semibold text-gray-700 pb-2 border-b border-gray-200 mb-4">Professional Information</h3>
                            </div>

                            {{-- Subject --}}
                            <div>
                                <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                                <input type="text" name="subject" id="subject" value="{{ old('subject', $teacher->subject) }}"
                                       class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors">
                                @error('subject') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Profile Section --}}
                            <div class="md:col-span-2 pt-4">
                                <h3 class="text-md font-semibold text-gray-700 pb-2 border-b border-gray-200 mb-4">Profile Picture</h3>
                            </div>

                            {{-- Profile Picture --}}
                            <div class="md:col-span-2">
                                <label for="profile_picture" class="block text-sm font-medium text-gray-700 mb-3">Upload New Picture</label>
                                <div class="flex items-center space-x-6">
                                    <div class="shrink-0">
                                        <div class="h-16 w-16 rounded-full overflow-hidden bg-gray-100 border border-gray-200">
                                            @if($teacher?->profile_picture)
                                                <img id="preview-image" src="{{ asset('storage/' . $teacher->profile_picture) }}"
                                                     class="h-full w-full object-cover" alt="Profile preview">
                                            @else
                                                <div id="preview-placeholder" class="h-full w-full flex items-center justify-center bg-gray-100 text-gray-400">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                    </svg>
                                                </div>
                                                <img id="preview-image" class="h-full w-full object-cover hidden" alt="Profile preview">
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <input type="file" name="profile_picture" id="profile_picture" accept="image/*"
                                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0
                                                    file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700
                                                    hover:file:bg-blue-100 transition-colors">
                                        <p class="mt-1 text-xs text-gray-500">PNG, JPG or GIF up to 2MB</p>
                                        @error('profile_picture') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Submit Button --}}
                        <div class="flex justify-start pt-4">
                            <button type="submit"
                                    class="px-5 py-2.5 bg-blue-600 text-white font-medium rounded-lg shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200">
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section("scripts")
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const profilePictureInput = document.getElementById('profile_picture');
            const previewImage = document.getElementById('preview-image');
            const previewPlaceholder = document.getElementById('preview-placeholder');

            if (profilePictureInput && previewImage) {
                profilePictureInput.addEventListener('change', function(e) {
                    if (e.target.files.length > 0) {
                        const file = e.target.files[0];
                        const reader = new FileReader();

                        reader.onload = function(e) {
                            previewImage.src = e.target.result;
                            previewImage.classList.remove('hidden');
                            if (previewPlaceholder) {
                                previewPlaceholder.classList.add('hidden');
                            }
                        }

                        reader.readAsDataURL(file);
                    }
                });
            }
        });
    </script>
@endsection
