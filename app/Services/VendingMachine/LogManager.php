<?php

namespace App\Services\VendingMachine;

use App\Models\Log;
use App\Models\VendingMachine;

class LogManager
{
    private $validLogTypes = [
        "info", 
        "error",
    ];

    public function createInfo(?int $vendingMachineId, string $text): Log|null
    {
        $log = null;

        if (is_null($vendingMachineId)) {
            return $log;
        }

        try {
            $log = $this->create($vendingMachineId, "info", $text);
        }
        catch (\Exception $e) {
            // Do something if logging of info fails.
        }

        return $log;
    }

    public function createError(?int $vendingMachineId, string $text): Log|null
    {
        $log = null;

        if (is_null($vendingMachineId)) {
            return $log;
        }

        try {
            $log = $this->create($vendingMachineId, "error", $text);
        }
        catch (\Exception $e) {
            // Do something if logging of info fails.
        }

        return $log;
    }

    public function createErrorFromException(?int $vendingMachineId, VendingMachineException|CurrencyManagerException $exception): Log|null
    {
        $log = null;

        if (is_null($vendingMachineId)) {
            return $log;
        }

        try {
            $error = $exception->getError();

            $log = $this->create($vendingMachineId, "error", "Code: " . $error->getCode() . ". Message: " . $error->getMessage());
        }
        catch (\Exception $e) {
            // Do something if logging of error fails.
        }

        return $log;
    }

    public function create(?int $vendingMachineId, ?string $type, ?string $text): Log
    {
        // $vendingMachineId checks.

        if (!isset($vendingMachineId)) {
            throw new VendingMachineException(VendingMachineError::VENDING_MACHINE_ID_INVALID());
        }

        $vendingMachine = VendingMachine::query()->where("id", "=", $vendingMachineId)->first();

        if (!isset($vendingMachine)) {
            throw new VendingMachineException(VendingMachineError::VENDING_MACHINE_NOT_FOUND());
        }

        // $type checks.

        if (empty($type)) {
            throw new VendingMachineException(VendingMachineError::LOG_TYPE_INVALID());
        }

        if (!in_array($type, $this->validLogTypes)) {
            throw new VendingMachineException(VendingMachineError::LOG_TYPE_INVALID());
        }

        // $text checks.

        if (empty($text)) {
            throw new VendingMachineException(VendingMachineError::LOG_TEXT_INVALID());
        }

        $log = null;

        try {
            $log =  Log::query()->create([
                "vending_machine_id" => $vendingMachineId,
                "type" => $type,
                "text" => $text,
            ]);
        }
        catch (\Exception $e) {
            throw new VendingMachineException(VendingMachineError::LOG_CREATE_FAILED());
        }

        return $log;
    }

    public function update(?Log $log, ?int $vendingMachineId, ?string $type, ?string $text): Log
    {
        // $log checks.

        if (!isset($log)) {
            throw new VendingMachineException(VendingMachineError::LOG_NOT_FOUND());
        }

        // $vendingMachineId checks.

        if (isset($vendingMachineId)) {
            $vendingMachine = VendingMachine::query()->where("id", "=", $vendingMachineId)->first();

            if (!isset($vendingMachine)) {
                throw new VendingMachineException(VendingMachineError::VENDING_MACHINE_NOT_FOUND());
            }

            $log->vending_machine_id = $vendingMachineId;
        }

        // $type checks.

        if (!empty($type)) {
            if (!in_array($type, $this->validLogTypes)) {
                throw new VendingMachineException(VendingMachineError::LOG_TYPE_INVALID());
            }

            $log->type = $type;
        }

        // $text checks.

        if (!empty($text)) {
            $log->text = $text;
        }

        $success = $log->save();

        if (!$success) {
            throw new VendingMachineException(VendingMachineError::LOG_UPDATE_FAILED());
        }

        return $log;
    }

    public function delete(?Log $log): bool
    {
        if (!isset($log)) {
            throw new VendingMachineException(VendingMachineError::LOG_NOT_FOUND());
        }

        $success = $log->delete();

        if (is_null($success)) {
            throw new VendingMachineException(VendingMachineError::LOG_NOT_FOUND());
        }

        return $success;
    }
}
