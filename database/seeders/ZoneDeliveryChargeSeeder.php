<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Zone;
use App\Models\ZoneDeliveryChargeSetting;

class ZoneDeliveryChargeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $zones = Zone::whereDoesntHave('activeDeliveryChargeSetting')->get();

        foreach ($zones as $zone) {
            ZoneDeliveryChargeSetting::create([
                'zone_id' => $zone->id,
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

        $this->command->info('Created default delivery charge settings for ' . $zones->count() . ' zones.');
    }
}