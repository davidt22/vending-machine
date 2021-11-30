<?php

namespace App\Application\Product\FindProducts;

class FindProductsRequest
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