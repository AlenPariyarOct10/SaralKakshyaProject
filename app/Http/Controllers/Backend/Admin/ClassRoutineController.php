<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\ClassRoutine;
use Illuminate\Http\Request;

class ClassRoutineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        $departments = Admin::find($user->id)->institute->departments()->get();
        return view('backend.admin.classroutine.index', compact('user', 'departments'));
    }


    public function getRoutines(Request $request)
    {
        try {
            // Get pagination parameters
            $perPage = $request->input('per_page', 5);
            $page = $request->input('page', 1);

            // Start building the query
            $query = ClassRoutine::with([
                'subjectTeacherMapping.teacher',
                'subjectTeacherMapping.subject',
                'subjectTeacherMapping.subject.program.department'
            ]);

            // Filter by institute_id
            $query->whereHas('subjectTeacherMapping', function($q) {
                $q->where('institute_id', session('institute_id'));
            });

            // Apply search filter
            if ($request->has('search') && $request->search != '') {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->whereHas('subjectTeacherMapping.teacher', function($q) use ($search) {
                        $q->where('fname', 'like', "%{$search}%")
                            ->orWhere('lname', 'like', "%{$search}%");
                    })->orWhereHas('subjectTeacherMapping.subject', function($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    })->orWhere('day', 'like', "%{$search}%")
                        ->orWhere('notes', 'like', "%{$search}%");
                });
            }

            // Apply department filter
            if ($request->has('department_id') && $request->department_id != '') {
                $query->whereHas('subjectTeacherMapping.subject.program', function($q) use ($request) {
                    $q->where('department_id', $request->department_id);
                });
            }

            // Execute the query with pagination
            $routines = $query->paginate($perPage, ['*'], 'page', $page);

            // Transform the results
            $transformed = $routines->getCollection()->map(function($routine) {
                return [
                    'id' => $routine->id,
                    'department' => [
                        'id' => $routine->subjectTeacherMapping->subject->program->department->id,
                        'name' => $routine->subjectTeacherMapping->subject->program->department->name
                    ],
                    'teacher' => [
                        'id' => $routine->subjectTeacherMapping->teacher->id,
                        'fname' => $routine->subjectTeacherMapping->teacher->fname,
                        'lname' => $routine->subjectTeacherMapping->teacher->lname
                    ],
                    'subject' => [
                        'id' => $routine->subjectTeacherMapping->subject->id,
                        'name' => $routine->subjectTeacherMapping->subject->name
                    ],
                    'day' => $routine->day,
                    'start_time' => $routine->start_time,
                    'end_time' => $routine->end_time,
                    'note' => $routine->notes,
                    'created_at' => $routine->created_at,
                    'updated_at' => $routine->updated_at
                ];
            });

            // Return response
            return response()->json([
                'status' => 'success',
                'data' => $transformed,
                'meta' => [
                    'current_page' => $routines->currentPage(),
                    'from' => $routines->firstItem(),
                    'last_page' => $routines->lastPage(),
                    'per_page' => $routines->perPage(),
                    'to' => $routines->lastItem(),
                    'total' => $routines->total()
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch routines',
                'error' => $e->getMessage()
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
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validate the request data
            $validated = $request->validate([
                'department_id' => 'required|exists:departments,id',
                'subject_teacher_mappings_id' => 'required|exists:subject_teacher_mappings,id',
                'day_schedules' => 'required|array|min:1',
                'day_schedules.*.day' => 'required|string|in:Sunday,Monday,Tuesday,Wednesday,Thursday,Friday,Saturday',
                'day_schedules.*.start_time' => 'required|date_format:H:i',
                'day_schedules.*.end_time' => 'required|date_format:H:i|after:day_schedules.*.start_time',
                'day_schedules.*.note' => 'nullable|string|max:255'
            ]);

            // Get the program ID from the subject teacher mapping
            $subjectTeacherMapping = \App\Models\SubjectTeacherMapping::with('subject.program')
                ->findOrFail($request->subject_teacher_mappings_id);
            $programId = $subjectTeacherMapping->subject->program->id;

            // Check for time conflicts - both teacher and program conflicts
            foreach ($request->day_schedules as $schedule) {
                // 1. Check teacher availability (existing check)
                $teacherConflict = ClassRoutine::where('subject_teacher_mappings_id', $request->subject_teacher_mappings_id)
                    ->where('day', $schedule['day'])
                    ->where(function($query) use ($schedule) {
                        $query->whereBetween('start_time', [$schedule['start_time'], $schedule['end_time']])
                            ->orWhereBetween('end_time', [$schedule['start_time'], $schedule['end_time']])
                            ->orWhere(function($q) use ($schedule) {
                                $q->where('start_time', '<=', $schedule['start_time'])
                                    ->where('end_time', '>=', $schedule['end_time']);
                            });
                    })
                    ->first();

                if ($teacherConflict) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Teacher has a time conflict for ' . $schedule['day'],
                        'conflict' => $teacherConflict
                    ], 422);
                }

                // 2. Check program availability (new check)
                $programConflict = ClassRoutine::whereHas('subjectTeacherMapping.subject.program', function($q) use ($programId) {
                    $q->where('id', $programId);
                })
                    ->where('day', $schedule['day'])
                    ->where(function($query) use ($schedule) {
                        $query->whereBetween('start_time', [$schedule['start_time'], $schedule['end_time']])
                            ->orWhereBetween('end_time', [$schedule['start_time'], $schedule['end_time']])
                            ->orWhere(function($q) use ($schedule) {
                                $q->where('start_time', '<=', $schedule['start_time'])
                                    ->where('end_time', '>=', $schedule['end_time']);
                            });
                    })
                    ->first();

                if ($programConflict) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Program has a time conflict for ' . $schedule['day'],
                        'conflict' => $programConflict
                    ], 422);
                }
            }

            // Create the routines
            $createdRoutines = [];
            foreach ($request->day_schedules as $schedule) {
                $routine = ClassRoutine::create([
                    'subject_teacher_mappings_id' => $request->subject_teacher_mappings_id,
                    'day' => $schedule['day'],
                    'start_time' => $schedule['start_time'],
                    'end_time' => $schedule['end_time'],
                    'notes' => $schedule['note'] ?? null
                ]);

                $createdRoutines[] = $routine->load([
                    'subjectTeacherMapping.teacher',
                    'subjectTeacherMapping.subject',
                    'subjectTeacherMapping.subject.program.department'
                ]);
            }

            // Transform the response
            $transformed = collect($createdRoutines)->map(function($routine) {
                return [
                    'id' => $routine->id,
                    'department' => [
                        'id' => $routine->subjectTeacherMapping->subject->program->department->id,
                        'name' => $routine->subjectTeacherMapping->subject->program->department->name
                    ],
                    'teacher' => [
                        'id' => $routine->subjectTeacherMapping->teacher->id,
                        'fname' => $routine->subjectTeacherMapping->teacher->fname,
                        'lname' => $routine->subjectTeacherMapping->teacher->lname
                    ],
                    'subject' => [
                        'id' => $routine->subjectTeacherMapping->subject->id,
                        'name' => $routine->subjectTeacherMapping->subject->name
                    ],
                    'day' => $routine->day,
                    'start_time' => $routine->start_time,
                    'end_time' => $routine->end_time,
                    'note' => $routine->notes,
                    'created_at' => $routine->created_at,
                    'updated_at' => $routine->updated_at
                ];
            });

            return response()->json([
                'status' => 'success',
                'message' => 'Class routine(s) created successfully',
                'data' => $transformed
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create class routine',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $routine = ClassRoutine::with([
                'subjectTeacherMapping.teacher',
                'subjectTeacherMapping.subject',
                'subjectTeacherMapping.subject.program.department'
            ])->findOrFail($id);

            return response()->json([
                'status' => 'success',
                'data' => $this->transformRoutine($routine)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Routine not found',
                'error' => $e->getMessage()
            ], 404);
        }
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
    public function update(Request $request, $id)
    {
        try {
            $routine = ClassRoutine::findOrFail($id);

            $validated = $request->validate([
                'day' => 'required|string|in:Sunday,Monday,Tuesday,Wednesday,Thursday,Friday,Saturday',
                'start_time' => 'required|date_format:H:i',
                'end_time' => 'required|date_format:H:i|after:start_time',
                'note' => 'nullable|string|max:255'
            ]);

            // Check for time conflicts
            $conflict = ClassRoutine::where('subject_teacher_mappings_id', $routine->subject_teacher_mappings_id)
                ->where('day', $request->day)
                ->where('id', '!=', $id)
                ->where(function($query) use ($request) {
                    $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                        ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                        ->orWhere(function($q) use ($request) {
                            $q->where('start_time', '<=', $request->start_time)
                                ->where('end_time', '>=', $request->end_time);
                        });
                })
                ->first();

            if ($conflict) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Time conflict detected',
                    'conflict' => $conflict
                ], 422);
            }

            $routine->update([
                'day' => $request->day,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'notes' => $request->note
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Routine updated successfully',
                'data' => $this->transformRoutine($routine->fresh())
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update routine',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $routine = ClassRoutine::findOrFail($id);
            $routine->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Routine deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete routine',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function transformRoutine($routine)
    {
        return [
            'id' => $routine->id,
            'department' => [
                'id' => $routine->subjectTeacherMapping->subject->program->department->id,
                'name' => $routine->subjectTeacherMapping->subject->program->department->name
            ],
            'teacher' => [
                'id' => $routine->subjectTeacherMapping->teacher->id,
                'fname' => $routine->subjectTeacherMapping->teacher->fname,
                'lname' => $routine->subjectTeacherMapping->teacher->lname
            ],
            'subject' => [
                'id' => $routine->subjectTeacherMapping->subject->id,
                'name' => $routine->subjectTeacherMapping->subject->name
            ],
            'day' => $routine->day,
            'start_time' => $routine->start_time,
            'end_time' => $routine->end_time,
            'note' => $routine->notes,
            'created_at' => $routine->created_at,
            'updated_at' => $routine->updated_at
        ];
    }
}
