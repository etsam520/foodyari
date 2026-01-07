<?php

namespace App\CentralLogics\Redis;

use Illuminate\Support\Facades\DB;
use Predis\Client as RedisClient;
use Exception;
use Predis\Connection\ConnectionException;

class RedisHelper
{
    protected $redisClient;
    protected $fallbackToDatabase = true;
    protected $defaultTtl = 3600; // 1 hour default expiration
    protected $keyPrefix;

    public function __construct(array $config = [])
    {
        // \Predis\Autoloader::register();
        
        // $this->redisClient = new \Predis\Client(array_merge([
        //     'scheme' => 'tcp',
        //     'host'   => env('REDIS_HOST', '127.0.0.1'),
        //     'port'   => env('REDIS_PORT', 6379),
        //     'password' => env('REDIS_PASSWORD', null),
        //     'database' => env('REDIS_DB', 0),
        // ], $config));

        \Predis\Autoloader::register();
        $this->redisClient = new \Predis\Client([
            'scheme' => 'tcp',
            'host'   => '127.0.0.1',
            'port'   => 6379,
        ]);
        
        $this->keyPrefix = env('APP_ENV') . ':';
    }

    /**
     * Prefix the key with environment
     */
    protected function prefixKey(string $key): string
    {
        return $this->keyPrefix . ltrim($key, ':');
    }

    /**
     * Set value with optional expiration and JSON encoding
     */
    public function set(
        string $key,
        $value,
        ?int $ttl = null,
        bool $encodeJson = false
    ): bool {
        $prefixedKey = $this->prefixKey($key);
        $valueToStore = $encodeJson ? json_encode($value) : $value;
        $ttl = $ttl ?? $this->defaultTtl;

        try {
            if ($this->isConnected()) {
                if ($ttl > 0) {
                    return (bool)$this->redisClient->setex($prefixedKey, $ttl, $valueToStore);
                }
                return (bool)$this->redisClient->set($prefixedKey, $valueToStore);
            }
        } catch (ConnectionException $e) {
            report($e);
        }

        return $this->fallbackToDatabase
            ? $this->setDatabaseValue($key, $valueToStore)
            : false;
    }

    /**
     * Get value with optional JSON decoding
     */
    public function get(string $key, bool $decodeJson = false)
    {
        $prefixedKey = $this->prefixKey($key);
        
        try {
            if ($this->isConnected()) {
                $value = $this->redisClient->get($prefixedKey);
                if ($value !== null) {
                    return $decodeJson ? json_decode($value, true) : $value;
                }
            }
        } catch (ConnectionException $e) {
            report($e);
        }

        if ($this->fallbackToDatabase) {
            $value = $this->getDatabaseValue($key);
            return $decodeJson && $value ? json_decode($value, true) : $value;
        }

        return null;
    }

    /**
     * Check if key exists
     */
    public function exists(string $key): bool
    {
        $prefixedKey = $this->prefixKey($key);
        
        try {
            return $this->isConnected()
                ? (bool)$this->redisClient->exists($prefixedKey)
                : $this->fallbackToDatabase && $this->getDatabaseValue($key) !== null;
        } catch (ConnectionException $e) {
            report($e);
            return false;
        }
    }

    /**
     * Delete one or more keys
     */
    public function delete(...$keys): int
    {
        $prefixedKeys = array_map(function($key) {
            return $this->prefixKey($key);
        }, $keys);
        
        try {
            if ($this->isConnected()) {
                return $this->redisClient->del($prefixedKeys);
            }
        } catch (ConnectionException $e) {
            report($e);
        }

        if ($this->fallbackToDatabase) {
            return $this->deleteDatabaseValues($keys);
        }

        return 0;
    }

    /**
     * Check Redis connection status
     */
    public function isConnected(): bool
    {
        try {
            return $this->redisClient->ping() == 'PONG';
        } catch (ConnectionException $e) {
            return false;
        }
    }

    /**
     * Set default TTL for new keys
     */
    public function setDefaultTtl(int $seconds): void
    {
        $this->defaultTtl = $seconds;
    }

    /**
     * Enable/disable database fallback
     */
    public function setFallback(bool $enabled): void
    {
        $this->fallbackToDatabase = $enabled;
    }

    /**
     * Database fallback methods
     */
    protected function setDatabaseValue(string $key, $value): bool
    {
        $parsed = $this->parseKey($key);
        if (!$parsed) return false;

        try {
            DB::table('redis_fallback')
                ->updateOrInsert(
                    ['key' => $key],
                    [
                        'identifier' => $parsed['identifier'],
                        'field' => $parsed['field'],
                        'value' => $value,
                        'updated_at' => now()
                    ]
                );
            return true;
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }

    protected function getDatabaseValue(string $key)
    {
        try {
            $record = DB::table('redis_fallback')
                ->where('key', $key)
                ->first();

            return $record->value ?? null;
        } catch (Exception $e) {
            report($e);
            return null;
        }
    }

    protected function deleteDatabaseValues(array $keys): int
    {
        try {
            return DB::table('redis_fallback')
                ->whereIn('key', $keys)
                ->delete();
        } catch (Exception $e) {
            report($e);
            return 0;
        }
    }

    /**
     * Parse Redis key into components
     */
    protected function parseKey(string $key): ?array
    {
        $parts = explode(':', $key);
        if (count($parts) < 3) return null;

        return [
            'identifier' => $parts[1],
            'field' => implode(':', array_slice($parts, 2))
        ];
    }

    /**
     * Flush all keys for current environment
     */
    public function flushAll(): bool
    {
        try {
            if ($this->isConnected()) {
                $keys = $this->redisClient->keys($this->keyPrefix . '*');
                if (!empty($keys)) {
                    return (bool)$this->redisClient->del($keys);
                }
                return true;
            }
        } catch (ConnectionException $e) {
            report($e);
        }
        return false;
    }
}