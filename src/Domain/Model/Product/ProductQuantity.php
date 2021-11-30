<?php

namespace App\Domain\Model\Product;

final class ProductQuantity
{
    private int $value;

    private function __construct(int $value)
    {
        $this->value = $value;
    }

    public static function fromValue(int $value): self
    {
        return new self($value);
    }

    public function value(): int
    {
        return $this->value;
    }

    public function add(int $value)
    {
        $this->value += $value;
    }

    /**
     * @throws ProductQuantityEmptyException
     */
    public function sub(int $value)
    {
        if ($this->value <= 0) {
            throw new ProductQuantityEmptyException('There are no available products');
        }

        $this->value -= $value;
    }
}