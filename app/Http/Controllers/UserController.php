<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all(); // Fetch all users
        $roles = Role::all();
        return view('users.index', compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            //'roles' => 'nullable|array',
        ]);
    
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        // Assign roles
        //if ($request->roles) {
        //    $user->syncRoles($request->roles);
        //}
        //return response()->json(['success' => true, 'message' => 'User created successfully.']);
        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    /* public function edit(User $user)
    {
        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    } */
    public function edit(User $user)
    {
        return response()->json([
            'name' => $user->name,
            'email' => $user->email,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            //'roles' => 'nullable|array', // Validate role if applicable
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // Update password if provided
        if ($request->password) {
            $user->update(['password' => bcrypt($request->password)]);
        }

        // Sync roles
        /* if ($request->roles) {
            $user->syncRoles($request->roles);
        } */

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }

    // Assign a role to a user
    public function assignRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|string|exists:roles,name',
        ]);

        // Assign the role to the user
        $user->assignRole($request->role);

        return response()->json(['success' => true, 'message' => 'Role assigned successfully.']);
    }

    // Remove a role from a user
    public function removeRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|string|exists:roles,name',
        ]);

        // Remove the role from the user
        $user->removeRole($request->role);

        return response()->json(['success' => true, 'message' => 'Role removed successfully.']);
    }

    // Add roles to a user
    public function addRoles(Request $request, User $user)
    {
        $request->validate([
            'roles' => 'required|array',
        ]);

        $user->assignRole($request->roles);

        return redirect()->route('users.edit', $user->id)->with('success', 'Roles added successfully.');
    }

    // Remove roles from a user
    public function removeRoles(Request $request, User $user)
    {
        $request->validate([
            'roles' => 'required|array',
        ]);

        $user->removeRole($request->roles);

        return redirect()->route('users.edit', $user->id)->with('success', 'Roles removed successfully.');
    }
}
