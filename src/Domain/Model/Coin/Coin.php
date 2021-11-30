<?php

namespace App\Domain\Model\Coin;

class Coin
{
    private CoinId $id;
    private CoinValue $value;

    private function __construct(CoinId $id, CoinValue $value)
    {
        $this->id = $id;
        $this->value = $value;
    }

    public static function create(CoinId $id, CoinValue $value): Coin
    {
        return new self($id, $value);
    }

    public function id(): CoinId
    {
        return $this->id;
    }

    public function value(): CoinValue
    {
        return $this->value;
    }

    public function equals(Coin $coin): bool
    {
        return $this->id === $coin->id() && $this->value === $coin->value();
    }

    public function __toString(): string
    {
        return $this->value->value();
    }
}