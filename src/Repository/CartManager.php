<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Repository;

use Psr\Log\LoggerInterface;
use Raketa\BackendTestTask\Domain\Cart;
use Raketa\BackendTestTask\Infrastructure\ConnectorFacade;
use Raketa\BackendTestTask\Infrastructure\ConnectorException;

class CartManager extends ConnectorFacade
{
    private const REDIS_CART_DB_INDEX = 1;
    private const REDIS_CART_TTL_SECONDS = 60 * 60 * 24;

    public function __construct(string $host, int $port, ?string $password, LoggerInterface $logger)
    {
        parent::__construct($host, $port, $password, self::REDIS_CART_DB_INDEX, $logger);
    }

    public function saveCart(Cart $cart): void
    {
        try {
            $this->connector->set(
                $cart->getUuid(),
                json_encode($cart->toArray()),
                self::REDIS_CART_TTL_SECONDS
            );
        } catch (ConnectorException $e) {
            $this->logger->error('Failed to save cart', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    public function getCart(): ?Cart
    {
        $sessionId = session_id();

        try {
            $cart = $this->connector->get($sessionId);

            if ($cart instanceof Cart) {
                return $cart;
            }
        } catch (ConnectorException $e) {
            $this->logger->error('Failed to get cart', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }

        return null;
    }
}
