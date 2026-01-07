<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;

use App\Models\Addon as AddonModel;

use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\Models\Restaurant;
use Illuminate\Support\Facades\Session;

class AddOnController extends Controller
{
    public function index()
    {
        $addons = AddonModel::where('restaurant_id', Session::get('restaurant')->id)->get();
        return view('vendor-views.addon.index',compact('addons'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
        ]);

        try {

            $addon = AddonModel::create([
                'name' => $request->name,
                'price' => $request->price,
                'restaurant_id' => Session::get('restaurant')->id,
            ]);
            return back()->with('success', __('messages.addons-created'));

        } catch (\Exception $e) {

            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    public function  edit(Request $request, AddonModel $addon,$id) {
        $addon = $addon->findOrFail($id);
        return view('vendor-views.addon.edit', compact('addon'));
    }

    public function  delete(Request $request, AddonModel $addon,$id) {
        $addon = $addon->findOrFail($id);
        $addon->delete();
        return back()->with('success', "Addon Deleted Successfully");
    }

    public function  update(Request $request, AddonModel $addon) {
        $vaidator = $request->validate([
            'id' => 'required',
            'name' => 'required',
            'price' => 'required'
        ]);
       $addon = $addon->findOrFail($request->id);

        $addon->update([
            'name' => $vaidator['name'],
            'price' => $vaidator['price']
        ]);

        return redirect()->route('vendor.addon.add')->with('success', 'Addon Updated');
    }

    public function status(Request $req , AddonModel $addon)
    {

        $addon = $addon->find($req->query('id'));

        $addon->status = filter_var( $req->query('status'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? false;

        $addon->save();
        return back()->with('success', 'Status Changed');


    }



    public function get_addons(){

        return response()->json(AddonModel::where('restaurant_id',Session::get('restaurant')->id)->get());
    }
}
