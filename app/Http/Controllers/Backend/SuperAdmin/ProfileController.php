<?php

namespace App\Http\Controllers\Backend\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\SuperAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        return view('backend.superadmin.profile.index', compact('user'));
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
    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
        ]);

        $user = SuperAdmin::all()->first();
        $user->fname = $validatedData['fname'];
        $user->lname = $validatedData['lname'];
        $user->email = $validatedData['email'];
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully'
        ]);
    }

    public function changePassword(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|different:currentPassword',
            'password_confirmation' => 'required|string|same:password',
        ]);

        // Get the authenticated user
        $user = SuperAdmin::all()->first();

        // Verify the current password
        if (!Hash::check($validatedData['current_password'], $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect'
            ], 401);
        }

        // Update the password
        $user->password = Hash::make($validatedData['password']);
        $user->save();

        // Return success response
        return response()->json([
            'success' => true,
            'message' => 'Password changed successfully'
        ]);
    }

    public function updateProfilePicture(Request $request)
    {
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $user = Auth::user();

        // Delete old profile picture if exists and not a default URL
        if ($user->profile_picture && Storage::exists($user->profile_picture)) {
            Storage::delete($user->profile_picture);
        }

        // Store new profile picture
        $path = $request->file('profile_picture')->store('profile_pictures', 'public');

        // Update user model
        $user->profile_picture = 'storage/' . $path;
        $user->save();

        return response()->json(["success"=>true]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
