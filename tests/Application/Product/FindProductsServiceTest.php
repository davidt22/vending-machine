<?php

namespace App\Tests\Application\Product;

use App\Application\Product\FindProducts\FindProductsRequest;
use App\Application\Product\FindProducts\FindProductsService;
use App\Application\VendingMachine\CreateVendingMachine\CreateVendingMachineRequest;
use App\Application\VendingMachine\CreateVendingMachine\CreateVendingMachineService;
use App\Application\VendingMachine\GetVendingMachine\GetVendingMachineService;
use App\Domain\Model\Product\Product;
use App\Domain\Model\Product\ProductId;
use App\Domain\Model\Product\ProductName;
use App\Domain\Model\Product\ProductPrice;
use App\Domain\Model\Product\ProductQuantity;
use App\Domain\Model\Product\ProductRepositoryInterface;
use App\Domain\Model\VendingMachine\VendingMachine;
use App\Domain\Model\VendingMachine\VendingMachineId;
use App\Domain\Model\VendingMachine\VendingMachineName;
use App\Domain\Model\VendingMachine\VendingMachineRepositoryInterface;
use App\Infrastructure\Persistance\Product\InMemoryProductRepository;
use App\Infrastructure\Persistance\VendingMachine\InMemoryVendingMachineRepository;
use PHPUnit\Framework\TestCase;

class FindProductsServiceTest extends TestCase
{
    const MACHINE = 'machine';
    private FindProductsService $findProductsService;
    private CreateVendingMachineService $createVendingMachineService;

    protected function setUp():void
    {
        parent::setUp();

        $vendingMachineRepository = new InMemoryVendingMachineRepository();
        $getVendingMachineService = new GetVendingMachineService($vendingMachineRepository);
        $this->findProductsService = new FindProductsService($getVendingMachineService);
        $this->createVendingMachineService = new CreateVendingMachineService($vendingMachineRepository);
    }

    public function testItFindProductsSuccess()
    {
        $machine = $this->createVendingMachineService->execute(new CreateVendingMachineRequest(
            self::MACHINE,
            []
        ));

        $machine->addProduct(Product::create(
            new ProductId(),
            ProductName::fromValue(ProductName::WATER),
            ProductPrice::fromValue(0.1),
            ProductQuantity::fromValue(10),
            $machine
        ));

        $machine->addProduct(
            Product::create(
                new ProductId(),
                ProductName::fromValue(ProductName::SODA),
                ProductPrice::fromValue(0.15),
                ProductQuantity::fromValue(10),
                $machine
            )
        );

        $request = new FindProductsRequest('machine');
        $products = $this->findProductsService->execute($request);

        $this->assertCount(2, $products);

        /** @var Product $aProduct */
        $aProduct = $products[0];
        $this->assertEquals(ProductName::WATER, $aProduct->name()->value());
        $this->assertEquals(0.1, $aProduct->price()->value());
        $this->assertEquals(10, $aProduct->quantity()->value());
        $this->assertEquals('machine', $aProduct->vendingMachine()->name()->value());
    }

    public function testItReturnsEmptyProducts()
    {
        $machine = $this->createVendingMachineService->execute(new CreateVendingMachineRequest(
            self::MACHINE,
            []
        ));

        $request = new FindProductsRequest(self::MACHINE);
        $products = $this->findProductsService->execute($request);

        $this->assertCount(0, $products);
    }
}
