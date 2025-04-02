<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TestimonialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $allTestimonials = Testimonial::all();
        return view('backend.admin.testimonial', compact('user'), compact('allTestimonials'));
    }

    public function get_testimonial($id)
    {
        $testimonial = Testimonial::findOrFail($id);
        return response()->json($testimonial);
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

        $user = Auth::user();
        $allTestimonials = Testimonial::all();
        // Validate the incoming request
        $request->validate([
            'userName' => 'required|string|max:255',
            'stars' => 'required|integer|min:1|max:5',
            'designation' => 'required|string|max:255',
            'testimonialDescription' => 'required|string|max:5000',
            'rank' => 'required|integer',
            'testimonialStatus' => 'required|string|in:active,inactive',
            'profilePicture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5000', // Validate file
        ]);

        // Handle file upload (if a file is uploaded)
        $imagePath = null;
        if ($request->hasFile('profilePicture')) {
            $imagePath = $request->file('profilePicture')->store('testimonials', 'public');
        }

        // Save data to the database
        $testimonial = Testimonial::create([
            'user_name' => $request->userName,
            'stars' => $request->stars,
            'designation' => $request->designation,
            'description' => $request->testimonialDescription,
            'rank' => $request->rank,
            'status' => $request->testimonialStatus,
            'profile_picture' => $imagePath,
        ]);



        if ($testimonial) {
            return redirect()->route('admin.testimonial.index')->with('success', 'Testimonial created successfully');
        } else {
            return redirect()->back()->with('error', 'Failed to create testimonial');
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
    public function update(Request $request)
    {
        $id = $request->testimonialId;
        $testimonial = Testimonial::findOrFail($id);

        $request->validate([
            'userName' => 'required|string|max:255',
            'stars' => 'required|integer|min:1|max:5',
            'designation' => 'required|string|max:255',
            'testimonialDescription' => 'required|string|max:5000',
            'rank' => 'required|integer',
            'testimonialStatus' => 'required|string|in:active,inactive',
            'profilePicture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5000',
        ]);

        // Handle profile picture update
        $imagePath = $testimonial->profile_picture;
        if ($request->hasFile('profilePicture')) {
            // Delete old image if exists
            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = $request->file('profilePicture')->store('testimonials', 'public');
        }

        $check = $testimonial->update([
            'user_name' => $request->userName,
            'stars' => $request->stars,
            'designation' => $request->designation,
            'description' => $request->testimonialDescription,
            'rank' => $request->rank,
            'status' => $request->testimonialStatus,
            'profile_picture' => $imagePath,
        ]);

        if ($check) {
            return redirect()->route('admin.testimonial.index')->with('success', 'Testimonial updated successfully');
        } else {
            return redirect()->back()->with('error', 'Failed to update testimonial');
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $testimonial = Testimonial::find($id);

            if (!$testimonial) {
                return response()->json(['success' => false, 'message' => 'Testimonial not found'], 404);
            }

            $testimonial->delete(); // Use Eloquent instead of destroy()

            return response()->json(['success' => true, 'message' => 'Testimonial deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

}
