<?php

namespace App\Infrastructure\Persistance\VendingMachine;

use App\Domain\Model\VendingMachine\VendingMachine;
use App\Domain\Model\VendingMachine\VendingMachineId;
use App\Domain\Model\VendingMachine\VendingMachineName;
use App\Domain\Model\VendingMachine\VendingMachineNotFoundException;
use App\Domain\Model\VendingMachine\VendingMachineRepositoryInterface;

class InMemoryVendingMachineRepository implements VendingMachineRepositoryInterface
{
    private array $machines = [];

    public function add(VendingMachine $vendingMachine)
    {
        $this->machines[] = $vendingMachine;
    }

    public function getByNameOrFail(VendingMachineName $name): VendingMachine
    {
        /** @var VendingMachine $machine */
        foreach ($this->machines as $machine) {
            if ($machine->name()->equals($name)) {
                return $machine;
            }
        }

        throw new VendingMachineNotFoundException('Machine not found');
    }

    public function nextIdentity(): VendingMachineId
    {
        return new VendingMachineId();
    }
}