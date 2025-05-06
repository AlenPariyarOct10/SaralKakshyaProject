<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use Illuminate\Http\Request;

class BatchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

    }

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
                'batch' => 'required',
                'status' => 'required'
            ]);

            $department_id = $request->input('department_id');
            $program_id = $request->input('program_id');
            $semester = $request->input('semester');
            $batch = $request->input('batch');
            $status = $request->input('status');

            Batch::create([
                'department_id' => $department_id,
                'program_id' => $program_id,
                'semester' => $semester,
                'batch' => $batch,
                'status' => $status,
            ]);

            return response()->json(["status"=>"success"]);
        }catch (\Exception $exception){
            return response()->json(["status"=>"fail", 'message'=>$exception->getMessage()]);
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
