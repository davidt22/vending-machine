<?php

namespace App\Application\VendingMachine\InsertCoins;

class InsertCoinsRequest
{
    private string $machineName;
    private array $coins;

    public function __construct(string $machineName, array $coins)
    {
        $this->machineName = $machineName;
        $this->coins = $coins;
    }

    public function getMachineName(): string
    {
        return $this->machineName;
    }

    public function getCoins(): array
    {
        return $this->coins;
    }
}