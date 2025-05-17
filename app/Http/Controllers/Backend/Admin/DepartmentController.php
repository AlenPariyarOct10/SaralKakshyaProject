<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Institute;
use App\Models\Program;
use App\Models\ProgramSection;
use App\Models\Subject;
use App\Models\SubjectTeacherMapping;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $institute = Institute::where('created_by', Auth::id())->first();

        $user = Auth::guard('admin')->user();
        $allDepartments = Department::where('institute_id', $institute->id)->get();
        return view('backend.admin.department', compact('user', 'allDepartments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function get_department($id)
    {
        $department = Department::findOrFail($id);
        return response()->json($department);
    }

    public function get_department_programs(Request $request)
    {
        $request->validate([
            'department_id' => 'required|exists:departments,id'
        ]);

        $programs = Program::where('department_id', $request->department_id)
            ->select('id', 'name')
            ->get();

        return response()->json($programs);
    }


    public function getAllDepartments()
    {
        $institutes = Institute::where('created_by', Auth::id())->first();
        $departments = Department::where('institute_id', $institutes->id)->get();
        return response()->json($departments?$departments:[]);
    }

    public function getTeacherMappings($id)
    {
        $mappings = SubjectTeacherMapping::with([
            'teacher',
            'subject.program.department' // Follow the relationship chain
        ])
            ->whereHas('subject.program', function($query) use ($id) {
                $query->where('department_id', $id);
            })
            ->get();

        // Return the collection directly as JSON array
        return response()->json($mappings);
    }


    public function storeSection(Request $request)
    {
        $validated = $request->validate([
            'program_id' => 'required|exists:programs,id',
            'sections' => 'required|array|min:1',
            'sections.*.name' => 'required|string|max:100|unique:program_sections,section_name,NULL,id,program_id,'.$request->program_id
        ]);

        try {
            $sections = array_map(function($section) use ($request) {
                return [
                    'program_id' => $request->program_id,
                    'section_name' => $section['name'],
                    'status' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }, $request->sections);

            ProgramSection::insert($sections);

            return response()->json([
                'success' => true,
                'message' => 'Sections created successfully',
                'sections' => $sections
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create sections',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getSubjects(Request $request)
    {
        $departmentId = $request->department_id;

        $subjects = Subject::whereHas('program', function ($query) use ($departmentId) {
            $query->where('department_id', $departmentId);
        })->get();

        if ($subjects->isEmpty()) {
            return response()->json(['message' => 'No subjects found for this department'], 404);
        }

        return response()->json($subjects);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       $request = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'description' => 'max:500',
        ]);

       $request['institute_id'] =Auth::guard('admin')->user()->institute->id;

       $status = Department::create($request);
       if($status)
       {
           return redirect()->route('admin.department.index')->with('success', 'Department created successfully');
       }else{
           return redirect()->route('admin.department.index')->with('error', 'Failed to create department');

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
    public function update(Request $request)
    {
        // Find the department record by ID
        $department = Department::findOrFail($request->id);

        // Validate the request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'description' => 'nullable|max:500', // Allow null values
        ]);

        // Update the department record
        $updated = $department->update($validatedData);

        // Redirect based on the result
        if ($updated) {
            return redirect()->route('admin.department.index')->with('success', 'Department updated successfully');
        } else {
            return redirect()->route('admin.department.index')->with('error', 'Failed to update Department');
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $department = Department::find($id);

            if (!$department) {
                return response()->json(['success' => false, 'message' => 'Department not found'], 404);
            }

            $department->delete();

            return response()->json(['success' => true, 'message' => 'Department deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }


    public function getBySubject($id)
    {
        $subject = Subject::findOrFail($id);
        $program = Program::findOrFail($subject->program_id);
        $sections = ProgramSection::where('program_id', $program->id)->get();
        return response()->json([
            'subject' => $subject,
            'program' => $program,
            'sections' => $sections
        ]);
    }
}
