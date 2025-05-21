@extends("backend.layout.student-dashboard-layout")


@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Edit Assignment Submission</h5>
                        <div>
                            <a href="{{ route('student.assignment-submissions.show', $submission->id) }}" class="btn btn-info btn-sm me-2">
                                <i class="fas fa-eye"></i> View Details
                            </a>
                            <a href="{{ route('student.assignment-submissions.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> Back to List
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        <div class="alert alert-info">
                            <strong>Note:</strong> You are editing your submission for the assignment
                            "<strong>{{ $submission->assignment->title }}</strong>".
                            @if($submission->status == 'rejected')
                                <div class="mt-2">
                                    <strong>Rejection Feedback:</strong> {{ $submission->feedback }}
                                </div>
                            @endif
                        </div>

                        <form action="{{ route('student.assignment-submissions.update', $submission->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="assignment_id" class="form-label">Assignment</label>
                                <input type="text" class="form-control" value="{{ $submission->assignment->title }} - {{ $submission->assignment->subject->name }}" readonly>
                                <input type="hidden" name="assignment_id" value="{{ $submission->assignment_id }}">
                            </div>

                            <div class="mb-3">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6>Assignment Details</h6>
                                        <p><strong>Due Date:</strong> {{ $submission->assignment->due_date }} {{ $submission->assignment->due_time }}</p>
                                        <p><strong>Full Marks:</strong> {{ $submission->assignment->full_marks }}</p>

                                        @php
                                            $now = new \DateTime();
                                            $dueDate = new \DateTime($submission->assignment->due_date . ' ' . $submission->assignment->due_time);
                                        @endphp

                                        @if($now > $dueDate)
                                            <div class="text-danger">
                                                <i class="fas fa-exclamation-triangle"></i>
                                                This assignment is past due date. Late submissions may be penalized.
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Current Attachments</label>
                                @if($submission->attachments->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>File Name</th>
                                                <th>File Type</th>
                                                <th>Size</th>
                                                <th>Actions</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($submission->attachments as $index => $attachment)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $attachment->original_name }}</td>
                                                    <td>{{ strtoupper($attachment->file_type) }}</td>
                                                    <td>{{ $attachment->file_size_formatted }}</td>
                                                    <td>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                   name="remove_attachments[]"
                                                                   value="{{ $attachment->id }}"
                                                                   id="remove_attachment_{{ $attachment->id }}">
                                                            <label class="form-check-label" for="remove_attachment_{{ $attachment->id }}">
                                                                Remove
                                                            </label>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="alert alert-warning">No attachments found for this submission.</div>
                                @endif
                            </div>

                            <div class="mb-3">
                                <label for="new_attachments" class="form-label">Add New Attachments</label>
                                <input type="file" name="new_attachments[]" id="new_attachments" class="form-control @error('new_attachments') is-invalid @enderror" multiple>
                                <small class="form-text text-muted">
                                    You can upload multiple files. Allowed file types: PDF, DOC, DOCX, JPG, PNG, ZIP (Max size: 10MB per file)
                                </small>
                                @error('new_attachments')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @error('new_attachments.*')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="comments" class="form-label">Comments (Optional)</label>
                                <textarea name="comments" id="comments" rows="3" class="form-control @error('comments') is-invalid @enderror">{{ old('comments', $submission->comments) }}</textarea>
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

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('student.assignment-submissions.show', $submission->id) }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update Submission
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
