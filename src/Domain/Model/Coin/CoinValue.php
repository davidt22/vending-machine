<?php

namespace App\Domain\Model\Coin;

final class CoinValue
{
    const FIVE_CENTS = 0.05;
    const TEN_CENTS = 0.10;
    const TWEENTY_FIVE_CENTS = 0.25;
    const ONE_EUR = 1.0;

    const VALID_VALUES = [
        self::FIVE_CENTS,
        self::TEN_CENTS,
        self::TWEENTY_FIVE_CENTS,
        self::ONE_EUR
    ];

    private float $value;

    private function __construct(float $value)
    {
        if (!$this->validate($value)) {
            throw new InvalidCoinValueException('This value is not a coin valid');
        }

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

    private function validate(float $value): bool
    {
        return in_array($value, self::VALID_VALUES);
    }
}