<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Department;
use App\Models\Institute;
use App\Models\Program;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BatchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $institute = Institute::where('created_by', Auth::guard('admin')->id())->first();
        $allDepartments = Department::where('institute_id', $institute->id)->get();
        $batches = Batch::with(['program', 'program.department'])
            ->where('institute_id', $institute->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $user = Auth::guard('admin')->user();
        return view('backend.admin.batch.index', compact('user', 'allDepartments', 'batches'));
    }

    /**
     * Get batches for a specific department and program
     */
    public function getBatches(Request $request)
    {
        $department_id = $request->input('department_id');
        $program_id = $request->input('program_id');

        echo json_encode(Batch::where('department_id', $department_id)->where('program_id', $program_id)->get());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $institute = Institute::where('created_by', Auth::guard('admin')->id())->first();
        $allDepartments = Department::where('institute_id', $institute->id)->get();
        $user = Auth::guard('admin')->user();

        return view('backend.admin.batch.create', compact('user', 'allDepartments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
            $request->validate([
                'department_id' => 'required|exists:departments,id',
                'program_id' => 'required|exists:programs,id',
                'semester' => 'required|integer|min:1',
                'batch' => 'required|string|max:255',
                'status' => 'required|in:active,inactive',
                'start_date' => 'required|date',
                'end_date' => 'required|date',
            ]);

            $department_id = $request->input('department_id');
            $program_id = $request->input('program_id');
            $semester = $request->input('semester');
            $batch = $request->input('batch');
            $start_date = $request->input('start_date');
            $end_date = $request->input('end_date');
            $status = $request->input('status');
            $institute_id = session('institute_id');

            // Check if batch already exists
            $exists = Batch::where('program_id', $program_id)
                ->where('semester', $semester)
                ->where('batch', $batch)
                ->exists();

            if ($exists) {
                return response()->json([
                    "status" => "fail",
                    'message' => 'A batch with this name already exists for this program and semester'
                ]);
            }

            Batch::create([
                'department_id' => $department_id,
                'program_id' => $program_id,
                'semester' => $semester,
                'batch' => $batch,
                'status' => $status,
                'institute_id' => $institute_id,
                'start_date' => $start_date,
                'end_date' => $end_date
            ]);

            return response()->json(["status" => "success", "message" => "Batch created successfully"]);
        } catch (\Exception $exception) {
            return response()->json(["status" => "fail", 'message' => $exception->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $batch = Batch::with(['program', 'program.department', 'subjects'])->findOrFail($id);
        $user = Auth::guard('admin')->user();

        return view('backend.admin.batch.show', compact('batch', 'user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $batch = Batch::findOrFail($id);

        return response()->json($batch);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $request->validate([
                'department_id' => 'required|exists:departments,id',
                'program_id' => 'required|exists:programs,id',
                'semester' => 'required|integer|min:1',
                'batch' => 'required|string|max:255',
                'status' => 'required|in:active,inactive',
                'start_date' => 'required|date',
                'end_date' => 'required|date',
            ]);

            $batch = Batch::findOrFail($id);

            // Check if batch already exists (excluding current batch)
            $exists = Batch::where('program_id', $request->program_id)
                ->where('semester', $request->semester)
                ->where('batch', $request->batch)
                ->where('id', '!=', $id)
                ->exists();

            if ($exists) {
                return response()->json([
                    "status" => "fail",
                    'message' => 'A batch with this name already exists for this program and semester'
                ]);
            }

            $batch->update([
                'department_id' => $request->department_id,
                'program_id' => $request->program_id,
                'semester' => $request->semester,
                'batch' => $request->batch,
                'status' => $request->status,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date
            ]);

            return response()->json(["status" => "success", "message" => "Batch updated successfully"]);
        } catch (\Exception $exception) {
            return response()->json(["status" => "fail", 'message' => $exception->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $batch = Batch::findOrFail($id);

            // Check if there are any dependencies (like students enrolled in this batch)
            // This is just a placeholder - you'll need to implement the actual check based on your database structure
            $hasDependencies = false; // Replace with actual check

            if ($hasDependencies) {
                return response()->json([
                    "status" => "fail",
                    'message' => 'Cannot delete this batch as it has students enrolled'
                ]);
            }

            $batch->delete();

            return response()->json(["status" => "success", "message" => "Batch deleted successfully"]);
        } catch (\Exception $exception) {
            return response()->json(["status" => "fail", 'message' => $exception->getMessage()]);
        }
    }

    /**
     * Get subjects for a specific batch.
     */
    public function getSubjects($id)
    {
        $batch = Batch::findOrFail($id);

        $subjects = Subject::where('semester', $batch->semester)->get();

        return response()->json($batch->subjects);
    }
}
