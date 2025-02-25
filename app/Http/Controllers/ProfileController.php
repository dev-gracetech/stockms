<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    // Show the user profile
    public function show()
    {
        $user = Auth::user();
        $notifications = $user->notifications;
        return view('users.profile', compact('user','notifications'));
    }

    // Update the user profile
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(),
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user = Auth::user();
        //$user->name = $request->name;
        //$user->email = $request->email;

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // Update password if provided
        if ($request->password) {
            $user->update(['password' => bcrypt($request->password)]);
        }

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    // Update the profile photo
    public function updatePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // 2MB max
        ]);

        $user = Auth::user();

        // Delete old photo if it exists
        if ($user->photo && Storage::exists($user->photo)) {
            Storage::delete($user->photo);
        }

        // Store the new photo
        $path = $request->file('photo')->store('profile-photos', 'public');
        $user->photo = $path;
        $user->save();

        return response()->json([
            'success' => true,
            'photo_url' => asset('storage/' . $path),
        ]);
    }

    // Remove the profile photo
    public function removePhoto(Request $request)
    {
        $user = Auth::user();

        if ($user->photo && Storage::exists($user->photo)) {
            Storage::delete($user->photo);
            $user->photo = null;
            $user->save();
        }

        return response()->json([
            'success' => true,
        ]);
    }
}