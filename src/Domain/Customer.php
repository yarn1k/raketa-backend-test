<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Domain;

final readonly class Customer
{
    public function __construct(
        private int $id,
        private string $firstName,
        private string $lastName,
        private string $middleName,
        private string $email,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getMiddleName(): string
    {
        return $this->middleName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getFullName(): string
    {
        return trim("{$this->lastName} {$this->firstName} {$this->middleName}");
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'middle_name' => $this->middleName,
            'email' => $this->email,
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) $data['id'],
            firstName: $data['first_name'] ?? '',
            lastName: $data['last_name'] ?? '',
            middleName: $data['middle_name'] ?? '',
            email: $data['email'],
        );
    }
}