<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Models\Addon as AddonModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class Addon extends Controller
{
    public function index()
    {
        try {
            $restaurants = Restaurant::select('id', 'name')->isActive(true)->orderBy('name')->get();
            $addons = AddonModel::with(['restaurant:id,name'])
                ->orderBy('created_at', 'desc')
                ->paginate(20);
            
            return view('admin-views.addons.index', compact('restaurants', 'addons'));
        } catch (\Exception $e) {
            Log::error('Addon index error: ' . $e->getMessage());
            return back()->with('error', __('An error occurred while loading addons.'));
        }
    }

    public function store(Request $request)
    {
        try {
            // Comprehensive validation
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255|min:2',
                'price' => 'required|numeric|min:0|max:999999.99',
                'restaurant_id' => 'required|exists:restaurants,id'
            ], [
                'name.required' => __('Addon name is required'),
                'name.min' => __('Addon name must be at least 2 characters'),
                'name.max' => __('Addon name cannot exceed 255 characters'),
                'price.required' => __('Price is required'),
                'price.numeric' => __('Price must be a valid number'),
                'price.min' => __('Price cannot be negative'),
                'price.max' => __('Price cannot exceed 999999.99'),
                'restaurant_id.required' => __('Please select a restaurant'),
                'restaurant_id.exists' => __('Selected restaurant does not exist'),
            ]);

            if ($validator->fails()) {
                return back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('error', __('Please fix the validation errors and try again.'));
            }

            // Check for duplicate addon in same restaurant
            $existingAddon = AddonModel::where('name', $request->name)
                ->where('restaurant_id', $request->restaurant_id)
                ->first();

            if ($existingAddon) {
                return back()
                    ->withInput()
                    ->with('error', __('An addon with this name already exists for the selected restaurant.'));
            }

            // Start database transaction
            DB::beginTransaction();

            $addon = AddonModel::create([
                'name' => trim($request->name),
                'price' => round($request->price, 2),
                'restaurant_id' => $request->restaurant_id,
                'status' => true, // Default to active
            ]);

            // Commit transaction
            DB::commit();

            return redirect()->route('admin.addon.add')->with('success', __('Addon created successfully!'));

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Addon store error: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', __('An error occurred while creating the addon. Please try again.'));
        }
    }

    public function destroy($id)
    {
        try {
            // Find the addon by its ID
            $addon = AddonModel::findOrFail($id);

            // Start database transaction
            DB::beginTransaction();

            // Check if addon is being used (you can add more checks here if needed)
            // For example, check if it's linked to any orders

            // Delete the addon
            $addon->delete();

            // Commit transaction
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => __('Addon deleted successfully!')
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => __('Addon not found.')
            ], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Addon delete error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => __('An error occurred while deleting the addon.')
            ], 500);
        }
    }

    public function get_addons(Request $request){
        $restaurant_id = $request->query('restaurant_id')??null;
        return response()->json(AddonModel::where('restaurant_id',$restaurant_id)->get());
    }

    public function edit($id) 
    {
        try {
            $addon = AddonModel::with('restaurant')->findOrFail($id);
            $restaurants = Restaurant::select('id', 'name')->isActive(true)->orderBy('name')->get();
            
            return view('admin-views.addons.edit', compact('addon', 'restaurants'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('admin.addon.add')->with('error', __('Addon not found.'));
        } catch (\Exception $e) {
            Log::error('Addon edit error: ' . $e->getMessage());
            return redirect()->route('admin.addon.add')->with('error', __('An error occurred while loading the addon.'));
        }
    }

    public function update(Request $request) 
    {
        try {
            // Comprehensive validation
            $validator = Validator::make($request->all(), [
                'id' => 'required|exists:addons,id',
                'name' => 'required|string|max:255|min:2',
                'price' => 'required|numeric|min:0|max:999999.99',
                'restaurant_id' => 'required|exists:restaurants,id'
            ], [
                'id.required' => __('Addon ID is required'),
                'id.exists' => __('Selected addon does not exist'),
                'name.required' => __('Addon name is required'),
                'name.min' => __('Addon name must be at least 2 characters'),
                'name.max' => __('Addon name cannot exceed 255 characters'),
                'price.required' => __('Price is required'),
                'price.numeric' => __('Price must be a valid number'),
                'price.min' => __('Price cannot be negative'),
                'price.max' => __('Price cannot exceed 999999.99'),
                'restaurant_id.required' => __('Please select a restaurant'),
                'restaurant_id.exists' => __('Selected restaurant does not exist'),
            ]);

            if ($validator->fails()) {
                return back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('error', __('Please fix the validation errors and try again.'));
            }

            // Find the addon
            $addon = AddonModel::findOrFail($request->id);

            // Check for duplicate addon in same restaurant (excluding current addon)
            $existingAddon = AddonModel::where('name', $request->name)
                ->where('restaurant_id', $request->restaurant_id)
                ->where('id', '!=', $request->id)
                ->first();

            if ($existingAddon) {
                return back()
                    ->withInput()
                    ->with('error', __('An addon with this name already exists for the selected restaurant.'));
            }

            // Start database transaction
            DB::beginTransaction();

            $addon->update([
                'name' => trim($request->name),
                'price' => round($request->price, 2),
                'restaurant_id' => $request->restaurant_id,
                'status' => $request->has('status') ? true : false,
            ]);

            // Commit transaction
            DB::commit();

            return redirect()->route('admin.addon.add')->with('success', __('Addon updated successfully!'));

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('admin.addon.add')->with('error', __('Addon not found.'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Addon update error: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', __('An error occurred while updating the addon. Please try again.'));
        }
    }

    public function status(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required|exists:addons,id',
                'status' => 'required|boolean'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => __('Invalid request parameters.')
                ], 400);
            }

            $addon = AddonModel::findOrFail($request->id);
            
            // Start database transaction
            DB::beginTransaction();

            $addon->status = $request->status;
            $addon->save();

            // Commit transaction
            DB::commit();

            $statusText = $request->status ? __('activated') : __('deactivated');
            
            return response()->json([
                'success' => true,
                'message' => __('Addon status updated successfully! Addon has been :status.', ['status' => $statusText])
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => __('Addon not found.')
            ], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Addon status update error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => __('An error occurred while updating addon status.')
            ], 500);
        }
    }

    public function view($id)
    {
        try {
            $addon = AddonModel::with('restaurant')->findOrFail($id);
            
            return view('admin-views.addons.view', compact('addon'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('admin.addon.add')->with('error', __('Addon not found.'));
        } catch (\Exception $e) {
            Log::error('Addon view error: ' . $e->getMessage());
            return redirect()->route('admin.addon.add')->with('error', __('An error occurred while loading the addon.'));
        }
    }
}
