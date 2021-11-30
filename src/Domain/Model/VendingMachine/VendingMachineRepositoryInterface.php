<?php

namespace App\Domain\Model\VendingMachine;

interface VendingMachineRepositoryInterface
{
    public function nextIdentity(): VendingMachineId;
    public function add(VendingMachine $vendingMachine);
    public function getByNameOrFail(VendingMachineName $name): VendingMachine;
}