<?php

namespace App\Application\VendingMachine\CreateVendingMachine;

use App\Domain\Model\VendingMachine\VendingMachine;
use App\Domain\Model\VendingMachine\VendingMachineName;
use App\Domain\Model\VendingMachine\VendingMachineRepositoryInterface;

class CreateVendingMachineService
{
    private VendingMachineRepositoryInterface $vendingMachineRepository;

    public function __construct(VendingMachineRepositoryInterface $vendingMachineRepository)
    {
        $this->vendingMachineRepository = $vendingMachineRepository;
    }

    public function execute(CreateVendingMachineRequest $request): VendingMachine
    {
        $machine = VendingMachine::create(
            $this->vendingMachineRepository->nextIdentity(),
            VendingMachineName::fromValue($request->getName()),
            $request->getCoins(),
            []
        );

        $this->vendingMachineRepository->add($machine);

        return $machine;
    }
}