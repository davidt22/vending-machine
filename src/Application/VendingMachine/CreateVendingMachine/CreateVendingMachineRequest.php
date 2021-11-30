<?php

namespace App\Application\VendingMachine\CreateVendingMachine;

class CreateVendingMachineRequest
{
    private string $name;
    private array $coins;

    public function __construct(string $name, array $coins)
    {
        $this->name = $name;
        $this->coins = $coins;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCoins(): array
    {
        return $this->coins;
    }
}