<?php

namespace App\Http\Controllers\Backend\Admin\Api;

use App\Http\Controllers\Controller;
use App\Models\ProgramSection;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        $section = ProgramSection::find($id);
        return response()->json($section);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $section = ProgramSection::find($id);
        $status =$section->update($request->all());

        return response()->json([
            'success'=>'true',
            'message'=>'Section updated successfully',
            'data'=>$section,
            'request'=>$status
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $section = ProgramSection::find($id);
        $section->delete();
        return response()->json([
            'success'=>'true',
            'message'=>'Section deleted successfully'
        ]);
    }
}
