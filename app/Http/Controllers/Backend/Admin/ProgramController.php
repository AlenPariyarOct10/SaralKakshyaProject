<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
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
        $allDepartments = Department::all();
        $programs = Program::with('department')->get();
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
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
