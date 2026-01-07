<?php

namespace App\Http\Controllers\Mess;

use App\Http\Controllers\Controller;
use App\Models\MessTiffin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class TiffinController extends Controller
{
    public function index()
    {
        $tiffins = MessTiffin::latest()->get();
        return view('mess-views.tiffin.add',compact('tiffins'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string',
            'code' => 'required|string', 
        ]);
    
        try {
    
             MessTiffin::create([
                'title' => $request->name??null,
                'no' => $request->code,
                'mess_id' => Session::get('mess')->id,
            ]);
            return redirect()->back()->with('success', __('Tiffin no Created'));
    
        } catch (\Exception $e) {
         
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
