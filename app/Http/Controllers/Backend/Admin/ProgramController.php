<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Institute;
use App\Models\Program;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $institute = Institute::where('created_by', Auth::id())->first();

        $allDepartments = Department::where('institute_id', $institute->id)->get();
        $programs = Program::with('department')->where('created_by', Auth::guard('admin')->id())->get();
        $user = Auth::guard('admin')->user();
        return view('backend.admin.program', compact('user', 'allDepartments', 'programs'));
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
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'department_id' => 'required|integer|exists:departments,id',
            'total_semesters' => 'required|integer|max:15',
            'duration' => 'required|integer|max:15',
            'status' => 'required|string',
            'description' => 'required|string|max:500',
        ]);

        $validated['created_by'] = Auth::guard('admin')->id();
        $status = Program::create($validated);

        if ($status)
        {
            return redirect()->route('admin.programs.index')->with('success', 'Program created successfully');
        }else{
            return redirect()->route('admin.programs.index')->with('error', 'Failed to create program');
        }
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
        $program = Program::with('department')->find($id); // Using `find()` simplifies the query

        // Check if the program was found
        if (!$program) {
            return response()->json(['message' => 'Program not found'], 404);
        }

        return response()->json($program);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'department_id' => 'required|integer|exists:departments,id',
            'total_semesters' => 'required|integer|max:15',
            'duration' => 'required|integer|max:15',
            'status' => 'required|string',
            'description' => 'nullable|string|max:500',
        ]);

        $program = Program::findOrFail($id);

        $status = $program->update($validated);

        if ($status) {
            return response()->json(['success' => 'Program updated successfully']);
        } else {
            return response()->json(['error' => 'Failed to update program']);
        }
    }


    public function get_program_semesters(Request $request)
    {
        $semester = Program::findOrFaile($request->id)->semester;
        return response()->json($semester);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $program = Program::find($id);

            if (!$program) {
                return response()->json(['success' => false, 'message' => 'Program not found'], 404);
            }

            $program->delete(); // Use Eloquent instead of destroy()

            return response()->json(['success' => true, 'message' => 'Program deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }


}
