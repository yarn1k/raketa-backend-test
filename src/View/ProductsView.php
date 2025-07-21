<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\View;

use Raketa\BackendTestTask\Repository\Entity\Product;
use Raketa\BackendTestTask\Repository\ProductRepository;

readonly class ProductsView
{
    public function __construct(
        private ProductRepository $productRepository
    ) {
    }

    public function toArray(int $categoryId): array
    {
        return array_map(
            fn (Product $product) => $product->toArray(),
            $this->productRepository->getByCategoryId($categoryId)
        );
    }
}