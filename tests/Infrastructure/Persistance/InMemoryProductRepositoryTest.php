<?php

namespace App\Tests\Infrastructure\Persistance;

use App\Domain\Model\Product\Product;
use App\Domain\Model\Product\ProductId;
use App\Domain\Model\Product\ProductName;
use App\Domain\Model\Product\ProductPrice;
use App\Domain\Model\Product\ProductQuantity;
use App\Domain\Model\VendingMachine\VendingMachine;
use App\Infrastructure\Persistance\Product\InMemoryProductRepository;
use PHPUnit\Framework\TestCase;

class InMemoryProductRepositoryTest extends TestCase
{
    private InMemoryProductRepository $productRepository;
    protected function setUp():void
    {
        parent::setUp();

        $this->productRepository = new InMemoryProductRepository();
    }

    public function testItGeneratesNewIdentity()
    {
        $id = $this->productRepository->nextIdentity();

        $this->assertEquals(ProductId::class, get_class($id));
    }

//    public function testItAddsNewProduct()
//    {
//        $vendingMachine = $this->createMock(VendingMachine::class);
//        $product = Product::create(
//            new ProductId(),
//            ProductName::fromValue(ProductName::SODA),
//            ProductPrice::fromValue(0.1),
//            ProductQuantity::fromValue(10),
//            $vendingMachine
//        );
//
//        $this->productRepository->add($product, $vendingMachine);
//
//        $this->assertCount(1, $vendingMachine->products());
//    }
}