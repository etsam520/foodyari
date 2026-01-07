<?php

namespace App\Http\Controllers\Vendor;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\VendorEmployee;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionController extends Controller
{
    public function index()
    {
        $users = VendorEmployee::where('vendor_id',Helpers::get_vendor_id())->with('roles')->get();
        $roles = Role::with('permissions')->where('guard_name', 'vendor_employee')->get();
        $permissions = Permission::where('guard_name', 'vendor_employee')->get();

        return view('vendor-views.permission.roles-permissions', compact('users', 'roles', 'permissions'));
    }

    public function assignRoleToUser(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:vendor_employees,id',
            'role_id' => 'required|exists:roles,id'
        ]);

        $user = VendorEmployee::findOrFail($request->user_id);
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
        $permissionNames = Permission::whereIn('id', $validated['permissions'])
            ->where('guard_name', 'vendor_employee') // ensure guard matches
            ->pluck('name')
            ->toArray(); // ensure it's array
        $role->syncPermissions($permissionNames); 

        return back()->with('status', 'Permissions assigned successfully!');
    }
}
