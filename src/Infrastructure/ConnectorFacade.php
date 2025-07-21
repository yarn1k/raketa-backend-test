<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Infrastructure;

use Redis;
use RedisException;
use Psr\Log\LoggerInterface;

class ConnectorFacade
{
    private string $host;
    private int $port;
    private ?string $password;
    private int $dbindex;
    protected Connector $connector;
    protected LoggerInterface $logger;

    public function __construct(string $host, int $port, ?string $password, int $dbindex, LoggerInterface $logger)
    {
        $this->host = $host;
        $this->port = $port;
        $this->password = $password;
        $this->dbindex = $dbindex;
        $this->logger = $logger;

        $this->build();
    }

    protected function build(): void
    {
        $redis = new Redis();

        try {
            $redis->connect($this->host, $this->port);

            if ($this->password !== null) {
                $redis->auth($this->password);
            }

            $redis->select($this->dbindex);

            $this->connector = new Connector($redis);
        } catch (RedisException $e) {
            $this->logger->error('Redis connection failed: ' . $e->getMessage());
        }
    }
}
