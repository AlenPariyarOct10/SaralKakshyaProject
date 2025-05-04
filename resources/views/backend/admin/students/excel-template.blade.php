<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Gender</th>
        <th>Approval Status</th>
        <th>Approved Date</th>
        <th>Registration Date</th>
    </tr>
    </thead>
    <tbody>
    @foreach($students as $student)
        <tr>
            <td>{{ $student->id }}</td>
            <td>{{ $student->fname }} {{ $student->lname }}</td>
            <td>{{ $student->email }}</td>
            <td>{{ $student->phone }}</td>
            <td>{{ ucfirst($student->gender) }}</td>
            <td>{{ $student->is_approved ? 'Approved' : 'Pending' }}</td>
            <td>{{ $student->approved_at ? \Carbon\Carbon::parse($student->approved_at)->format('d-m-Y') : 'N/A' }}</td>
            <td>{{ $student->created_at->format('d-m-Y') }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
