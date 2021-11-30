<?php

namespace App\Application\VendingMachine\GetVendingMachine;

use App\Domain\Model\VendingMachine\VendingMachine;
use App\Domain\Model\VendingMachine\VendingMachineName;
use App\Domain\Model\VendingMachine\VendingMachineRepositoryInterface;

class GetVendingMachineService
{
    private VendingMachineRepositoryInterface $vendingMachineRepository;

    public function __construct(VendingMachineRepositoryInterface $vendingMachineRepository)
    {
        $this->vendingMachineRepository = $vendingMachineRepository;
    }

    public function execute(GetVendingMachineRequest $vendingMachineRequest): VendingMachine
    {
        return $this->vendingMachineRepository->getByNameOrFail(
            VendingMachineName::fromValue($vendingMachineRequest->getName())
        );
    }

}