@extends("backend.layout.student-dashboard-layout")


@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Submit Assignment</h5>
                        <a href="{{ route('student.assignment-submission.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                    <div class="card-body">
                        @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        <form action="{{ route('student.assignment-submission.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label for="assignment_id" class="form-label">Select Assignment <span class="text-danger">*</span></label>
                                <select name="assignment_id" id="assignment_id" class="form-select @error('assignment_id') is-invalid @enderror" required>
                                    <option value="">-- Select Assignment --</option>
                                    @foreach($assignments as $assignment)
                                        <option value="{{ $assignment->id }}"
                                                {{ old('assignment_id') == $assignment->id ? 'selected' : '' }}
                                                data-due-date="{{ $assignment->due_date }}"
                                                data-due-time="{{ $assignment->due_time }}"
                                                data-full-marks="{{ $assignment->full_marks }}">
                                            {{ $assignment->title }} - {{ $assignment->subject->name }}
                                            (Due: {{ $assignment->due_date }} {{ $assignment->due_time }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('assignment_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3" id="assignment-details" style="display: none;">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6>Assignment Details</h6>
                                        <div id="assignment-info"></div>
                                        <div class="text-danger" id="due-date-warning" style="display: none;">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            This assignment is past due date. Late submissions may be penalized.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="attachments" class="form-label">Attachments <span class="text-danger">*</span></label>
                                <input type="file" name="attachments[]" id="attachments" class="form-control @error('attachments') is-invalid @enderror" multiple required>
                                <small class="form-text text-muted">
                                    You can upload multiple files. Allowed file types: PDF, DOC, DOCX, JPG, PNG, ZIP (Max size: 10MB per file)
                                </small>
                                @error('attachments')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @error('attachments.*')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="comments" class="form-label">Comments (Optional)</label>
                                <textarea name="comments" id="comments" rows="3" class="form-control @error('comments') is-invalid @enderror">{{ old('comments') }}</textarea>
                                @error('comments')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-check mb-3">
                                <input class="form-check-input @error('confirmation') is-invalid @enderror" type="checkbox" name="confirmation" id="confirmation" required>
                                <label class="form-check-label" for="confirmation">
                                    I confirm that this is my own work and I have not plagiarized from any source.
                                </label>
                                @error('confirmation')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane"></i> Submit Assignment
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const assignmentSelect = document.getElementById('assignment_id');
                const assignmentDetails = document.getElementById('assignment-details');
                const assignmentInfo = document.getElementById('assignment-info');
                const dueDateWarning = document.getElementById('due-date-warning');

                assignmentSelect.addEventListener('change', function() {
                    if (this.value) {
                        const selectedOption = this.options[this.selectedIndex];
                        const dueDate = selectedOption.dataset.dueDate;
                        const dueTime = selectedOption.dataset.dueTime;
                        const fullMarks = selectedOption.dataset.fullMarks;

                        // Display assignment details
                        assignmentInfo.innerHTML = `
                    <p><strong>Due Date:</strong> ${dueDate} ${dueTime}</p>
                    <p><strong>Full Marks:</strong> ${fullMarks}</p>
                `;

                        assignmentDetails.style.display = 'block';

                        // Check if past due date
                        const now = new Date();
                        const dueDateObj = new Date(`${dueDate} ${dueTime}`);

                        if (now > dueDateObj) {
                            dueDateWarning.style.display = 'block';
                        } else {
                            dueDateWarning.style.display = 'none';
                        }
                    } else {
                        assignmentDetails.style.display = 'none';
                    }
                });

                // Trigger change event if a value is already selected (e.g., on form validation error)
                if (assignmentSelect.value) {
                    assignmentSelect.dispatchEvent(new Event('change'));
                }
            });
        </script>
    @endpush
@endsection
