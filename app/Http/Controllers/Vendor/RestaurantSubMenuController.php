<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\RestaurantMenu;
use App\Models\RestaurantSubMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class RestaurantSubMenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $submenus = RestaurantSubMenu::whereHas('menu', function($query) {
            $query->where('restaurant_id', Session::get('restaurant')->id);
        })
        ->with(['menu:id,name']) // Simplified the eager loading
        ->orderBy('restaurant_sub_menus.restaurant_menu_id')
        ->get();
        // dd($submenus);

        return response()->view('vendor-views.restaurant-menu.sub.index', compact('submenus'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $restaurant = Session::get('restaurant');
        $menu = RestaurantMenu::where('restaurant_id',$restaurant->id)->orderBy('position')->get();
        return response()->view('vendor-views.restaurant-menu.sub.create',compact('menu'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'name' => 'required',
            'menu_id' => 'required',
            'status' => 'required|in:0,1'
        ]);

        DB::beginTransaction();

        try {
            $menu = RestaurantMenu::findOrFail($request->menu_id);

            $customId = (RestaurantSubMenu::whereHas('menu', function($query) use($menu) {
                $query->where('restaurant_id', $menu->restaurant_id);
            })
            ->orderBy('custom_id', 'DESC')
            ->first()
            ->custom_id ?? 0) + 1;

            $menu->submenu()->create([
                'name' => $request->name,
                'status' => $request->status,
                'custom_id' => $customId,
                'restaurant_id' => $menu->restaurant_id
            ]);

            DB::commit();

            return redirect()->route('vendor.restaurant-sub-menu.index')->with('success', 'Created Successfully');
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
    public function edit(RestaurantSubMenu $submenu, $id)
    {
        $submenu = $submenu->with('menu')->find($id);
        $restaurant = Session::get('restaurant');
        $menu = RestaurantMenu::where('restaurant_id',$restaurant->id)->orderBy('position')->get();

        return response()->view('vendor-views.restaurant-menu.sub.edit', compact('submenu','menu'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RestaurantSubMenu $submenu)
    {
        $validated = $request->validate([
            'name' => 'required',
            'id' => 'required',
            'restaurant_menu_id' => 'required',
            'status' => 'required|in:0,1'
        ]);
        $submenu = $submenu->find($request->id);
        $submenu->update($validated);

        return back()->with('success', 'Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request , $submenu)
    {
        $submenu = RestaurantSubMenu::find($submenu);
        $submenu->delete();

        return back()->with('success', 'Menu deleted successfully');
    }

    public function menuCustomIdRegenerate(){

        $submenus = RestaurantSubMenu::whereHas('menu', function($query)  {
            $query->where('restaurant_id', Session::get('restaurant')->id);
        })->get();

        $customId = 1;
        foreach ($submenus as $submenu) {
            $submenu->update(['custom_id' => $customId]);
            $customId++;
        }
        return back();

        // return back()->with('success','Menu Not resore' )
    }

    public function sort()
    {
        $restaurant = Session::get('restaurant');
        $menu = RestaurantSubMenu::whereHas('menu', function($query)  {
            $query->where('restaurant_id', Session::get('restaurant')->id);
        })->orderBy('position')->get();
        return view('vendor-views.restaurant-menu.sub.sort',compact('menu'));
    }

    public function sort_update(Request $request)
    {
        $sortedArray = $request->json('sortedArray');
        foreach($sortedArray as $key => $value){
            $menu = RestaurantSubMenu::find($value);
            $menu->position = $key;
            $menu->save();

        }
        return true;
    }

    public function status(Request $req , RestaurantSubMenu $submenu)
    {
        $submenu = $submenu->find($req->query('id'));
        $submenu->status = filter_var( $req->query('status'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? false;
        $submenu->save();
        return back()->with('success', 'Status Changed');
    }
}
