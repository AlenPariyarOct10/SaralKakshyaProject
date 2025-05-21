@extends("backend.layout.student-dashboard-layout")


@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">My Assignment Submissions</h5>
                        <a href="{{ route('student.assignment-submission.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Submit New Assignment
                        </a>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>Assignment</th>
                                    <th>Subject</th>
                                    <th>Submitted On</th>
                                    <th>Status</th>
                                    <th>Marks</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($submissions as $submission)
                                    <tr>
                                        <td>{{ $submission->assignment->title }}</td>
                                        <td>{{ $submission->assignment->subject->name }}</td>
                                        <td>{{ $submission->submitted_at }}</td>
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
                                        <td>
                                            @if($submission->marks)
                                                {{ $submission->marks }} / {{ $submission->assignment->full_marks }}
                                            @else
                                                <span class="text-muted">Not graded</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('student.assignment-submission.show', $submission->id) }}"
                                                   class="btn btn-info btn-sm" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>

                                                @if($submission->status == 'pending' || $submission->status == 'rejected')
                                                    <a href="{{ route('student.assignment-submission.edit', $submission->id) }}"
                                                       class="btn btn-primary btn-sm" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                @endif

                                                @if($submission->status == 'pending')
                                                    <form action="{{ route('student.assignment-submission.destroy', $submission->id) }}"
                                                          method="POST" class="d-inline"
                                                          onsubmit="return confirm('Are you sure you want to delete this submission?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No assignment submissions found.</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center mt-4">
                            {{ $submissions->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
