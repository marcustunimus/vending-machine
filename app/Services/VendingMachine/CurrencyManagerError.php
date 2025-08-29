<?php

namespace App\Services\VendingMachine;

class CurrencyManagerError
{
    public static function CURRENCY_CODE_INVALID(): CurrencyManagerError
    {
        return new CurrencyManagerError(
            "CURRENCY_CODE_INVALID",
            "The currency code should be exactly 3 characters long and with only capital letters."
        );
    }
    public static function CURRENCY_CODE_DUPLICATE(): CurrencyManagerError
    {
        return new CurrencyManagerError(
            "CURRENCY_CODE_DUPLICATE",
            "The currency code should be unique and not already existing in the database."
        );
    }
    public static function CURRENCY_EURO_RATE_INVALID(): CurrencyManagerError
    {
        return new CurrencyManagerError(
            "CURRENCY_EURO_RATE_INVALID",
            "The currency euro rate should be a numeric value above 0."
        );
    }
    public static function CURRENCY_FORMAT_INVALID(): CurrencyManagerError
    {
        return new CurrencyManagerError(
            "CURRENCY_FORMAT_INVALID",
            "The \"format\" string does not contain {PRICE}."
        );
    }
    public static function CURRENCY_CREATE_FAILED(): CurrencyManagerError
    {
        return new CurrencyManagerError(
            "CURRENCY_CREATE_FAILED",
            "The currency creation to the database failed."
        );
    }
    public static function CURRENCY_UPDATE_FAILED(): CurrencyManagerError
    {
        return new CurrencyManagerError(
            "CURRENCY_UPDATE_FAILED",
            "The currency update to the database failed."
        );
    }
    public static function CURRENCY_NOT_FOUND(): CurrencyManagerError
    {
        return new CurrencyManagerError(
            "CURRENCY_NOT_FOUND",
            "The currency model does not exist."
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
