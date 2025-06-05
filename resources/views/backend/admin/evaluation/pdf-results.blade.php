<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Evaluation Results - {{ $batch->program->name }} - Semester {{ $batch->semester }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        .header { text-align: center; margin-bottom: 20px; }
        .title { font-size: 18px; font-weight: bold; }
        .subtitle { font-size: 14px; margin-bottom: 10px; }
        .info { margin-bottom: 15px; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .pass { background-color: #d4edda; color: #155724; }
        .fail { background-color: #f8d7da; color: #721c24; }
        .footer { margin-top: 20px; font-size: 10px; text-align: right; }
    </style>
</head>
<body>
<div class="header">
    <div class="title">Evaluation Results</div>
    <div class="subtitle">{{ $batch->program->department->name }} - {{ $batch->program->name }}</div>
    <div class="info">Semester: {{ $batch->semester }} | Batch: {{ \Carbon\Carbon::parse( $batch->start_date)->format('Y') }}-{{ \Carbon\Carbon::parse($batch->end_date)->format('Y') }}</div>
</div>

<table>
    <thead>
    <tr>
        <th>#</th>
        <th>Student</th>
        <th>Midterm</th>
        <th>Preboard</th>
        <th>Assignment</th>
        <th>Attendance</th>
        <th>Total</th>
        <th>Status</th>
    </tr>
    </thead>
    <tbody>
    @foreach($results as $index => $student)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $student['student_name'] }}</td>
            <td>{{ $student['exam_details']['Midterm']['obtained_marks'] ?? 0 }}/{{ $student['exam_details']['Midterm']['full_marks'] ?? 0 }}</td>
            <td>{{ $student['exam_details']['Preboard']['obtained_marks'] ?? 0 }}/{{ $student['exam_details']['Preboard']['full_marks'] ?? 0 }}</td>
            <td>{{ $student['assignment']['obtained_marks'] }}/{{ $student['assignment']['full_marks'] }}</td>
            <td>{{ $student['attendance']['obtained_marks'] }}/{{ $student['attendance']['full_marks'] }}</td>
            <td>{{ $student['total']['obtained_marks'] }}/{{ $student['total']['full_marks'] }}</td>
            <td class="{{ strtolower($student['status']) }}">{{ $student['status'] }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<div class="footer">
    Generated on: {{ now()->format('Y-m-d H:i:s') }}
</div>
</body>
</html>
