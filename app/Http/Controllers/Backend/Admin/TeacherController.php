<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Exports\Admin\TeacherExport;
use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $instituteId = Auth::user()->institute->id;
        $teachers = Teacher::whereHas('institutes', function ($query) use ($instituteId) {
            $query->where('institute_id', $instituteId);
        })->orderBy('created_at', 'DESC')->get();

        $pendingCount = Teacher::whereHas('institutes', function ($query) use ($instituteId) {
            $query->where('institute_id', $instituteId)
                ->whereNull('institute_teacher.approvedAt');
        })->orderBy('created_at', 'DESC')->get();

        $pendingCount = count($pendingCount);

        return view('backend.admin.teachers.index', compact('user','teachers', 'pendingCount'));
    }

    public function generatePDF()
    {
        $fileName = 'Teachers_'.Auth::user()->institute->name.'_'.now()->format('[d M, Y]').'.xlsx';
        return Excel::download(new TeacherExport(), $fileName);
    }

    public function index_pending_teachers()
    {
        $adminInstituteId = Auth::user()->institute->id;
        $user = Auth::user();

        $teachers = Teacher::whereHas('institutes', function ($query) use ($adminInstituteId) {
            $query->where('institute_id', $adminInstituteId)
                ->whereNull('institute_teacher.approvedAt');
        })->orderBy('created_at', 'DESC')->get();

        return view('backend.admin.teachers.unapproved', compact('teachers', 'user'));
    }

    public function toggle_status($id)
    {
        try {
            $teacher = Teacher::findOrFail($id);
            if ($teacher->status == 1) {
                $teacher->status = 0;
            } else {
                $teacher->status = 1;
            }

            $teacher->save();

            return json_encode(['status' => 'success']);
        }catch (\Exception $exception)
        {
            return json_encode(['status' => 'failed']);
        }
    }

    public function approve_teacher($id)
    {
        $adminInstituteId = Auth::user()->institute->id;
        $institutename = Auth::user()->institute->name;
        DB::table('institute_teacher')
            ->where('teacher_id', $id)
            ->where('institute_id', $adminInstituteId)
            ->update(['approvedAt' => Carbon::now()]);

        $teacher = \App\Models\Teacher::findOrFail($id);
        $email = $teacher->email;

        // Send test email
        Mail::raw('Your registration has been approved at '.$institutename, function ($message) use ($email) {
            $message->to($email)
                ->subject('Approval Notification');
        });

        return redirect()->back()->with('success', 'Teacher approved and notified successfully.');
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
