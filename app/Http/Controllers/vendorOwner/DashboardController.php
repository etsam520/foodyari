<?php

namespace App\Http\Controllers\vendorOwner;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $vendor = Vendor::with(['restaurants','messes'])->find(auth('vendor')->user()->id); 
        return view('vendor-owner-views.dashboard',compact('vendor'));
    }
    public function myMess($id)
    {
        $vendor = Vendor::with(['messes' => function($query) use($id){
            return $query->where('id', $id);
        }])->find(auth('vendor')->user()->id); 
        $mess = $vendor->messes->first();
        Session::put('vendor', ['mess_name' => $mess->name, 'mess_logo' => $mess->logo, 'mess_id' => $mess->id]);
        return redirect()->route('mess.dashboard');
    }
}
