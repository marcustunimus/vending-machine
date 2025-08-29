<?php

namespace App\Services\VendingMachine;

use App\Models\Currency;

class Helpers
{
    public static function cloneArray(array $array): array
    {
        $clonedArray = [];

        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $clonedArray[$key] = self::cloneArray($value);
            }
            else if (is_object($value)){
                // Skip objects.
                continue;
            }
            else {
                $clonedArray[$key] = $value;
            }
        }

        return $clonedArray;
    }

    public static function sameTwoArrays(array $array1, array $array2): bool
    {
        if (count($array1) !== count($array2)) {
            return false;
        }

        foreach ($array1 as $key => $value) {
            if (!isset($array2[$key])) {
                return false;
            }

            if ($array1[$key] !== $array2[$key]) {
                return false;
            }
        }

        return true;
    }

    public static function removeZeroValuesFromArray(array $array): array
    {
        $arrayWithRemovedZeros = [];

        foreach ($array as $key => $value) {
            if ($value != 0) {
                $arrayWithRemovedZeros[$key] = $value;
            }
        }

        return $arrayWithRemovedZeros;
    }

    public static function getMaximumNumberWithSameLength(int $number): int
    {
        $numberText = (string)$number;
        $started = false;
        $maxNumberText = "";

        for ($i = 0; $i < strlen($numberText); $i++) {
            if (!$started && $numberText[$i] !== "0" && is_numeric($numberText[$i])) {
                $started = true;
            }

            if ($started && is_numeric($numberText[$i])) {
                
                $maxNumberText = $maxNumberText . "9";
            }
            else {
                $maxNumberText = $maxNumberText . $numberText[$i];
            }
        }

        return (int)$maxNumberText;
    }



    public static function formatRate(int $rate): string
    {
        return substr((string)$rate, 0, -2) . "." . ((string)($rate % 100));
    }

    

    public static function formatPriceForCurrency(int $price, Currency $currency, bool $withConversionRate = false): string
    {
        return str_replace(
            "{PRICE}", 
            (($withConversionRate) ? self::formatPriceWithConversionRate($price, $currency->euro_rate) : self::formatPrice($price)), 
            $currency->format
        );
    }

    public static function formatPrice(int $price): string
    {
        return ((strlen($price) > 2) ? substr((string)$price, 0, -2) : "0") . "." . ((($price % 100) < 10) ? "0" . ((string)($price % 100)) : ((string)($price % 100)));
    }

    public static function formatPriceWithConversionRate(int $price, int $conversionRate): string
    {
        return number_format(round(($price * round($conversionRate / 100, 2)) / 100, 2), 2);
    }
}
