<?php

namespace App\Application\VendingMachine\GetVendingMachine;

class GetVendingMachineRequest
{
    private string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }
}