<?php

namespace App\Application\Product\BuyProduct;

class BuyProductRequest
{
    private string $productName;
    private array $coins;
    private string $machineName;

    public function __construct(string $productName, array $coins, string $machineName)
    {
        $this->productName = $productName;
        $this->coins = $coins;
        $this->machineName = $machineName;
    }

    public function getProductName(): string
    {
        return $this->productName;
    }

    public function getCoins(): array
    {
        return $this->coins;
    }

    public function getMachineName(): string
    {
        return $this->machineName;
    }
}