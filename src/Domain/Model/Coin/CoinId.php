<?php

namespace App\Domain\Model\Coin;

use Ramsey\Uuid\Uuid;

final class CoinId
{
    private string $id;

    public function __construct(string $id = null)
    {
        $this->id = null === $id ? Uuid::uuid4()->toString() : $id;
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