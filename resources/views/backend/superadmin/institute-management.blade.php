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

@section("title")
    Institute Management
@endsection

@section('content')
    <!-- Main Content Area - Made Scrollable -->
    <main class="scrollable-content p-4 md:p-6">
        <!-- Institute Management Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Institute Management</h1>
                <p class="text-gray-600 dark:text-gray-400">Manage all educational institutes in the system</p>
            </div>
        </div>


        <!-- Search and Filter -->
        <div class="flex flex-col md:flex-row gap-4 mb-6">
            <div class="flex-1">
                <div class="relative">

                    <input type="text" id="institute-search" class="form-input pl-10" placeholder="Search institutes...">
                </div>
            </div>
            <div class="flex flex-col sm:flex-row gap-2">
                <select id="status-filter" class="form-input">
                    <option selected value="">All Institutes</option>
                    <option value="active">Active</option>
                    <option value="deleted">Deleted</option>
                </select>
            </div>
        </div>

        <!-- Institute List -->
        <div class="card">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            <div class="flex items-center">
                                Institute
                                <i class="fas fa-sort ml-1"></i>
                            </div>
                        </th>

                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            <div class="flex items-center">
                                Description
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            <div class="flex items-center">
                                Registered at
                            </div>
                        </th>
             <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            <div class="flex items-center">
                                Action
                            </div>
                        </th>

                    </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700" id="instituteTableBody">
                    @forelse($institutes as $institute)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name=Tech+University&background=0D8ABC&color=fff" alt="">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{$institute->name}}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{$institute->email}}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500 dark:text-gray-400">Institute</div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{$institute->created_at}}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($institute->deleted_at)
                                    <button class="toggle-status-btn text-sm text-gray-500 dark:text-gray-400"
                                            data-id="{{ $institute->id }}"
                                            title="Activate">
                                        <i class="fa-solid fa-circle-check text-green-500"></i> Activate
                                    </button>
                                @else
                                    <button class="toggle-status-btn text-sm text-gray-500 dark:text-gray-400"
                                            data-id="{{ $institute->id }}"
                                            title="Disable">
                                        <i class="fa-solid fa-circle-xmark text-red-500"></i> Disable
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div id="pagination-container">
            </div>

        </div>
        <!-- Footer -->
        @component('components.backend.dashboard-footer')
        @endcomponent
    </main>
@endsection

