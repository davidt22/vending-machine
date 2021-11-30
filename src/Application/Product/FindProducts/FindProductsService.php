<?php

namespace App\Application\Product\FindProducts;

use App\Application\VendingMachine\GetVendingMachine\GetVendingMachineRequest;
use App\Application\VendingMachine\GetVendingMachine\GetVendingMachineService;

class FindProductsService
{
    private GetVendingMachineService $getVendingMachineService;

    public function __construct(GetVendingMachineService $getVendingMachineService)
    {
        $this->getVendingMachineService = $getVendingMachineService;
    }

    public function execute(FindProductsRequest $request): array
    {
        $getVendingMachineRequest = new GetVendingMachineRequest($request->getMachineName());
        $machine = $this->getVendingMachineService->execute($getVendingMachineRequest);

        return $machine->products();
    }
}