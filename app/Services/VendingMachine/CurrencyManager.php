<?php

namespace App\Services\VendingMachine;

use App\Models\Currency;

class CurrencyManager
{
    public function create(?string $code, ?int $euroRate, ?string $format): Currency
    {
        // $code checks.

        if (empty($code)) {
            throw new CurrencyManagerException(CurrencyManagerError::CURRENCY_CODE_INVALID());
        }

        if (strlen($code) !== 3 && strtoupper($code) === $code) {
            throw new CurrencyManagerException(CurrencyManagerError::CURRENCY_CODE_INVALID());
        }

        $duplicateCodeCheck = Currency::query()->where("code", "=", $code)->first();

        if (!is_null($duplicateCodeCheck)) {
            throw new CurrencyManagerException(CurrencyManagerError::CURRENCY_CODE_DUPLICATE());
        }

        // $euroRate checks.

        if (!isset($euroRate)) {
            throw new CurrencyManagerException(CurrencyManagerError::CURRENCY_EURO_RATE_INVALID());
        }

        if ($euroRate <= 0) {
            throw new CurrencyManagerException(CurrencyManagerError::CURRENCY_EURO_RATE_INVALID());
        }

        // $format checks.

        if (empty($format)) {
            throw new CurrencyManagerException(CurrencyManagerError::CURRENCY_FORMAT_INVALID());
        }

        if (!str_contains($format, "{PRICE}")) {
            throw new CurrencyManagerException(CurrencyManagerError::CURRENCY_FORMAT_INVALID());
        }

        $currency = null;

        try {
            $currency = Currency::query()->create([
                "code" => $code,
                "euro_rate" => $euroRate,
                "format" => $format,
            ]);
        }
        catch (\Exception $e) {
            throw new CurrencyManagerException(CurrencyManagerError::CURRENCY_CREATE_FAILED());
        }

        return $currency;
    }

    public function update(?Currency $currency, ?string $code, ?int $euroRate, ?string $format): Currency
    {
        // $currency checks.

        if (!isset($currency)) {
            throw new CurrencyManagerException(CurrencyManagerError::CURRENCY_NOT_FOUND());
        }

        // $code checks.
        
        if (!empty($code)) {
            if (strlen($code) !== 3 && strtoupper($code) === $code) {
                throw new CurrencyManagerException(CurrencyManagerError::CURRENCY_CODE_INVALID());
            }

            $duplicateCodeCheck = Currency::query()->where("code", "=", $code)->first();

            if (!is_null($duplicateCodeCheck)) {
                throw new CurrencyManagerException(CurrencyManagerError::CURRENCY_CODE_DUPLICATE());
            }

            $currency->code = $code;
        }

        // $euroRate checks.

        if (isset($euroRate)) {
            if ($euroRate <= 0) {
                throw new CurrencyManagerException(CurrencyManagerError::CURRENCY_EURO_RATE_INVALID());
            }

            $currency->euro_rate = $euroRate;
        }

        // $format checks.

        if (!empty($format)) {
            if (!str_contains($format, "{PRICE}")) {
                throw new CurrencyManagerException(CurrencyManagerError::CURRENCY_FORMAT_INVALID());
            }

            $currency->format = $format;
        }

        $success = $currency->save();

        if (!$success) {
            throw new CurrencyManagerException(CurrencyManagerError::CURRENCY_UPDATE_FAILED());
        }

        return $currency;
    }

    public function delete(?Currency $currency): bool
    {
        if (!isset($currency)) {
            throw new CurrencyManagerException(CurrencyManagerError::CURRENCY_NOT_FOUND());
        }

        $success = $currency->delete();

        if (is_null($success)) {
            throw new CurrencyManagerException(CurrencyManagerError::CURRENCY_NOT_FOUND());
        }

        return $success;
    }
}
