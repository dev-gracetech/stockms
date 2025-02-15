<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
   // List all roles
   public function index()
   {
       $roles = Role::all();
       $permissions = Permission::all();
       return view('roles.index', compact('roles', 'permissions'));
   }

   // Show the create role form
   public function create()
   {
       $permissions = Permission::all();
       return view('roles.create', compact('permissions'));
   }

   // Store a new role
   public function store(Request $request)
   {
       $request->validate([
           'name' => 'required|string|unique:roles,name',
           'permissions' => 'nullable|array',
       ]);

       $role = Role::create(['name' => $request->name]);

       // Assign permissions to the role
       if ($request->permissions) {
           $role->syncPermissions($request->permissions);
       }

       return redirect()->route('roles.index')->with('success', 'Role created successfully.');
   }

   // Show the edit role form
   public function edit(Role $role)
   {
       $permissions = Permission::all();
       return view('roles.edit', compact('role', 'permissions'));
   }

   // Fetch role data for editing
   public function editData(Role $role)
   {
       $role->load('permissions'); // Load the role's permissions
       return response()->json([
           'role' => $role,
           'permissions' => Permission::all(), // All available permissions
       ]);
   }

   // Update a role
   public function update(Request $request, Role $role)
   {
       $request->validate([
           'name' => 'required|string|unique:roles,name,' . $role->id,
           'permissions' => 'nullable|array',
       ]);

       $role->update(['name' => $request->name]);

       // Sync permissions
       if ($request->permissions) {
           $role->syncPermissions($request->permissions);
       }

       return redirect()->route('roles.index')->with('success', 'Role updated successfully.');
   }

   // Delete a role
   public function destroy(Role $role)
   {
       $role->delete();
       return redirect()->route('roles.index')->with('success', 'Role deleted successfully.');
   }

   // Add permissions to a role
   public function addPermissions(Request $request, Role $role)
   {
       $request->validate([
           'permissions' => 'required|array',
       ]);

       $role->givePermissionTo($request->permissions);

       return redirect()->route('roles.edit', $role->id)->with('success', 'Permissions added successfully.');
   }

   // Remove permissions from a role
   /* public function removePermissions(Request $request, Role $role)
   {
       $request->validate([
           'permissions' => 'required|array',
       ]);

       $role->revokePermissionTo($request->permissions);

       return redirect()->route('roles.edit', $role->id)->with('success', 'Permissions removed successfully.');
   } */

   public function removePermission(Request $request, Role $role)
    {
        $request->validate([
            'permission' => 'required|string|exists:permissions,name',
        ]);

        // Revoke the permission from the role
        $role->revokePermissionTo($request->permission);

        return response()->json(['success' => true, 'message' => 'Permission removed successfully.']);
    }
}
