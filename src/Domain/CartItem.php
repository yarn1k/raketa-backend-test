<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Domain;

final readonly class CartItem
{
    public function __construct(
        private string $uuid,
        private string $productUuid,
        private float $price,
        private int $quantity,
    ) {
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getProductUuid(): string
    {
        return $this->productUuid;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getTotal(): float
    {
        return $this->price * $this->quantity;
    }

    public function toArray(): array
    {
        return [
            'uuid' => $this->uuid,
            'product_uuid' => $this->productUuid,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'total' => $this->getTotal(),
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            uuid: $data['uuid'],
            productUuid: $data['product_uuid'],
            price: (float) $data['price'],
            quantity: (int) $data['quantity'],
        );
    }
}
