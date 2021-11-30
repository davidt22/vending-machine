<?php

namespace App\Application\VendingMachine\ReturnCoins;

class ReturnCoinsRequest
{
    private string $machineName;

    public function __construct(string $machineName)
    {
        $this->machineName = $machineName;
    }

    public function getMachineName(): string
    {
        return $this->machineName;
    }
}