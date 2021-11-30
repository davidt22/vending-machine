<?php

namespace App\Tests\Application\VendingMachine;

use App\Application\VendingMachine\CreateVendingMachine\CreateVendingMachineRequest;
use App\Application\VendingMachine\CreateVendingMachine\CreateVendingMachineService;
use App\Domain\Model\VendingMachine\VendingMachineId;
use App\Domain\Model\VendingMachine\VendingMachineRepositoryInterface;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class CreateVendingMachineTest extends TestCase
{
    private CreateVendingMachineService $createVendingMachineService;
    private VendingMachineRepositoryInterface $vendingMachineRepository;

    protected function setUp():void
    {
        parent::setUp();

        $this->vendingMachineRepository = $this->createMock(VendingMachineRepositoryInterface::class);
        $this->createVendingMachineService = new CreateVendingMachineService($this->vendingMachineRepository);
    }

    public function testItCreatesAVMachineSuccess()
    {
        $this->vendingMachineRepository
            ->method('nextIdentity')
            ->willReturn(VendingMachineId::fromValue(Uuid::uuid4()->toString()));

        $request = new CreateVendingMachineRequest('machine', []);
        $machine = $this->createVendingMachineService->execute($request);

        $this->assertEquals('machine', $machine->name()->value());
        $this->assertEmpty($machine->availableChange());
    }
}