# MQTT Order Test - Documentation

This project includes MQTT testing capabilities for order processing with both API endpoints and command-line interface.

## Features

✅ Test MQTT connection
✅ Publish dummy order data to MQTT broker
✅ Command-line interface for quick testing
✅ API endpoints for integration testing
✅ Customizable topics and order data

---

## Command Line Interface

### Basic Usage

Test MQTT by publishing a single dummy order:
```bash
php artisan mqtt:test-order
```

### With Custom Topic

Publish to a specific MQTT topic:
```bash
php artisan mqtt:test-order --topic=orders/pending
```

### Multiple Orders

Publish multiple orders at once:
```bash
php artisan mqtt:test-order --count=5
```

### Dry Run Mode

Test without actually connecting to MQTT (useful for testing the data generation):
```bash
php artisan mqtt:test-order --dry-run
```

### Combined Options

```bash
php artisan mqtt:test-order --topic=orders/new --count=10
```

Or with dry run:
```bash
php artisan mqtt:test-order --topic=orders/test --count=3 --dry-run
```

### Command Output Example

```
📡 Starting MQTT Order Test
Topic: orders/new
Orders to send: 1

🔄 Publishing order #1...
+------------+-------------------------+
| Field      | Value                   |
+------------+-------------------------+
| Order ID   | ORD-A8F3D5K2           |
| Customer   | John Doe               |
| Restaurant | Pizza Palace           |
| Amount     | $12.99                 |
| Status     | pending                |
| Items      | 1                      |
+------------+-------------------------+
✅ Order #1 published successfully!

🎉 All 1 order(s) published successfully to MQTT broker!
```

---

## API Endpoints

Base URL: `http://your-domain.com/api`

### 1. Test Connection

Check if MQTT broker connection is working.

**Endpoint:** `GET /mqtt-test/connection`

**Response:**
```json
{
    "success": true,
    "message": "MQTT connection is working",
    "broker": "mqtt.givni.in:1883",
    "timestamp": "2026-01-06 10:30:00"
}
```

**cURL Example:**
```bash
curl http://localhost:8000/api/mqtt-test/connection
```

---

### 2. Test Order (Dummy Data)

Publish dummy order data to MQTT broker.

**Endpoint:** `POST /mqtt-test/order`

**Parameters:**
- `topic` (optional): MQTT topic (default: "orders/new")
- `count` (optional): Number of orders to publish (default: 1)

**Request:**
```bash
curl -X POST http://localhost:8000/api/mqtt-test/order \
  -H "Content-Type: application/json" \
  -d '{
    "topic": "orders/new",
    "count": 2
  }'
```

**Response:**
```json
{
    "success": true,
    "message": "Successfully published 2 order(s) to MQTT broker",
    "topic": "orders/new",
    "orders": [
        {
            "order_id": "ORD-A8F3D5K2",
            "customer_id": 42,
            "customer_name": "John Doe",
            "customer_phone": "+11234567890",
            "restaurant_id": 15,
            "restaurant_name": "Pizza Palace",
            "order_amount": 12.99,
            "delivery_charge": 3.99,
            "total_tax_amount": 1.04,
            "payment_method": "card",
            "payment_status": "paid",
            "order_status": "pending",
            "order_type": "delivery",
            "delivery_address": {
                "street": "123 Main Street",
                "city": "New York",
                "state": "NY",
                "zip": "10012"
            },
            "items": [
                {
                    "name": "Margherita Pizza",
                    "price": 12.99,
                    "quantity": 1
                }
            ],
            "order_note": "Please ring the doorbell",
            "cooking_instruction": "Extra spicy",
            "scheduled": false,
            "created_at": "2026-01-06 10:30:00",
            "estimated_delivery_time": "2026-01-06 11:00:00"
        }
    ],
    "timestamp": "2026-01-06 10:30:00"
}
```

---

### 3. Test Custom Order

Publish custom order data to MQTT broker.

**Endpoint:** `POST /mqtt-test/custom-order`

**Parameters:**
- `topic` (required): MQTT topic
- `order_data` (required): Custom order data object

**Request:**
```bash
curl -X POST http://localhost:8000/api/mqtt-test/custom-order \
  -H "Content-Type: application/json" \
  -d '{
    "topic": "orders/priority",
    "order_data": {
        "order_id": "CUSTOM-001",
        "customer_name": "Custom Customer",
        "order_amount": 25.50,
        "order_status": "confirmed",
        "items": [
            {
                "name": "Custom Item",
                "price": 25.50,
                "quantity": 1
            }
        ]
    }
  }'
```

**Response:**
```json
{
    "success": true,
    "message": "Successfully published custom order to MQTT broker",
    "topic": "orders/priority",
    "order": {
        "order_id": "CUSTOM-001",
        "customer_name": "Custom Customer",
        "order_amount": 25.50,
        "order_status": "confirmed",
        "items": [
            {
                "name": "Custom Item",
                "price": 25.50,
                "quantity": 1
            }
        ]
    },
    "timestamp": "2026-01-06 10:30:00"
}
```

---

## Dummy Data Structure

The system generates realistic dummy orders with the following structure:

- **Order ID**: Random 8-character code (e.g., ORD-A8F3D5K2)
- **Customer**: Random from pool of 5 names
- **Restaurant**: Random from pool of 5 restaurants
- **Order Amount**: Calculated from selected items
- **Delivery Charge**: $3.99
- **Tax**: 8% of order amount
- **Payment Methods**: cash, card, or digital_wallet
- **Order Status**: pending, confirmed, processing, or picked_up
- **Items**: 1-3 random food items with prices

---

## MQTT Configuration

Current MQTT broker settings (from `config/mqtt-client.php`):

- **Broker**: mqtt.givni.in
- **Port**: 1883
- **Client ID**: 01234-id-6
- **Protocol**: MQTT 3.1

To change the broker settings, update the `.env` file:
```env
MQTT_HOST=mqtt.givni.in
MQTT_PORT=1883
MQTT_CLIENT_ID=your-client-id
```

---

## Testing Workflow

### Quick Test
```bash
# 1. Test connection
curl http://localhost:8000/api/mqtt-test/connection

# 2. Send a test order
php artisan mqtt:test-order
```

### Load Testing
```bash
# Send 100 orders via command line
php artisan mqtt:test-order --count=100 --topic=orders/load-test
```

### Integration Testing
```bash
# Test via API with Postman or curl
curl -X POST http://localhost:8000/api/mqtt-test/order \
  -H "Content-Type: application/json" \
  -d '{"topic": "orders/test", "count": 10}'
```

---

## Troubleshooting

### Connection Issues

If you get connection errors:

1. Check MQTT broker is running
2. Verify broker address in `.env` or `MqttService.php`
3. Check firewall rules for port 1883
4. Verify network connectivity

### Permission Issues

If you get permission errors:
```bash
chmod +x artisan
php artisan cache:clear
```

---

## Files Created

1. **Command**: `app/Console/Commands/TestMqttOrder.php`
2. **Controller**: `app/Http/Controllers/Api/V1/MqttTestController.php`
3. **Routes**: Added to `routes/api.php`

---

## Dependencies

Required packages (should already be installed):
- `php-mqtt/client`

Check if installed:
```bash
composer show php-mqtt/client
```

If not installed:
```bash
composer require php-mqtt/client
```

---

## Support

For issues or questions, check:
- MQTT Service: `app/Services/MqttService.php`
- MQTT Config: `config/mqtt-client.php`
- Laravel Logs: `storage/logs/laravel.log`
