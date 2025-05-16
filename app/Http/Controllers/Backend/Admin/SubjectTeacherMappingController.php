<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubjectTeacherMapping;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SubjectTeacherMappingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->per_page ?? 15;
            $search = $request->search;
            $departmentId = $request->department_id;

            $query = SubjectTeacherMapping::with([
                'teacher:id,fname,lname,email',
                'subject:id,name,code,program_id',
                'subject.program:id,name,department_id',
                'subject.program.department:id,name',
                'subject.program.sections:id,program_id,section_name', // Add program sections
                'assignedBy:id,fname,lname'
            ])
                ->latest();

            // Apply search filter
            if ($search) {
                $query->whereHas('teacher', function($q) use ($search) {
                    $q->where('fname', 'like', "%$search%")
                        ->orWhere('lname', 'like', "%$search%");
                })
                    ->orWhereHas('subject', function($q) use ($search) {
                        $q->where('name', 'like', "%$search%")
                            ->orWhere('code', 'like', "%$search%");
                    });
            }

            // Apply department filter
            if ($departmentId) {
                $query->whereHas('subject.program', function($q) use ($departmentId) {
                    $q->where('department_id', $departmentId);
                });
            }

            $mappings = $query->paginate($perPage);

            // Transform the data
            $transformedData = $mappings->getCollection()->map(function ($mapping) {
                return [
                    'id' => $mapping->id,
                    'teacher' => $mapping->teacher,
                    'subject' => $mapping->subject,
                    'program' => [
                        'id' => $mapping->subject->program->id ?? null,
                        'name' => $mapping->subject->program->name ?? null,
                        'sections' => $mapping->subject->program->sections ?? [],
                    ],
                    'department' => $mapping->subject->program->department ?? null,
                    'assigned_by' => $mapping->assignedBy,
                    'assigned_at' => $mapping->assigned_at,
                    'created_at' => $mapping->created_at,
                ];
            });

            return response()->json([
                'status' => 'success',
                'data' => $transformedData,
                'meta' => [
                    'current_page' => $mappings->currentPage(),
                    'last_page' => $mappings->lastPage(),
                    'per_page' => $mappings->perPage(),
                    'total' => $mappings->total(),
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to load mappings: ' . $e->getMessage()
            ], 500);
        }
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
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => [
                'required',
                'exists:teachers,id',
                Rule::unique('subject_teacher_mappings')->where(function ($query) use ($request) {
                    return $query->where('subject_id', $request->subject_id);
                })->ignore($request->id)
            ],
        ]);

        // Store the subject-teacher mapping
        $mapping = new SubjectTeacherMapping();
        $mapping->subject_id = $request->subject_id;
        $mapping->teacher_id = $request->teacher_id;
        $mapping->assigned_by = auth()->user()->id;
        $mapping->assigned_at = now();
        $mapping->save();

        return response()->json(['status'=>'success','message' => 'Subject-Teacher mapping created successfully.']);
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
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Find the existing mapping or fail
        $mapping = SubjectTeacherMapping::findOrFail($id);

        // Validate the request
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => [
                'required',
                'exists:teachers,id',
                Rule::unique('subject_teacher_mappings')
                    ->where(function ($query) use ($request) {
                        return $query->where('subject_id', $request->subject_id);
                    })
                    ->ignore($id)
            ],
        ]);

        // Update the mapping
        $mapping->subject_id = $request->subject_id;
        $mapping->teacher_id = $request->teacher_id;
        // Optionally update assigned_by and assigned_at if needed
        $mapping->assigned_by = auth()->user()->id;
        $mapping->assigned_at = now();
        $mapping->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Subject-Teacher mapping updated successfully.',
            'data' => $mapping
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $mapping = SubjectTeacherMapping::findOrFail($id);
        $mapping->delete();
        return response()->json(['status'=>'success','message' => 'Subject-Teacher mapping deleted successfully.']);
    }
}
