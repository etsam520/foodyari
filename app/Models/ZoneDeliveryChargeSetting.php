<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZoneDeliveryChargeSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'zone_id',
        'tiers',
        'rain_weight',
        'traffic_weight',
        'night_weight',
        'surge_multiplier',
        'location_multiplier',
        'min_fee',
        'is_active',
        // Environmental factors
        'rain_factor',
        'traffic_factor',
        'night_factor',
        'auto_detect_night',
        'auto_detect_traffic',
        'auto_detect_rain',
        'night_start_time',
        'night_end_time',
    ];

    protected $casts = [
        'tiers' => 'array',
        'rain_weight' => 'decimal:3',
        'traffic_weight' => 'decimal:3',
        'night_weight' => 'decimal:3',
        'surge_multiplier' => 'decimal:2',
        'location_multiplier' => 'decimal:2',
        'min_fee' => 'decimal:2',
        'is_active' => 'boolean',
        // Environmental factors
        'rain_factor' => 'decimal:2',
        'traffic_factor' => 'decimal:2',
        'night_factor' => 'decimal:2',
        'auto_detect_night' => 'boolean',
        'auto_detect_traffic' => 'boolean',
        'auto_detect_rain' => 'boolean',
        'night_start_time' => 'datetime:H:i',
        'night_end_time' => 'datetime:H:i',
    ];

    /**
     * Get the zone that owns the delivery charge setting.
     */
    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    /**
     * Get default tier configuration
     */
    public static function getDefaultTiers(): array
    {
        return [
            'A' => [
                'max_distance' => 2,
                'base' => 5,
                'min_order' => 50,
                'per_km' => 0,
                'start_km' => 0,
            ],
            'B' => [
                'max_distance' => 6,
                'base' => 7,
                'min_order' => 200,
                'per_km' => 0,
                'start_km' => 2,
            ],
            'C' => [
                'max_distance' => PHP_INT_MAX,
                'base' => 20,
                'min_order' => null,
                'per_km' => 5,
                'start_km' => 6,
            ]
        ];
    }

    /**
     * Scope for active settings
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get active setting for a zone
     */
    public static function getActiveSettingForZone($zoneId)
    {
        return static::where('zone_id', $zoneId)
            ->where('is_active', true)
            ->first();
    }

    /**
     * Get environmental factors for this zone setting
     * @return array ['rain' => float, 'traffic' => float, 'night' => float]
     */
    public function getEnvironmentalFactors(): array
    {
        return [
            'rain' => $this->getRainFactor(),
            'traffic' => $this->getTrafficFactor(),
            'night' => $this->getNightFactor(),
        ];
    }

    /**
     * Get rain factor (manual override or auto-detect)
     */
    public function getRainFactor(): float
    {
        if ($this->rain_factor !== null) {
            return (float) $this->rain_factor;
        }

        if ($this->auto_detect_rain) {
            // TODO: Integrate with weather API
            return 0.0;
        }

        return 0.0;
    }

    /**
     * Get traffic factor (manual override or auto-detect)
     */
    public function getTrafficFactor(): float
    {
        if ($this->traffic_factor !== null) {
            return (float) $this->traffic_factor;
        }

        if ($this->auto_detect_traffic) {
            // Auto-detect based on rush hours
            $hour = (int) date('H');
            if (($hour >= 7 && $hour <= 10) || ($hour >= 17 && $hour <= 20)) {
                return 0.6; // Rush hour
            } elseif (($hour >= 11 && $hour <= 16) || ($hour >= 21 && $hour <= 23)) {
                return 0.3; // Moderate traffic
            } else {
                return 0.1; // Light traffic
            }
        }

        return 0.0;
    }

    /**
     * Get night factor (manual override or auto-detect)
     */
    public function getNightFactor(): float
    {
        if ($this->night_factor !== null) {
            return (float) $this->night_factor;
        }

        if ($this->auto_detect_night) {
            return $this->calculateNightFactorFromTime();
        }

        return 0.0;
    }

    /**
     * Calculate night factor based on current time and zone settings
     */
    private function calculateNightFactorFromTime(): float
    {
        $currentTime = date('H:i');
        $nightStart = $this->night_start_time ? $this->night_start_time->format('H:i') : '20:00';
        $nightEnd = $this->night_end_time ? $this->night_end_time->format('H:i') : '06:00';

        // Convert times to minutes for comparison
        $current = $this->timeToMinutes($currentTime);
        $start = $this->timeToMinutes($nightStart);
        $end = $this->timeToMinutes($nightEnd);

        // Handle overnight periods (e.g., 20:00 to 06:00)
        if ($start > $end) {
            if ($current >= $start || $current <= $end) {
                // It's night time
                if ($current >= $start) {
                    // Evening hours (20:00 - 23:59)
                    $minutesIntoNight = $current - $start;
                    $totalNightMinutes = (24 * 60 - $start) + $end;
                } else {
                    // Early morning hours (00:00 - 06:00)
                    $minutesIntoNight = (24 * 60 - $start) + $current;
                    $totalNightMinutes = (24 * 60 - $start) + $end;
                }
                
                // Peak night factor at middle of night period
                $nightProgress = $minutesIntoNight / $totalNightMinutes;
                if ($nightProgress <= 0.5) {
                    return $nightProgress * 2; // 0 to 1
                } else {
                    return 2 - ($nightProgress * 2); // 1 to 0
                }
            }
        } else {
            // Same day period (e.g., 02:00 to 06:00)
            if ($current >= $start && $current <= $end) {
                $minutesIntoNight = $current - $start;
                $totalNightMinutes = $end - $start;
                $nightProgress = $minutesIntoNight / $totalNightMinutes;
                
                if ($nightProgress <= 0.5) {
                    return $nightProgress * 2; // 0 to 1
                } else {
                    return 2 - ($nightProgress * 2); // 1 to 0
                }
            }
        }

        return 0.0; // Day time
    }

    /**
     * Convert time string to minutes
     */
    private function timeToMinutes(string $time): int
    {
        list($hours, $minutes) = explode(':', $time);
        return ((int) $hours * 60) + (int) $minutes;
    }
}