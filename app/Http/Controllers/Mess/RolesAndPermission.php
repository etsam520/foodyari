<?php

namespace App\Http\Controllers\Mess;

use App\Http\Controllers\Controller;
use App\Models\UserRole;
use Illuminate\Http\Request;

class RolesAndPermission extends Controller
{
    public function index()
    {
        return view('mess-views.Employee.Role-and-Permission.index');
    }
    public function submit(Request $request)
    {
        $request->validate([
            'role' => 'required|string|unique:user_roles',
            'status' => 'required|boolean'
        ]);

        UserRole::create([
            'role' => $request->role,
            'status' => $request->status
        ]);
        return redirect()->back()->with('success', "User Role Created");
    }

}
