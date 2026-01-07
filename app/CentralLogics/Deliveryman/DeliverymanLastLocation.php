<?php
namespace App\CentralLogics\Deliveryman;
use App\CentralLogics\Redis\RedisHelper;

class DeliverymanLastLocation
{
    public int $dmId;
    public Location $currentLocation;
    public ?Location $previousLocation;

    public function __construct(int $dmId, float $lat, float $lng, string $timestamp)
    {
        $this->dmId = $dmId;
        $this->currentLocation = new Location($lat, $lng, $timestamp);
        $this->previousLocation = null;
    }

    public static function fromArray(array $data): self
    {
        if (!isset($data['dmId'], $data['currentLocation']['lat'], $data['currentLocation']['lng'], $data['currentLocation']['timestamp'])) {
            throw new \InvalidArgumentException("Invalid data for DeliverymanLastLocation");
        }

        $instance = new self(
            $data['dmId'],
            $data['currentLocation']['lat'],
            $data['currentLocation']['lng'],
            $data['currentLocation']['timestamp']
        );

        if (isset($data['previousLocation'])) {
            $instance->previousLocation = new Location(
                $data['previousLocation']['lat'],
                $data['previousLocation']['lng'],
                $data['previousLocation']['timestamp']
            );
        }

        return $instance;
    }

    public function saveLastLocation(): bool
    {
        $date =  date('d-m-Y');
        $redis = new RedisHelper();
        $last = $this->getLastLocation();

        if ($last) {
            $this->setPreviousLocation(
                $last->currentLocation->lat,
                $last->currentLocation->lng,
                $last->currentLocation->timestamp
            );
        }
        return $redis->set("deliveryman:{$this->dmId}:last_location:{$date}",$this->toArray(),3600,true);
    }

    public function getLastLocation(): ?self
    {
        $redis = new RedisHelper();
        $date =  date('d-m-Y');
        $data = $redis->get("deliveryman:{$this->dmId}:last_location:{$date}", true);
 
        if (!$data || !is_array($data)) {
            return null;
        }

        try {
            return self::fromArray($data);
        } catch (\Throwable $e) {
            error_log("Failed to parse last location: " . $e->getMessage());
            return null;
        }
    }

    public function toArray(): array
    {
        return [
            'dmId' => $this->dmId,
            'currentLocation' => [
                'lat' => $this->currentLocation->lat,
                'lng' => $this->currentLocation->lng,
                'timestamp' => $this->currentLocation->timestamp
            ],
            'previousLocation' => $this->previousLocation ? [
                'lat' => $this->previousLocation->lat,
                'lng' => $this->previousLocation->lng,
                'timestamp' => $this->previousLocation->timestamp
            ] : null
        ];
    }

    public function toJson(): string
    {
        return json_encode($this->toArray());
    }

    public function setPreviousLocation(float $lat, float $lng, string $timestamp): void
    {
        $this->previousLocation = new Location($lat, $lng, $timestamp);
    }
}

class Location
{
    public float $lat;
    public float $lng;
    public string $timestamp;

    public function __construct(float $lat, float $lng, string $timestamp)
    {
        $this->lat = $lat;
        $this->lng = $lng;
        $this->timestamp = $timestamp;
    }
}
