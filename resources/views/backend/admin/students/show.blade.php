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
            .info-label {
                @apply text-sm text-gray-500 dark:text-gray-400;
            }
            .info-value {
                @apply text-base font-medium text-gray-900 dark:text-white mt-1;
            }
        }
    </style>
@endpush

@section('content')
    <main class="scrollable-content p-4 md:p-6">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 mb-3 rounded relative" role="alert">
                <strong class="font-bold">Success!</strong>
                <span class="block sm:inline">{{session('success')}}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 mb-3 rounded relative" role="alert">
                <strong class="font-bold">Error!</strong>
                <span class="block sm:inline">{{session('error')}}</span>
            </div>
        @endif

        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('admin.student.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Students
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column - Basic Info -->
            <div class="card p-6">
                <div class="flex flex-col items-center text-center mb-6">
                    <img class="h-32 w-32 rounded-full object-cover border-4 border-gray-200 dark:border-gray-700 mb-4"
                         src="{{ $student->profile_picture ? asset("/storage/$student->profile_picture") : "https://ui-avatars.com/api/?name=".urlencode($student->fname.' '.$student->lname) }}"
                         alt="{{ $student->fname }} {{ $student->lname }}">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ $student->fname }} {{ $student->lname }}
                    </h2>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $student->email }}</p>
                    <div class="mt-3">
                        @if($student->status)
                            <span class="badge bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100 px-3 py-1">
                                Active
                            </span>
                        @else
                            <span class="badge bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100 px-3 py-1">
                                Pending
                            </span>
                        @endif
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <p class="info-label">Phone</p>
                        <p class="info-value">{{ $student->phone }}</p>
                    </div>
                    <div>
                        <p class="info-label">Address</p>
                        <p class="info-value">{{ $student->address }}</p>
                    </div>
                    <div>
                        <p class="info-label">Guardian Information</p>
                        <p class="info-value">{{ $student->guardian_name }}</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $student->guardian_phone }}</p>
                    </div>
                </div>
            </div>

            <!-- Right Column - Academic Details -->
            <div class="lg:col-span-2">
                <div class="card p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Academic Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="info-label">Roll Number</p>
                            <p class="info-value">{{ $student->roll_number }}</p>
                        </div>
                        <div>
                            <p class="info-label">Batch</p>
                            <p class="info-value">{{ $student->batch->batch }}</p>
                        </div>

                        <div>
                            <p class="info-label">Admission Date</p>
                            <p class="info-value">{{ $student->admission_date->format('d M, Y') }}</p>
                        </div>
                        <div>
                            <p class="info-label">Gender</p>
                            <p class="info-value">{{ ucfirst($student->gender) }}</p>
                        </div>
                        <div>
                            <p class="info-label">Date of Birth</p>
                            <p class="info-value">{{ $student->dob->format('d M, Y') }}</p>
                        </div>
                    </div>
                </div>

                <div class="card p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Account Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="info-label">Account Created</p>
                            <p class="info-value">{{ $student->created_at->format('d M, Y') }}</p>
                        </div>
                        <div>
                            <p class="info-label">Last Updated</p>
                            <p class="info-value">{{ $student->updated_at->format('d M, Y') }}</p>
                        </div>
                        <div>
                            <p class="info-label">Email Verified</p>
                            <p class="info-value">
                                @if($student->email_verified_at)
                                    {{ $student->email_verified_at->format('d M, Y') }}
                                @else
                                    <span class="text-yellow-600 dark:text-yellow-400">Not verified</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <p class="info-label">Institute</p>
                            <p class="info-value">{{ $insitute->name }}</p>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex flex-wrap gap-3">
                    @if(!$student->status)
                        <form action="{{ route('admin.student.approve', $student->id) }}" method="POST" class="inline">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn-primary">
                                <i class="fas fa-check-circle mr-2"></i>
                                Approve Student
                            </button>
                        </form>
                    @else
                        <form action="{{ route('admin.student.unapprove', $student->id) }}" method="POST" class="inline">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn-danger">
                                <i class="fas fa-ban mr-2"></i>
                                Block Student
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </main>
@endsection
