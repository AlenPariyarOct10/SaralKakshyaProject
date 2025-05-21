<?php

namespace App\Http\Controllers\Backend\Student;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Batch;
use App\Models\Institute;
use App\Models\Program;
use App\Models\Student;
use App\Models\SystemSetting;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
class AuthController extends Controller
{

    public function showRegister()
    {
        $system_info = SystemSetting::first();
        $institutes = Institute::all();
        return view('backend.student.signup', compact('system_info', 'institutes'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'fname' => 'required|string|max:255|min:3',
            'lname' => 'required|string|max:255|min:2',
            'email' => 'required|string|email|max:255|unique:students',
            'institute' => 'required|exists:institutes,id',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Temporarily store the basic details in the session
        $request->session()->put('student_registration', [
            'fname' => $request->fname,
            'lname' => $request->lname,
            'email' => $request->email,
            'institute' => $request->institute,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('student.complete-profile')->with('success', 'Basic registration successful! Please complete your profile.');
    }

    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('student.dashboard');
        }
        $institutes = Institute::all();

        $system_info = SystemSetting::first();
        return view('backend.student.login', compact('system_info', 'institutes'));
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|max:255',
            'institute' => 'required|exists:institutes,id',
            'password' => 'required|string|min:8',
        ]);

        if (!Auth::guard('student')->attempt($request->only('email', 'password'))) {
            return back()
                ->withErrors(['email' => 'Invalid credentials'])
                ->withInput($request->only('email', 'institute'));
        }

        $student = Auth::guard('student')->user();

        if (!$student->status) {
            Auth::guard('student')->logout();
            return back()
                ->with('error', 'Your account is not activated. Please contact support.')
                ->withInput($request->only('email', 'institute'));
        }

        // Fetch the related InstituteStudent record
        $instituteStudent = $student->instituteStudents()
            ->where('institute_id', $validated['institute'])
            ->where('is_approved', true)
            ->first();

        if (!$instituteStudent) {
            Auth::guard('student')->logout();
            return back()
                ->withErrors([
                    'institute' => 'You are either not associated with the selected institute or not yet approved.'
                ])
                ->withInput($request->only('email', 'institute'));
        }

        // Update current institute
        $student->update(['institute_id' => $validated['institute']]);

        $request->session()->regenerate();

        // Store required values in session
        session([
            'institute_id'   => $validated['institute'],
            'student_id'     => $student->id,
            'batch_id'       => $instituteStudent->batch_id,
            'department_id'  => $instituteStudent->department_id,
            'program_id'     => $instituteStudent->program_id,
            'section_id'     => $instituteStudent->section_id,
        ]);

        // Log activity
        ActivityLog::create([
            'user_id'     => $student->id,
            'user_type'   => 'student',
            'action_type' => 'login',
            'description' => 'Student logged in',
            'model_type'  => get_class($student),
            'model_id'    => $student->id,
            'url'         => $request->fullUrl(),
            'ip_address'  => $request->ip(),
            'user_agent'  => $request->userAgent(),
        ]);

        return redirect()->intended(route('student.dashboard'));
    }


    public function logout(Request $request)
    {
        ActivityLog::create([
            'user_id'     => Auth::guard('student')->id(),
            'user_type'   => 'student',
            'action_type' => 'logout',
            'description' => 'Student logged out',
            'model_type'  => get_class(Auth::guard('student')->user()),
            'model_id'    => Auth::guard('student')->id(),
            'url'         => $request->fullUrl(),
            'ip_address'  => $request->ip(),
            'user_agent'  => $request->userAgent(),
        ]);

        Auth::guard('student')->logout();

        return redirect()->route('student.login');
    }

    public function showCompleteProfile(Request $request)
    {
        // Check if basic registration details are in the session
        if (!$request->session()->has('student_registration')) {
            return redirect()->route('student.register')->with('error', 'Please complete the basic registration first.');
        }

        $studentData = $request->session()->get('student_registration');

        $departments = Institute::find($studentData['institute'])->departments()->get();


        return view('backend.student.signup-phase2', compact('studentData',  'departments'));
    }

    public function completeProfile(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|max:15',
            'address' => 'required|string|max:255',
            'gender' => 'required|in:male,female,other',
            'dob' => 'required|date',
            'guardian_name' => 'required|string|max:255',
            'guardian_phone' => 'required|string|max:15',
            'roll_number' => 'required|string|max:20|unique:students',
            'department_id' => 'required|exists:departments,id',
            'program_id' => 'required|exists:programs,id',
            'batch_id' => 'required|exists:batches,id',
            'section_id' => 'required|exists:program_sections,id',
            'admission_date' => 'required|date',
            'profile_picture' => 'nullable|image|max:2048',
        ]);

        $basicDetails = $request->session()->get('student_registration');
        if (!$basicDetails) {
            return redirect()->route('student.register')->with('error', 'Session expired. Please register again.');
        }

        // Handle profile picture upload
        $profilePicturePath = null;
        if ($request->hasFile('profile_picture')) {
            $profilePicturePath = $request->file('profile_picture')->store('profile_pictures', 'public');
        }

        // Create the student
        $student = Student::create([
            'fname' => $basicDetails['fname'],
            'lname' => $basicDetails['lname'],
            'email' => $basicDetails['email'],
            'password' => $basicDetails['password'],
            'phone' => $request->phone,
            'address' => $request->address,
            'gender' => $request->gender,
            'dob' => $request->dob,
            'guardian_name' => $request->guardian_name,
            'guardian_phone' => $request->guardian_phone,
            'roll_number' => $request->roll_number,
            'batch_id' => $request->batch_id,
            'section_id' => $request->section_id,
            'department_id' => $request->department_id,
            'program_id' => $request->program_id,
            'admission_date' => $request->admission_date,
            'profile_picture' => $profilePicturePath,
            'status' => true,
            'institute_id' => $basicDetails['institute'],
        ]);

        // Create the institute_student record
        $student->instituteStudents()->create([
            'institute_id' => $basicDetails['institute'],
            'batch_id' => $request->batch_id,
            'department_id' => $request->department_id,
            'program_id' => $request->program_id,
            'section_id' => $request->section_id,
            'is_approved' => false, // Assuming immediate approval for self-registration
            'approved_at' => null,
        ]);

        ActivityLog::create([
            'user_id' => $student->id,
            'user_type' => 'student',
            'action_type' => 'complete_profile',
            'description' => 'Student completed their profile (Phase 2)',
            'model_type' => get_class($student),
            'model_id' => $student->id,
            'url' => $request->fullUrl(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $request->session()->forget('student_registration');
        return redirect()->route('student.login')->with('success', 'Profile completed successfully!');
    }

    public function getDepartmentPrograms($id)
    {
        $programs = Program::where('department_id', $id)->get();
        return response()->json($programs);
    }

    public function getProgramBatches(Request $request)
    {
        $programId = $request->input('program_id');
        $departmentId = $request->input('department_id');

        $batches = Batch::where('program_id', $programId)
            ->where('department_id', $departmentId)
            ->get();
        return response()->json($batches);
    }

    public function getProgramSections($programId)
    {
        $sections = Program::find($programId)->sections()->get();

        return response()->json($sections);
    }
}
