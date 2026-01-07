<?php

namespace App\Http\Controllers\Vendor;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\UserRole;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    public function index()
    {
        return view('vendor-views.customer.add');
    }


    public function submit(Request $request)
    {
   
        $validator = Validator::make($request->all(), [
            "f_name" => 'required|string',
            "l_name" => 'nullable|string',
            "street" => 'required|string',
            "phone" => 'required|numeric|digits:10|unique:customers',
            "email" => 'required|email|unique:customers',
            "pincode" => 'required|digits:6',
            "city" => "required|string",
            "password" => "required|min:6", 
            "c_password" => "required|same:password", 
            'image' => 'required|mimes:jpeg,jpg,png', 
        ],[
            'f_name' => 'First Name is Required',
            'street' => 'Address is Required',
            'phone' => 'Phone is Required',
            'email' => 'Email is Required',
            'email.unique' => 'Email already exists',
            'pincode' => 'Pincode is Required',
            'city' => 'City is Required',
            'password' => 'Password is Required',
            'c_password' => 'Password not matched',
            'image' => 'Image Required',
        ]);

        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();
        
            $role = UserRole::where('role', 'customer')->first();
            
            if(!$role) {
                throw new \Exception('Role not found');
            }
        
            $customer = Customer::create([
                'f_name' => $request->f_name,
                'l_name' => $request->l_name,
                'phone' => $request->phone,
                'email' => $request->email,
                'image' => Helpers::uploadFile($request->image, 'customers'),
                'password' => bcrypt($request->password),
                'role_id' => $role->id,
                'status' => 1,
                'address' => json_encode([
                    'street' => $request->street,
                    'city' => $request->city,
                    'pincode' => $request->pincode
                ]),
            ]);
            
            if (!$customer) {
                throw new \Exception('Failed to Add Customer');
            }

             Wallet::create([
                'customer_id' => $customer->id,
            ]);

            $customer->cretedBy()->create([
                'restuarant_id' => Session::get('restaurant')->id,
                'added_by'=> 'restaurant'
            ]);
            DB::commit();
            
            return response()->json(['success' => 'Customer Added Successfully']);
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json(['errors' => $ex->getMessage()], 422);
        }
    }

    public function list()
    {
        $customers = Customer::customerCreatedBy('restaurant');

    return view('vendor-views.customer.list', compact('customers'));
    }


    public function view($id)
    {
        $customer = Customer::find($id);
        if (isset($customer)) {
            $orders = Order::latest()->where(['customer_id' => $id])->paginate(15);
            return view('vendor-views.customer.customer-view', compact('customer', 'orders'));
        }
        Session::flash('error',__('messages.customer_not_found'));
        return back();
    }

    public function getdata()
    {
        $customers = Customer::latest()->get();
        return response()->json(compact('customers'));
    }
}
