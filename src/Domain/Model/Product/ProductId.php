<?php

namespace App\Domain\Model\Product;

use Ramsey\Uuid\Uuid;

final class ProductId
{
    private string $id;

    public function __construct(string $id = null)
    {
        $this->id = null === $id ? Uuid::uuid4()->toString() : $id;
    }

    public static function fromValue(string $value): ProductId
    {
        return new self($value);
    }

    public function id(): string
    {
        return $this->id;
    }

    public function __toString(): string
    {
        return $this->id();
    }
}