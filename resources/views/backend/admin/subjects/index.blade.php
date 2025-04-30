@php use Illuminate\Support\Facades\Auth; @endphp
@extends("backend.layout.admin-dashboard-layout")

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
            .sidebar-item {
                @apply flex items-center gap-3 px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md transition-colors duration-200;
            }
            .sidebar-item.active {
                @apply bg-primary-50 dark:bg-gray-700 text-primary-600 dark:text-primary-400 font-medium;
            }
            .form-input {
                @apply w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:text-white text-sm;
            }
            .form-label {
                @apply block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1;
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
            .dropdown-item {
                @apply block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-150;
            }
        }
    </style>
@endpush

@section('content')
    <main class="scrollable-content p-4 md:p-6">

        <!-- Action Bar -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
            <!-- Search & Filter -->
            <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                <!-- Search -->
                <div class="relative w-full max-w-md">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input
                        type="text"
                        placeholder="Search programs..."
                        class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                    >
                </div>

                <!-- Department Filter -->
                <div class="relative w-full max-w-md">
                    <select
                        id="departmentFilter"
                        class="w-full pl-4 pr-4 py-2 rounded-lg border border-gray-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition bg-white text-gray-700"
                    >
                    </select>
                </div>
            </div>

            <!-- Add Subject Button -->
            <div class="flex justify-end">
                <a href="{{ route('admin.subjects.create') }}"
                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition shadow-sm">
                    <i class="fas fa-plus mr-2"></i> Add New Subject
                </a>
            </div>
        </div>

        <!-- Programs Table -->
        <div class="card">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th scope="col" class="table-header">Subject Name</th>
                        <th scope="col" class="table-header">Code</th>
                        <th scope="col" class="table-header">Credit</th>
                        <th scope="col" class="table-header">Description</th>
                        <th scope="col" class="table-header">Program</th>
                        <th scope="col" class="table-header">Semester</th>
                        <th scope="col" class="table-header">Status</th>
                        <th scope="col" class="table-header">Action</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700" id="programsTableBody">
                    @forelse($allSubjects as $subject)
                        <tr id="subject-row-{{$subject->id}}">
                            <td class="table-cell font-medium">{{$subject->name}}</td>
                            <td class="table-cell font-medium">{{$subject->code}}</td>
                            <td class="table-cell font-medium">{{$subject->credit}}</td>
                            <td class="table-cell font-medium w-64">
                                <div class="max-h-24 overflow-y-auto break-words whitespace-pre-line">
                                    {{$subject->description}}
                                </div>
                            </td>
                            <td class="table-cell font-medium">{{$subject->program->name}}</td>
                            <td class="table-cell font-medium">{{$subject?->semester}}</td>
                            <td class="table-cell">
                                @if($subject->status == 1)
                                    <span class="badge bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">Active</span>
                                @endif

                                @if($subject->status == 0)
                                    <span class="badge bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">Inactive</span>
                                @endif

                            </td>
                            <td class="table-cell">
                                <div class="flex items-center space-x-2">
                                    <a href="{{route('admin.subjects.edit', $subject->id)}}" class="edit-program-btn p-1.5 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-full" data-id="{{$subject->id}}" aria-label="Edit program">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button class="p-1.5 text-red-600 hover:text-red-800" onclick="confirmDelete({{ $subject->id }}, '{{ $subject->name }}')">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="table-cell font-medium text-center" colspan="7">No Subjects Found</td>
                        </tr>
                    @endforelse


                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 bg-white dark:bg-gray-800 border-t dark:border-gray-700 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    Showing <span class="font-medium text-gray-700 dark:text-gray-300">1</span> to <span class="font-medium text-gray-700 dark:text-gray-300">5</span> of <span class="font-medium text-gray-700 dark:text-gray-300">12</span> programs
                </div>
                <div class="flex items-center space-x-1">
                    <button class="p-2 rounded-md text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 disabled:opacity-50 disabled:cursor-not-allowed" disabled aria-label="Previous page">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="p-2 rounded-md bg-primary-50 dark:bg-gray-700 text-primary-600 dark:text-primary-400" aria-label="Page 1">1</button>
                    <button class="p-2 rounded-md text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700" aria-label="Page 2">2</button>
                    <button class="p-2 rounded-md text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700" aria-label="Page 3">3</button>
                    <button class="p-2 rounded-md text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700" aria-label="Next page">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
            <div class="fixed inset-0 z-50 flex items-center justify-center hidden">
                <div class="absolute inset-0 bg-black bg-opacity-50"></div>
                <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full mx-4">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-xl font-semibold">Confirm Deletion</h3>
                            <button wire:click="cancelDeleteSubject" class="p-1 text-gray-500 hover:bg-gray-100 rounded">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <p class="text-gray-600 dark:text-gray-400 mb-6">
                            Are you sure you want to delete this subject? This action cannot be undone.
                        </p>
                        <div class="flex justify-end gap-2">
                            <button wire:click="cancelDeleteSubject" class="btn-secondary">Cancel</button>
                            <button wire:click="cancelDeleteSubject" class="btn-danger">Delete</button>
                        </div>
                    </div>
                </div>
            </div>

    </main>

@endsection



@section("scripts")

    <script>



        $(document).ready( function () {


            fetch(`/admin/department/getAllDepartments`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById("departmentFilter").innerHTML = "";
                    if (data) {
                        document.getElementById("departmentFilter").innerHTML += `<option value="">All Departments</option>`;
                        data.forEach((item) => {
                            document.getElementById("departmentFilter").innerHTML += `<option value="${item.id}">${item.name}</option>`;
                        });

                    }
                })
                .catch(error => console.error('Error:', error));
        });

        function confirmDelete(id, name) {
            Swal.fire({
                title: 'Are you sure?',
                text: `Delete "${name}"? This action cannot be undone.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/admin/subject/${id}`, { method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{csrf_token()}}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    })
                        .then(response => response.json())
                        .then(async data => {
                            if (data.status == "success") {
                                $(`#subject-row-${id}`).remove();
                                await Toast.fire({
                                    icon: 'success',
                                    title: 'Subject deleted',
                                })
                            }
                        })
                        .catch(error => console.error('Error:', error));
                }
            });
        }

    </script>
@endsection
