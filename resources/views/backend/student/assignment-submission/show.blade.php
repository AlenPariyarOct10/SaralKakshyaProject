@extends("backend.layout.student-dashboard-layout")

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Assignment Submission Details</h5>
                        <a href="{{ route('student.assignment-submissions.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="border-bottom pb-2 mb-3">Assignment Information</h6>
                                <table class="table table-borderless">
                                    <tr>
                                        <th style="width: 150px;">Title:</th>
                                        <td>{{ $submission->assignment->title }}</td>
                                    </tr>
                                    <tr>
                                        <th>Subject:</th>
                                        <td>{{ $submission->assignment->subject->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Batch:</th>
                                        <td>{{ $submission->assignment->batch->batch }}</td>
                                    </tr>
                                    <tr>
                                        <th>Semester:</th>
                                        <td>{{ $submission->assignment->semester }}</td>
                                    </tr>
                                    <tr>
                                        <th>Teacher:</th>
                                        <td>{{ $submission->assignment->teacher->getFullNameAttribute() }}</td>
                                    </tr>
                                    <tr>
                                        <th>Due Date:</th>
                                        <td>{{ $submission->assignment->due_date }} {{ $submission->assignment->due_time }}</td>
                                    </tr>
                                    <tr>
                                        <th>Full Marks:</th>
                                        <td>{{ $submission->assignment->full_marks }}</td>
                                    </tr>
                                </table>
                            </div>

                            <div class="col-md-6">
                                <h6 class="border-bottom pb-2 mb-3">Submission Information</h6>
                                <table class="table table-borderless">
                                    <tr>
                                        <th style="width: 150px;">Submitted On:</th>
                                        <td>{{ $submission->submitted_at }}</td>
                                    </tr>
                                    <tr>
                                        <th>Status:</th>
                                        <td>
                                            @if($submission->status == 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @elseif($submission->status == 'graded')
                                                <span class="badge bg-success">Graded</span>
                                            @elseif($submission->status == 'rejected')
                                                <span class="badge bg-danger">Rejected</span>
                                            @else
                                                <span class="badge bg-secondary">{{ $submission->status }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @if($submission->status == 'graded')
                                        <tr>
                                            <th>Marks:</th>
                                            <td>
                                                <strong>{{ $submission->marks }} / {{ $submission->assignment->full_marks }}</strong>
                                                ({{ number_format(($submission->marks / $submission->assignment->full_marks) * 100, 2) }}%)
                                            </td>
                                        </tr>
                                    @endif
                                    @if($submission->feedback)
                                        <tr>
                                            <th>Feedback:</th>
                                            <td>{{ $submission->feedback }}</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <th>Last Updated:</th>
                                        <td>{{ $submission->updated_at }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <h6 class="border-bottom pb-2 mb-3">Submitted Attachments</h6>

                                @if($submission->attachments->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-striped">
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
                                                        <a href="{{ route('attachments.download', $attachment->id) }}"
                                                           class="btn btn-sm btn-primary">
                                                            <i class="fas fa-download"></i> Download
                                                        </a>

                                                        @if(in_array($attachment->file_type, ['pdf', 'jpg', 'jpeg', 'png', 'gif']))
                                                            <a href="{{ route('attachments.preview', $attachment->id) }}"
                                                               class="btn btn-sm btn-info ms-1" target="_blank">
                                                                <i class="fas fa-eye"></i> Preview
                                                            </a>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="alert alert-info">No attachments found for this submission.</div>
                                @endif
                            </div>
                        </div>

                        @if($submission->comments)
                            <div class="row mt-4">
                                <div class="col-12">
                                    <h6 class="border-bottom pb-2 mb-3">Comments</h6>
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            {{ $submission->comments }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    @if($submission->status == 'pending' || $submission->status == 'rejected')
                                        <a href="{{ route('student.assignment-submissions.edit', $submission->id) }}"
                                           class="btn btn-primary">
                                            <i class="fas fa-edit"></i> Edit Submission
                                        </a>
                                    @endif

                                    <a href="{{ route('student.assignment-submissions.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left"></i> Back to List
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
