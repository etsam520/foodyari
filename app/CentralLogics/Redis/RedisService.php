<?php

namespace App\CentralLogics\Redis;

use Predis\Client as RedisClient;

class RedisService
{
    protected $redis;

    public function __construct()
    {
        $this->redis = new RedisClient();
    }

    public function get($key, $default = null)
    {
        $value = $this->redis->get($key);
        return $value !== null ? $value : $default;
    }

    public function set($key, $value, $ttl = null)
    {
        return $ttl
            ? $this->redis->setex($key, $ttl, $value)
            : $this->redis->set($key, $value);
    }

    public function getJson($key, $default = [])
    {
        $value = $this->get($key);
        return $value ? json_decode($value, true) : $default;
    }

    public function setJson($key, $arrayValue, $ttl = null)
    {
        return $this->set($key, json_encode($arrayValue), $ttl);
    }

    public function appendUniqueToJsonArray($key, $item)
    {
        $array = $this->getJson($key, []);
        if (!in_array($item, $array)) {
            $array[] = $item;
            $this->setJson($key, $array);
        }
        return $array;
    }
}
