<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Zone;
use App\Models\ZoneDeliveryChargeSetting;
use App\CentralLogics\Restaurant\DeliveryCharge\ZoneWiseDeliveryChargeCalculate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ZoneDeliveryChargeController extends Controller
{
    /**
     * Display a listing of zones with their delivery charge settings
     */
    public function index()
    {
        $zones = Zone::with('activeDeliveryChargeSetting')
            ->withCount(['restaurants', 'messes', 'deliverymen'])
            ->isActive()
            ->get();

        return view('admin-views.zone-delivery-charge.index', compact('zones'));
    }

    /**
     * Show the form for creating/editing delivery charge settings for a specific zone
     */
    public function edit($zoneId)
    {
        $zone = Zone::with('activeDeliveryChargeSetting')->findOrFail($zoneId);
        
        $setting = $zone->activeDeliveryChargeSetting;
        
        // If no setting exists, create default
        if (!$setting) {
            $setting = new ZoneDeliveryChargeSetting([
                'zone_id' => $zoneId,
                'tiers' => ZoneDeliveryChargeSetting::getDefaultTiers(),
                'rain_weight' => 0.20,
                'traffic_weight' => 0.15,
                'night_weight' => 0.10,
                'surge_multiplier' => 1.0,
                'location_multiplier' => 1.0,
                'min_fee' => 5.0,
                'is_active' => true,
            ]);
        }

        return view('admin-views.zone-delivery-charge.edit', compact('zone', 'setting'));
    }

    /**
     * Store or update the delivery charge settings for a zone
     */
    public function store(Request $request, $zoneId)
    {
        $validator = Validator::make($request->all(), [
            'tiers' => 'required|array',
            'tiers.A.max_distance' => 'required|numeric|min:0',
            'tiers.B.max_distance' => 'required|numeric|min:0',
            'tiers.C.max_distance' => 'required|in:unlimited',
            'tiers.*.base' => 'required|numeric|min:0',
            'tiers.*.min_order' => 'nullable|numeric|min:0',
            'tiers.*.per_km' => 'required|numeric|min:0',
            'tiers.*.start_km' => 'required|numeric|min:0',
            'rain_weight' => 'required|numeric|min:0|max:1',
            'traffic_weight' => 'required|numeric|min:0|max:1',
            'night_weight' => 'required|numeric|min:0|max:1',
            'surge_multiplier' => 'required|numeric|min:0',
            'location_multiplier' => 'required|numeric|min:0',
            'min_fee' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            $zone = Zone::findOrFail($zoneId);

            // Delete existing settings (due to unique constraint on zone_id + is_active)
            ZoneDeliveryChargeSetting::where('zone_id', $zoneId)->delete();

            // Process tiers data
            $tiers = [];
            foreach ($request->tiers as $tierName => $tierData) {
                $tiers[$tierName] = [
                    'max_distance' => $tierData['max_distance'] == 'unlimited' ? PHP_INT_MAX : (float)$tierData['max_distance'],
                    'base' => (float)$tierData['base'],
                    'min_order' => $tierData['min_order'] ? (float)$tierData['min_order'] : null,
                    'per_km' => (float)$tierData['per_km'],
                    'start_km' => (float)$tierData['start_km'],
                ];
            }

            // Create new setting
            $setting = ZoneDeliveryChargeSetting::create([
                'zone_id' => $zoneId,
                'tiers' => $tiers,
                'rain_weight' => $request->rain_weight,
                'traffic_weight' => $request->traffic_weight,
                'night_weight' => $request->night_weight,
                'surge_multiplier' => $request->surge_multiplier,
                'location_multiplier' => $request->location_multiplier,
                'min_fee' => $request->min_fee,
                'is_active' => true,
            ]);

            DB::commit();

            return redirect()
                ->route('admin.zone-delivery-charge.index')
                ->with('success', 'Delivery charge settings updated successfully for ' . $zone->name);

        } catch (\Exception $e) {
            DB::rollback();
            return back()
                ->with('error', 'Failed to update delivery charge settings: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Test the delivery charge calculation
     */
    public function testCalculation(Request $request, $zoneId)
    {
        $validator = Validator::make($request->all(), [
            'distance' => 'required|numeric|min:0',
            'order_amount' => 'required|numeric|min:0',
            'rain_factor' => 'nullable|numeric|min:0|max:1',
            'traffic_factor' => 'nullable|numeric|min:0|max:1',
            'night_factor' => 'nullable|numeric|min:0|max:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $calculator = new ZoneWiseDeliveryChargeCalculate($zoneId);
            
            $result = $calculator->calculate(
                $request->distance,
                $request->order_amount,
                $request->rain_factor ?? 0,
                $request->traffic_factor ?? 0,
                $request->night_factor ?? 0
            );

            return response()->json([
                'success' => true,
                'result' => $result
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get zone delivery charge settings as JSON
     */
    public function getSettings($zoneId)
    {
        $setting = ZoneDeliveryChargeSetting::getActiveSettingForZone($zoneId);
        
        if (!$setting) {
            $setting = [
                'tiers' => ZoneDeliveryChargeSetting::getDefaultTiers(),
                'rain_weight' => 0.20,
                'traffic_weight' => 0.15,
                'night_weight' => 0.10,
                'surge_multiplier' => 1.0,
                'location_multiplier' => 1.0,
                'min_fee' => 5.0,
            ];
        }

        return response()->json($setting);
    }

    /**
     * Clone settings from one zone to another
     */
    public function cloneSettings(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'source_zone_id' => 'required|exists:zones,id',
            'target_zone_ids' => 'required|array',
            'target_zone_ids.*' => 'exists:zones,id',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        try {
            DB::beginTransaction();

            $sourceSetting = ZoneDeliveryChargeSetting::getActiveSettingForZone($request->source_zone_id);
            
            if (!$sourceSetting) {
                return back()->with('error', 'Source zone has no delivery charge settings to clone.');
            }

            $cloned = 0;
            foreach ($request->target_zone_ids as $targetZoneId) {
                if ($targetZoneId == $request->source_zone_id) {
                    continue; // Skip self
                }

                // Delete existing settings (due to unique constraint on zone_id + is_active)
                ZoneDeliveryChargeSetting::where('zone_id', $targetZoneId)->delete();

                // Create new setting
                ZoneDeliveryChargeSetting::create([
                    'zone_id' => $targetZoneId,
                    'tiers' => $sourceSetting->tiers,
                    'rain_weight' => $sourceSetting->rain_weight,
                    'traffic_weight' => $sourceSetting->traffic_weight,
                    'night_weight' => $sourceSetting->night_weight,
                    'surge_multiplier' => $sourceSetting->surge_multiplier,
                    'location_multiplier' => $sourceSetting->location_multiplier,
                    'min_fee' => $sourceSetting->min_fee,
                    'is_active' => true,
                ]);

                $cloned++;
            }

            DB::commit();

            return back()->with('success', "Successfully cloned delivery charge settings to {$cloned} zones.");

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Failed to clone settings: ' . $e->getMessage());
        }
    }
}