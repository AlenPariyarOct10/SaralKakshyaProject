<?php
namespace App\Listeners;

use App\Events\AssignmentCreated;
use App\Models\Assignment;
use App\Models\Batch;
use App\Models\Notification;
use App\Models\Student;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener]
class NotifyStudentsAboutAssignment implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(AssignmentCreated $event): void
    {
        Log::info('NotifyStudentsAboutAssignment listener triggered');
        $assignment = $event->assignment;

        $batch = Batch::where('id', $assignment->batch_id)->first();
        $students = Student::where('batch', $batch->batch)->get();

        Log::info('Batch id : ' . $batch->batch);
        Log::info('Students : ' . json_encode($students));

        foreach ($students as $student) {
            Log::info('Creating notification for student: ' . $student->id);

            Notification::create([
                'title' => 'New Assignment: ' . $assignment->title,
                'creator_type' => 'teacher',
                'creator_id' => $assignment->teacher_id,
                'url' => route('student.assignment.show', $assignment->id),
                'visibility' => 'students',
                'scope_type' => 'batch',
                'scope_id' => $assignment->batch_id,
                'subscope_type' => 'subject',
                'subscope_id' => $assignment->subject_id,
                'notifiable_type' => Student::class,
                'notifiable_id' => $student->id,
                'parent_type' => Assignment::class,
                'parent_id' => $assignment->id,
            ]);
        }

        // Broadcast the event once to notify all clients listening for this batch
    }
}
