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

@section("title")
    Profile
@endsection

@section('content')
    <div class="min-h-screen bg-gray-50 overflow-auto pb-20">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 py-8">
            {{-- Page Header --}}
            <div class="mb-8">
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800">My Profile</h1>
                <p class="text-gray-600 mt-1">Manage your personal information and account settings</p>
                <a href="{{route('teacher.profile.routine.show')}}" class="inline-flex items-center px-6 py-1 mt-2 bg-blue-600 text-white font-medium rounded-md shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Setup My Routine
                </a>
            </div>

            {{-- Profile Card --}}
            <div class="bg-white rounded-xl shadow-sm overflow-hidden transition-all duration-300 hover:shadow-md">
                {{-- Profile Header --}}
                <div class="relative bg-gradient-to-r from-blue-700 to-indigo-600 text-white p-6 md:p-8">
                    <div class="flex flex-col md:flex-row items-start md:items-center gap-6">
                        <div class="relative group">
                            <div class="w-24 h-24 md:w-32 md:h-32 rounded-full overflow-hidden border-4 border-white/80 shadow-lg">
                                @if($teacher?->profile_picture)
                                    <img src="{{ asset('storage/' . $teacher->profile_picture) }}" alt="{{ $teacher->fname }} {{ $teacher->lname }}"
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-blue-500 to-indigo-500 flex items-center justify-center">
                                        <span class="text-2xl font-bold text-white/90">
                                            {{ strtoupper(substr($teacher->fname, 0, 1) . substr($teacher->lname, 0, 1)) }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="flex-1">
                            <h1 class="text-3xl font-bold mb-1">{{ $teacher->fname }} {{ $teacher->lname }}</h1>
                            <div class="flex flex-wrap gap-x-6 gap-y-3 mt-3 text-white/90">

                                <div class="flex items-center gap-2">
                                    ✉️ {{ $teacher->email }}
                                </div>
                                <div class="flex items-center gap-2">
                                    📞 {{ $teacher->phone }}
                                </div>
                                <div class="flex items-center gap-2">
                                    📅 Joined {{ $teacher->created_at->format('F, Y') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Profile Edit Form --}}
                <div class="p-6 md:p-8">
                    <div class="mb-6">
                        <h2 class="text-xl font-bold text-gray-800">Edit Profile Information</h2>
                        <p class="text-gray-600 text-sm mt-1">Update your personal details below</p>
                    </div>

                    <form action="{{ route('teacher.profile.update', $teacher->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- First Name --}}
                            <div>
                                <label for="fname" class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                                <input type="text" name="fname" id="fname" value="{{ old('fname', $teacher->fname) }}"
                                       class="block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                @error('fname') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Last Name --}}
                            <div>
                                <label for="lname" class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                                <input type="text" name="lname" id="lname" value="{{ old('lname', $teacher->lname) }}"
                                       class="block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                @error('lname') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Gender --}}
                            <div>
                                <label for="gender" class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
                                <select name="gender" id="gender"
                                        class="block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="male" {{ old('gender', $teacher->gender) === 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender', $teacher->gender) === 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ old('gender', $teacher->gender) === 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('gender') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- DOB --}}
                            <div>
                                <label for="dob" class="block text-sm font-medium text-gray-700 mb-1">Date of Birth</label>
                                <input type="date" name="dob" id="dob" value="{{ old('dob', $teacher->dob) }}"
                                       class="block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                @error('dob') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Phone --}}
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                                <input type="text" name="phone" id="phone" value="{{ old('phone', $teacher->phone) }}"
                                       class="block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                @error('phone') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Email --}}
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                <input type="text" name="email" id="email" value="{{ old('email', $teacher->email) }}"
                                       class="block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                @error('email') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Address --}}
                            <div>
                                <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                                <input type="text" name="address" id="address" value="{{ old('address', $teacher->address) }}"
                                       class="block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Eg. Nepal"
                                >
                                @error('address') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Qualification --}}
                            <div>
                                <label for="qualification" class="block text-sm font-medium text-gray-700 mb-1">Qualification</label>
                                <input type="text" name="email" id="email" value="{{ old('qualification', $teacher->qualification) }}"
                                       class="block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                placeholder="MCA, M.Tech, CSIT"
                                >
                                @error('qualification') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Upload Picture --}}
                            <div class="md:col-span-2">
                                <label for="profile_picture" class="block text-sm font-medium text-gray-700 mb-3">Upload New Picture</label>
                                <div class="flex items-center space-x-6">
                                    <div class="shrink-0">
                                        <div class="h-16 w-16 rounded-full overflow-hidden bg-gray-100 border border-gray-200">
                                            @if($teacher?->profile_picture)
                                                <img id="preview-image" src="{{ asset('storage/' . $teacher->profile_picture) }}" class="h-full w-full object-cover" alt="Profile preview">
                                            @else
                                                <div id="preview-placeholder" class="h-full w-full flex items-center justify-center bg-gray-100 text-gray-400">
                                                    <svg class="h-8 w-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path d="M16 7a4 4..."/>
                                                    </svg>
                                                </div>
                                                <img id="preview-image" class="h-full w-full object-cover hidden" alt="Profile preview">
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <input type="file" name="profile_picture" id="profile_picture" accept="image/*"
                                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0
                                                file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition">
                                        <p class="mt-1 text-xs text-gray-500">PNG, JPG or GIF up to 2MB</p>
                                        @error('profile_picture') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Submit Button --}}
                        <div class="pt-4">
                            <button type="submit"
                                    class="inline-flex items-center px-6 py-2 bg-blue-600 text-white font-medium rounded-md shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
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
        document.addEventListener('DOMContentLoaded', function () {
            const profilePictureInput = document.getElementById('profile_picture');
            const previewImage = document.getElementById('preview-image');
            const previewPlaceholder = document.getElementById('preview-placeholder');

            if (profilePictureInput && previewImage) {
                profilePictureInput.addEventListener('change', function (e) {
                    if (e.target.files.length > 0) {
                        const file = e.target.files[0];
                        const reader = new FileReader();

                        reader.onload = function (e) {
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
