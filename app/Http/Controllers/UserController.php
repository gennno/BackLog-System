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

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('profile_photos', 'public');
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
    if ($user->photo && Storage::disk('public')->exists($user->photo)) {
        Storage::disk('public')->delete($user->photo);
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

    if ($request->filled('password')) {
        $data['password'] = Hash::make($data['password']);
    } else {
        unset($data['password']); // Don't update if empty
    }

    if ($request->hasFile('photo')) {
        if ($user->photo && Storage::disk('public')->exists($user->photo)) {
            Storage::disk('public')->delete($user->photo);
        }
        $data['photo'] = $request->file('photo')->store('profile_photos', 'public');
    }

    $user->update($data);

    return redirect()->route('pengguna.index')->with('success', 'Pengguna berhasil diperbarui!');
}

}
