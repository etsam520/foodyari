<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\UserRole;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class EmployeeController extends Controller
{
    public function add_new()
    {
        // $rls = UserRole::whereNotIn('id', [1,2,3,4,9])->get();
        $rls = Role::where('guard_name', 'admin')->whereNotIn('name', ['Super Admin'])->get();
        $zns = Zone::all();
        return view('admin-views.employee.add-new', compact('rls','zns'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'f_name' => 'required',
            'l_name' => 'nullable|max:100',
            'role_id' => 'required|exists:roles,id',
            'zone_id' => 'required',
            'image' => 'required|max:2048',
            'email' => 'required|unique:admins',
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:20|unique:admins',
            'password' =>'required|min:6'
        ]);

        try{
            if ($request->role_id == 1) {
                throw new \Exception(__('messages.access_denied')) ;
            }

            $employee = Admin::create([
                'f_name' => $request->f_name,
                'l_name' => $request->l_name,
                'phone' => $request->phone,
                'zone_id' => $request->zone_id,
                'email' => $request->email,
                // 'role_id' => $request->role_id,
                'password' => bcrypt($request->password),
                'image' => Helpers::uploadFile($request->file('image'),'admin/'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $employee->syncRoles($request->role_id);

            return redirect()->route('admin.employee.list')->with('success',__('messages.employee_added_successfully'));
        }catch(\Exception $ex){
            return redirect()->back()->with('error',$ex->getMessage());
        }
    }

    function list()
    {
        $em = Admin::zone()->latest()->get();
        return view('admin-views.employee.list', compact('em'));
    }

    public function edit($id)
    {
        // $e = Admin::zone()->where('role_id', '!=','1')->where(['id' => $id])->first();
        $e = Admin::zone()->where(['id' => $id])->first();
        // $e->roles;
        // if (auth('admin')->id()  == $e['id']){
        //     return redirect()->route('admin.employee.list')
        //         ->with('error',__('messages.You_can_not_edit_your_own_info'));
        // }
        // $rls = UserRole::whereNotIn('id', [1,9])->get();
        $rls = Role::where('guard_name', 'admin')->whereNotIn('name', ['Super Admin'])->get();
        $zns = Zone::all();
        return view('admin-views.employee.edit', compact('rls', 'e','zns'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'f_name' => 'required|max:100',
            'l_name' => 'nullable|max:100',
            'role_id' => 'required',
            'email' => 'required|unique:admins,email,'.$id,
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:20|unique:admins,phone,'.$id,
            'password' => 'nullable|min:6',
            'image' => 'nullable|max:2048',
        ], [
            'f_name.required' => __('messages.first_name_is_required'),
        ]);

        try{
           $e = Admin::zone()->where(['id' => $id])->first();

            if ($request['password'] == null) {
                $pass = $e['password'];
            } else {
                if (strlen($request['password']) < 6) {
                    throw new \Error(__('messages.password_length_warning',['length'=>'6']));
                }
                $pass = bcrypt($request['password']);
            }

            if ($request->has('image')) {
                $e['image'] = Helpers::updateFile($request->file('image'),'admin/', $e->image );
            }

            $e->syncRoles(Role::findById($request->role_id)->pluck('name')->toArray());


            $e->update([
                'f_name' => $request->f_name,
                'l_name' => $request->l_name,
                'phone' => $request->phone,
                'zone_id' => $request->zone_id,
                'email' => $request->email,
                'password' => $pass,
                'image' => $e['image'],
                'updated_at' => now(),
            ]);

            return redirect()->route('admin.employee.list')->with('success',__('messages.employee_updated_successfully'));
        }catch(\Exception $ex){
            return redirect()->route('admin.employee.list')->with('info',$ex->getMessage());
        }catch(\Error $err){
            return redirect()->back()->with('error',$err->getMessage());
        }
    }

    public function distroy($id)
    {
        $role=Admin::zone()->where('role_id', '!=','1')->where(['id'=>$id])->first();
        if (auth('admin')->id()  == $role['id']){
            return redirect()->route('admin.employee.list')->with('error',__('messages.You_can_not_edit_your_own_info'));
        }
        $role->delete();
        return back()->with('success',__('messages.employee_deleted_successfully'));
    }

    public function search(Request $request){
        $key = explode(' ', $request['search']);
        $employees=Admin::zone()->where('role_id', '!=','1')
        ->where(function ($q) use ($key) {
            foreach ($key as $value) {
                $q->orWhere('f_name', 'like', "%{$value}%");
                $q->orWhere('l_name', 'like', "%{$value}%");
                $q->orWhere('phone', 'like', "%{$value}%");
                $q->orWhere('email', 'like', "%{$value}%");
            }
        })->limit(50)->get();
        return response()->json([
            'view'=>view('admin-views.employee.partials._table',compact('employees'))->render(),
            'count'=>$employees->count()
        ]);
    }

    public function employee_list_export(Request $request){
        // $withdraw_request = Admin::zone()->with(['role'])->where('role_id', '!=','1')->get();
        // if($request->type == 'excel'){
        //     return (new FastExcel($withdraw_request))->download('Employee.xlsx');
        // }elseif($request->type == 'csv'){
        //     return (new FastExcel($withdraw_request))->download('Employee.csv');
        // }
    }
}
