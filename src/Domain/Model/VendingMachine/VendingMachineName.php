<?php

namespace App\Domain\Model\VendingMachine;

final class VendingMachineName
{
    private string $value;

    public function __construct(string $value)
    {
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

    public function equals(VendingMachineName $name): bool
    {
        return $this->value === $name->value();
    }
}