<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\View;

use Raketa\BackendTestTask\Domain\Cart;
use Raketa\BackendTestTask\Repository\ProductRepository;

readonly class CartView
{
    public function __construct(
        private ProductRepository $productRepository
    ) {
    }

    public function toArray(Cart $cart): array
    {
        $result = $cart->toArray();

        foreach ($result['items'] as &$item) {
            $product = $this->productRepository->getByUuid($item['product_uuid']);
            $item['product'] = $product->toArray();
            unset($item['product_uuid']);
        }

        return $result;
    }
}
