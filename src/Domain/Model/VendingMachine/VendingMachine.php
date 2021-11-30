<?php

namespace App\Domain\Model\VendingMachine;

use App\Domain\Model\Coin\Coin;
use App\Domain\Model\Product\Product;
use App\Domain\Model\Product\ProductException;
use App\Domain\Model\Product\ProductNotFoundException;

class VendingMachine
{
    private VendingMachineId $id;
    private VendingMachineName $name;
    private array $products;

    /** @var Coin[] */
    private array $availableChange;

    /** @var Coin[] */
    private array $currentlyInsertedMoney;

    private function __construct(
        VendingMachineId $id,
        VendingMachineName $name,
        array $coins,
        array $products
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->products = $products;
        $this->availableChange = $coins;
        $this->currentlyInsertedMoney = [];
    }

    public static function create(
        VendingMachineId $id,
        VendingMachineName $name,
        array $coins,
        array $products
    ): VendingMachine {
        return new self($id, $name, $coins, $products);
    }

    public function id(): VendingMachineId
    {
        return $this->id;
    }

    public function name(): VendingMachineName
    {
        return $this->name;
    }

    public function products(): array
    {
        return $this->products;
    }

    public function addProduct(Product $product)
    {
        $this->products[] = $product;
    }

    public function insertMoney(array $coins)
    {
        /** @var Coin $coin */
        foreach ($coins as $coin) {
            $this->currentlyInsertedMoney[] = $coin;
            $this->availableChange[] = $coin;
        }
    }

    public function insertedMoneyValue(): float
    {
        $insertedMoney = 0;
        if (!empty($this->currentlyInsertedMoney)) {
            /** @var Coin $coin */
            foreach ($this->currentlyInsertedMoney as $coin) {
                $insertedMoney += $coin->value()->value();
            }
        }
        
        return $insertedMoney;
    }

    // Devuelve el dinero introducido para la compra de un producto
    public function returnInsertedMoney()
    {
        /** @var Coin $insertedMoney */
        foreach ($this->currentlyInsertedMoney as $keyInserted => $insertedMoney) {

            /** @var Coin $availableChange */
            foreach ($this->availableChange as $keyAvailable => $availableChange) {

                if ($availableChange->equals($insertedMoney)) {
                    unset($this->availableChange[$keyAvailable]);
                    unset($this->currentlyInsertedMoney[$keyInserted]);
                }
            }
        }
    }

    public function availableChange(): array
    {
        return $this->availableChange;
    }

    public function currentlyInsertedMoney(): array
    {
        return $this->currentlyInsertedMoney;
    }

    public function setCurrentlyInsertedMoney(array $currentlyInsertedMoney): void
    {
        $this->currentlyInsertedMoney = $currentlyInsertedMoney;
    }

    public function availableChangeValue(): float
    {
        $change = 0;
        /** @var Coin $coin */
        foreach ($this->availableChange as $coin) {
            $change += $coin->value()->value();
        }

        return $change;
    }

}