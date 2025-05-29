<!DOCTYPE html>
<html>
<head>
    <title>Class Routine</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
<h2>Class Routine - {{ $student->batch->program->name ?? '' }}</h2>
<p><strong>Student:</strong> {{ $student->fname ?? '' }} {{ $student->lname ?? '' }}</p>
<p><strong>Batch:</strong> {{ $student->batch->batch ?? '' }}</p>

<table>
    <thead>
    <tr>
        <th>Day</th>
        <th>Time Slot</th>
        <th>Subject</th>
        <th>Teacher</th>
        <th>Notes</th>
    </tr>
    </thead>
    <tbody>
    @foreach($routine as $class)
        <tr>
            <td>{{ $class->day }}</td>
            <td>{{ $class->time_slot }}</td>
            <td>{{ $class->subject_name }}</td>
            <td>{{ $class->teacher_name }}</td>
            <td>{{ $class->notes ?? '-' }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
