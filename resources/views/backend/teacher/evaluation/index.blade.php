@extends("backend.layout.teacher-dashboard-layout")

@section('title', 'Student Evaluations')

@section('content')
    <!-- Main Content Area -->
    <main class="p-6 md:p-6 min-h-screen overflow-y-auto pb-16">
        <x-show-success-failure-badge />
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-white mb-1">
                    Student Evaluations
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Manage and track student performance evaluations
                </p>
            </div>
            <a href="{{ route('teacher.evaluation.create') }}" class="mt-4 md:mt-0 btn-primary flex items-center">
                <i class="fas fa-plus mr-2"></i> Create New Evaluation
            </a>
        </div>

        <!-- Evaluation Filters -->
        <div class="card mb-6 p-4">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Filters</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="relative">
                    <label for="batchFilter" class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 block">Batch</label>
                    <select id="batchFilter" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                        <option value="">All Batches</option>
                        @foreach($batches as $batch)
                            <option value="{{ $batch->id }}">{{ $batch->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="relative">
                    <label for="subjectFilter" class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 block">Subject</label>
                    <select id="subjectFilter" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                        <option value="">All Subjects</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}">{{ $subject->name }} ({{ $subject->code }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="relative">
                    <label for="formatFilter" class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 block">Evaluation Format</label>
                    <select id="formatFilter" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                        <option value="">All Formats</option>
                        @foreach($evaluationFormats as $format)
                            <option value="{{ $format->id }}">{{ $format->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="relative">
                    <label for="statusFilter" class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 block">Status</label>
                    <select id="statusFilter" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                        <option value="">All Status</option>
                        <option value="1">Finalized</option>
                        <option value="0">Draft</option>
                    </select>
                </div>
            </div>

            <div class="flex flex-col md:flex-row gap-4 mt-4">
                <div class="flex-grow relative">
                    <label for="searchInput" class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 block">Search</label>
                    <div class="flex items-center border border-gray-300 rounded-md dark:border-gray-700 overflow-hidden">
                        <input id="searchInput" type="text" placeholder="Search by student name, roll number..." class="w-full px-4 py-2 focus:outline-none dark:bg-gray-800 dark:text-white">
                        <button class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>

                <div class="flex items-end">
                    <button id="resetFilters" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                        Reset Filters
                    </button>
                </div>
            </div>
        </div>

        <!-- Evaluations List -->
        <div class="card">
            <div class="flex items-center justify-between mb-4 p-4">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">All Evaluations</h3>
                <div class="text-sm text-gray-500 dark:text-gray-400">Total: <span id="total-count">0</span> evaluations</div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Student</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Subject</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Batch</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Evaluation Format</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Marks</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700" id="evaluations-table-body">
                    <!-- Data will be loaded dynamically -->
                    <tr>
                        <td colspan="7" class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-800 dark:text-white text-center">Loading evaluations...</div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="flex items-center justify-between border-t border-gray-200 dark:border-gray-700 px-4 py-3 sm:px-6">
                <div class="flex-1 flex justify-between sm:hidden">
                    <a href="#" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Previous
                    </a>
                    <a href="#" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Next
                    </a>
                </div>
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700 dark:text-gray-300">
                            Showing
                            <span class="font-medium" id="pagination-from">1</span>
                            to
                            <span class="font-medium" id="pagination-to">10</span>
                            of
                            <span class="font-medium" id="pagination-total">0</span>
                            results
                        </p>
                    </div>
                    <div>
                        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination" id="pagination-container">
                            <!-- Pagination will be added dynamically -->
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Delete Confirmation Modal (Hidden by default) -->
    <div id="deleteConfirmationModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
        <div class="relative bg-white dark:bg-gray-800 rounded-lg max-w-md w-full mx-4">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Delete Evaluation</h3>
                    <button id="closeDeleteModal" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <div class="mb-6">
                    <p class="text-gray-700 dark:text-gray-300">Are you sure you want to delete this evaluation? This action cannot be undone.</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Note: All associated evaluation details will also be deleted.</p>
                </div>

                <div class="flex justify-end space-x-3">
                    <button id="cancelDelete" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                        Cancel
                    </button>
                    <button id="confirmDelete" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // DOM Elements
            const batchFilter = document.getElementById('batchFilter');
            const subjectFilter = document.getElementById('subjectFilter');
            const formatFilter = document.getElementById('formatFilter');
            const statusFilter = document.getElementById('statusFilter');
            const searchInput = document.getElementById('searchInput');
            const resetFilters = document.getElementById('resetFilters');
            const evaluationsTableBody = document.getElementById('evaluations-table-body');
            const totalCountElement = document.getElementById('total-count');
            const paginationContainer = document.getElementById('pagination-container');
            const paginationFrom = document.getElementById('pagination-from');
            const paginationTo = document.getElementById('pagination-to');
            const paginationTotal = document.getElementById('pagination-total');

            // Modal Elements
            const deleteConfirmationModal = document.getElementById('deleteConfirmationModal');
            const closeDeleteModal = document.getElementById('closeDeleteModal');
            const cancelDelete = document.getElementById('cancelDelete');
            const confirmDelete = document.getElementById('confirmDelete');

            // Current page for pagination
            let currentPage = 1;

            // Load evaluations on page load
            loadEvaluations();

            // Event Listeners
            batchFilter.addEventListener('change', () => {
                currentPage = 1;
                loadEvaluations();
            });
            subjectFilter.addEventListener('change', () => {
                currentPage = 1;
                loadEvaluations();
            });
            formatFilter.addEventListener('change', () => {
                currentPage = 1;
                loadEvaluations();
            });
            statusFilter.addEventListener('change', () => {
                currentPage = 1;
                loadEvaluations();
            });
            searchInput.addEventListener('input', debounce(() => {
                currentPage = 1;
                loadEvaluations();
            }, 300));
            resetFilters.addEventListener('click', resetAllFilters);

            // Modal Event Listeners
            if (closeDeleteModal) closeDeleteModal.addEventListener('click', () => toggleModal(deleteConfirmationModal));
            if (cancelDelete) cancelDelete.addEventListener('click', () => toggleModal(deleteConfirmationModal));
            if (confirmDelete) confirmDelete.addEventListener('click', handleDeleteEvaluation);

            // Functions
            async function loadEvaluations() {
                try {
                    // Show loading state
                    evaluationsTableBody.innerHTML = '<tr><td colspan="7" class="px-6 py-4 whitespace-nowrap"><div class="text-sm font-medium text-gray-800 dark:text-white text-center">Loading evaluations...</div></td></tr>';

                    // Build query parameters
                    const params = new URLSearchParams();
                    if (batchFilter.value) params.append('batch_id', batchFilter.value);
                    if (subjectFilter.value) params.append('subject_id', subjectFilter.value);
                    if (formatFilter.value) params.append('format_id', formatFilter.value);
                    if (statusFilter.value) params.append('is_finalized', statusFilter.value);
                    if (searchInput.value) params.append('search', searchInput.value);
                    params.append('page', currentPage);

                    const response = await fetch(`/teacher/evaluations?${params.toString()}`);
                    if (!response.ok) throw new Error('Failed to fetch evaluations');

                    const data = await response.json();
                    const evaluations = data.data;
                    const meta = data.meta;

                    // Update total count
                    totalCountElement.textContent = meta.total;

                    // Render evaluations
                    renderEvaluationsTable(evaluations);

                    // Update pagination
                    updatePagination(meta);

                } catch (error) {
                    console.error('Error loading evaluations:', error);
                    evaluationsTableBody.innerHTML = '<tr><td colspan="7" class="px-6 py-4 whitespace-nowrap"><div class="text-sm font-medium text-red-600 dark:text-red-400 text-center">Error loading evaluations</div></td></tr>';
                }
            }

            function renderEvaluationsTable(evaluations) {
                evaluationsTableBody.innerHTML = '';

                if (evaluations.length === 0) {
                    evaluationsTableBody.innerHTML = '<tr><td colspan="7" class="px-6 py-4 whitespace-nowrap"><div class="text-sm font-medium text-gray-800 dark:text-white text-center">No evaluations found</div></td></tr>';
                    return;
                }

                evaluations.forEach(evaluation => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <img class="h-10 w-10 rounded-full object-cover" src="${evaluation.student.profile_picture || '/images/default-avatar.png'}" alt="${evaluation.student.full_name}">
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-800 dark:text-white">${evaluation.student.full_name}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">Roll: ${evaluation.student.roll_number}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-800 dark:text-white">${evaluation.subject.name}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">${evaluation.subject.code}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-800 dark:text-white">${evaluation.batch.name}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Semester: ${evaluation.semester}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-800 dark:text-white">${evaluation.evaluation_format.name}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Weight: ${evaluation.evaluation_format.weight}%</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-800 dark:text-white">${evaluation.total_obtained_marks} / ${evaluation.evaluation_format.full_marks}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Normalized: ${evaluation.total_normalized_marks}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-medium rounded-full ${evaluation.is_finalized ? 'bg-green-100 dark:bg-green-800 text-green-800 dark:text-green-100' : 'bg-yellow-100 dark:bg-yellow-800 text-yellow-800 dark:text-yellow-100'}">
                                ${evaluation.is_finalized ? 'Finalized' : 'Draft'}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex space-x-2">
                                <a href="/teacher/evaluation/${evaluation.id}" class="text-primary-600 hover:text-primary-800" title="View Evaluation">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="/teacher/evaluation/${evaluation.id}/edit" class="text-yellow-600 hover:text-yellow-800" title="Edit Evaluation">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button class="delete-btn text-red-600 hover:text-red-800" title="Delete Evaluation" data-id="${evaluation.id}">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </td>
                    `;
                    evaluationsTableBody.appendChild(row);
                });

                // Setup event listeners for delete buttons
                document.querySelectorAll('.delete-btn').forEach(button => {
                    button.addEventListener('click', (e) => {
                        const evaluationId = e.currentTarget.getAttribute('data-id');
                        deleteConfirmationModal.setAttribute('data-evaluation-id', evaluationId);
                        toggleModal(deleteConfirmationModal);
                    });
                });
            }

            function updatePagination(meta) {
                paginationFrom.textContent = meta.from || 0;
                paginationTo.textContent = meta.to || 0;
                paginationTotal.textContent = meta.total;

                paginationContainer.innerHTML = '';

                // Previous Page Button
                const prevButton = document.createElement('a');
                prevButton.href = '#';
                prevButton.className = `relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-sm font-medium text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 ${meta.current_page === 1 ? 'opacity-50 cursor-not-allowed' : ''}`;
                prevButton.innerHTML = '<span class="sr-only">Previous</span><i class="fas fa-chevron-left"></i>';
                if (meta.current_page > 1) {
                    prevButton.addEventListener('click', (e) => {
                        e.preventDefault();
                        currentPage--;
                        loadEvaluations();
                    });
                }
                paginationContainer.appendChild(prevButton);

                // Page Numbers
                const startPage = Math.max(1, meta.current_page - 2);
                const endPage = Math.min(meta.last_page, meta.current_page + 2);

                for (let i = startPage; i <= endPage; i++) {
                    const pageButton = document.createElement('a');
                    pageButton.href = '#';
                    pageButton.className = `relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-sm font-medium ${i === meta.current_page ? 'z-10 bg-primary-50 dark:bg-primary-900 border-primary-500 text-primary-600 dark:text-primary-200' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700'}`;
                    pageButton.textContent = i;

                    if (i !== meta.current_page) {
                        pageButton.addEventListener('click', (e) => {
                            e.preventDefault();
                            currentPage = i;
                            loadEvaluations();
                        });
                    }

                    paginationContainer.appendChild(pageButton);
                }

                // Next Page Button
                const nextButton = document.createElement('a');
                nextButton.href = '#';
                nextButton.className = `relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-sm font-medium text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 ${meta.current_page === meta.last_page ? 'opacity-50 cursor-not-allowed' : ''}`;
                nextButton.innerHTML = '<span class="sr-only">Next</span><i class="fas fa-chevron-right"></i>';
                if (meta.current_page < meta.last_page) {
                    nextButton.addEventListener('click', (e) => {
                        e.preventDefault();
                        currentPage++;
                        loadEvaluations();
                    });
                }
                paginationContainer.appendChild(nextButton);
            }

            function resetAllFilters() {
                batchFilter.value = '';
                subjectFilter.value = '';
                formatFilter.value = '';
                statusFilter.value = '';
                searchInput.value = '';
                currentPage = 1;
                loadEvaluations();
            }

            async function handleDeleteEvaluation() {
                const evaluationId = deleteConfirmationModal.getAttribute('data-evaluation-id');

                try {
                    const response = await fetch(`/teacher/evaluation/${evaluationId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    });

                    if (!response.ok) throw new Error('Failed to delete evaluation');

                    toggleModal(deleteConfirmationModal);
                    loadEvaluations();

                    // Show success message
                    const successMessage = document.createElement('div');
                    successMessage.className = 'fixed top-4 right-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-md z-50';
                    successMessage.innerHTML = '<div class="flex"><div class="py-1"><i class="fas fa-check-circle text-green-500 mr-3"></i></div><div><p class="font-bold">Success!</p><p>Evaluation deleted successfully.</p></div></div>';
                    document.body.appendChild(successMessage);

                    // Remove success message after 3 seconds
                    setTimeout(() => {
                        successMessage.remove();
                    }, 3000);

                } catch (error) {
                    console.error('Error deleting evaluation:', error);

                    // Show error message
                    const errorMessage = document.createElement('div');
                    errorMessage.className = 'fixed top-4 right-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-md z-50';
                    errorMessage.innerHTML = '<div class="flex"><div class="py-1"><i class="fas fa-exclamation-circle text-red-500 mr-3"></i></div><div><p class="font-bold">Error!</p><p>Failed to delete evaluation.</p></div></div>';
                    document.body.appendChild(errorMessage);

                    // Remove error message after 3 seconds
                    setTimeout(() => {
                        errorMessage.remove();
                    }, 3000);
                }
            }

            function toggleModal(modal) {
                modal.classList.toggle('hidden');
                document.body.style.overflow = modal.classList.contains('hidden') ? '' : 'hidden';
            }

            function debounce(func, wait) {
                let timeout;
                return function() {
                    const context = this, args = arguments;
                    clearTimeout(timeout);
                    timeout = setTimeout(() => func.apply(context, args), wait);
                };
            }
        });
    </script>
@endsection
