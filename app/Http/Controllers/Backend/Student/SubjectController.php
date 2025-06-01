<?php

namespace App\Http\Controllers\Backend\Student;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $batch = Batch::where('id', $user->batch_id)->first();

        $subjects = Subject::where('program_id', session('program_id'))
            ->where('semester', $batch->semester)
            ->orderBy('code', 'ASC')
            ->get();


        return view('backend.student.subjects.index', compact('subjects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function subjectResources($id)
    {
        $subject = Subject::with(['resources' => function ($query) {
            $query->orderBy('created_at', 'DESC');
        }])->findOrFail($id);

        return view('backend.student.subjects.resources.index', [
            'subject' => $subject,
            'resources' => $subject->resources // Add this line
        ]);
    }

    public function subjectAssignments($id)
    {
        $user = Auth::user();

        // Find the subject and load assignments
        $subject = Subject::with(['assignments' => function($query) {
            $query->orderBy('created_at', 'DESC')
                ->where('status', 'active'); // Optional: match same condition
        }])->findOrFail($id);

        // Filter assignments belonging to the subject
        $assignments = $subject->assignments;

        // Separate submitted and pending assignments
        $submittedAssignments = $assignments->filter(function ($assignment) use ($user) {
            return $assignment->submissions()->where('student_id', $user->id)->exists();
        });

        $pendingAssignments = $assignments->filter(function ($assignment) use ($user) {
            return !$assignment->submissions()->where('student_id', $user->id)->exists();
        });

        return view('backend.student.subjects.assignments.index', [
            'user' => $user,
            'submittedAssignments' => $submittedAssignments,
            'pendingAssignments' => $pendingAssignments,
            'subject' => $subject // Optional: for subject name display
        ]);
    }


    public function showResource($id, $resourceId)
    {
        $subject = Subject::with(['resources' => function ($query) {
            $query->orderBy('created_at', 'DESC');
        }])->findOrFail($id);

        $resource = $subject->resources()->findOrFail($resourceId);

        return view('backend.student.subjects.resources.show', [
            'subject' => $subject,
            'resource' => $resource
        ]);
    }
}
