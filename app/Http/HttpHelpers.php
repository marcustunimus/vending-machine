<?php

namespace App\Http;

use App\Services\VendingMachine\CurrencyManagerException;
use App\Services\VendingMachine\VendingMachineException;
use Illuminate\Validation\ValidationException;

class HttpHelpers
{
    public static function validationExceptionFromVendingMachineService(
        CurrencyManagerException|VendingMachineException $exception
    ): ValidationException 
    {
        $error = $exception->getError();

        return ValidationException::withMessages([
            "code" => $error->getCode(),
            "message" => $error->getMessage(),
        ]);
    }
}
