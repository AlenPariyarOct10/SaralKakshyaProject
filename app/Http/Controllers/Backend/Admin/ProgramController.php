<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Institute;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProgramController extends Controller
{
    public function index()
    {
        $institute = Institute::where('created_by', Auth::id())->first();
        $allDepartments = Department::where('institute_id', $institute->id)->get();
        $programs = Program::with('department')
            ->where('created_by', Auth::guard('admin')->id())
            ->get();

        $user = Auth::guard('admin')->user();
        return view('backend.admin.program', compact('user', 'allDepartments', 'programs'));
    }

    public function store(Request $request)
    {
        $instituteId = Institute::where('created_by', Auth::guard('admin')->id())->value('id');

        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'department_id' => 'required|integer|exists:departments,id',
            'total_semesters' => 'required|integer|max:15',
            'duration' => 'required|integer|max:15',
            'status' => 'required|string|in:active,inactive',
            'description' => 'nullable|string|max:500',
        ]);

        // Add additional fields after validation
        $validated['institute_id'] = $instituteId;
        $validated['created_by'] = Auth::guard('admin')->id();

        try {
            $program = Program::create($validated);
            return redirect()->route('admin.programs.index')
                ->with('success', 'Program created successfully');
        } catch (\Exception $e) {
            return redirect()->route('admin.programs.index')
                ->with('error', 'Failed to create program: ' . $e->getMessage());
        }
    }

    public function edit(string $id)
    {
        $program = Program::with('department')->find($id);

        if (!$program) {
            return response()->json(['message' => 'Program not found'], 404);
        }

        return response()->json($program);
    }

    public function getSemesters($id)
    {
        $program = Program::find($id);
        return response()->json([
            'total_semesters' => $program ? $program->total_semesters : 0
        ]);
    }

    public function getSubjects($id)
    {
        $program = Program::with('subjects')->find($id);
        return response()->json([
            'subjects' => $program ? $program->subjects : []
        ]);
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'department_id' => 'required|integer|exists:departments,id',
            'total_semesters' => 'required|integer|max:15',
            'duration' => 'required|integer|max:15',
            'status' => 'required|string|in:active,inactive',
            'description' => 'nullable|string|max:500',
        ]);

        $program = Program::findOrFail($id);

        try {
            $program->update($validated);
            return response()->json(['success' => true, 'message' => 'Program updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to update program'], 500);
        }
    }

    public function get_program_semesters(Request $request)
    {
        $program = Program::findOrFail($request->id);
        return response()->json([
            'semesters' => $program->total_semesters
        ]);
    }

    public function destroy(string $id)
    {
        try {
            $program = Program::findOrFail($id);
            $program->delete();

            return response()->json([
                'success' => true,
                'message' => 'Program deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete program: ' . $e->getMessage()
            ], 500);
        }
    }
}
