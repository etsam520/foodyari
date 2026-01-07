<?php

namespace App\Http\Controllers\Vendor;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\RestaurantMenu;
use App\Models\RestaurantSubMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class RestaurantMenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $restaurantMenus = RestaurantMenu::where('restaurant_id', Session::get('restaurant')->id)->get();
        return response()->view('vendor-views.restaurant-menu.index', compact('restaurantMenus'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return response()->view('vendor-views.restaurant-menu.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'image' => 'nullable|image|mimes:png,jpg,jpeg',
            'status' => 'required|in:0,1'
        ]);


        $validated['restaurant_id'] = Session::get('restaurant')->id;

        $customId = (RestaurantMenu::where('restaurant_id', Session::get('restaurant')->id)->orderBy('custom_id', 'DESC')->first()->custom_id ?? 0) + 1;
        while (RestaurantMenu::where('restaurant_id', Session::get('restaurant')->id)->where('custom_id', $customId)->exists()) {
            $customId++;
        }
        $validated['custom_id'] = $customId;
        DB::beginTransaction();
        try {
            if($request->has('image')){
                $filename = Helpers::uploadFile($request->file('image'), 'Category')  ;
            }else{
               $filename = null;
            }
            $validated['image'] = $filename;
            $validated['position'] = $customId;
            // dd($validated);
            RestaurantMenu::create($validated);

            DB::commit();
            return redirect()->route('vendor.restaurant-menu.index')->with('success', 'Created Successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(RestaurantMenu $restaurantMenu)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RestaurantMenu $restaurantMenu)
    {
        return response()->view('vendor-views.restaurant-menu.edit', compact('restaurantMenu'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RestaurantMenu $restaurantMenu)
    {
        $validated = $request->validate([
            'name' => 'required',
            'image' => 'nullable',
            'status' => 'required|in:0,1'
        ]);

        $restaurantMenu->update($validated);

        return back()->with('success', 'Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RestaurantMenu $restaurantMenu)
    {
        // Delete all associated submenus
        RestaurantSubMenu::where('restaurant_menu_id', $restaurantMenu->id)->delete();

        // Delete the menu itself
        $restaurantMenu->delete();

        // Return back with a success message
        return back()->with('success', 'Menu deleted successfully');
    }

    public function menuCustomIdRegenerate(){
        RestaurantMenu::where('restaurant_id', Session::get('restaurant')->id)->update(['custom_id' => 0]);

        $menus = RestaurantMenu::where('restaurant_id', Session::get('restaurant')->id)->get();
        $customId = 1;
        foreach ($menus as $menu) {
            while (RestaurantMenu::where('restaurant_id', Session::get('restaurant')->id)->where('custom_id', $customId)->exists()) {
                $customId++;
            }
            $menu->update(['custom_id' => $customId]);
            $customId++;
        }
        return back();
        // return response('success');
    }

    public function sort()
    {
        $restaurant = Session::get('restaurant');
        $menu = RestaurantMenu::where('restaurant_id',$restaurant->id)->orderBy('position')->get();


        return view('vendor-views.restaurant-menu.sort',compact('menu'));
    }

    public function sort_update(Request $request)
    {
        $sortedArray = $request->json('sortedArray');
        foreach($sortedArray as $key => $value){
            $menu = RestaurantMenu::find($value);
            $menu->position = $key;
            $menu->save();

        }
        return true;
    }

    public function status(Request $req , RestaurantMenu $menu)
    {
        $menu = $menu->find($req->query('id'));
        $menu->status = filter_var( $req->query('status'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? false;
        $menu->save();
        return back()->with('success', 'Status Changed');
    }
}
