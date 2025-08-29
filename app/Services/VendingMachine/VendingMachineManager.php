<?php

namespace App\Services\VendingMachine;

use App\Models\Currency;
use App\Models\VendingMachine;
use Illuminate\Support\Str;

class VendingMachineManager
{
    public function create(?string $currencyCode, ?string $key, ?string $location, ?int $balanceAmount = 0): VendingMachine
    {
        // $currencyCode checks.

        if (empty($code)) {
            throw new VendingMachineException(VendingMachineError::VENDING_MACHINE_CURRENCY_CODE_INVALID());
        }

        if (strlen($code) !== 3 && strtoupper($code) === $code) {
            throw new VendingMachineException(VendingMachineError::VENDING_MACHINE_CURRENCY_CODE_INVALID());
        }

        $currency = Currency::query()->where("code", "=", $currencyCode)->first();

        if (!isset($currency)) {
            throw new CurrencyManagerException(CurrencyManagerError::CURRENCY_NOT_FOUND());
        }

        // $key checks.

        if ($key === "") {
            throw new VendingMachineException(VendingMachineError::VENDING_MACHINE_KEY_INVALID());
        }

        if (is_null($key)) {
            $key = Str::random(64);
        }

        // $location checks.

        if (empty($location)) {
            throw new VendingMachineException(VendingMachineError::VENDING_MACHINE_LOCATION_INVALID());
        }

        $duplicateLocationCheck = VendingMachine::query()->where("location", "=", $location)->first();

        if (!is_null($duplicateLocationCheck)) {
            throw new VendingMachineException(VendingMachineError::COIN_STACK_TYPE_DUPLICATE());
        }

        // $balanceAmount checks.

        if (!isset($balanceAmount)) {
            $balanceAmount = 0;
        }

        if ($balanceAmount < 0) {
            throw new VendingMachineException(VendingMachineError::VENDING_MACHINE_BALANCE_AMOUNT_INVALID());
        }

        $vendingMachine = null;

        try {
            $vendingMachine =  VendingMachine::query()->create([
                "currency_code" => $currencyCode,
                "key" => $key,
                "location" => $location,
                "balance_amount" => $balanceAmount,
            ]);
        }
        catch (\Exception $e) {
            throw new VendingMachineException(VendingMachineError::VENDING_MACHINE_CREATE_FAILED());
        }

        return $vendingMachine;
    }

    public function update(?VendingMachine $vendingMachine, ?string $currencyCode, ?string $key, ?string $location, ?int $balanceAmount = 0): VendingMachine
    {
        // $vendingMachine checks.

        if (!isset($vendingMachine)) {
            throw new VendingMachineException(VendingMachineError::VENDING_MACHINE_NOT_FOUND());
        }

        // $currencyCode checks.

        if (!empty($currencyCode)) {
            if (strlen($currencyCode) !== 3 && strtoupper($currencyCode) === $currencyCode) {
                throw new VendingMachineException(VendingMachineError::VENDING_MACHINE_CURRENCY_CODE_INVALID());
            }

            $currencyCodeCheck = Currency::query()->where("code", "=", $currencyCode)->first();

            if (!isset($currencyCodeCheck)) {
                throw new CurrencyManagerException(CurrencyManagerError::CURRENCY_NOT_FOUND());
            }

            $vendingMachine->currency_code = $currencyCode;
        }

        // $key checks.

        if (!empty($key)) {
            if ($key === "new") {
                $key = Str::random(64);
            }

            $vendingMachine->key = $key;
        }

        // $location checks.

        if (!empty($location)) {
            $duplicateLocationCheck = VendingMachine::query()->where("location", "=", $location)->first();

            if (!is_null($duplicateLocationCheck)) {
                if ($duplicateLocationCheck->id !== $vendingMachine->id) {
                    throw new VendingMachineException(VendingMachineError::COIN_STACK_TYPE_DUPLICATE());
                }
            }

            $vendingMachine->location = $location;
        }

        // $balanceAmount checks.

        if (isset($balanceAmount)) {
            if ($balanceAmount < 0) {
                throw new VendingMachineException(VendingMachineError::VENDING_MACHINE_BALANCE_AMOUNT_INVALID());
            }

            $vendingMachine->balance_amount = $balanceAmount;
        }

        $success = $vendingMachine->save();

        if (!$success) {
            throw new VendingMachineException(VendingMachineError::VENDING_MACHINE_UPDATE_FAILED());
        }

        return $vendingMachine;
    }

    public function delete(?VendingMachine $vendingMachine): bool
    {
        if (!isset($vendingMachine)) {
            throw new VendingMachineException(VendingMachineError::VENDING_MACHINE_NOT_FOUND());
        }

        $success = $vendingMachine->delete();

        if (is_null($success)) {
            throw new VendingMachineException(VendingMachineError::VENDING_MACHINE_NOT_FOUND());
        }

        return $success;
    }
}
