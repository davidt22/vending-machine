<?php

namespace App\Domain\Model\Product;

final class ProductName
{
    const WATER = 'Water';
    const JUICE = 'Juice';
    const SODA = 'Soda';

    const AVAILABLE_NAMES = [
        self::WATER,
        self::JUICE,
        self::SODA
    ];

    private string $value;

    private function __construct(string $value)
    {
        if (!$this->validate($value)) {
            throw new ProductNameException('Invalid product name');
        }

        $this->value = $value;
    }

    public static function fromValue(string $value): self
    {
        return new self($value);
    }

    public function value(): string
    {
        return $this->value;
    }

    private function validate(string $value): bool
    {
        return in_array($value, self::AVAILABLE_NAMES);
    }

    public function equals(ProductName $productName): bool
    {
        return $this->value === $productName->value();
    }
}