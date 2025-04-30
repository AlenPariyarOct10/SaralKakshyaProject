<div class="card">
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 mb-3 rounded relative" role="alert">
            <strong class="font-bold">Success !</strong>
            <span class="block sm:inline">{{session('success')}}</span>
        </div>
    @endif
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

    <div class="p-6">
        <form action="{{ route('admin.subjects.store') }}" method="POST">
            @csrf
            <!-- Basic Information -->
            <div class="mb-6">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Basic Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="subjectName" class="form-label">Subject Name <span class="text-red-500">*</span></label>
                        <input type="text" id="subjectName" name="name" class="form-input" placeholder="Enter subject name" required>
                        @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="subjectCode" class="form-label">Subject Code <span class="text-red-500">*</span></label>
                        <input type="text" id="subjectCode" name="code" class="form-input" placeholder="e.g. CS101" required>
                        @error('code')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <!--Subjects Marks-->
                    <div>
                        <label for="subjectName" class="form-label">External Full Marks <span class="text-red-500">*</span></label>
                        <input type="text" id="max_external_marks" name="max_external_marks" class="form-input" placeholder="Enter full marks (External)" required>
                        @error('max_external_marks')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="subjectName" class="form-label">Internal Full Marks <span class="text-red-500">*</span></label>
                        <input type="text" id="max_internal_marks" name="max_internal_marks" class="form-input" placeholder="Enter full marks (Internal)" required>
                        @error('max_internal_marks')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <!--Credit Hour-->
                    <div>
                        <label for="credit" class="form-label">Credit Hours <span class="text-red-500">*</span></label>
                        <input type="number" id="credit" name="credit" class="form-input" min="1" max="6" placeholder="e.g. 3" required>
                        @error('credit')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="status" class="form-label">Status</label>
                        <select id="status" name="status" class="form-input">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                        @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Program and Batch Information -->
            <div class="mb-6">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Program Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Program Dropdown -->
                    <div>
                        <label for="program" class="form-label">Program <span class="text-red-500">*</span></label>
                        <select id="program" name="program_id" class="form-input" required>
                            <option value="">Select Program</option>
                            @foreach($programs as $program)
                                <option value="{{ $program->id }}" @if($selectedProgram == $program->id) selected @endif>
                                    {{ $program->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('selectedProgram')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Semester Dropdown -->
                    <div>
                        <label for="semester" class="form-label">Semester <span class="text-red-500">*</span></label>
                        <select id="semester" name="semester" class="form-input" required>
                            <option value="">Select Semester</option>
                        </select>
                        @error('selectedSemester')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>


                </div>
            </div>

            <!-- Description -->
            <div class="mb-6">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Description</h2>
                <div>
                    <label for="description" class="form-label">Subject Description</label>
                    <textarea id="description" name="description" rows="4" class="form-input" placeholder="Enter subject description"></textarea>
                    @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <!-- Evaluation Formats -->
            <div class="mb-6">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white">Evaluation Formats  [<span id="total-weight" class="text-sm font-medium text-gray-900 dark:text-white mb-3">Evaluation Formats</span>]</h2>
                <div class="mb-3">
                    <label for="reuse_format" class="form-label">Use existing format </label>
                    <select id="reuse_format" class="form-input">
                        <option value="">Select Subject to duplicate format</option>

                    </select>
                    @error('selectedProgram')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div id="evaluation-formats" class="space-y-4">
                    <div class="evaluation-format grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label for="criteria_1" class="form-label">Criteria <span class="text-red-500">*</span></label>
                            <input type="text" id="criteria_1" name="criteria[]" class="form-input" placeholder="e.g. Midterm Exam" required>
                        </div>
                        <div>
                            <label for="full_marks_1" class="form-label">Full Marks <span class="text-red-500">*</span></label>
                            <input type="number" id="full_marks_1" name="full_marks[]" class="form-input" min="1" placeholder="e.g. 100" required>
                        </div>
                        <div>
                            <label for="pass_marks_1" class="form-label">Pass Marks <span class="text-red-500">*</span></label>
                            <input type="number" id="pass_marks_1" name="pass_marks[]" class="form-input" min="1" placeholder="e.g. 40" required>
                        </div>
                        <div>
                            <label for="marks_weight_1" class="form-label">Marks Weight <span class="text-red-500">*</span></label>
                            <input type="number" id="marks_weight_1" name="marks_weight[]" class="form-input marks-weight" min="1" placeholder="e.g. 40" required>
                        </div>
                    </div>
                </div>
                <div class="mt-3 grid grid-cols-1 md:grid-cols-4 gap-4">
                    <button type="button" id="add-evaluation-format" class="btn-secondary flex items-center">
                        <i class="fas fa-plus mr-2"></i> Add Another Evaluation Format
                    </button>
                </div>
            </div>
            <!-- Form Actions -->
            <div class="flex justify-end space-x-3 mt-8">
                <a href="{{ route('admin.subjects.index') }}" class="btn-secondary">Cancel</a>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save mr-2"></i> Save Subject
                </button>
            </div>
        </form>
    </div>


</div>
