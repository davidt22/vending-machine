<?php

namespace App\Application\Product\BuyProduct;

use App\Application\VendingMachine\GetVendingMachine\GetVendingMachineRequest;
use App\Application\VendingMachine\GetVendingMachine\GetVendingMachineService;
use App\Domain\Model\Coin\Coin;
use App\Domain\Model\Product\Product;
use App\Domain\Model\Product\ProductRepositoryInterface;
use App\Domain\Model\VendingMachine\NotEnoughCoinsException;
use App\Domain\Model\VendingMachine\VendingMachine;
use App\Domain\Model\VendingMachine\VendingMachineName;

class BuyProductService
{
    private ProductRepositoryInterface $productRepository;
    private GetVendingMachineService $getVendingMachineService;

    public function __construct(ProductRepositoryInterface $productRepository, GetVendingMachineService $getVendingMachineService)
    {
        $this->productRepository = $productRepository;
        $this->getVendingMachineService = $getVendingMachineService;
    }

    public function execute(BuyProductRequest $request): array
    {
        $vendingMachine = $this->getVendingMachineService->execute(
            new GetVendingMachineRequest($request->getMachineName())
        );

        $products = $vendingMachine->products();
        $coins = $request->getCoins();

        /** @var Product $product */
        foreach ($products as $product) {

            $selectedProductName = $request->getProductName();

            $existsProduct = $product->name()->value() === $selectedProductName;
            $isAvailable = $product->quantity()->value() > 0;

            if ($existsProduct && $isAvailable) {
                $vendingMachine->insertCoins($coins);
                $insertedCoinsValue = $vendingMachine->insertedCoinsValue();

                // Restamos cantidad de producto
                // Entregamos el producto y cambio
                // Vaciamos monedero
                $productPriceValue = $product->price()->value();
                if ($insertedCoinsValue >= $productPriceValue) {
                    $product->quantity()->sub(1);

                    $changeCoins = $this->returnChange($insertedCoinsValue, $productPriceValue, $vendingMachine);

                    $vendingMachine->setCurrentlyInsertedCoins([]);

                    return ['product' => $product->name()->value(), 'change' => $changeCoins];
                } else {
                    throw new NotEnoughCoinsException('Not enough coins to buy this product');
                }
            }
        }

        return [];
    }

    private function returnChange(float $insertedCoinsValue, float $productPriceValue, VendingMachine $vendingMachine): array
    {
        return $this->search($vendingMachine->availableChange(), $insertedCoinsValue, $productPriceValue, $vendingMachine);
    }

    private function search(
        array $availableChange,
        float $insertedCoinsValue,
        float $productPriceValue,
        VendingMachine $vendingMachine,
        int $index = null
    ): array {
        $diff = $insertedCoinsValue - $productPriceValue;
        $returnCoins = [];
        $sum = 0;

        $sumAvailableChange = $vendingMachine->availableChangeValue();

        if ($sumAvailableChange >= $diff){
            $index = $index == null ? count($availableChange) -1 : $index;
            for ($i = $index; $i >= 0 && $sum < $diff; $i--) {
                /** @var Coin $coin */
                $coin = $availableChange[$i];

                if ($sum + $coin->value()->value() > $diff) {
                    $this->search($availableChange, $insertedCoinsValue, $productPriceValue, $vendingMachine, $index-1);
                } elseif ($sum + $coin->value()->value() < $diff) {
                    $sum += $coin->value()->value();
                    $returnCoins[] = $coin;
                } else {
                    $returnCoins[] = $coin;
                    break;
                }
            }
        }

        return $returnCoins;
    }
}