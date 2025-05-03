<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Gender</th>
        <th>Subject</th>
        <th>Qualification</th>
        <th>Approval Status</th>
        <th>Approved Date</th>
        <th>Registration Date</th>
    </tr>
    </thead>
    <tbody>
    @foreach($teachers as $teacher)
        <tr>
            <td>{{ $teacher->id }}</td>
            <td>{{ $teacher->fname }} {{ $teacher->lname }}</td>
            <td>{{ $teacher->email }}</td>
            <td>{{ $teacher->phone }}</td>
            <td>{{ ucfirst($teacher->gender) }}</td>
            <td>{{ $teacher->subject }}</td>
            <td>{{ $teacher->qualification }}</td>
            <td>{{ $teacher->isApproved ? 'Approved' : 'Pending' }}</td>
            <td>{{ $teacher->approvedAt ? \Carbon\Carbon::parse($teacher->approvedAt)->format('d-m-Y') : 'N/A' }}</td>
            <td>{{ $teacher->created_at->format('d-m-Y') }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
