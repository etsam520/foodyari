<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Models\Vendor;
use App\Models\VendorMess;
use App\Models\Zone;
use Carbon\Carbon;
use Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class VendorMessController extends Controller
{
    public function index()
    {
        $vendors = Vendor::all();
        $zones = Zone::select('name','id','coordinates')->get();
        return view('admin-views.vendor.mess.create', compact('vendors','zones'));
    }

    public function edit($id)  {
        try {//
            if(empty($id)){
                throw new Error('Mess Id can\'t be null');
            }
            $mess = VendorMess::find($id);
            $zones = Zone::select('name','id','coordinates')->get();

            return view('admin-views.vendor.mess._edit', compact('mess','zones'));
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }
// 
    public function store(Request $request)
    {
        
        $rules = [
            'name' => 'required|string|max:191',
            'ownertype' => 'required|string',
            'street' => 'required|string|max:1000',
            'city' => 'required|string|max:1000',
            'pincode' => 'required|digits:6',
            'radius' => 'required|numeric|max:180',
            'latitude' => 'required|numeric|min:-90|max:90',
            'longitude' => 'required|numeric|min:-180|max:180',
            'logo' => 'required|max:2048',
            'cover_photo' => 'required|max:2048',
            'tax' => 'required',
            'email' => 'nullable|email|unique:vendors',
            'mess_no' => 'required|string',
        ];
        if ($request->ownertype === "new") {
            $rules = array_merge([
                'password' => 'required|min:6',
                'cpwd' => 'required|same:password',
                'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:20|unique:vendors',
                'fname' => 'required|string|max:100',
                'lname' => 'nullable|string|max:100',
            ], $rules);
        } else {
            $rules = array_merge($rules, [
                'vendor_id' => 'required|numeric',
            ]);
        }
        
        $validator = Validator::make($request->all(), $rules); 
        
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }
        try {
            $vendor = null;
            if (!isset($request->vendor_id)) {
                $vendor = new Vendor();
                $vendor->f_name = $request->fname;
                $vendor->l_name = $request->lname;
                $vendor->email = $request->email;
                $vendor->phone = $request->phone;
                $vendor->password = bcrypt($request->password);
                $vendor->save();
            }

            $vendorMess = new VendorMess();
            $vendorMess->name = $request->name;
            $vendorMess->logo = Helpers::uploadFile($request->file('logo'), 'vendorMess');
            $vendorMess->cover_photo = Helpers::uploadFile($request->file('cover_photo'), 'vendorMess/cover/');
            $vendorMess->radius = $request->radius;
            $vendorMess->address = json_encode([
                'street' => $request->street,
                'city' => $request->city,
                'pincode' => $request->pincode 
            ]) ;
            $vendorMess->mess_no = $request->mess_no;
            $vendorMess->latitude =$request->latitude ;
            $vendorMess->longitude = $request->longitude;
            $vendorMess->coordinates = json_encode([
                "latitude" => $request->latitude,
                "longitude" => $request->longitude,
            ]);
            $vendorMess->vendor_id = $vendor->id ?? $request->vendor_id;
            $vendorMess->tax = $request->tax;
            $vendorMess->zone_id = $request->zone_id;
            $vendorMess->save();

            return redirect()->route('admin.mess.list')->with('success', __('messages.mess-added'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function list()
    {
        $messes = VendorMess::with(['vendor'=> function($q){
            $q->select('id','phone','email','f_name','l_name');
        }])->get();
        // dd($mess);
        return view('admin-views.vendor.mess.list',compact('messes'));
    }

    public function update(Request $request) {
        $rules = [
            'name' => 'required|string|max:191',
            'id' => 'required',
            'mess_no' => 'nullable',
            // 'mess_no' => 'required|unique:vendor_messes',
            'street' => 'required|string|max:1000',
            'city' => 'required|string|max:1000',
            'pincode' => 'required|numeric|digits:6',
            'radius' => 'required|numeric|max:180',
            'badge_one' => 'required|string|max:1000',
            'badge_two' =>'required|string|max:1000',
            'latitude' => 'nullable|numeric|min:-90|max:90',
            'longitude' => 'nullable|numeric|min:-180|max:180',
        ];
        
        
        $validator = Validator::make($request->all(), $rules); 
        
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }
        try {
            $vendorMess = VendorMess::find($request->id);
            $vendorMess->name = $request->name;
            $vendorMess->mess_no = $request->mess_no;
            $vendorMess->latitude = $request->latitude;
            $vendorMess->longitude = $request->longitude;
            $vendorMess->coordinates = json_encode([
                'longitude' => $request->longitude,
                'latitude' => $request->latitude,
            ]);

            if($request->file('logo')){
                $vendorMess->logo = Helpers::updateFile($request->file('logo'), 'vendorMess',$vendorMess->logo);
            }
            if($request->file('cover_photo')){
                $vendorMess->cover_photo = Helpers::updateFile($request->file('cover_photo'), 'vendorMess/cover/',$vendorMess->cover_photo);
            }
            $vendorMess->radius = $request->radius;
            $vendorMess->address = json_encode([
                                        'street' => $request->street,
                                        'city' => $request->city,
                                        'pincode' => $request->pincode,
                                    ]);
            $vendorMess->badges = json_encode(['b1' => $request->badge_one, 'b2' => $request->badge_two]);
            $vendorMess->zone_id = $request->zone_id;
            $vendorMess->save();

            return redirect()->back()->with('success', __('Mess Updated'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function access($id)
    {
        $mess = VendorMess::with('vendor')->find($id);
        if(!$mess){
            return redirect()->route('admin.mess.list')->with('warning', 'Mess Not Found');
        }
        Auth::guard('vendor')->login($mess->vendor);
        Session::put('mess',$mess);
        // Session::put('vendor', ['mess_name' => $mess->name, 'mess_logo' => $mess->logo, 'mess_id' => $mess->id]);
        // Session::put('vendor', ['mess_name' => $mess->name, 'mess_logo' => $mess->logo, 'mess_id' => $mess->id]);
        
        return redirect()->route('mess.dashboard');

    }

}
