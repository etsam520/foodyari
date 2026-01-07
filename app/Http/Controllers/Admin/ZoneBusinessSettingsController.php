<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Zone;
use App\Models\ZoneBusinessSetting;
use App\Models\BusinessSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;

class ZoneBusinessSettingsController extends Controller
{
    /**
     * Display a listing of zones with business settings
     */
    public function index()
    {
        $zones = Zone::withCount('businessSettings')->orderBy('name')->paginate(15);
        return view('admin-views.zone.business-settings.index', compact('zones'));
    }

    /**
     * Show the form for editing zone business settings
     */
    public function edit($zoneId)
    {
        $zone = Zone::findOrFail($zoneId);
        $settings = ZoneBusinessSetting::getZoneSettings($zoneId);
        
        // Get global settings for reference
        $globalSettings = BusinessSetting::whereIn('key', ZoneBusinessSetting::getZoneConfigurableKeys())
                                        ->pluck('value', 'key')
                                        ->toArray();
        
        return view('admin-views.zone.business-settings.edit', compact('zone', 'settings', 'globalSettings'));
    }

    /**
     * Update zone business settings
     */
    public function update(Request $request, $zoneId)
    {
        try {
            DB::beginTransaction();
            
            $zone = Zone::findOrFail($zoneId);
            $configurableKeys = ZoneBusinessSetting::getZoneConfigurableKeys();
            
            foreach ($configurableKeys as $key) {
                if ($request->has($key)) {
                    $value = $request->get($key);
                    
                    // Handle special cases
                    switch ($key) {
                        case 'business_model':
                            $businessModel = [];
                            if ($request->has('commission')) {
                                $businessModel['commission'] = $request->get('commission') == '1' ? 1 : 0;
                            }
                            if ($request->has('subscription')) {
                                $businessModel['subscription'] = $request->get('subscription') == '1' ? 1 : 0;
                            }
                            $value = json_encode($businessModel);
                            break;
                            
                        case 'odc':
                            // Map odc to order_delivery_verification
                            ZoneBusinessSetting::setSettingValue('order_delivery_verification', $zoneId, $value);
                            continue 2;
                            break;
                            
                        case 'vnv':
                            // Map vnv to toggle_veg_non_veg
                            ZoneBusinessSetting::setSettingValue('toggle_veg_non_veg', $zoneId, $value);
                            continue 2;
                            break;
                            
                        case 'admin_comission_in_delivery_charge':
                            // Map to delivery_charge_comission
                            ZoneBusinessSetting::setSettingValue('delivery_charge_comission', $zoneId, $value);
                            continue 2;
                            break;
                    }
                    
                    ZoneBusinessSetting::setSettingValue($key, $zoneId, $value);
                }
            }
            
            DB::commit();
            Toastr::success(__('Zone business settings updated successfully!'));
            
        } catch (\Exception $e) {
            DB::rollBack();
            Toastr::error(__('Failed to update zone business settings: ') . $e->getMessage());
        }
        
        return redirect()->back();
    }

    /**
     * Copy global settings to a zone
     */
    public function copyGlobalSettings(Request $request, $zoneId)
    {
        try {
            $zone = Zone::findOrFail($zoneId);
            ZoneBusinessSetting::copyGlobalSettingsToZone($zoneId);
            
            Toastr::success(__('Global settings copied to zone successfully!'));
            
        } catch (\Exception $e) {
            Toastr::error(__('Failed to copy global settings: ') . $e->getMessage());
        }
        
        return redirect()->back();
    }

    /**
     * Reset zone settings to global defaults
     */
    public function resetToGlobal(Request $request, $zoneId)
    {
        try {
            DB::beginTransaction();
            
            $zone = Zone::findOrFail($zoneId);
            
            // Delete all zone-specific settings
            ZoneBusinessSetting::where('zone_id', $zoneId)->delete();
            
            DB::commit();
            Toastr::success(__('Zone settings reset to global defaults successfully!'));
            
        } catch (\Exception $e) {
            DB::rollBack();
            Toastr::error(__('Failed to reset zone settings: ') . $e->getMessage());
        }
        
        return redirect()->back();
    }

    /**
     * Clone settings from one zone to another
     */
    public function cloneSettings(Request $request)
    {
        $request->validate([
            'source_zone_id' => 'required|exists:zones,id',
            'target_zone_ids' => 'required|array',
            'target_zone_ids.*' => 'exists:zones,id'
        ]);

        try {
            DB::beginTransaction();
            
            $sourceZoneId = $request->get('source_zone_id');
            $targetZoneIds = $request->get('target_zone_ids');
            
            // Get source zone settings
            $sourceSettings = ZoneBusinessSetting::where('zone_id', $sourceZoneId)->get();
            
            foreach ($targetZoneIds as $targetZoneId) {
                // Skip if source and target are the same
                if ($sourceZoneId == $targetZoneId) {
                    continue;
                }
                
                // Delete existing settings for target zone
                ZoneBusinessSetting::where('zone_id', $targetZoneId)->delete();
                
                // Copy settings from source to target
                foreach ($sourceSettings as $setting) {
                    ZoneBusinessSetting::create([
                        'zone_id' => $targetZoneId,
                        'key' => $setting->key,
                        'value' => $setting->value
                    ]);
                }
            }
            
            DB::commit();
            Toastr::success(__('Settings cloned successfully to selected zones!'));
            
        } catch (\Exception $e) {
            DB::rollBack();
            Toastr::error(__('Failed to clone settings: ') . $e->getMessage());
        }
        
        return redirect()->back();
    }

    /**
     * Get zone settings for API/AJAX calls
     */
    public function getZoneSettings($zoneId)
    {
        try {
            $zone = Zone::findOrFail($zoneId);
            $settings = ZoneBusinessSetting::getZoneSettings($zoneId);
            
            return response()->json([
                'success' => true,
                'zone' => $zone,
                'settings' => $settings
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Compare zone settings with global settings
     */
    public function compareWithGlobal($zoneId)
    {
        try {
            $zone = Zone::findOrFail($zoneId);
            $zoneSettings = ZoneBusinessSetting::where('zone_id', $zoneId)->pluck('value', 'key')->toArray();
            $globalSettings = BusinessSetting::whereIn('key', ZoneBusinessSetting::getZoneConfigurableKeys())
                                            ->pluck('value', 'key')
                                            ->toArray();
            
            $differences = [];
            $configurableKeys = ZoneBusinessSetting::getZoneConfigurableKeys();
            
            foreach ($configurableKeys as $key) {
                $zoneValue = $zoneSettings[$key] ?? null;
                $globalValue = $globalSettings[$key] ?? null;
                
                if ($zoneValue !== $globalValue) {
                    $differences[$key] = [
                        'zone_value' => $zoneValue,
                        'global_value' => $globalValue
                    ];
                }
            }
            
            return view('admin-views.zone.business-settings.compare', compact('zone', 'differences'));
            
        } catch (\Exception $e) {
            Toastr::error(__('Failed to compare settings: ') . $e->getMessage());
            return redirect()->back();
        }
    }
}