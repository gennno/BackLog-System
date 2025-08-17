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

    // Handle profile update
    public function update(Request $request)
    {
        $user = Auth::user();

        // Validate input
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
            'photo' => 'nullable|image|max:2048',
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
            if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }
            $user->photo = $request->file('photo')->store('profile_photos', 'public');
        }

        // Save all changes
        $user->save();

        return redirect()->route('pengaturan.edit')->with('success', 'Profil berhasil diperbarui!');
    }
}
