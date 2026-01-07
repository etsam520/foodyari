# Zone-wise Delivery Charge System

This system provides zone-specific delivery charge calculation with environmental factors and tier-based pricing.

## 🚀 Quick Start

### 1. Run Migration
```bash
php artisan migrate
```

### 2. Seed Default Settings (Optional)
```bash
php artisan db:seed --class=ZoneDeliveryChargeSeeder
```

### 3. Access Admin Panel
Navigate to: `/admin/zone-delivery-charge`

## 💡 Usage Examples

### Basic Calculation
```php
use App\Services\DeliveryChargeService;

// Calculate delivery charge for zone 1
$result = DeliveryChargeService::calculateForZone(
    zoneId: 1,
    distanceKm: 3.5,
    orderAmount: 150
);

echo "Delivery Charge: ₹" . $result['charge'];
// Output: Delivery Charge: ₹7.00
```

### With Environmental Factors
```php
// Calculate with weather and traffic conditions
$result = DeliveryChargeService::calculateForZone(
    zoneId: 1,
    distanceKm: 3.5,
    orderAmount: 150,
    environmentalFactors: [
        'rain' => 0.8,      // Heavy rain
        'traffic' => 0.6,   // High traffic
        'night' => 1.0      // Night time
    ]
);

echo "Delivery Charge: ₹" . $result['charge'];
// Output: Delivery Charge: ₹10.85
```

### Auto-detection of Environmental Factors
```php
// Auto-detect based on current conditions
$result = DeliveryChargeService::calculateWithAutoDetection(
    zoneId: 1,
    distanceKm: 3.5,
    orderAmount: 150,
    autoDetectConditions: [
        'weather' => 'rain',
        'traffic_level' => 'high',
        'time' => '23:30'  // Optional, defaults to current time
    ]
);
```

### Check Tier Information
```php
$tierInfo = DeliveryChargeService::getTierInfo(
    zoneId: 1, 
    distanceKm: 3.5
);

echo "Tier: " . $tierInfo['name']; // Output: Tier: B
```

### Zone-wise Calculator Class
```php
use App\CentralLogics\Restaurant\DeliveryCharge\ZoneWiseDeliveryChargeCalculate;

$calculator = new ZoneWiseDeliveryChargeCalculate(zoneId: 1);

$result = $calculator->calculate(
    distanceKm: 3.5,
    orderAmount: 150,
    rainFactor: 0.5,
    trafficFactor: 0.3,
    nightFactor: 0
);

// Get detailed breakdown
print_r($result['details']);
```

## 🔧 Admin Configuration

### Tier Setup
- **Tier A**: Short distance (0-2km) - Fixed base charge
- **Tier B**: Medium distance (2-6km) - Fixed base charge  
- **Tier C**: Long distance (6km+) - Base + per KM charges

### Environmental Factors
- **Rain Weight**: 0-1 scale (typically 0.20 = 20% increase)
- **Traffic Weight**: 0-1 scale (typically 0.15 = 15% increase)  
- **Night Weight**: 0-1 scale (typically 0.10 = 10% increase)

### Multipliers
- **Surge Multiplier**: Peak hour pricing (1.0 = normal, 1.2 = 20% surge)
- **Location Multiplier**: Area difficulty (1.0 = normal, 1.1 = 10% more for remote areas)

## 📊 Data Structure

### Result Format
```php
[
    'charge' => 10.50,  // Final delivery charge
    'details' => [
        'zone_id' => 1,
        'tier' => 'B',
        'distance_km' => 3.5,
        'order_amount' => 150,
        'free_delivery' => false,
        'tier_base' => 7.0,
        'environmental_multiplier' => 1.25,
        'surge_multiplier' => 1.0,
        'min_fee' => 5.0,
        'environmental_factors' => [
            'rain' => 0.5,
            'traffic' => 0.3,
            'night' => 0
        ],
        'calculation_breakdown' => [
            'base_charge' => 7.0,
            'after_environmental' => 8.75,
            'after_surge' => 8.75,
            'final_charge' => 10.50
        ]
    ]
]
```

### Database Tables

#### `zone_delivery_charge_settings`
- `zone_id`: Foreign key to zones table
- `tiers`: JSON configuration for A, B, C tiers
- `rain_weight`, `traffic_weight`, `night_weight`: Environmental factors
- `surge_multiplier`, `location_multiplier`: Pricing multipliers
- `min_fee`: Minimum delivery charge
- `is_active`: Only one active setting per zone

## 🛠️ Integration with Orders

### In Order Controller
```php
use App\Services\DeliveryChargeService;

public function calculateDeliveryCharge(Request $request)
{
    $result = DeliveryChargeService::calculateWithAutoDetection(
        zoneId: $request->zone_id,
        distanceKm: $request->distance,
        orderAmount: $request->order_total,
        autoDetectConditions: [
            'weather' => $this->getCurrentWeather(),
            'traffic_level' => $this->getCurrentTrafficLevel()
        ]
    );
    
    return response()->json([
        'delivery_charge' => $result['charge'],
        'details' => $result['details']
    ]);
}
```

## 📱 API Endpoints

### Admin Routes
- `GET /admin/zone-delivery-charge` - List all zones with settings
- `GET /admin/zone-delivery-charge/{zone}/edit` - Configure zone settings
- `POST /admin/zone-delivery-charge/{zone}/store` - Save zone settings
- `POST /admin/zone-delivery-charge/{zone}/test` - Test calculation
- `POST /admin/zone-delivery-charge/clone` - Clone settings between zones

## 🔐 Permissions

Required permissions for admin users:
- `zone-delivery-charge.index`
- `zone-delivery-charge.edit`
- `zone-delivery-charge.store`
- `zone-delivery-charge.test`
- `zone-delivery-charge.clone`

## 🧪 Testing

### Test Configuration
In the admin panel, use the test section to verify calculations before saving:

1. Enter distance and order amount
2. Click "Test Current Config"
3. Review the calculated charge and tier

### Unit Testing
```php
// In your test file
use App\Services\DeliveryChargeService;

public function test_delivery_charge_calculation()
{
    $result = DeliveryChargeService::calculateForZone(1, 2.5, 100);
    
    $this->assertArrayHasKey('charge', $result);
    $this->assertArrayHasKey('details', $result);
    $this->assertGreaterThan(0, $result['charge']);
}
```

## 🔄 Migration from Old System

If you have existing delivery charge logic, you can gradually migrate:

1. Create zone settings with current values
2. Test calculations match existing logic
3. Replace old calculation calls with new service
4. Gradually add environmental factors

## 🚨 Important Notes

- Each zone can only have one active delivery charge setting
- Environmental factors are on a 0-1 scale
- Tier C (long distance) should have unlimited max_distance
- Free delivery applies when order amount >= tier's min_order
- Minimum fee is enforced even after all calculations