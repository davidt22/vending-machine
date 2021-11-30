<?php

namespace App\Domain\Model\Product;

use App\Domain\Model\VendingMachine\VendingMachine;

class Product
{
    private ProductId $id;
    private ProductName $name;
    private ProductPrice $price;
    private ProductQuantity $quantity;
    private VendingMachine $vendingMachine;

    private function __construct(
        ProductId $id,
        ProductName $name,
        ProductPrice $price,
        ProductQuantity $quantity,
        VendingMachine $vendingMachine
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->quantity = $quantity;
        $this->vendingMachine = $vendingMachine;
    }

    public static function create(
        ProductId $id,
        ProductName $name,
        ProductPrice $price,
        ProductQuantity $quantity,
        VendingMachine $vendingMachine
    ): Product {
        return new self($id, $name, $price, $quantity, $vendingMachine);
    }

    public function id(): ProductId
    {
        return $this->id;
    }

    public function name(): ProductName
    {
        return $this->name;
    }

    public function price(): ProductPrice
    {
        return $this->price;
    }

    public function quantity(): ProductQuantity
    {
        return $this->quantity;
    }

    public function vendingMachine(): VendingMachine
    {
        return $this->vendingMachine;
    }

    public function __toString(): string
    {
        return $this->name->value() .' - ' . $this->price->value() . ' - ' . $this->quantity->value();
    }
}