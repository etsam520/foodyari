<?php

namespace App\Http\Controllers\Vendor;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\VendorEmployee;
use App\Models\EmployeeRole;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Rap2hpoutre\FastExcel\FastExcel;
use Spatie\Permission\Models\Role;

class EmployeeController extends Controller
{

    public function add_new()
    {
        $rls = Role::where('guard_name','vendor_employee')->get();
        return view('vendor-views.employee.add-new', compact('rls'));
    }

    public function store(Request $request)
    {
        try{
            Log::debug("request data: ", $request->all());
            $request->validate([
                'f_name' => 'required',
                'l_name' => 'nullable|max:100',
                'role_id' => 'required',
                'image' => 'required|max:2048',
                'email' => 'required|unique:vendor_employees',
                'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|max:20|unique:vendor_employees',
                'password' => 'required|min:8',
            ]);

            $emplyee = VendorEmployee::insert([
                'f_name' => $request->f_name,
                'l_name' => $request->l_name,
                'phone' => $request->phone,
                'email' => $request->email,
                'employee_role_id' => $request->role_id,
                'password' => bcrypt($request->password),
                'vendor_id'=> Helpers::get_vendor_id(),
                'restaurant_id'=>Helpers::get_restaurant_id(),
                'image' => Helpers::updateFile($request->file('image'), 'vendor/'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $emplyee->syncRoles($request->role_id);

            Toastr::success('Employee added successfully!');
            return redirect()->route('vendor.employee.list');
        } catch (\Exception $e) {
            Log::error("Error occurred: " . $e->getMessage());
            Toastr::error($e->getMessage());
            return redirect()->back();
        }
        
    }

    function list()
    {
        $employees = VendorEmployee::where('vendor_id', Helpers::get_vendor_id())->latest()->get();
        return view('vendor-views.employee.list', compact('employees'));
    }

    public function edit($id)
    {
        $e = VendorEmployee::where('vendor_id', Helpers::get_vendor_id())->where(['id' => $id])->first();
        if (auth('vendor_employee')->id()  == $e['id']){
            Toastr::error('You can not edit your own info');
            return redirect()->route('vendor.employee.list');
        }
        $rls = Role::where('guard_name','vendor_employee')->get();
        return view('vendor-views.employee.edit', compact('rls', 'e'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'f_name' => 'required',
            'l_name' => 'nullable|max:100',
            'role_id' => 'required',
            'email' => 'required|unique:vendor_employees,email,'.$id,
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|max:20|unique:vendor_employees,phone,'.$id,
            'image' => 'nullable|max:2048',
        ], [
            'f_name.required' => translate('messages.first_name_is_required'),
        ]);

        $e = VendorEmployee::where('restaurant_id', Helpers::get_restaurant_id())->find($id);

        if (auth('vendor_employee')->id()  == $e['id']){
            Toastr::error(translate('messages.You_can_not_edit_your_own_info'));
            return redirect()->route('vendor.employee.list');
        }

        if ($request['password'] == null) {
            $pass = $e['password'];
        } else {
            if (strlen($request['password']) < 7) {
                Toastr::warning(translate('messages.password_length_warning',['length'=>'8']));
                return back();
            }
            $pass = bcrypt($request['password']);
        }

        if ($request->has('image')) {
            $e['image'] = Helpers::update('vendor/', $e->image, 'png', $request->file('image'));
        }

        DB::table('vendor_employees')->where(['id' => $id])->update([
            'f_name' => $request->f_name,
            'l_name' => $request->l_name,
            'phone' => $request->phone,
            'email' => $request->email,
            'employee_role_id' => $request->role_id,
            'vendor_id'=> Helpers::get_vendor_id(),
            'restaurant_id'=>Helpers::get_restaurant_id(),
            'password' => $pass,
            'image' => $e['image'],
            'updated_at' => now(),
        ]);

        Toastr::success('Employee updated successfully!');
        return redirect()->route('vendor.employee.list');
    }

    public function distroy($id)
    {
        $role=VendorEmployee::where('restaurant_id', Helpers::get_restaurant_id())->where(['id'=>$id])->first();
        if (auth('vendor_employee')->id()  == $role['id']){
            Toastr::error(translate('messages.You_can_not_edit_your_own_info'));
            return redirect()->route('vendor.employee.list');
        }
        $role->delete();
        Toastr::info(translate('messages.employee_deleted_successfully'));
        return back();
    }

    public function search(Request $request){
        $key = explode(' ', $request['search']);
        $employees=VendorEmployee::where('restaurant_id', Helpers::get_restaurant_id())->
        where(function ($q) use ($key) {
            foreach ($key as $value) {
                $q->orWhere('f_name', 'like', "%{$value}%");
                $q->orWhere('l_name', 'like', "%{$value}%");
                $q->orWhere('phone', 'like', "%{$value}%");
                $q->orWhere('email', 'like', "%{$value}%");
            }
        })->limit(50)->get();
        return response()->json([
            'view'=>view('vendor-views.employee.partials._table',compact('employees'))->render(),
            'count'=>$employees->count()
        ]);
    }

    public function list_export(Request $request){
        $em = VendorEmployee::where('restaurant_id', Helpers::get_restaurant_id())->with(['role'])->latest()->get();
        if($request->type == 'excel'){
            return (new FastExcel(Helpers::vendor_employee_list_export($em)))->download('Employee.xlsx');
        }elseif($request->type == 'csv'){
            return (new FastExcel(Helpers::vendor_employee_list_export($em)))->download('Employee.csv');
        }
    }
}