@section("scripts")
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Event delegation for all toggle buttons
            document.addEventListener('click', async function(e) {
                if (e.target.closest('.toggle-status-btn')) {
                    const button = e.target.closest('.toggle-status-btn');
                    const instituteId = button.dataset.id;

                    try {
                        // Set loading state
                        const originalHTML = button.innerHTML;
                        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                        button.disabled = true;

                        const response = await fetch('/superadmin/api/institute/toggle-status', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Authorization': `Bearer ${localStorage.getItem('auth_token')}`
                            },
                            body: JSON.stringify({ id: instituteId })
                        });

                        const data = await response.json();

                        if (!response.ok) throw new Error(data.message || 'Request failed');

                        // Update UI
                        if (data.success) {
                            // Update button appearance
                            if (data.data.action === 'activate') {
                                button.innerHTML = '<i class="fa-solid fa-circle-xmark text-red-500"></i> Disable';
                                button.title = 'Disable';
                                button.closest('tr').classList.remove('bg-gray-100');
                            } else {
                                button.innerHTML = '<i class="fa-solid fa-circle-check text-green-500"></i> Activate';
                                button.title = 'Activate';
                                button.closest('tr').classList.add('bg-gray-100');
                            }

                            // Show success message
                            showToast('success', data.message);
                        }

                    } catch (error) {
                        console.error('Error:', error);
                        showToast('error', error.message || 'Failed to toggle status');
                    } finally {
                        button.disabled = false;
                    }
                }
            });

            // Toast notification helper
            function showToast(type, message) {
                const toast = document.createElement('div');
                toast.className = `fixed top-4 right-4 px-4 py-2 rounded-md text-white ${
                    type === 'success' ? 'bg-green-500' : 'bg-red-500'
                }`;
                toast.textContent = message;
                document.body.appendChild(toast);

                setTimeout(() => {
                    toast.remove();
                }, 3000);
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Elements
            const searchInput = document.getElementById('institute-search');
            const statusFilter = document.getElementById('status-filter');
            const institutesTableBody = document.querySelector('#instituteTableBody');
            const paginationContainer = document.getElementById('pagination-container');

            // Debounce function to limit API calls during typing
            let debounceTimer;
            const debounceDelay = 500;

            // Current state
            let currentPage = 1;
            let currentSearch = '';
            let currentStatus = '';
            let currentSort = 'created_at';
            let currentDirection = 'desc';

            // Initialize
            fetchInstitutes();

            // Event Listeners
            searchInput.addEventListener('input', function(e) {
                currentSearch = e.target.value;
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    currentPage = 1;
                    fetchInstitutes();
                }, debounceDelay);
            });

            statusFilter.addEventListener('change', function(e) {
                currentStatus = e.target.value;
                currentPage = 1;
                fetchInstitutes();
            });

            // Sort handler (if you have sortable columns)
            document.querySelectorAll('[data-sort]').forEach(header => {
                header.addEventListener('click', function() {
                    const sortColumn = this.dataset.sort;
                    if (currentSort === sortColumn) {
                        currentDirection = currentDirection === 'asc' ? 'desc' : 'asc';
                    } else {
                        currentSort = sortColumn;
                        currentDirection = 'asc';
                    }
                    fetchInstitutes();
                });
            });

            // Fetch institutes from API
            function fetchInstitutes() {
                const url = new URL('/superadmin/api/institute', window.location.origin);
                url.searchParams.append('page', currentPage);
                if (currentSearch) url.searchParams.append('search', currentSearch);
                if (currentStatus) url.searchParams.append('status', currentStatus);
                if (currentSort) url.searchParams.append('sort', currentSort);
                if (currentDirection) url.searchParams.append('direction', currentDirection);

                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            renderInstitutes(data.data.data);
                            renderPagination(data.data);
                        } else {
                            throw new Error(data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Failed to load institutes: ' + error.message);
                    });
            }

            // Render institutes in table
            function renderInstitutes(institutes) {
                institutesTableBody.innerHTML = '';

                if (institutes.length === 0) {
                    institutesTableBody.innerHTML = `
                <tr>
                    <td colspan="5" class="py-4 text-center text-gray-500">
                        No institutes found
                    </td>
                </tr>
            `;
                    return;
                }

                institutes.forEach(institute => {
                    const row = document.createElement('tr');
                    row.className = institute.deleted_at ? 'bg-gray-50' : '';

                    row.innerHTML = `
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10">
                            <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name=${encodeURIComponent(institute.name)}&background=0D8ABC&color=fff" alt="${institute.name}">
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-900 dark:text-white">${institute.name}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">${institute.email}</div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-500 dark:text-gray-400">${institute.admin ? institute.admin.name : 'N/A'}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                    ${new Date(institute.created_at).toLocaleDateString()}
                </td>

                <td class="px-6 py-4 whitespace-nowrap">
                    ${institute.deleted_at ?
                        `<button class="toggle-status-btn text-sm text-gray-500 dark:text-gray-400" data-id="${institute.id}" title="Activate">
                            <i class="fa-solid fa-circle-check text-green-500"></i> Activate
                        </button>` :
                        `<button class="toggle-status-btn text-sm text-gray-500 dark:text-gray-400" data-id="${institute.id}" title="Disable">
                            <i class="fa-solid fa-circle-xmark text-red-500"></i> Disable
                        </button>`}
                </td>
            `;

                    institutesTableBody.appendChild(row);
                });

                // Re-attach event listeners to toggle buttons
                document.querySelectorAll('.toggle-status-btn').forEach(btn => {
                    btn.addEventListener('click', handleToggleStatus);
                });
            }

            // Render pagination
            function renderPagination(pagination) {
                paginationContainer.innerHTML = '';

                if (pagination.last_page <= 1) return;

                const paginationEl = document.createElement('div');
                paginationEl.className = 'flex items-center justify-between mt-4';

                // Previous button
                const prevBtn = document.createElement('button');
                prevBtn.className = `px-4 py-2 rounded-md ${pagination.current_page === 1 ? 'bg-gray-200 cursor-not-allowed' : 'bg-blue-500 text-white'}`;
                prevBtn.textContent = 'Previous';
                prevBtn.disabled = pagination.current_page === 1;
                prevBtn.addEventListener('click', () => {
                    if (pagination.current_page > 1) {
                        currentPage = pagination.current_page - 1;
                        fetchInstitutes();
                    }
                });

                // Page info
                const pageInfo = document.createElement('span');
                pageInfo.className = 'text-sm text-gray-700';
                pageInfo.textContent = `Page ${pagination.current_page} of ${pagination.last_page}`;

                // Next button
                const nextBtn = document.createElement('button');
                nextBtn.className = `px-4 py-2 rounded-md ${pagination.current_page === pagination.last_page ? 'bg-gray-200 cursor-not-allowed' : 'bg-blue-500 text-white'}`;
                nextBtn.textContent = 'Next';
                nextBtn.disabled = pagination.current_page === pagination.last_page;
                nextBtn.addEventListener('click', () => {
                    if (pagination.current_page < pagination.last_page) {
                        currentPage = pagination.current_page + 1;
                        fetchInstitutes();
                    }
                });

                paginationEl.appendChild(prevBtn);
                paginationEl.appendChild(pageInfo);
                paginationEl.appendChild(nextBtn);
                paginationContainer.appendChild(paginationEl);
            }

            // Toggle status handler
            function handleToggleStatus(e) {
                const button = e.currentTarget;
                const instituteId = button.dataset.id;

                // Show loading
                const originalHTML = button.innerHTML;
                button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
                button.disabled = true;

                fetch('/superadmin/api/institute/toggle-status', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ id: instituteId })
                })
                    .then(response => response.json())
                    .then(data => {
                        console.log(data);
                        if (data.success) {
                            fetchInstitutes(); // Refresh the table
                            showToast('success', data.message);
                        } else {
                            throw new Error(data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showToast('error', error.message);
                    })
                    .finally(() => {
                        button.disabled = false;
                        button.innerHTML = originalHTML;
                    });
            }

            // Toast notification
            function showToast(type, message) {
                // Using SweetAlert if available, otherwise fallback to alert
                if (typeof window.Swal !== 'undefined') {
                    window.Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: type,
                        title: message,
                        showConfirmButton: false,
                        timer: 3000
                    });
                } else {
                    alert(`${type}: ${message}`);
                }
            }
        });
    </script>
@endsection
