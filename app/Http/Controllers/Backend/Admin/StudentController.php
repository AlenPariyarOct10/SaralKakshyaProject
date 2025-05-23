<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Exports\Admin\TeacherExport;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Student;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user(); // assuming Admin is logged in
        $instituteId = $user->institute->id;

        // Get students through the pivot table relationship
        $students = Student::whereHas('institutes', function ($query) use ($instituteId) {
            $query->where('institutes.id', $instituteId)
                ->whereNotNull('institute_student.approved_at'); // This is the correct way
        })->get();

        return view('backend.admin.students.index', compact('user', 'students'));
    }


    public function index_pending_students()
    {
        $user = Auth::user();
        $adminInstituteId = $user->institute->id;

        // Get students who belong to this institute and are not yet approved
        $students = Student::whereHas('institutes', function ($query) use ($adminInstituteId) {
            $query->where('institutes.id', $adminInstituteId)
                ->whereNull('institute_student.approved_at'); // ← This is the correct way
        })->orderBy('created_at', 'desc')->get();

        return view('backend.admin.students.unapproved', compact('students', 'user'));
    }



    public function generatePDF()
    {
        $fileName = 'Students_'.Auth::user()->institute->name.'_'.now()->format('[d M, Y]').'.xlsx';
        return Excel::download(new TeacherExport(), $fileName);
    }

    public function toggle_status($id)
    {
        try {
            $student = Student::findOrFail($id);
            if ($student->status == 1) {
                $student->status = 0;
            } else {
                $student->status = 1;
            }

            $student->save();

            return json_encode(['status' => 'success']);
        }catch (\Exception $exception)
        {
            return json_encode(['status' => 'failed']);
        }
    }


    public function approve_student($id)
    {
        $adminInstituteId = Auth::user()->institute->id;
        $institutename = Auth::user()->institute->name;
        DB::table('institute_student')
            ->where('student_id', $id)
            ->where('institute_id', $adminInstituteId)
            ->update(['approved_at' => Carbon::now(), 'is_approved' => 1]);

        $student = \App\Models\Student::findOrFail($id);
        $email = $student->email;

        Mail::send([], [], function ($message) use ($email, $institutename) {
            $message->to($email)
                ->subject('Registration Approved - ' . $institutename)
                ->html('
            <!DOCTYPE html>
            <html>
            <head>
                <style>
                    body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                    .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                    .header { background-color: #4f46e5; padding: 20px; text-align: center; }
                    .header h1 { color: white; margin: 0; }
                    .content { padding: 30px; background-color: #f9fafb; }
                    .button {
                        display: inline-block;
                        padding: 10px 20px;
                        background-color: #4f46e5;
                        color: white !important;
                        text-decoration: none;
                        border-radius: 5px;
                        margin: 15px 0;
                    }
                    .footer {
                        margin-top: 20px;
                        padding-top: 20px;
                        border-top: 1px solid #eee;
                        font-size: 12px;
                        color: #666;
                    }
                </style>
            </head>
            <body>
                <div class="container">
                    <div class="header">
                        <h1>Registration Approved</h1>
                    </div>
                    <div class="content">
                        <p>Hello,</p>

                        <p>Your registration has been approved at <strong>' . $institutename . '</strong>.</p>

                        <p>You can now access your account and enjoy all the features available to you.</p>

                        <a href="' . route('student.login') . '" class="button">Login to Your Account</a>

                        <p>If you have any questions, please contact our support team.</p>

                        <div class="footer">
                            <p>© ' . date('Y') . ' ' . 'SaralKakhsya' . '. All rights reserved.</p>
                        </div>
                    </div>
                </div>
            </body>
            </html>
        ');
        });

        return redirect()->back()->with('success', 'Student approved and notified successfully.');
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = Auth::user();
        $instituteId = $user->institute->id;

        $student = Student::whereHas('institutes', function ($query) use ($instituteId) {
            $query->where('institutes.id', $instituteId);
        })->findOrFail($id);

        return view('backend.admin.students.show', compact('student', 'user'));
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = Auth::user();
        $instituteId = $user->institute->id;

        $student = Student::whereHas('institutes', function ($query) use ($instituteId) {
            $query->where('institutes.id', $instituteId);
        })->findOrFail($id);

        try {
            // Detach from institute first
            $student->institutes()->detach($instituteId);

            // Delete the student if they don't belong to any other institutes
            if ($student->institutes()->count() === 0) {
                $student->delete();
            }

            return redirect()->route('admin.students.index')->with('success', 'Student removed successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error removing student: ' . $e->getMessage());
        }
    }


}
