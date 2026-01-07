<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\appartus\ZoneHelper;
use App\Http\Controllers\Controller;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ZoneController extends Controller
{
    public function list()
    {
        $zones = Zone::withCount(['restaurants', 'messes','deliverymen'])->latest()->get();
        // dd($zones);
        return view('admin-views.zone.list',compact('zones'));
    }
    public function add()
    {
        return view('admin-views.zone.index');
    }

    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|unique:zones|max:191',
            "latitude" => 'required',
            "longitude" => 'required',
            "platform_charge_original" => 'required|numeric',
            "platform_charge" => 'required|numeric',
            'max_cod_order_amount' => 'nullable|numeric',
            'zone_coordinates' => 'nullable|string', // For polygon coordinates
        ]);

        $zone_id=Zone::all()->count() + 1;
        $zone = new Zone();
        $zone->name = $request->name;
        
        // Handle polygon coordinates or fallback to center point
        if ($request->has('zone_coordinates') && !empty($request->zone_coordinates)) {
            // Validate JSON format
            $polygonCoordinates = json_decode($request->zone_coordinates, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($polygonCoordinates) && count($polygonCoordinates) >= 3) {
                // Store polygon coordinates with center point for backward compatibility
                $zone->coordinates = json_encode([
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                    'polygon' => $polygonCoordinates,
                    'type' => 'polygon'
                ]);
            } else {
                // Invalid polygon data, store as center point
                $zone->coordinates = json_encode([
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                    'type' => 'point'
                ]);
            }
        } else {
            // No polygon coordinates provided, store as center point
            $zone->coordinates = json_encode([
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'type' => 'point'
            ]);
        }
        
        $zone->restaurant_wise_topic =  'zone_'.$zone_id.'_restaurant';
        $zone->isTopOrders = $request->isTopOrders??0;
        $zone->customer_wise_topic = 'zone_'.$zone_id.'_customer';
        $zone->deliveryman_wise_topic = 'zone_'.$zone_id.'_delivery_man';
        $zone->delivery_verification = $request->delivery_verification == 'on'? 1 : 0;
        $zone->max_cod_order_amount = $request->max_cod_order_amount ?? null;
        $zone->platform_charge = $request->platform_charge ?? null;
        $zone->platform_charge_original = $request->platform_charge_original ?? null;

        $zone->save();
        
        // Log the saved coordinates for debugging
        Log::info('Zone created with coordinates: ' . $zone->coordinates);
        
        return redirect(route('admin.zone.list'))->with('success', 'Zone Added Successfully');

    }

    public function edit(Request $request, $id)
    {
        $zone = Zone::find($id);
        return view('admin-views.zone._edit',compact('zone'));
    }

    public function update(Request $request , $id)
    {

        $request->validate([
            'name' => 'required|max:191',
            "latitude" => 'required',
            "longitude" => 'required',
            "platform_charge_original" => 'required|numeric',
            "platform_charge" => 'required|numeric',
            'max_cod_order_amount' => 'nullable|numeric',
            'zone_coordinates' => 'nullable|string', // For polygon coordinates
        ]);

        $zone = Zone::find($id);
        $zone->name = $request->name;
        
        // Handle polygon coordinates or fallback to center point
        if ($request->has('zone_coordinates') && !empty($request->zone_coordinates)) {
            // Validate JSON format
            $polygonCoordinates = json_decode($request->zone_coordinates, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($polygonCoordinates) && count($polygonCoordinates) >= 3) {
                // Store polygon coordinates with center point for backward compatibility
                $zone->coordinates = json_encode([
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                    'polygon' => $polygonCoordinates,
                    'type' => 'polygon'
                ]);
            } else {
                // Invalid polygon data, store as center point
                $zone->coordinates = json_encode([
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                    'type' => 'point'
                ]);
            }
        } else {
            // No polygon coordinates provided, store as center point
            $zone->coordinates = json_encode([
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'type' => 'point'
            ]);
        }

        $zone->max_cod_order_amount = $request->max_cod_order_amount ?? null;
        $zone->platform_charge = $request->platform_charge ?? null;
        $zone->delivery_verification = $request->delivery_verification === 'on';
        $zone->platform_charge_original = $request->platform_charge_original ?? null;
        $zone->isTopOrders = $request->isTopOrders??0;
        $zone->min_purchase = $request->min_purchase != null ? $request->min_purchase : 0;

        $zone->save();
        
        // Log the updated coordinates for debugging
        Log::info('Zone updated with coordinates: ' . $zone->coordinates);
        
        return redirect(route('admin.zone.list'))->with('success', 'Zone Updated Successfully');

    }

    public function status(Request $request , $id, $status)
    {
      $zone = Zone::find($id);
      $zone->status = $status;
      $zone->save();
      if($status == 1){
          return back()->with('success', 'Zone Activated');
        }elseif($status == 0){
          return back()->with('warning', 'Zone Deactivated');
      }
      return back();
    }

    public function setOrderZone(Request $request)
    {
        $zoneId = $request->query('zone_id')??'all';
        ZoneHelper::setOrderZone($zoneId);
        return response()->json(['message' => Str::ucfirst($zoneId).' Order zone set success'], 200);

    }

}
