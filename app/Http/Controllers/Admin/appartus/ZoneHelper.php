<?php


namespace App\Http\Controllers\Admin\appartus;

use App\CentralLogics\Helpers;
use App\Models\Zone;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class ZoneHelper
{
    static $counter = 0;
    public static function setOrderZone($zoneId = 'all')
    {
        if ($zoneId !== 'all') {
            $zone = Zone::find($zoneId);

            if ($zone) {
                Cache::put('order_zone', $zone, now()->addHours(6));
                self::$counter++;
                return [
                    'success' => true,
                    'message' => Str::ucfirst($zone->name) . ' order zone set successfully',
                ];
            }

            return [
                'success' => false,
                'message' => 'Zone not found',
            ];
        }

        Cache::forget('order_zone');
        return [
            'success' => true,
            'message' => 'Default zone set to all',
        ];
    }

    public static function getOrderZone()
    {   
        if(self::$counter > 0){
            $isStaff = !Helpers::isAdmin();
            $zoneId = "all";
            if($isStaff) $zoneId = Helpers::getStaff()->zone_id ?? 'all';
            self::setOrderZone($zoneId);
        }
        return Cache::get('order_zone', 'all');
    }
}