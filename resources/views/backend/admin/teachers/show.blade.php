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
            .btn-success {
                @apply px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors duration-200 font-medium text-sm;
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
            .badge {
                @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium;
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
            <a href="{{ route('admin.teacher.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Teachers
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column - Basic Info -->
            <div class="card p-6">
                <div class="flex flex-col items-center text-center mb-6">
                    <img class="h-32 w-32 rounded-full object-cover border-4 border-gray-200 dark:border-gray-700 mb-4"
                         src="{{ $teacher->profile_picture ? asset("/storage/$teacher->profile_picture") : "https://ui-avatars.com/api/?name=".urlencode($teacher->fname.' '.$teacher->lname) }}"
                         alt="{{ $teacher->fname }} {{ $teacher->lname }}">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ $teacher->fname }} {{ $teacher->lname }}
                    </h2>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $teacher->email }}</p>
                    <div class="mt-3">
                        @if($teacher->status)
                            <span class="badge bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                <i class="fas fa-check-circle mr-1"></i>
                                Active
                            </span>
                        @else
                            <span class="badge bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100">
                                <i class="fas fa-clock mr-1"></i>
                                Pending Approval
                            </span>
                        @endif
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <p class="info-label">Phone</p>
                        <p class="info-value">{{ $teacher->phone ?? 'Not provided' }}</p>
                    </div>
                    <div>
                        <p class="info-label">Address</p>
                        <p class="info-value">{{ $teacher->address ?? 'Not provided' }}</p>
                    </div>
                    <div>
                        <p class="info-label">Gender</p>
                        <p class="info-value">{{ ucfirst($teacher->gender) ?? 'Not specified' }}</p>
                    </div>
                    <div>
                        <p class="info-label">Date of Birth</p>
                        <p class="info-value">
                            {{ $teacher->dob ? $teacher->dob->format('d M, Y') : 'Not provided' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Right Column - Professional Details -->
            <div class="lg:col-span-2">
                <!-- Professional Information -->
                <div class="card p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        <i class="fas fa-graduation-cap mr-2"></i>
                        Professional Information
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="info-label">Qualification</p>
                            <p class="info-value">{{ $teacher->qualification ?? 'Not provided' }}</p>
                        </div>
                        <div>
                            <p class="info-label">Employee ID</p>
                            <p class="info-value">TCH-{{ str_pad($teacher->id, 4, '0', STR_PAD_LEFT) }}</p>
                        </div>
                        <div>
                            <p class="info-label">Joining Date</p>
                            <p class="info-value">{{ $teacher->created_at->format('d M, Y') }}</p>
                        </div>
                        <div>
                            <p class="info-label">Email Verified</p>
                            <p class="info-value">
                                @if($teacher->email_verified_at)
                                    <span class="text-green-600 dark:text-green-400">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        {{ $teacher->email_verified_at->format('d M, Y') }}
                                    </span>
                                @else
                                    <span class="text-yellow-600 dark:text-yellow-400">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                        Not verified
                                    </span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Teaching Assignments -->
                <div class="card p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        <i class="fas fa-chalkboard-teacher mr-2"></i>
                        Teaching Assignments
                    </h3>
                    @if($teacher->subjectTeacherMappings && $teacher->subjectTeacherMappings->count() > 0)
                        <div class="space-y-3">
                            @foreach($teacher->subjectTeacherMappings as $mapping)
                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white">
                                            {{ $mapping->subject->name ?? 'Subject' }}
                                        </p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            {{ $mapping->subject->program->name ?? 'Program' }} -
                                            Semester {{ $mapping->subject->semester ?? 'N/A' }}
                                        </p>
                                    </div>
                                    <span class="badge bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100">
                                        {{ $mapping->subject->credit_hours ?? 0 }} Credits
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-chalkboard text-4xl text-gray-300 dark:text-gray-600 mb-3"></i>
                            <p class="text-gray-500 dark:text-gray-400">No teaching assignments yet</p>
                        </div>
                    @endif
                </div>

                <!-- Teaching Batches -->
                @if($teacher->teachingBatches && $teacher->teachingBatches->count() > 0)
                    <div class="card p-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                            <i class="fas fa-users mr-2"></i>
                            Teaching Batches
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach($teacher->teachingBatches as $batch)
                                <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <p class="font-medium text-gray-900 dark:text-white">{{ $batch->batch }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        {{ $batch->program->name ?? 'Program' }} - Semester {{ $batch->semester }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Account Information -->
                <div class="card p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        <i class="fas fa-user-cog mr-2"></i>
                        Account Information
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="info-label">Account Created</p>
                            <p class="info-value">{{ $teacher->created_at->format('d M, Y \a\t H:i') }}</p>
                        </div>
                        <div>
                            <p class="info-label">Last Updated</p>
                            <p class="info-value">{{ $teacher->updated_at->format('d M, Y \a\t H:i') }}</p>
                        </div>
                        <div>
                            <p class="info-label">Institute</p>
                            <p class="info-value">{{ $institute->name ?? 'Not assigned' }}</p>
                        </div>
                        <div>
                            <p class="info-label">Account Status</p>
                            <p class="info-value">
                                @if($teacher->status)
                                    <span class="text-green-600 dark:text-green-400">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Active
                                    </span>
                                @else
                                    <span class="text-yellow-600 dark:text-yellow-400">
                                        <i class="fas fa-clock mr-1"></i>
                                        Pending Approval
                                    </span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-wrap gap-3">
                    @if(!$teacher->status)
                        <form action="{{ route('admin.teacher.approve', $teacher->id) }}" method="POST" class="inline">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn-success" onclick="return confirm('Are you sure you want to approve this teacher?')">
                                <i class="fas fa-check-circle mr-2"></i>
                                Approve Teacher
                            </button>
                        </form>
                    @else
                        <form action="{{ route('admin.teacher.status', $teacher->id) }}" method="POST" class="inline">
                            @csrf
                            <input type="hidden" name="status" value="0">
                            <button type="submit" class="btn-danger" onclick="return confirm('Are you sure you want to deactivate this teacher?')">
                                <i class="fas fa-ban mr-2"></i>
                                Deactivate Teacher
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('admin.teacher.index') }}" class="btn-secondary">
                        <i class="fas fa-list mr-2"></i>
                        View All Teachers
                    </a>

                    @if($teacher->subjectTeacherMappings && $teacher->subjectTeacherMappings->count() > 0)
                        <a href="{{ route('admin.subject-teacher.mapping.index') }}?teacher_id={{ $teacher->id }}" class="btn-primary">
                            <i class="fas fa-cog mr-2"></i>
                            Manage Assignments
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
    <script>
        // Add any JavaScript functionality here if needed
        document.addEventListener('DOMContentLoaded', function() {
            // Example: Auto-hide success/error messages after 5 seconds
            const alerts = document.querySelectorAll('[role="alert"]');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.transition = 'opacity 0.5s';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                }, 5000);
            });
        });
    </script>
@endpush
