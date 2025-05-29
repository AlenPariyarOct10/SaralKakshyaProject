@extends("backend.layout.teacher-dashboard-layout")

@section('title', 'Edit Evaluation')

@section('content')
    <!-- Main Content Area -->
    <main class="p-6 md:p-6 min-h-screen overflow-y-auto pb-16">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-white mb-1">
                    Edit Evaluation
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Update student evaluation details
                </p>
            </div>
            <div class="mt-4 md:mt-0 flex flex-col md:flex-row gap-3">
                <a href="{{ route('teacher.evaluation.show', $evaluation->id) }}" class="btn-outline flex items-center justify-center">
                    <i class="fas fa-eye mr-2"></i> View Evaluation
                </a>
                <a href="{{ route('teacher.evaluation.index') }}" class="btn-secondary flex items-center justify-center">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Evaluations
                </a>
            </div>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 mb-3 rounded relative" role="alert">
                <strong class="font-bold">Whoops!</strong>
                <ul class="mt-2 list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 mb-3 rounded relative" role="alert">
                <strong class="font-bold">Success!</strong>
                <p class="mt-2 text-sm">{{ session('success') }}</p>
            </div>
        @endif

        <!-- Edit Evaluation Form -->
        <div class="card mb-8">
            <div class="p-6">
                <form action="{{ route('teacher.evaluation.update', $evaluation->id) }}" method="POST" id="editEvaluationForm">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Left Column -->
                        <div class="space-y-6">
                            <!-- Batch Information (Read-only) -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Batch
                                </label>
                                <div class="w-full px-4 py-2 bg-gray-100 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-md text-gray-700 dark:text-gray-300">
                                    {{ $evaluation->batch->name }} ({{ $evaluation->batch->program->name }})
                                </div>
                            </div>

                            <!-- Subject Information (Read-only) -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Subject
                                </label>
                                <div class="w-full px-4 py-2 bg-gray-100 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-md text-gray-700 dark:text-gray-300">
                                    {{ $evaluation->subject->name }} ({{ $evaluation->subject->code }})
                                </div>
                            </div>

                            <!-- Evaluation Format Information (Read-only) -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Evaluation Format
                                </label>
                                <div class="w-full px-4 py-2 bg-gray-100 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-md text-gray-700 dark:text-gray-300">
                                    {{ $evaluation->evaluationFormat->criteria }}
                                </div>
                            </div>

                            <!-- Semester (Read-only) -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Semester
                                </label>
                                <div class="w-full px-4 py-2 bg-gray-100 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-md text-gray-700 dark:text-gray-300">
                                    Semester {{ $evaluation->semester }}
                                </div>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="space-y-6">
                            <!-- Status -->
                            <div>
                                <label for="is_finalized" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Status <span class="text-red-500">*</span>
                                </label>
                                <select
                                    id="is_finalized"
                                    name="is_finalized"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    required
                                >
                                    <option value="0" {{ old('is_finalized', $evaluation->is_finalized ? '0' : '1') == '0' ? 'selected' : '' }}>Draft</option>
                                    <option value="1" {{ old('is_finalized', $evaluation->is_finalized ? '1' : '0') == '1' ? 'selected' : '' }}>Finalized</option>
                                </select>
                            </div>

                            <!-- Comment -->
                            <div>
                                <label for="comment" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    General Comment
                                </label>
                                <textarea
                                    id="comment"
                                    name="comment"
                                    rows="4"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    placeholder="Enter general comment about the evaluation"
                                >{{ old('comment', $evaluation->comment) }}</textarea>
                            </div>

                            <!-- Format Information -->
                            <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-md">
                                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Format Information</h4>
                                <div class="space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600 dark:text-gray-400">Full Marks:</span>
                                        <span class="text-sm font-medium text-gray-800 dark:text-white">{{ $evaluation->evaluationFormat->full_marks }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600 dark:text-gray-400">Weight:</span>
                                        <span class="text-sm font-medium text-gray-800 dark:text-white">{{ $evaluation->evaluationFormat->marks_weight }}</span>
                                    </div>
                                    @if($evaluation->evaluationFormat->description)
                                        <div class="flex justify-between">
                                            <span class="text-sm text-gray-600 dark:text-gray-400">Description:</span>
                                            <span class="text-sm font-medium text-gray-800 dark:text-white">{{ $evaluation->evaluationFormat->description }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Student Evaluations Section -->
                    <div class="mt-8">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Student Evaluations</h3>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Student</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Roll Number</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Obtained Marks <span class="text-red-500">*</span></th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Comment</th>
                                </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($evaluationDetails as $detail)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <img class="h-10 w-10 rounded-full object-cover" src="{{ asset('/storage/'.$detail->student->profile_picture) ?? '/images/default-avatar.png' }}" alt="{{ $detail->student->full_name }}">
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-800 dark:text-white">{{ $detail->student->full_name }}</div>
                                                    <input type="hidden" name="students[]" value="{{ $detail->student->id }}">
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-800 dark:text-white">{{ $detail->student->roll_number }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input
                                                type="number"
                                                name="marks[{{ $detail->student->id }}]"
                                                value="{{ old('marks.' . $detail->student->id, $detail->obtained_marks) }}"
                                                class="w-24 px-2 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                                min="0"
                                                max="{{ $evaluation->evaluationFormat->full_marks }}"
                                                step="0.01"
                                                required
                                            >
                                            <span class="text-xs text-gray-500 dark:text-gray-400 ml-2">/ {{ $evaluation->evaluationFormat->full_marks }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input
                                                type="text"
                                                name="comments[{{ $detail->student->id }}]"
                                                value="{{ old('comments.' . $detail->student->id, $detail->comment) }}"
                                                class="w-full px-2 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                                placeholder="Optional comment"
                                            >
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="mt-8 mb-4 flex justify-end space-x-3">
                        <a href="{{ route('teacher.evaluation.index') }}" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                            Cancel
                        </a>
                        <button type="submit" name="finalize" value="1" class="btn-primary">
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // DOM Elements
            const isFinalized = document.getElementById('is_finalized');
            const editEvaluationForm = document.getElementById('editEvaluationForm');

            // Add event listeners for mark validation
            document.querySelectorAll('input[name^="marks["]').forEach(input => {
                input.addEventListener('input', function() {
                    const value = parseFloat(this.value);
                    const max = parseFloat(this.getAttribute('max'));

                    if (isNaN(value) || value < 0) {
                        this.value = '';
                        this.classList.add('border-red-500');
                    } else if (value > max) {
                        this.value = max;
                        this.classList.add('border-red-500');
                        setTimeout(() => {
                            this.classList.remove('border-red-500');
                        }, 1000);
                    } else {
                        this.classList.remove('border-red-500');
                    }
                });
            });

            // Form submission
            if (editEvaluationForm) {
                editEvaluationForm.addEventListener('submit', function(e) {
                    // Validate that all students have marks entered
                    const markInputs = document.querySelectorAll('input[name^="marks["]');
                    let valid = true;

                    markInputs.forEach(input => {
                        if (!input.value || isNaN(parseFloat(input.value))) {
                            valid = false;
                            input.classList.add('border-red-500');
                        } else {
                            input.classList.remove('border-red-500');
                        }
                    });

                    if (!valid) {
                        e.preventDefault();
                        alert('Please enter valid marks for all students');
                        return false;
                    }

                    // Set is_finalized based on which button was clicked
                    if (e.submitter.name === 'finalize') {
                        isFinalized.value = '1';
                    } else if (e.submitter.name === 'save_draft') {
                        isFinalized.value = '0';
                    }
                });
            }
        });
    </script>
@endsection
