<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    // Show list of users
    public function index()
    {
        $users = User::all();
        return view('pengguna', compact('users'));
    }

    // Store new user
    public function store(Request $request)
    {
        // Validate input
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|string',
            'phone_number' => 'nullable|string|max:20',
            'photo' => 'nullable|image|max:2048', // max 2MB
        ]);

// Store method - photo upload
if ($request->hasFile('photo')) {
    $file = $request->file('photo');
    $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

    $destination = $_SERVER['DOCUMENT_ROOT'] . '/profile_photos';
    if (!file_exists($destination)) {
        mkdir($destination, 0755, true);
    }

    $file->move($destination, $filename);
    $data['photo'] = 'profile_photos/' . $filename; // relative path for DB
}


        // Hash password
        $data['password'] = Hash::make($data['password']);

        // Create user
        User::create($data);

        return redirect()->route('pengguna.index')->with('success', 'Pengguna berhasil ditambahkan!');
    }

    // Optional: delete user
    public function destroy(User $user)
{
// Destroy method - delete photo
if ($user->photo && file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $user->photo)) {
    unlink($_SERVER['DOCUMENT_ROOT'] . '/' . $user->photo);
}

    $user->delete();

    return redirect()->route('pengguna.index')->with('success', 'Pengguna berhasil dihapus!');
}


public function update(Request $request, User $user)
{
    $data = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'password' => 'nullable|string|min:6|confirmed',
        'role' => 'required|string',
        'phone_number' => 'nullable|string|max:20',
        'photo' => 'nullable|image|max:2048',
    ]);

    // Hash password if provided
    if ($request->filled('password')) {
        $data['password'] = Hash::make($data['password']);
    } else {
        unset($data['password']); // Don't update if empty
    }

    // Update photo if a new file is uploaded
    if ($request->hasFile('photo')) {
        // Delete old photo if exists
        if ($user->photo && file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $user->photo)) {
            unlink($_SERVER['DOCUMENT_ROOT'] . '/' . $user->photo);
        }

        $file = $request->file('photo');
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

        $destination = $_SERVER['DOCUMENT_ROOT'] . '/profile_photos';
        if (!file_exists($destination)) {
            mkdir($destination, 0755, true);
        }

        $file->move($destination, $filename);
        $data['photo'] = 'profile_photos/' . $filename; // store relative path in DB
    } else {
        unset($data['photo']); // prevent overwriting DB with null
    }

    $user->update($data);

    return redirect()->route('pengguna.index')->with('success', 'Pengguna berhasil diperbarui!');
}

}
