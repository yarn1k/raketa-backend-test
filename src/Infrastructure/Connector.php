<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Infrastructure;

use Raketa\BackendTestTask\Domain\Cart;
use Redis;
use RedisException;

class Connector
{
    private Redis $redis;

    public function __construct(Redis $redis)
    {
        $this->redis = $redis;
    }

    /**
     * @throws ConnectorException
     */
    public function get(string $key): ?Cart
    {
        try {
            $data = $this->redis->get($key);

            if (!$data) {
                return null;
            }

            $decoded = json_decode($data, true);

            if (!is_array($decoded)) {
                return null;
            }

            return Cart::fromArray($decoded);
        } catch (RedisException $e) {
            throw new ConnectorException('Connector error: ' . $e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @throws ConnectorException
     */
    public function set(string $key, string $value, int $ttl): void
    {
        try {
            $this->redis->setex($key, $ttl, $value);
        } catch (RedisException $e) {
            throw new ConnectorException('Connector error: ' . $e->getMessage(), $e->getCode(), $e);
        }
    }

    public function has(string $key): bool
    {
        return $this->redis->exists($key) > 0;
    }
}
