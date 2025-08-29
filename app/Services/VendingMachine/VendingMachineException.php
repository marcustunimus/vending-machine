<?php

namespace App\Services\VendingMachine;

use Exception;

class VendingMachineException extends Exception
{
    private VendingMachineError $vendingMachineError;

    public function __construct(VendingMachineError $vendingMachineError)
    {
        $this->vendingMachineError = $vendingMachineError;
    }

    public function getError(): VendingMachineError
    {
        return $this->vendingMachineError;
    }
}
