<?php

namespace App\Services\VendingMachine;

use Exception;

class CurrencyManagerException extends Exception
{
    private CurrencyManagerError $currencyManagerError;

    public function __construct(CurrencyManagerError $currencyManagerError)
    {
        $this->currencyManagerError = $currencyManagerError;
    }

    public function getError(): CurrencyManagerError
    {
        return $this->currencyManagerError;
    }
}
