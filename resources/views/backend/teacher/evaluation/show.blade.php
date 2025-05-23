@extends("backend.layout.teacher-dashboard-layout")

@section('title', 'View Evaluation')

@section('content')
    <!-- Main Content Area -->
    <main class="p-6 md:p-6 min-h-screen overflow-y-auto pb-16">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-white mb-1">
                    Evaluation Details
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ $evaluation->subject->name }} ({{ $evaluation->subject->code }}) - {{ $evaluation->evaluationFormat->name }}
                </p>
            </div>
            <div class="mt-4 md:mt-0 flex flex-col md:flex-row gap-3">
                @if(!$evaluation->is_finalized)
                    <a href="{{ route('teacher.evaluation.edit', $evaluation->id) }}" class="btn-outline flex items-center justify-center">
                        <i class="fas fa-edit mr-2"></i> Edit Evaluation
                    </a>
                @endif
                <a href="{{ route('teacher.evaluation.index') }}" class="btn-secondary flex items-center justify-center">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Evaluations
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 mb-6 rounded relative" role="alert">
                <strong class="font-bold">Success!</strong>
                <p class="mt-2 text-sm">{{ session('success') }}</p>
            </div>
        @endif

        <!-- Evaluation Details -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <!-- Main Information -->
            <div class="lg:col-span-2">
                <div class="card h-full">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Evaluation Information</h3>

                        <div class="space-y-6">
                            <!-- Status Badge -->
                            <div class="flex items-center">
                                @if($evaluation->is_finalized)
                                    <span class="px-3 py-1 text-xs font-medium rounded-full bg-green-100 dark:bg-green-800 text-green-800 dark:text-green-100">Finalized</span>
                                @else
                                    <span class="px-3 py-1 text-xs font-medium rounded-full bg-yellow-100 dark:bg-yellow-800 text-yellow-800 dark:text-yellow-100">Draft</span>
                                @endif
                            </div>

                            <!-- General Comment -->
                            @if($evaluation->comment)
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">General Comment</h4>
                                    <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-md">
                                        <p class="text-gray-800 dark:text-gray-200">{{ $evaluation->comment }}</p>
                                    </div>
                                </div>
                            @endif

                            <!-- Subject, Batch, Format -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Subject</h4>
                                    <p class="text-gray-800 dark:text-white">{{ $evaluation->subject->name }} ({{ $evaluation->subject->code }})</p>
                                </div>

                                <div>
                                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Batch</h4>
                                    <p class="text-gray-800 dark:text-white">{{ $evaluation->batch->name }}</p>
                                </div>

                                <div>
                                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Semester</h4>
                                    <p class="text-gray-800 dark:text-white">Semester {{ $evaluation->semester }}</p>
                                </div>
                            </div>

                            <!-- Format Details -->
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Evaluation Format</h4>
                                <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-md">
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <h5 class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Name</h5>
                                            <p class="text-gray-800 dark:text-white">{{ $evaluation->evaluationFormat->name }}</p>
                                        </div>
                                        <div>
                                            <h5 class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Full Marks</h5>
                                            <p class="text-gray-800 dark:text-white">{{ $evaluation->evaluationFormat->full_marks }}</p>
                                        </div>
                                        <div>
                                            <h5 class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Weight</h5>
                                            <p class="text-gray-800 dark:text-white">{{ $evaluation->evaluationFormat->weight }}%</p>
                                        </div>
                                    </div>
                                    @if($evaluation->evaluationFormat->description)
                                        <div class="mt-2">
                                            <h5 class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Description</h5>
                                            <p class="text-gray-800 dark:text-white">{{ $evaluation->evaluationFormat->description }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Information -->
            <div>
                <div class="card mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Evaluation Summary</h3>

                        <div class="space-y-4">
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Total Students</h4>
                                <p class="text-gray-800 dark:text-white">{{ count($evaluationDetails) }}</p>
                            </div>

                            <div>
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Average Marks</h4>
                                <p class="text-gray-800 dark:text-white">
                                    @php
                                        $totalMarks = 0;
                                        foreach($evaluationDetails as $detail) {
                                            $totalMarks += $detail->obtained_marks;
                                        }
                                        $avgMarks = count($evaluationDetails) > 0 ? $totalMarks / count($evaluationDetails) : 0;
                                    @endphp
                                    {{ number_format($avgMarks, 2) }} / {{ $evaluation->evaluationFormat->full_marks }}
                                </p>
                            </div>

                            <div>
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Highest Marks</h4>
                                <p class="text-gray-800 dark:text-white">
                                    @php
                                        $highestMarks = 0;
                                        foreach($evaluationDetails as $detail) {
                                            if($detail->obtained_marks > $highestMarks) {
                                                $highestMarks = $detail->obtained_marks;
                                            }
                                        }
                                    @endphp
                                    {{ number_format($highestMarks, 2) }} / {{ $evaluation->evaluationFormat->full_marks }}
                                </p>
                            </div>

                            <div>
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Lowest Marks</h4>
                                <p class="text-gray-800 dark:text-white">
                                    @php
                                        $lowestMarks = $evaluation->evaluationFormat->full_marks;
                                        foreach($evaluationDetails as $detail) {
                                            if($detail->obtained_marks < $lowestMarks) {
                                                $lowestMarks = $detail->obtained_marks;
                                            }
                                        }
                                        if(count($evaluationDetails) === 0) {
                                            $lowestMarks = 0;
                                        }
                                    @endphp
                                    {{ number_format($lowestMarks, 2) }} / {{ $evaluation->evaluationFormat->full_marks }}
                                </p>
                            </div>

                            <div>
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Created By</h4>
                                <p class="text-gray-800 dark:text-white">{{ $evaluation->evaluatedBy->fname }}</p>
                            </div>

                            <div>
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Created At</h4>
                                <p class="text-gray-800 dark:text-white">{{ \Carbon\Carbon::parse($evaluation->created_at)->format('M d, Y') }}</p>
                            </div>

                            @if($evaluation->updated_at != $evaluation->created_at)
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Last Updated</h4>
                                    <p class="text-gray-800 dark:text-white">{{ \Carbon\Carbon::parse($evaluation->updated_at)->diffForHumans() }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="card">
                    <div class="p-6">
                        <div class="space-y-3">
                            @if(!$evaluation->is_finalized)
                                <form action="{{ route('teacher.evaluation.finalize', $evaluation->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn-primary w-full flex items-center justify-center">
                                        <i class="fas fa-check-circle mr-2"></i> Finalize Evaluation
                                    </button>
                                </form>

                                <a href="{{ route('teacher.evaluation.edit', $evaluation->id) }}" class="btn-outline w-full flex items-center justify-center">
                                    <i class="fas fa-edit mr-2"></i> Edit Evaluation
                                </a>
                            @endif

                            <button type="button" id="printEvaluationBtn" class="btn-secondary w-full flex items-center justify-center">
                                <i class="fas fa-print mr-2"></i> Print Evaluation
                            </button>

                            <button type="button" id="exportEvaluationBtn" class="px-4 py-2 w-full border border-gray-300 dark:border-gray-600 rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center justify-center">
                                <i class="fas fa-file-export mr-2"></i> Export as Excel
                            </button>

                            @if(!$evaluation->is_finalized)
                                <button type="button" id="deleteEvaluationBtn" class="px-4 py-2 w-full bg-red-600 text-white rounded-md hover:bg-red-700 flex items-center justify-center">
                                    <i class="fas fa-trash-alt mr-2"></i> Delete Evaluation
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Student Evaluations Table -->
        <div class="card mb-6">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Student Evaluations</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Student</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Roll Number</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Obtained Marks</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Normalized Marks</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Comment</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($evaluationDetails as $detail)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <img class="h-10 w-10 rounded-full object-cover" src="{{ $detail->student->profile_picture ?? '/images/default-avatar.png' }}" alt="{{ $detail->student }}">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-800 dark:text-white">{{ $detail->student }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-800 dark:text-white">{{ $detail->student }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-800 dark:text-white">
                                    {{ number_format($detail->obtained_marks, 2) }} / {{ $evaluation->evaluationFormat->full_marks }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-800 dark:text-white">
                                    {{ number_format($detail->normalized_marks, 2) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-normal">
                                <div class="text-sm text-gray-800 dark:text-white">
                                    {{ $detail->comment ?? '-' }}
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
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

                <form action="{{ route('teacher.evaluation.destroy', $evaluation->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="flex justify-end space-x-3">
                        <button type="button" id="cancelDelete" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                            Delete
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // DOM Elements
            const deleteEvaluationBtn = document.getElementById('deleteEvaluationBtn');
            const deleteConfirmationModal = document.getElementById('deleteConfirmationModal');
            const closeDeleteModal = document.getElementById('closeDeleteModal');
            const cancelDelete = document.getElementById('cancelDelete');
            const printEvaluationBtn = document.getElementById('printEvaluationBtn');
            const exportEvaluationBtn = document.getElementById('exportEvaluationBtn');

            // Event Listeners
            if (deleteEvaluationBtn) {
                deleteEvaluationBtn.addEventListener('click', () => toggleModal(deleteConfirmationModal));
            }

            if (closeDeleteModal) {
                closeDeleteModal.addEventListener('click', () => toggleModal(deleteConfirmationModal));
            }

            if (cancelDelete) {
                cancelDelete.addEventListener('click', () => toggleModal(deleteConfirmationModal));
            }

            if (printEvaluationBtn) {
                printEvaluationBtn.addEventListener('click', printEvaluation);
            }

            if (exportEvaluationBtn) {
                exportEvaluationBtn.addEventListener('click', exportEvaluation);
            }

            // Functions
            function toggleModal(modal) {
                modal.classList.toggle('hidden');
                document.body.style.overflow = modal.classList.contains('hidden') ? '' : 'hidden';
            }

            function printEvaluation() {
                window.print();
            }

            function exportEvaluation() {
                window.location.href = "{{ route('teacher.evaluation.export', $evaluation->id) }}";
            }
        });
    </script>
@endsection
