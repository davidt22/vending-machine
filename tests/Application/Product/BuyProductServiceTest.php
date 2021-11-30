<?php

namespace App\Tests\Application\Product;

use App\Application\Product\BuyProduct\BuyProductRequest;
use App\Application\Product\BuyProduct\BuyProductService;
use App\Application\VendingMachine\CreateVendingMachine\CreateVendingMachineRequest;
use App\Application\VendingMachine\CreateVendingMachine\CreateVendingMachineService;
use App\Application\VendingMachine\GetVendingMachine\GetVendingMachineService;
use App\Domain\Model\Coin\Coin;
use App\Domain\Model\Coin\CoinId;
use App\Domain\Model\Coin\CoinValue;
use App\Domain\Model\Product\Product;
use App\Domain\Model\Product\ProductId;
use App\Domain\Model\Product\ProductName;
use App\Domain\Model\Product\ProductPrice;
use App\Domain\Model\Product\ProductQuantity;
use App\Domain\Model\Product\ProductRepositoryInterface;
use App\Domain\Model\VendingMachine\NotEnoughCoinsException;
use App\Domain\Model\VendingMachine\VendingMachineRepositoryInterface;
use App\Infrastructure\Persistance\Product\InMemoryProductRepository;
use App\Infrastructure\Persistance\VendingMachine\InMemoryVendingMachineRepository;
use PHPUnit\Framework\TestCase;

class BuyProductServiceTest extends TestCase
{
    const MACHINE = 'machine';
    private BuyProductService $buyProductService;
    private CreateVendingMachineService $createVendingMachineService;

    protected function setUp():void
    {
        parent::setUp();

        $productRepository = new InMemoryProductRepository();
        $vendingMachineRepository = new InMemoryVendingMachineRepository();
        $getVendingMachineService = new GetVendingMachineService($vendingMachineRepository);
        $this->buyProductService = new BuyProductService($productRepository, $getVendingMachineService);
        $this->createVendingMachineService = new CreateVendingMachineService($vendingMachineRepository);
    }

    public function testItBuysAProductSuccess()
    {
        $machine = $this->createVendingMachineService->execute(new CreateVendingMachineRequest(
            self::MACHINE,
            []
        ));

        $machine->addProduct(
            Product::create(
                new ProductId(),
                ProductName::fromValue(ProductName::WATER),
                ProductPrice::fromValue(CoinValue::TWEENTY_FIVE_CENTS),
                ProductQuantity::fromValue(1),
                $machine
            )
        );

        $coins = [
            Coin::create(
                new CoinId(),
                CoinValue::fromValue(CoinValue::TWEENTY_FIVE_CENTS)
            )
        ];

        $request = new BuyProductRequest(ProductName::WATER, $coins, self::MACHINE);
        $result = $this->buyProductService->execute($request);

        $this->assertEquals(ProductName::WATER, $result['product']);
        $this->assertEmpty($result['change']);
    }

    public function testItFailsBuyingAProduct()
    {
        $this->expectException(NotEnoughCoinsException::class);

        $machine = $this->createVendingMachineService->execute(new CreateVendingMachineRequest(
            self::MACHINE,
            []
        ));

        $machine->addProduct(Product::create(
            new ProductId(),
            ProductName::fromValue(ProductName::WATER),
            ProductPrice::fromValue(CoinValue::TWEENTY_FIVE_CENTS),
            ProductQuantity::fromValue(1),
            $machine
        ));

        $coins = [
            Coin::create(
                new CoinId(),
                CoinValue::fromValue(CoinValue::TEN_CENTS)
            )
        ];

        $request = new BuyProductRequest(ProductName::WATER, $coins, self::MACHINE);
        $this->buyProductService->execute($request);
    }
}
