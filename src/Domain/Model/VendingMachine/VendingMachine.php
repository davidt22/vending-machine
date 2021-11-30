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
    private array $currentlyInsertedCoins;

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
        $this->currentlyInsertedCoins = [];
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

    public function insertCoins(array $coins)
    {
        /** @var Coin $coin */
        foreach ($coins as $coin) {
            $this->currentlyInsertedCoins[] = $coin;
            $this->availableChange[] = $coin;
        }
    }

    public function insertedCoinsValue(): float
    {
        $insertedCoins = 0;
        if (!empty($this->currentlyInsertedCoins)) {
            /** @var Coin $coin */
            foreach ($this->currentlyInsertedCoins as $coin) {
                $insertedCoins += $coin->value()->value();
            }
        }
        
        return $insertedCoins;
    }

    // Devuelve el dinero introducido para la compra de un producto
    public function returnInsertedCoins()
    {
        /** @var Coin $insertedCoins */
        foreach ($this->currentlyInsertedCoins as $keyInserted => $insertedCoins) {

            /** @var Coin $availableChange */
            foreach ($this->availableChange as $keyAvailable => $availableChange) {

                if ($availableChange->equals($insertedCoins)) {
                    unset($this->availableChange[$keyAvailable]);
                    unset($this->currentlyInsertedCoins[$keyInserted]);
                }
            }
        }
    }

    public function availableChange(): array
    {
        return $this->availableChange;
    }

    public function currentlyInsertedCoins(): array
    {
        return $this->currentlyInsertedCoins;
    }

    public function setCurrentlyInsertedCoins(array $currentlyInsertedCoins): void
    {
        $this->currentlyInsertedCoins = $currentlyInsertedCoins;
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