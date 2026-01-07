<?php

namespace App\Http\Controllers\Mess;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserRole;
use App\Models\VendorEmployee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeController extends Controller
{
    public function index()
    {
        $roles = UserRole::whereNotIn('id', [1, 2, 3])->latest()->get();
        return view('mess-views.Employee.add', compact('roles'));
    }

    public function submit(Request $request)
    {
        // dd($request);
        $request->validate([
            "role" => "required",
            "f_name" => "required|string|max:100",
            "l_name" => "nullable|string|max:100",
            "street" => "required|string", 
            "phone" => "required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:20|unique:users",
            "email" => "required|email|unique:users",
            "pincode" => "required|digits:6",
            "city" => "required|string", 
            "password" => "required|min:6",
            "c_password" => "required|same:password",
            'image' => 'required|max:2048',
        ]);
        try {
            $role = UserRole::find($request->role);
            
            if($role){
                $user = User::create([
                    'f_name'=> $request->f_name,
                    'l_name'=> $request->l_name,
                    'phone'=> $request->phone,
                    'email'=> $request->email,
                    'image'=> Helpers::uploadFile($request->image, 'users'), 
                    'password'=> bcrypt($request->password), 
                    'role_id'=> $role->id,
                ]);
                
                if(!$user){
                    throw new \Exception('Failed to Add User');
                }
                
                $staff = VendorEmployee::create([
                    'user_id' => $user->id,
                    'vendor_id' => Auth::guard('vendor')->user()->id,
                    'status' => 1,
                    'address' => json_encode([
                        'street' => $request->street,
                        'city' => $request->city,
                        'pincode' => $request->pincode
                    ]),
                ]);
                
                return redirect()->back()->with('success', 'Staff Added Successfully');
            } else {
                throw new \Exception('Role not found');
            }
        } catch (\Exception $ex) {
            return redirect()->back()->with('error', $ex->getMessage());
        }               
    }

    public function list()
    {
        $employees = VendorEmployee::with(['user'=> function($query){
            return $query->with('role');
        }])->get();
        // dd($employees);
        return view('mess-views.Employee.list',compact('employees'));
    }
}
