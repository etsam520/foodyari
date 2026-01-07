<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionController extends Controller
{
    public function index()
    {
        $users = Admin::with('roles')->get();
        $roles = Role::with('permissions')->where('guard_name', 'admin')->get();
        // dd($roles);
        // dd($roles);
        $permissions = Permission::where('guard_name', 'admin')->get();
        
        return view('admin-views.permission.roles-permissions', compact('users', 'roles', 'permissions'));
    }

    public function assignRoleToUser(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:admins,id',
            'role_id' => 'required|exists:roles,id'
        ]);
        
        $user = Admin::findOrFail($request->user_id);
        $role = Role::findOrFail($request->role_id);
        
        $user->assignRole($role);
        
        return back()->with('status', 'Role assigned successfully!');
    }

    public function assignPermissionToRole(Request $request)
    {
        $validated = $request->validate([
            'role_id' => 'required|exists:roles,id',
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        $role = Role::findOrFail($validated['role_id']);
        // Get permission names
        $permissionNames = Permission::whereIn('id', $validated['permissions'])
            ->where('guard_name', 'admin') // ensure guard matches
            ->pluck('name')
            ->toArray(); // ensure it's array
        // dd(Permission::findByName('edit articles'));
        // dd($role , $permissionNames);
        // Assign permissions
        $role->syncPermissions($permissionNames); 
        // OR if you just want to add (not replace), use:
        // $role->givePermissionTo($permissionNames);

        return back()->with('status', 'Permissions assigned successfully!');
    }
}
