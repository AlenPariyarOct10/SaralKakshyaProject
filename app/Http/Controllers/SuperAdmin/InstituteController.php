<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Institute;
use Illuminate\Http\Request;
use PharIo\Manifest\Author;

class InstituteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $institutes = Institute::withTrashed()
            ->with(['admin' => function($query) {
                $query->withTrashed(); // Include trashed admins if needed
            }])
            ->get();

        return view('backend.superadmin.institute-management', compact('institutes'));
    }

    /*
     * Active or Disable Institute
     * */
    public function toggleStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:institutes,id'
        ]);

        $institute = Institute::withTrashed()->findOrFail($request->id);

        if ($institute->trashed()) {
            $institute->restore();
            $message = "Institute Activated";
        } else {
            $institute->delete();
            $message = "Institute Disabled";
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $institute
        ]);
    }



    public function get_institutes(Request $request)
    {
        try {
            $query = Institute::withTrashed()
                ->with(['admin' => function($query) {
                    $query->withTrashed();
                }])
                ->select('institutes.*');

            // Search filter
            if ($request->has('search') && !empty($request->search)) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhereHas('admin', function($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        });
                });
            }

            // Status filter
            if ($request->has('status') && !empty($request->status)) {
                if ($request->status === 'active') {
                    $query->whereNull('deleted_at');
                } elseif ($request->status === 'deleted') {
                    $query->whereNotNull('deleted_at');
                }
            }

            // Sorting
            $sortColumn = $request->input('sort', 'created_at');
            $sortDirection = $request->input('direction', 'desc');
            $query->orderBy($sortColumn, $sortDirection);

            // Pagination
            $institutes = $query->paginate($request->input('per_page', 15));

            return response()->json([
                'success' => true,
                'data' => $institutes,
                'message' => 'Institutes retrieved successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve institutes: ' . $e->getMessage()
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
