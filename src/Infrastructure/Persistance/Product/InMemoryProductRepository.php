<?php

namespace App\Infrastructure\Persistance\Product;

use App\Domain\Model\Product\Product;
use App\Domain\Model\Product\ProductId;
use App\Domain\Model\Product\ProductName;
use App\Domain\Model\Product\ProductRepositoryInterface;
use App\Domain\Model\VendingMachine\VendingMachine;

class InMemoryProductRepository implements ProductRepositoryInterface
{
    public function nextIdentity(): ProductId
    {
        return new ProductId();
    }

    public function getByNameOrNull(ProductName $productName, VendingMachine $machine): ?Product
    {
        /** @var Product $product */
        foreach ($machine->products() as $product) {
            if ($product->name()->equals($productName)) {
                return $product;
            }
        }

        return null;
    }
}