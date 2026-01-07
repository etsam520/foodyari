<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Zone;
use App\Models\ZoneBusinessSetting;
use App\Models\BusinessSetting;
use Illuminate\Support\Facades\DB;

class ZoneBusinessSettingSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $this->command->info('Starting zone business settings migration...');

        try {
            DB::beginTransaction();

            // Get all zones
            $zones = Zone::all();
            $this->command->info("Found {$zones->count()} zones to process");

            // Get all zone-configurable business settings
            $configurableKeys = ZoneBusinessSetting::getZoneConfigurableKeys();
            $globalSettings = BusinessSetting::whereIn('key', $configurableKeys)->get();

            $this->command->info("Found {$globalSettings->count()} global settings to migrate");

            foreach ($zones as $zone) {
                $this->command->info("Processing zone: {$zone->name} (ID: {$zone->id})");

                $migratedCount = 0;
                foreach ($globalSettings as $setting) {
                    // Check if zone-specific setting already exists
                    $existingSetting = ZoneBusinessSetting::where('zone_id', $zone->id)
                                                         ->where('key', $setting->key)
                                                         ->first();

                    if (!$existingSetting) {
                        ZoneBusinessSetting::create([
                            'zone_id' => $zone->id,
                            'key' => $setting->key,
                            'value' => $setting->value
                        ]);
                        $migratedCount++;
                    }
                }

                $this->command->info("  - Migrated {$migratedCount} settings for {$zone->name}");
            }

            DB::commit();
            $this->command->info('Zone business settings migration completed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('Error during migration: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Migrate specific settings for a zone
     */
    private function migrateZoneSettings($zoneId, $settingsData)
    {
        foreach ($settingsData as $key => $value) {
            ZoneBusinessSetting::updateOrCreate(
                ['zone_id' => $zoneId, 'key' => $key],
                ['value' => is_array($value) || is_object($value) ? json_encode($value) : $value]
            );
        }
    }

    /**
     * Create default settings for a new zone
     */
    public static function createDefaultSettingsForZone($zoneId)
    {
        $defaultSettings = [
            // Notification Messages
            'order_confirm_message' => 'Your order has been confirmed!',
            'order_delivered_message' => 'Your order has been delivered successfully!',
            'delivery_boy_delivered_message' => 'Order delivered to customer',
            'order_cancled_message' => 'Your order has been cancelled',
            'order_handover_message' => 'Your order is ready for pickup',
            'order_refunded_message' => 'Your order has been refunded',
            'out_for_delivery_message' => 'Your order is out for delivery',
            
            // Business Settings - Default values
            'customer_verification' => '0',
            'toggle_restaurant_registration' => '1',
            'toggle_dm_registration' => '1',
            'admin_order_notification' => '1',
            'schedule_order' => '1',
            'order_delivery_verification' => '0',
            'dm_tips_status' => '1',
            'show_dm_earning' => '1',
            'toggle_veg_non_veg' => '1',
            'business_model' => json_encode(['commission' => 1, 'subscription' => 0]),
            'order_subscription' => '0',
            'order_confirmation_model' => 'restaurant',
            'canceled_by_deliveryman' => '0',
            'canceled_by_restaurant' => '1',
            
            // Operational Settings
            'schedule_order_slot_duration' => '30',
            'dm_maximum_orders' => '5',
            'free_delivery_over' => '100',
            'dm_max_cash_in_hand' => '1000',
            'admin_commission' => '10',
            'delivery_charge_comission' => '20',
            'loyalty_percent' => '5',
            'loyalty_value' => '1',
            'minimum_redeem_value' => '10',
        ];

        foreach ($defaultSettings as $key => $value) {
            ZoneBusinessSetting::updateOrCreate(
                ['zone_id' => $zoneId, 'key' => $key],
                ['value' => $value]
            );
        }
    }
}
