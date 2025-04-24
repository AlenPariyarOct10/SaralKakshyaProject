<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use PHPUnit\Event\Telemetry\System;
use Illuminate\Support\Str;
class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('backend.superadmin.setting');
    }

    public function contact_index()
    {
        return view('backend.superadmin.setting.contact');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }



    public function store_general(Request $request)
    {
        try {
            $system = SystemSetting::first();

            $system->name = $request->input('name');
            $system->description = $request->input('description');
            $system->long_description = $request->input('long_description');

            // Handle file uploads
            $logoPath = $this->uploadImage($request, 'logo') ?? $system->logo;
            $faviconPath = $this->uploadImage($request, 'favicon') ?? $system->favicon;

            $system->logo = $logoPath;
            $system->favicon = $faviconPath;


            $system->save();


            session()->flash("success", "Your settings have been updated.");
            return redirect()->route('superadmin.setting.index');
        } catch (\Exception $exception) {
            session()->flash("error", "Failed to update setting " . $exception->getMessage());
            return redirect()->route('superadmin.setting.index');
        }
    }
    public function store_contact(Request $request)
    {
        try {
            $system = SystemSetting::first();

            $request->validate([
                'address' => 'string|required|max:255',
                'phone' => 'string|required|max:15',
                'email' => 'string|required|max:100',
                'facebook' => 'string|max:200',
                'instagram' => 'string|max:200',
                'twitter' => 'string|max:200',
            ]);

            $system->address = $request->input('address');
            $system->phone = $request->input('phone');
            $system->email = $request->input('email');
            $system->facebook = $request->input('facebook');
            $system->instagram = $request->input('instagram');
            $system->twitter = $request->input('twitter');

            $system->save();

            session()->flash("success", "Your contact settings have been updated.");
            return redirect()->route('superadmin.setting.contact')->withInput();
        } catch (\Exception $exception) {
            session()->flash("error", "Failed to update contact setting " . $exception->getMessage());
            return redirect()->route('superadmin.setting.contact')->withInput()->withErrors($exception->getMessage());
        }
    }

// Helper function to upload image
    private function uploadImage(Request $request, $inputName)
    {
        if ($request->hasFile($inputName)) {
            $file = $request->file($inputName);
            $destinationPath = public_path('assets/images');

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $fileName = Str::uuid() . '_' . $file->getClientOriginalName();
            $file->move($destinationPath, $fileName);

            return 'assets/images/' . $fileName;
        }

        return null;
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
