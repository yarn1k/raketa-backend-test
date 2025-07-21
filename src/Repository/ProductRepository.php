<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Repository;

use Doctrine\DBAL\Connection;
use Raketa\BackendTestTask\Repository\Entity\Product;

class ProductRepository
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getByUuid(string $uuid): Product
    {
        $row = $this->connection->fetchAssociative(
            'SELECT * FROM products WHERE uuid = :uuid',
            ['uuid' => $uuid]
        );

        if (empty($row)) {
            throw new RuntimeException("Product with uuid '{$uuid}' not found");
        }

        return $this->make($row);
    }

    public function getByCategoryId(int $categoryId): array
    {
        $rows = $this->connection->fetchAllAssociative(
            'SELECT * FROM products WHERE is_active = 1 AND category_id = :categoryId ORDER BY name ASC',
            ['categoryId' => $categoryId]
        );

        return array_map(
            fn (array $row): Product => $this->make($row),
            $rows
        );
    }

    public function make(array $row): Product
    {
        return new Product(
            (int)$row['id'],
            $row['uuid'],
            (bool)$row['is_active'],
            (int)$row['category_id'],
            $row['name'],
            $row['description'],
            $row['thumbnail'],
            (float)$row['price'],
        );
    }
}
