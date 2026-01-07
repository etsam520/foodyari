# MQTT Order Test - Quick Reference

## ⚡ Quick Start

```bash
# Test with dry run (no actual MQTT connection needed)
php artisan mqtt:test-order --dry-run

# Send 1 order to MQTT broker
php artisan mqtt:test-order

# Send 5 orders
php artisan mqtt:test-order --count=5

# Custom topic
php artisan mqtt:test-order --topic=orders/priority
```

## 🌐 API Endpoints

```bash
# Test connection
curl http://localhost:8000/api/mqtt-test/connection

# Send dummy order
curl -X POST http://localhost:8000/api/mqtt-test/order \
  -H "Content-Type: application/json" \
  -d '{"topic": "orders/new", "count": 1}'

# Send custom order
curl -X POST http://localhost:8000/api/mqtt-test/custom-order \
  -H "Content-Type: application/json" \
  -d '{
    "topic": "orders/custom",
    "order_data": {
      "order_id": "TEST-001",
      "customer_name": "Test User",
      "order_amount": 50.00
    }
  }'
```

## 📋 Command Options

| Option | Default | Description |
|--------|---------|-------------|
| `--topic` | orders/new | MQTT topic to publish to |
| `--count` | 1 | Number of orders to generate |
| `--dry-run` | false | Show data without publishing |

## 📂 Files Created

- ✅ `/app/Console/Commands/TestMqttOrder.php` - CLI command
- ✅ `/app/Http/Controllers/Api/V1/MqttTestController.php` - API controller
- ✅ `/routes/api.php` - API routes added
- ✅ `/MQTT_TEST_README.md` - Full documentation

## 🎯 Example Order Data

```json
{
  "order_id": "ORD-A8F3D5K2",
  "customer_name": "John Doe",
  "restaurant_name": "Pizza Palace",
  "order_amount": 12.99,
  "order_status": "pending",
  "payment_method": "card",
  "items": [
    {
      "name": "Margherita Pizza",
      "price": 12.99,
      "quantity": 1
    }
  ]
}
```

## 🔧 Troubleshooting

**Connection timeout?**
- Use `--dry-run` to test without connecting
- Check MQTT broker is running
- Verify broker address in `app/Services/MqttService.php`

**Command not found?**
```bash
php artisan cache:clear
php artisan list | grep mqtt
```

---

See [MQTT_TEST_README.md](MQTT_TEST_README.md) for complete documentation.
