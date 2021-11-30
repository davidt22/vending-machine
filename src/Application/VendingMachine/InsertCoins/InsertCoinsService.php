<?php

namespace App\Application\VendingMachine\InsertCoins;

use App\Domain\Model\VendingMachine\VendingMachineName;
use App\Domain\Model\VendingMachine\VendingMachineRepositoryInterface;

class InsertCoinsService
{
    private VendingMachineRepositoryInterface $vendingMachineRepository;

    public function __construct(VendingMachineRepositoryInterface $vendingMachineRepository)
    {
        $this->vendingMachineRepository = $vendingMachineRepository;
    }

    public function execute(InsertCoinsRequest $request)
    {
        $vendingMachineName = VendingMachineName::fromValue($request->getMachineName());
        $machine = $this->vendingMachineRepository->getByNameOrFail($vendingMachineName);

        $machine->insertCoins($request->getCoins());
    }
}