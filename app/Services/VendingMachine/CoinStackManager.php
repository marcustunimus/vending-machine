<?php

namespace App\Services\VendingMachine;

use App\Models\CoinStack;
use App\Models\VendingMachine;

class CoinStackManager
{
    public function create(?int $vendingMachineId, ?int $type, ?int $remainingAmount = 0): CoinStack
    {
        // vendingMachineId checks.

        if (!isset($vendingMachineId)) {
            throw new VendingMachineException(VendingMachineError::VENDING_MACHINE_ID_INVALID());
        }

        $vendingMachine = VendingMachine::query()->where("id", "=", $vendingMachineId)->first();

        if (is_null($vendingMachine)) {
            throw new VendingMachineException(VendingMachineError::VENDING_MACHINE_NOT_FOUND());
        }

        // $type checks.

        if (!isset($type)) {
            throw new VendingMachineException(VendingMachineError::COIN_STACK_TYPE_INVALID());
        }

        if ($type < 0) {
            throw new VendingMachineException(VendingMachineError::COIN_STACK_TYPE_INVALID());
        }

        $duplicateTypeCheck = CoinStack::query()->where([["vending_machine_id", "=", $vendingMachineId], ["type", "=", $type]])->first();

        if (!is_null($duplicateTypeCheck)) {
            throw new VendingMachineException(VendingMachineError::COIN_STACK_TYPE_DUPLICATE());
        }

        // $remainingAmount checks.

        if (!isset($remainingAmount)) {
            $remainingAmount = 0;
        }

        if ($remainingAmount < 0) {
            throw new VendingMachineException(VendingMachineError::COIN_STACK_COUNT_INVALID());
        }

        $coinStack = null;

        try {
            $coinStack =  CoinStack::query()->create([
                "vending_machine_id" => $vendingMachineId,
                "type" => $type,
                "remaining_amount" => $remainingAmount,
            ]);
        }
        catch (\Exception $e) {
            throw new VendingMachineException(VendingMachineError::COIN_STACK_CREATE_FAILED());
        }

        return $coinStack;
    }

    public function update(?CoinStack $coinStack, ?int $vendingMachineId, ?int $type, ?int $remainingAmount = 0): CoinStack
    {
        // $coinStack checks.

        if (!isset($coinStack)) {
            throw new VendingMachineException(VendingMachineError::COIN_STACK_NOT_FOUND());
        }

        // $vendingMachineId checks.

        if (isset($vendingMachineId)) {
            $vendingMachine = VendingMachine::query()->where("id", "=", $vendingMachineId)->first();

            if (is_null($vendingMachine)) {
                throw new VendingMachineException(VendingMachineError::VENDING_MACHINE_NOT_FOUND());
            }

            $coinStack->vending_machine_id = $vendingMachineId;
        }

        // $type checks.

        if (isset($type)) {
            if ($type < 0) {
                throw new VendingMachineException(VendingMachineError::COIN_STACK_TYPE_INVALID());
            }

            $duplicateTypeCheck = CoinStack::query()->where([["vending_machine_id", "=", $coinStack->vending_machine_id], ["type", "=", $type]])->first();

            if (!is_null($duplicateTypeCheck)) {
                if ($duplicateTypeCheck->id !== $coinStack->id) {
                    throw new VendingMachineException(VendingMachineError::COIN_STACK_TYPE_DUPLICATE());
                }
            }

            $coinStack->type = $type;
        }

        // $remainingAmount checks.

        if (isset($remainingAmount)) {
            if ($remainingAmount < 0) {
                throw new VendingMachineException(VendingMachineError::COIN_STACK_COUNT_INVALID());
            }

            $coinStack->remaining_amount = $remainingAmount;
        }

        $success = $coinStack->save();

        if (!$success) {
            throw new VendingMachineException(VendingMachineError::COIN_STACK_UPDATE_FAILED());
        }

        return $coinStack;
    }

    public function delete(?CoinStack $coinStack): bool
    {
        if (!isset($coinStack)) {
            throw new VendingMachineException(VendingMachineError::COIN_STACK_NOT_FOUND());
        }

        $success = $coinStack->delete();

        if (is_null($success)) {
            throw new VendingMachineException(VendingMachineError::COIN_STACK_NOT_FOUND());
        }

        return $success;
    }
}
