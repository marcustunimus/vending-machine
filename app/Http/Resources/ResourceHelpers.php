<?php

namespace App\Http\Resources;

use App\Services\VendingMachine\CurrencyManagerException;
use App\Services\VendingMachine\VendingMachineException;

class ResourceHelpers
{
    public static function errorMessageFromVendingMachineService(
        CurrencyManagerException|VendingMachineException $exception
    ): string
    {
        $error = $exception->getError();

        return "Code: " . $error->getCode() . " Message: " . $error->getMessage();
    }
}
