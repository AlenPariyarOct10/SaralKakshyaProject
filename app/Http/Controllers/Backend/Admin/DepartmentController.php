<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
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
        $user = Auth::guard('admin')->user();
        $allDepartments = Department::all();
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

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
//       $request = $request->validate([
//            'name' => 'required|string|max:255',
//            'status' => 'required|string|max:255',
//            'description' => 'max:500',
//        ]);
//
//
//       $status = Department::create($request);
//       if($status)
//       {
//           return redirect()->route('admin.department.index')->with('success', 'Department created successfully');
//       }else{
//           return redirect()->route('admin.department.index')->with('error', 'Failed to create department');
//
//       }

        echo "hello";


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
}
