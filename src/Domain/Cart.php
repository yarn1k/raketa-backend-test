<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Domain;

final class Cart
{
    public function __construct(
        readonly private string $uuid,
        readonly private Customer $customer,
        readonly private string $paymentMethod,
        private array $items,
    ) {
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getCustomer(): Customer
    {
        return $this->customer;
    }

    public function getPaymentMethod(): string
    {
        return $this->paymentMethod;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function addItem(CartItem $item): void
    {
        $this->items[] = $item;
    }

    public function getTotal(): float
    {
        return array_reduce(
            $this->items,
            fn (float $carry, CartItem $item) => $carry + $item->getTotal(),
            0
        );
    }

    public function toArray(): array
    {
        return [
            'uuid' => $this->uuid,
            'customer' => $this->customer->toArray(),
            'payment_method' => $this->paymentMethod,
            'items' => array_map(fn (CartItem $item) => $item->toArray(), $this->items),
            'total' => $this->getTotal(),
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            uuid: $data['uuid'],
            customer: Customer::fromArray($data['customer']),
            paymentMethod: $data['payment_method'],
            items: array_map(fn(array $item) => CartItem::fromArray($item), $data['items'] ?? []),
        );
    }
}
