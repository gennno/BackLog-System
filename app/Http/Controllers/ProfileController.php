<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    // Show the profile settings page
    public function edit()
    {
        return view('pengaturan'); // your blade file
    }

public function update(Request $request)
{
    $user = Auth::user();

    // Validate input
    $data = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'password' => 'nullable|string|min:6|confirmed',
        'photo' => 'nullable|mimes:jpg,jpeg,png,gif,webp|max:2048',
    ]);

    // Update name & email
    $user->name = $data['name'];
    $user->email = $data['email'];

    // Update password if provided
    if ($request->filled('password')) {
        $user->password = Hash::make($request->password);
    }

    // Update profile photo if uploaded
    if ($request->hasFile('photo')) {
        // Delete old photo if exists
        if ($user->photo && file_exists(base_path('public_html/' . $user->photo))) {
            unlink(base_path('public_html/' . $user->photo));
        }

        $file = $request->file('photo');
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

        $destination = base_path('public_html/profile_photos');
        if (!file_exists($destination)) {
            mkdir($destination, 0755, true);
        }

        $file->move($destination, $filename);
        $user->photo = 'profile_photos/' . $filename;
    }

    // Save all changes
    $user->save();

    return redirect()->route('pengaturan.edit')->with('success', 'Profil berhasil diperbarui!');
}

}
