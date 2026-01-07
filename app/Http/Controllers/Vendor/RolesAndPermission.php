<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RolesAndPermission extends Controller
{
    public function index()
    {
        $roles = Role::where('guard_name', 'vendor_employee')->get();
        return view('vendor-views.employee.index', compact('roles'));
    }

    public function submit(Request $request)
    {
        $request->validate([
            'role' => 'required|string|unique:user_roles,role',
            // 'status' => 'required|boolean'
        ]);

        Role::create([
            'name' => $request->role,
            'guard_name' => 'vendor_employee'
        ]);
        return redirect()->back()->with('success', "User Role Created");
    }
}
