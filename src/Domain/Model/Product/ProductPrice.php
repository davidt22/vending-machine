<?php

namespace App\Domain\Model\Product;

final class ProductPrice
{
    private float $value;

    private function __construct(float $value)
    {
        $this->value = $value;
    }

    public static function fromValue(float $value): self
    {
        return new self($value);
    }

    public function value(): float
    {
        return $this->value;
    }
}