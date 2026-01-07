<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RolesAndPermission extends Controller
{
    public function index()
    {
        // $roles = UserRole::whereNotIn('role', ['customer', 'admin'])
        //         ->latest()->get();
        // dd($roles);
        $roles = Role::where('guard_name', 'admin')->get();
        return view('admin-views.employee.index', compact('roles'));
    }

    public function submit(Request $request)
    {
        $request->validate([
            'role' => 'required|string|unique:user_roles,role',
            // 'status' => 'required|boolean'
        ]);

        // UserRole::create([
        //     'role' => $request->role,
        //     'status' => $request->status
        // ]);

        Role::create([
            'name' => $request->role,
            'guard_name' => 'admin'
        ]);
        return redirect()->back()->with('success', "User Role Created");
    }
}
