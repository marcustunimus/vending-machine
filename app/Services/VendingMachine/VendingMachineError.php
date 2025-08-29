<?php

namespace App\Services\VendingMachine;

class VendingMachineError
{
    public static function VENDING_MACHINE_ID_INVALID(): VendingMachineError
    {
        return new VendingMachineError(
            "VENDING_MACHINE_ID_INVALID",
            "The vending machine ID must be an positive integer number."
        );
    }
    public static function VENDING_MACHINE_CURRENCY_CODE_INVALID(): VendingMachineError
    {
        return new VendingMachineError(
            "VENDING_MACHINE_ID_INVALID",
            "The vending machine currency code must be a string with at 3 characters length and only capital letters."
        );
    }
    public static function VENDING_MACHINE_KEY_INVALID(): VendingMachineError
    {
        return new VendingMachineError(
            "VENDING_MACHINE_KEY_INVALID",
            "The vending machine key must be a string with at least 1 character."
        );
    }
    public static function VENDING_MACHINE_LOCATION_INVALID(): VendingMachineError
    {
        return new VendingMachineError(
            "VENDING_MACHINE_LOCATION_INVALID",
            "The vending machine location must be a string with at least 1 character."
        );
    }
    public static function VENDING_MACHINE_LOCATION_DUPLICATE(): VendingMachineError
    {
        return new VendingMachineError(
            "VENDING_MACHINE_LOCATION_DUPLICATE",
            "The vending machine location already exists in the database."
        );
    }
    public static function VENDING_MACHINE_BALANCE_AMOUNT_INVALID(): VendingMachineError
    {
        return new VendingMachineError(
            "VENDING_MACHINE_BALANCE_AMOUNT_INVALID",
            "The vending machine balance amount must be a number above 0."
        );
    }
    public static function VENDING_MACHINE_CREATE_FAILED(): VendingMachineError
    {
        return new VendingMachineError(
            "VENDING_MACHINE_CREATE_FAILED",
            "The vending machine creation to the database failed."
        );
    }
    public static function VENDING_MACHINE_UPDATE_FAILED(): VendingMachineError
    {
        return new VendingMachineError(
            "VENDING_MACHINE_UPDATE_FAILED",
            "The vending machine update to the database failed."
        );
    }
    public static function VENDING_MACHINE_NOT_FOUND(): VendingMachineError
    {
        return new VendingMachineError(
            "VENDING_MACHINE_NOT_FOUND",
            "The vending machine model does not exist."
        );
    }

    public static function COIN_STACK_TYPE_INVALID(): VendingMachineError
    {
        return new VendingMachineError(
            "COIN_STACK_TYPE_INVALID",
            "The coin stack type must be a numeric type and a positive value."
        );
    }
    public static function COIN_STACK_TYPE_DUPLICATE(): VendingMachineError
    {
        return new VendingMachineError(
            "COIN_STACK_TYPE_DUPLICATE",
            "The coin stack type already exists in the databasefor that specific vending machine."
        );
    }
    public static function COIN_STACK_COUNT_INVALID(): VendingMachineError
    {
        return new VendingMachineError(
            "COIN_STACK_TYPE_INVALID",
            "The coin stack count must be an integer and a positive value."
        );
    }
    public static function COIN_STACK_CREATE_FAILED(): VendingMachineError
    {
        return new VendingMachineError(
            "COIN_STACK_CREATE_FAILED",
            "The coin stack creation to the database failed."
        );
    }
    public static function COIN_STACK_UPDATE_FAILED(): VendingMachineError
    {
        return new VendingMachineError(
            "COIN_STACK_UPDATE_FAILED",
            "The coin stack update to the database failed."
        );
    }
    public static function COIN_STACK_NOT_FOUND(): VendingMachineError
    {
        return new VendingMachineError(
            "COIN_STACK_NOT_FOUND",
            "The coin stack model does not exist."
        );
    }

    public static function ITEM_NAME_INVALID(): VendingMachineError
    {
        return new VendingMachineError(
            "ITEM_NAME_INVALID",
            "The item name must be a string and contain at least 1 symbol."
        );
    }
    public static function ITEM_NAME_DUPLICATE(): VendingMachineError
    {
        return new VendingMachineError(
            "ITEM_NAME_DUPLICATE",
            "The item name already exists in the database for that specific vending machine."
        );
    }
    public static function ITEM_PRICE_INVALID(): VendingMachineError
    {
        return new VendingMachineError(
            "ITEM_PRICE_INVALID",
            "The item price must be a numeric type and a positive value."
        );
    }
    public static function ITEM_QUANTITY_INVALID(): VendingMachineError
    {
        return new VendingMachineError(
            "ITEM_QUANTITY_INVALID",
            "The item quantity must be an integer and a positive value."
        );
    }
    public static function ITEM_CREATE_FAILED(): VendingMachineError
    {
        return new VendingMachineError(
            "ITEM_CREATE_FAILED",
            "The item creation to the database failed."
        );
    }
    public static function ITEM_UPDATE_FAILED(): VendingMachineError
    {
        return new VendingMachineError(
            "ITEM_UPDATE_FAILED",
            "The item update to the database failed."
        );
    }
    public static function ITEM_NOT_FOUND(): VendingMachineError
    {
        return new VendingMachineError(
            "ITEM_NOT_FOUND",
            "The item model does not exist."
        );
    }

    public static function LOG_TYPE_INVALID(): VendingMachineError
    {
        return new VendingMachineError(
            "LOG_TYPE_INVALID",
            "The log type must be either \"info\" or \"error\"."
        );
    }
    public static function LOG_TEXT_INVALID(): VendingMachineError
    {
        return new VendingMachineError(
            "LOG_TEXT_INVALID",
            "The log text must be a string and contain at least 1 character."
        );
    }
    public static function LOG_CREATE_FAILED(): VendingMachineError
    {
        return new VendingMachineError(
            "LOG_CREATE_FAILED",
            "The log creation to the database failed."
        );
    }
    public static function LOG_UPDATE_FAILED(): VendingMachineError
    {
        return new VendingMachineError(
            "LOG_UPDATE_FAILED",
            "The log update to the database failed."
        );
    }
    public static function LOG_NOT_FOUND(): VendingMachineError
    {
        return new VendingMachineError(
            "LOG_NOT_FOUND",
            "The log model does not exist."
        );
    }

    private string $code;
    private ?string $message;

    private function __construct(string $code, ?string $message = null)
    {
        $this->code = $code;
        $this->message = $message;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }
}
