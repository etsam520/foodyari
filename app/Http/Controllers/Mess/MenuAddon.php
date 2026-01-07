<?php

namespace App\Http\Controllers\Mess;

use App\Http\Controllers\Controller;
use App\Models\MessAddonModel;
use Illuminate\Http\Request;

class MenuAddon extends Controller
{
    public function index()
    {
        $addons = MessAddonModel::latest()->get();
        return view('mess-views.addons.index',compact('addons'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric', 
        ]);
    
        try {
    
            $addon = MessAddonModel::create([
                'name' => $request->name,
                'price' => $request->price,
            ]);
            return redirect()->route('mess.addon.add')->with('success', __('messages.addons-created'));
    
        } catch (\Exception $e) {
         
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

}
