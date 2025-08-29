<?php

namespace App\Services\VendingMachine;

use App\Models\Item;
use App\Models\VendingMachine;

class ItemManager
{
    public function create(?int $vendingMachineId, ?string $name, ?int $price, ?int $quantity = 0): Item
    {
        // $vendingMachineId checks.

        if (!isset($vendingMachineId)) {
            throw new VendingMachineException(VendingMachineError::VENDING_MACHINE_ID_INVALID());
        }

        $vendingMachine = VendingMachine::query()->where("id", "=", $vendingMachineId)->first();

        if (is_null($vendingMachine)) {
            throw new VendingMachineException(VendingMachineError::VENDING_MACHINE_NOT_FOUND());
        }

        // $name checks.

        if (empty($name)) {
            throw new VendingMachineException(VendingMachineError::ITEM_NAME_INVALID());
        }

        $duplicateNameCheck = Item::query()->where([["vending_machine_id", "=", $vendingMachineId], ["name", "=", $name]])->first();

        if (!is_null($duplicateNameCheck)) {
            throw new VendingMachineException(VendingMachineError::ITEM_NAME_DUPLICATE());
        }

        // $price checks.

        if (!isset($price)) {
            throw new VendingMachineException(VendingMachineError::ITEM_PRICE_INVALID());
        }

        if ($price < 0) {
            throw new VendingMachineException(VendingMachineError::ITEM_PRICE_INVALID());
        }

        // $quantity checks.

        if (!isset($quantity)) {
            $quantity = 0;
        }

        if ($quantity < 0) {
            throw new VendingMachineException(VendingMachineError::ITEM_QUANTITY_INVALID());
        }

        $item = null;

        try {
            $item =  Item::query()->create([
                "vending_machine_id" => $vendingMachineId,
                "name" => $name,
                "price" => $price,
                "remaining_quantity" => $quantity,
            ]);
        }
        catch (\Exception $e) {
            throw new VendingMachineException(VendingMachineError::ITEM_CREATE_FAILED());
        }

        return $item;
    }

    public function update(?Item $item, ?int $vendingMachineId, ?string $name, ?int $price, ?int $quantity = 0): Item
    {
        // $item checks.

        if (!isset($item)) {
            throw new VendingMachineException(VendingMachineError::ITEM_NOT_FOUND());
        }

        // $vendingMachineId checks.

        if (isset($vendingMachineId)) {
            $vendingMachine = VendingMachine::query()->where("id", "=", $vendingMachineId)->first();

            if (!isset($vendingMachine)) {
                throw new VendingMachineException(VendingMachineError::VENDING_MACHINE_NOT_FOUND());
            }

            $item->vending_machine_id = $vendingMachineId;
        }

        // $name checks.

        if (!empty($name)) {
            $duplicateNameCheck = Item::query()->where([["vending_machine_id", "=", $item->vending_machine_id], ["name", "=", $name]])->first();

            if (!is_null($duplicateNameCheck)) {
                if ($duplicateNameCheck->id !== $item->id) {
                    throw new VendingMachineException(VendingMachineError::ITEM_NAME_DUPLICATE());
                }
            }

            $item->name = $name;
        }

        // $price checks.

        if (isset($price)) {
            if ($price < 0) {
                throw new VendingMachineException(VendingMachineError::ITEM_PRICE_INVALID());
            }

            $item->price = $price;
        }

        // $quantity checks.

        if (isset($quantity)) {
            if ($quantity < 0) {
                throw new VendingMachineException(VendingMachineError::ITEM_QUANTITY_INVALID());
            }

            $item->remaining_quantity = $quantity;
        }

        $success = $item->save();

        if (!$success) {
            throw new VendingMachineException(VendingMachineError::ITEM_UPDATE_FAILED());
        }

        return $item;
    }

    public function delete(?Item $item): bool
    {
        if (!isset($item)) {
            throw new VendingMachineException(VendingMachineError::ITEM_NOT_FOUND());
        }

        $success = $item->delete();

        if (is_null($success)) {
            throw new VendingMachineException(VendingMachineError::ITEM_NOT_FOUND());
        }

        return $success;
    }
}
