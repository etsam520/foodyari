# 🚚 DeliveryChargeCalculate Class

A configurable PHP class to **dynamically calculate delivery charges** based on:

- **Distance ranges (tiers)**
- **Minimum order amount per tier (free delivery rules)**
- **Environmental factors:** rain, traffic, and night-time conditions
- **Surge and location difficulty multipliers**
- **Flexible configuration & extension support**

---

## 📦 File

`DeliveryChargeCalculate.php`

---

## 🧮 Formula Overview

### Base Formula


Charge = (TierBase) × E × S

Where:

| Symbol | Meaning |
|:-------|:---------|
| `TierBase` | Base charge determined by distance tier |
| `E` | Environmental multiplier |
| `S` | Surge multiplier |

### Environmental Multiplier

E = 1 + (Rain × w_r) + (Traffic × w_t) + (Night × w_n) + (L - 1)


| Variable | Description | Example |
|:--|:--|:--|
| `Rain` | 0 = no rain, 1 = heavy rain | 0.5 |
| `Traffic` | 0 = clear, 1 = heavy | 0.6 |
| `Night` | 0 = day, 1 = night | 1 |
| `w_r`, `w_t`, `w_n` | Weights (in %) | 0.20, 0.15, 0.10 |
| `L` | Location multiplier (1 = normal, >1 = remote) | 1.2 |

---

## 🗺️ Tier Configuration

| Tier | Distance Range | Base Fee | Per KM | Min Order (for free delivery) | Notes |
|:--|:--|:--|:--|:--|:--|
| A | 0–2 km | ₹5 | 0 | ₹50 | Free if order ≥ ₹50 |
| B | 2–6 km | ₹7 | 0 | ₹200 | Free if order ≥ ₹200 |
| C | >6 km | ₹20 | ₹5/km | — | No free delivery |

---

## ⚙️ Constructor Options

You can override defaults by passing an array to the constructor.

```php
$calc = new DeliveryChargeCalculate([
    'surgeMultiplier' => 1.2,       // peak hours
    'locationMultiplier' => 1.1,    // remote location
    'minFee' => 5.0,                // minimum charge
]);


