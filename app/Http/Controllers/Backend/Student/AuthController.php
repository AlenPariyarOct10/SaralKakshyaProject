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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
            'fname' => [
                'required',
                'string',
                'max:255',
                'min:2',
                'regex:/^[a-zA-Z\s]+$/'
            ],
            'lname' => [
                'required',
                'string',
                'max:255',
                'min:2',
                'regex:/^[a-zA-Z\s]+$/'
            ],
            'email' => 'required|string|email|max:255|unique:students,email',
            'institute' => 'required|exists:institutes,id',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ], [
            'fname.regex' => 'First name should only contain letters and spaces.',
            'lname.regex' => 'Last name should only contain letters and spaces.',
            'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, and one number.',
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

        return view('backend.student.signup-phase2', compact('studentData', 'departments'));
    }

    public function completeProfile(Request $request)
    {
        // Enhanced validation with comprehensive phone number validation
        $request->validate([
            // Enhanced phone validation - format, uniqueness, length
            'phone' => [
                'required',
                'string',
                'min:10',
                'max:15',
                'regex:/^(\+977|977)?[0-9]{10}$|^[0-9]{10}$/',
                'unique:students,phone'
            ],

            // Address validation
            'address' => 'required|string|min:10|max:500',

            // Gender validation
            'gender' => 'required|in:male,female,other',

            // Enhanced DOB validation
            'dob' => [
                'required',
                'date',
                'before:today',
                'after:' . now()->subYears(100)->format('Y-m-d'),
                function ($attribute, $value, $fail) {
                    $dob = \Carbon\Carbon::parse($value);
                    $age = $dob->diffInYears(now());
                    if ($age < 15) {
                        $fail('You must be at least 15 years old to register.');
                    }
                    if ($age > 80) {
                        $fail('Age cannot exceed 80 years.');
                    }
                }
            ],

            // Guardian name validation
            'guardian_name' => [
                'required',
                'string',
                'min:3',
                'max:255',
                'regex:/^[a-zA-Z\s]+$/'
            ],

            // Enhanced guardian phone validation - format, uniqueness, different from student phone
            'guardian_phone' => [
                'required',
                'string',
                'min:10',
                'max:15',
                'regex:/^(\+977|977)?[0-9]{10}$|^[0-9]{10}$/',
                'unique:students,guardian_phone',
                'different:phone'
            ],

            // Roll number validation
            'roll_number' => [
                'required',
                'string',
                'min:1',
                'max:20',
                'regex:/^[a-zA-Z0-9\-_]+$/',
                'unique:students,roll_number'
            ],

            // Department validation
            'department_id' => 'required|exists:departments,id',

            // Program validation with department relationship
            'program_id' => [
                'required',
                'exists:programs,id',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->department_id) {
                        $program = Program::where('id', $value)
                            ->where('department_id', $request->department_id)
                            ->first();
                        if (!$program) {
                            $fail('The selected program does not belong to the selected department.');
                        }
                    }
                }
            ],

            // Admission date validation
            'admission_date' => [
                'required',
                'date',
                'before_or_equal:today',
                'after:' . now()->subYears(10)->format('Y-m-d'),
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->dob) {
                        $dob = \Carbon\Carbon::parse($request->dob);
                        $admissionDate = \Carbon\Carbon::parse($value);
                        if ($admissionDate->lt($dob)) {
                            $fail('Admission date cannot be before date of birth.');
                        }
                    }
                }
            ],

            // Profile picture validation
            'profile_picture' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,gif',
                'max:2048',
                'dimensions:min_width=100,min_height=100,max_width=2000,max_height=2000'
            ],

            // Basic batch validation (capacity will be checked separately in transaction)
            'batch_id' => [
                'required',
                'exists:batches,id',
                function ($attribute, $value, $fail) use ($request) {
                    // Check if batch belongs to selected program and department
                    if ($request->program_id && $request->department_id) {
                        $batch = Batch::where('id', $value)
                            ->where('program_id', $request->program_id)
                            ->where('department_id', $request->department_id)
                            ->first();
                        if (!$batch) {
                            $fail('The selected batch does not belong to the selected program and department.');
                        }
                    }
                }
            ],
        ], [
            // Custom error messages
            'phone.regex' => 'Phone number must be a valid Nepali number (10 digits, optionally starting with +977 or 977).',
            'phone.unique' => 'This phone number is already registered with another student.',
            'phone.min' => 'Phone number must be at least 10 digits.',
            'phone.max' => 'Phone number cannot exceed 15 characters.',

            'guardian_phone.regex' => 'Guardian phone number must be a valid Nepali number (10 digits, optionally starting with +977 or 977).',
            'guardian_phone.unique' => 'This guardian phone number is already registered with another student.',
            'guardian_phone.different' => 'Guardian phone number must be different from student phone number.',
            'guardian_phone.min' => 'Guardian phone number must be at least 10 digits.',
            'guardian_phone.max' => 'Guardian phone number cannot exceed 15 characters.',

            'guardian_name.regex' => 'Guardian name should only contain letters and spaces.',
            'roll_number.unique' => 'This roll number is already taken by another student.',
            'roll_number.regex' => 'Roll number can only contain letters, numbers, hyphens, and underscores.',
            'address.min' => 'Address must be at least 10 characters long.',
            'dob.before' => 'Date of birth cannot be in the future.',
            'dob.after' => 'Date of birth cannot be more than 100 years ago.',
            'admission_date.before_or_equal' => 'Admission date cannot be in the future.',
            'admission_date.after' => 'Admission date cannot be more than 10 years ago.',
            'profile_picture.dimensions' => 'Profile picture must be between 100x100 and 2000x2000 pixels.',
            'profile_picture.max' => 'Profile picture size cannot exceed 2MB.',
        ]);

        $basicDetails = $request->session()->get('student_registration');
        if (!$basicDetails) {
            return redirect()->route('student.register')->with('error', 'Session expired. Please register again.');
        }

        // Use database transaction to prevent race conditions and ensure data consistency
        try {
            DB::beginTransaction();

            // Check batch capacity with row locking to prevent race conditions
            $batch = Batch::where('id', $request->batch_id)->lockForUpdate()->first();

            if (!$batch) {
                DB::rollBack();
                return back()->withErrors(['batch_id' => 'Selected batch not found.'])->withInput();
            }

            // Count current students in this batch with lock to prevent race conditions
            $currentStudentCount = Student::where('batch_id', $request->batch_id)->lockForUpdate()->count();

            // Log the capacity check for debugging
            Log::info("Batch capacity check", [
                'batch_id' => $request->batch_id,
                'batch_name' => $batch->batch ?? 'Unknown',
                'current_count' => $currentStudentCount,
                'max_capacity' => 35,
                'attempting_user' => $basicDetails['email']
            ]);

            // Check if batch has reached maximum capacity
            if ($currentStudentCount >= 35) {
                DB::rollBack();

                Log::warning("Batch capacity exceeded - Registration blocked", [
                    'batch_id' => $request->batch_id,
                    'batch_name' => $batch->batch ?? 'Unknown',
                    'current_count' => $currentStudentCount,
                    'max_capacity' => 35,
                    'blocked_user' => $basicDetails['email'],
                    'timestamp' => now()
                ]);

                return back()
                    ->withErrors([
                        'batch_id' => 'This batch has reached its maximum capacity of 35 students. Currently ' . $currentStudentCount . ' students are enrolled. Please select a different batch.'
                    ])
                    ->withInput();
            }

            // Handle profile picture upload
            $profilePicturePath = null;
            if ($request->hasFile('profile_picture')) {
                $profilePicturePath = $request->file('profile_picture')->store('profile_pictures', 'public');
            }

            // Create the student record
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
                'is_approved' => false,
                'approved_at' => null,
            ]);

            // Log the successful registration
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

            // Commit the transaction
            DB::commit();

            // Log successful registration
            Log::info("Student registration completed successfully", [
                'student_id' => $student->id,
                'student_name' => $student->fname . ' ' . $student->lname,
                'email' => $student->email,
                'batch_id' => $request->batch_id,
                'batch_name' => $batch->batch ?? 'Unknown',
                'new_batch_count' => $currentStudentCount + 1,
                'timestamp' => now()
            ]);

            // Clear session data
            $request->session()->forget('student_registration');

            return redirect()->route('student.login')->with('success', 'Profile completed successfully! Please login to continue.');

        } catch (\Exception $e) {
            DB::rollBack();

            // Log the error for debugging
            Log::error("Student registration failed with exception", [
                'error_message' => $e->getMessage(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'email' => $basicDetails['email'] ?? 'unknown',
                'batch_id' => $request->batch_id ?? 'unknown',
                'timestamp' => now()
            ]);

            return back()
                ->with('error', 'Registration failed due to a system error. Please try again. If the problem persists, contact support.')
                ->withInput();
        }
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

        // Get batches with current student count and availability info
        $batches = Batch::where('program_id', $programId)
            ->where('department_id', $departmentId)
            ->withCount('students')
            ->get()
            ->map(function ($batch) {
                $availableSlots = max(0, 35 - $batch->students_count);
                return [
                    'id' => $batch->id,
                    'batch' => $batch->batch,
                    'student_count' => $batch->students_count,
                    'available_slots' => $availableSlots,
                    'is_full' => $batch->students_count >= 35,
                    'display_text' => $batch->batch . ' (' . $availableSlots . ' slots available)'
                ];
            });

        return response()->json($batches);
    }
}
