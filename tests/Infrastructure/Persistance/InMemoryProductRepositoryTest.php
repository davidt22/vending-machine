<?php

namespace App\Tests\Infrastructure\Persistance;

use App\Application\VendingMachine\CreateVendingMachine\CreateVendingMachineRequest;
use App\Application\VendingMachine\CreateVendingMachine\CreateVendingMachineService;
use App\Domain\Model\Product\Product;
use App\Domain\Model\Product\ProductId;
use App\Domain\Model\Product\ProductName;
use App\Domain\Model\Product\ProductPrice;
use App\Domain\Model\Product\ProductQuantity;
use App\Infrastructure\Persistance\Product\InMemoryProductRepository;
use App\Infrastructure\Persistance\VendingMachine\InMemoryVendingMachineRepository;
use PHPUnit\Framework\TestCase;

class InMemoryProductRepositoryTest extends TestCase
{
    private CreateVendingMachineService $createVendingMachineService;
    private InMemoryProductRepository $inMemoryProductRepository;

    protected function setUp():void
    {
        parent::setUp();

        $this->inMemoryProductRepository = new InMemoryProductRepository();
        $inMemoryVendingMachineRepository = new InMemoryVendingMachineRepository();
        $this->createVendingMachineService = new CreateVendingMachineService($inMemoryVendingMachineRepository);
    }

    public function testItGeneratesNewIdentity()
    {
        $id = $this->inMemoryProductRepository->nextIdentity();

        $this->assertEquals(ProductId::class, get_class($id));
    }

    public function testItAddsNewProduct()
    {
        $request = new CreateVendingMachineRequest('machine', []);
        $vendingMachine = $this->createVendingMachineService->execute($request);

        $product = Product::create(
            new ProductId(),
            ProductName::fromValue(ProductName::SODA),
            ProductPrice::fromValue(0.1),
            ProductQuantity::fromValue(10),
            $vendingMachine
        );

        $vendingMachine->addProduct($product);

        $this->assertCount(1, $vendingMachine->products());
    }
}