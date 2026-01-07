<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Zone;
use App\Models\ZoneDeliveryChargeSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ZoneEnvironmentalFactorsController extends Controller
{
    /**
     * Display a listing of zones with environmental factors
     */
    public function index(Request $request)
    {
        $zones = Zone::with(['deliveryChargeSetting' => function($query) {
            $query->where('is_active', true);
        }])->get();

        // Process zones data for the view
        $zonesData = $zones->map(function($zone) {
            $setting = $zone->deliveryChargeSetting;
            // dd($setting);
            return [
                'id' => $zone->id,
                'name' => $zone->name,
                'delivery_setting' => $setting ? 'Configured' : 'Not Configured',
                'rain_factor' => $this->formatFactorDisplay($setting, 'rain_factor', 'auto_detect_rain'),
                'traffic_factor' => $this->formatFactorDisplay($setting, 'traffic_factor', 'auto_detect_traffic'),
                'night_factor' => $this->formatFactorDisplay($setting, 'night_factor', 'auto_detect_night'),
                'night_hours' => $this->formatNightHours($setting),
                'current_factors' => $this->formatCurrentFactors($setting),
                'edit_url' => route('admin.zone-delivery-charge.environmental-factors.edit', $zone->id)
            ];
        });
            // DD($zonesData);

        return view('admin.zone-environmental-factors.index', compact('zonesData'));
    }

    /**
     * Format factor display for view
     */
    private function formatFactorDisplay($setting, $factorField, $autoDetectField)
    {
        if (!$setting) return 'N/A';
        if ($setting->$factorField !== null) {
            return number_format($setting->$factorField, 2) . ' (Manual)';
        }
        
        return $setting->$autoDetectField ? 'Auto-Detect' : 'Not Set';
    }

    /**
     * Format night hours display
     */
    private function formatNightHours($setting)
    {
        if (!$setting) return 'N/A';
        
        $start = $setting->night_start_time ? $setting->night_start_time->format('H:i') : '20:00';
        $end = $setting->night_end_time ? $setting->night_end_time->format('H:i') : '06:00';
        
        return $start . ' - ' . $end;
    }

    /**
     * Format current factors display
     */
    private function formatCurrentFactors($setting)
    {
        if (!$setting) return 'N/A';
        
        $factors = $setting->getEnvironmentalFactors();
        return 'R:' . number_format($factors['rain'], 2) . 
               ' T:' . number_format($factors['traffic'], 2) . 
               ' N:' . number_format($factors['night'], 2);
    }    /**
     * Show the form for editing environmental factors for a zone
     */
    public function edit($zoneId)
    {
        $zone = Zone::findOrFail($zoneId);
        
        // Get or create delivery charge setting
        $setting = ZoneDeliveryChargeSetting::firstOrCreate(
            ['zone_id' => $zoneId, 'is_active' => true],
            [
                'tiers' => ZoneDeliveryChargeSetting::getDefaultTiers(),
                'rain_weight' => 0.2,
                'traffic_weight' => 0.15,
                'night_weight' => 0.25,
                'surge_multiplier' => 1.0,
                'location_multiplier' => 1.0,
                'min_fee' => 0,
                'auto_detect_night' => true,
                'auto_detect_traffic' => true,
                'auto_detect_rain' => false,
                'night_start_time' => '20:00',
                'night_end_time' => '06:00',
            ]
        );

        return view('admin.zone-environmental-factors.edit', compact('zone', 'setting'));
    }

    /**
     * Update environmental factors for a zone
     */
    public function update(Request $request, $zoneId)
    {
        $validator = Validator::make($request->all(), [
            'rain_factor' => 'nullable|numeric|between:0,1',
            'traffic_factor' => 'nullable|numeric|between:0,1',
            'night_factor' => 'nullable|numeric|between:0,1',
            'auto_detect_night' => 'boolean',
            'auto_detect_traffic' => 'boolean',
            'auto_detect_rain' => 'boolean',
            'night_start_time' => 'required|date_format:H:i',
            'night_end_time' => 'required|date_format:H:i',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $zone = Zone::findOrFail($zoneId);
        
        $setting = ZoneDeliveryChargeSetting::where('zone_id', $zoneId)
            ->where('is_active', true)
            ->first();

        if (!$setting) {
            return response()->json([
                'success' => false,
                'message' => 'Zone delivery charge setting not found'
            ], 404);
        }

        // Update environmental factors
        $setting->update([
            'rain_factor' => $request->rain_factor,
            'traffic_factor' => $request->traffic_factor,
            'night_factor' => $request->night_factor,
            'auto_detect_night' => $request->has('auto_detect_night'),
            'auto_detect_traffic' => $request->has('auto_detect_traffic'),
            'auto_detect_rain' => $request->has('auto_detect_rain'),
            'night_start_time' => $request->night_start_time,
            'night_end_time' => $request->night_end_time,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Environmental factors updated successfully',
            'current_factors' => $setting->getEnvironmentalFactors()
        ]);
    }

    /**
     * Get current environmental factors for a zone (for AJAX)
     */
    public function getCurrentFactors($zoneId)
    {
        $setting = ZoneDeliveryChargeSetting::where('zone_id', $zoneId)
            ->where('is_active', true)
            ->first();

        if (!$setting) {
            return response()->json([
                'success' => false,
                'message' => 'Zone delivery charge setting not found'
            ]);
        }

        return response()->json([
            'success' => true,
            'factors' => $setting->getEnvironmentalFactors(),
            'settings' => [
                'rain_factor' => $setting->rain_factor,
                'traffic_factor' => $setting->traffic_factor,
                'night_factor' => $setting->night_factor,
                'auto_detect_night' => $setting->auto_detect_night,
                'auto_detect_traffic' => $setting->auto_detect_traffic,
                'auto_detect_rain' => $setting->auto_detect_rain,
                'night_start_time' => $setting->night_start_time ? $setting->night_start_time->format('H:i') : '20:00',
                'night_end_time' => $setting->night_end_time ? $setting->night_end_time->format('H:i') : '06:00',
            ]
        ]);
    }
}
