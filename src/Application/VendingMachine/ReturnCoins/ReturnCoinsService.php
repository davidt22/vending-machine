<?php

namespace App\Application\VendingMachine\ReturnCoins;

use App\Domain\Model\VendingMachine\VendingMachineName;
use App\Domain\Model\VendingMachine\VendingMachineRepositoryInterface;

class ReturnCoinsService
{
    private VendingMachineRepositoryInterface $vendingMachineRepository;

    public function __construct(VendingMachineRepositoryInterface $vendingMachineRepository)
    {
        $this->vendingMachineRepository = $vendingMachineRepository;
    }

    public function execute(ReturnCoinsRequest $request)
    {
        $vendingMachineName = VendingMachineName::fromValue($request->getMachineName());
        $machine = $this->vendingMachineRepository->getByNameOrFail(
            $vendingMachineName
        );

        $insertedCoins = $machine->currentlyInsertedCoins();

        $machine->returnInsertedCoins();

        return $insertedCoins;
    }
}