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
            .card {
                @apply bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden;
            }
            .form-input {
                @apply w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:text-white text-sm;
            }
            .table-header {
                @apply px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider;
            }
            .table-cell {
                @apply px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200;
            }
            .badge {
                @apply px-2.5 py-1 text-xs font-medium rounded-full;
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

        <!-- Action Bar -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="relative">
                    <input type="text" id="searchStudents" placeholder="Search students..." class="form-input pl-12 pr-4 py-2">
                </div>
            </div>
            <div class="flex justify-end">
                <a href="{{route('admin.student.index')}}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-blue-700 transition shadow-sm">
                    <i class="fa-solid fa-arrow-left mr-2"></i> View all
                </a>
            </div>
        </div>

        <!-- Students Table -->
        <div class="card">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th scope="col" class="table-header">Name</th>
                        <th scope="col" class="table-header">Email</th>
                        <th scope="col" class="table-header">Created at</th>
                        <th scope="col" class="table-header">Actions</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($students as $student)
                        <tr>
                            <td class="table-cell">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 flex-shrink-0">
                                        <img src="{{
                                                $student->profile_picture
                                                    ? asset('/storage/'.$student->profile_picture)
                                                    : 'https://ui-avatars.com/api/?name=' . urlencode($student->fname . ' ' . $student->lname) . '&background=random'
                                            }}"
                                             alt="{{ $student->fname }} {{ $student->lname }} Profile Picture"
                                             class="w-10 h-10 rounded-full object-cover">
                                    </div>
                                    <div class="ml-4">
                                        <div class="font-medium text-gray-900 dark:text-white">
                                            {{ $student->fname }} {{ $student->lname }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="table-cell">{{ $student->email }}</td>
                            <td class="table-cell">
                                    <span class="badge bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                        {{$student->created_at->diffForHumans()}}
                                    </span>
                            </td>
                            <td class="table-cell">
                                <div class="flex items-center space-x-2">
                                    <a href="{{route('admin.student.show', $student->id)}}"
                                       class="p-1.5 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-full"
                                       title="View Profile">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <form action="{{route('admin.student.approve', $student->id)}}" method="POST" class="inline">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit"
                                                class="p-1.5 text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300 hover:bg-green-50 dark:hover:bg-green-900/20 rounded-full"
                                                title="Approve Student">
                                            <i class="fas fa-check-circle"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="table-cell text-center">
                                No pending approvals found
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

{{--            @if($students->hasPages())--}}
{{--                <div class="px-6 py-4 bg-white dark:bg-gray-800 border-t dark:border-gray-700">--}}
{{--                    {{ $students->links() }}--}}
{{--                </div>--}}
{{--            @endif--}}
        </div>
    </main>
@endsection

@section('scripts')
    <script>
        // Search functionality
        const searchInput = document.getElementById('searchStudents');

        function filterStudents() {
            const searchTerm = searchInput.value.toLowerCase();
            const rows = document.querySelectorAll('tbody tr');

            rows.forEach(row => {
                const studentName = row.querySelector('td:first-child').textContent.toLowerCase();
                const studentEmail = row.querySelector('td:nth-child(2)').textContent.toLowerCase();

                const matchesSearch = studentName.includes(searchTerm) || studentEmail.includes(searchTerm);
                row.style.display = matchesSearch ? '' : 'none';
            });
        }

        searchInput.addEventListener('input', filterStudents);

        // Initialize tooltips
        document.querySelectorAll('[title]').forEach(element => {
            new Tooltip(element);
        });
    </script>
@endsection
