<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Institute;
use App\Models\InstituteSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class InstituteSessionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user(); // assuming Admin is logged in
        $institute = Institute::where('created_by', $user->id)->first();
        $sessions = $institute->sessions()->get();
        return view('backend.admin.session.index', compact('user', 'institute', 'sessions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function bulkCreate(Request $request)
    {
        $data = $request->all();

        // Validate required fields
        if (!isset($data['dates']) || empty($data['dates'])) {
            return response([
                'status' => 'error',
                'message' => 'Dates are required for bulk creation',
            ], 400);
        }

        $createdSessions = [];

        foreach ($data['dates'] as $date) {
            $sessionData = [
                'date' => $date,
                'start_time' => $data['start_time'] ?? null,
                'end_time' => $data['end_time'] ?? null,
                'status' => $data['status'] ?? null,
                'notes' => $data['notes'] ?? null,

                'institute_id' => Auth::user()->institute->id,
                'creator_type' => "admin",
                'creator_id' => Auth::user()->id,
                'specific_group' => null,
                'specific_group_id' => null,
            ];

            $session = InstituteSession::create($sessionData);
            $createdSessions[] = $session;
        }

        return response([
            'status' => 'success',
            'message' => 'Sessions created successfully',
            'created_sessions' => $createdSessions,
            'count' => count($createdSessions),
        ]);
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $session = InstituteSession::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'status' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $session->update([
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'status' => $request->status,
            'notes' => $request->notes,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Session updated successfully',
            'session' => $session
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $session = InstituteSession::findOrFail($id);

        // Authorization check
        if ($session->creator_id != Auth::id() || $session->institute_id != Auth::user()->institute->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized to delete this session'
            ], 403);
        }

        $session->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Session deleted successfully'
        ]);
    }
}
