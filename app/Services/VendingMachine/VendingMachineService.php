<?php

namespace App\Services\VendingMachine;

use App\Models\CoinStack;
use App\Models\Item;
use App\Models\VendingMachine;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class VendingMachineService
{
    private VendingMachineManager $vendingMachineManager;
    private CoinStackManager $coinStackManager;
    private ItemManager $itemManager;
    private LogManager $logManager;



    public function __construct()
    {
        $this->vendingMachineManager = new VendingMachineManager();
        $this->coinStackManager = new CoinStackManager();
        $this->itemManager = new ItemManager();
        $this->logManager = new LogManager();
    }



    public function getVendingMachineManager(): VendingMachineManager
    {
        return $this->vendingMachineManager;
    }

    public function getCoinStackManager(): CoinStackManager
    {
        return $this->coinStackManager;
    }

    public function getItemManager(): ItemManager
    {
        return $this->itemManager;
    }

    public function getLogManager(): LogManager
    {
        return $this->logManager;
    }



    public function insertCoin(VendingMachine $vendingMachine, string $key, int $value, string $currencyCode): array
    {
        if ($vendingMachine->key !== $key) {
            $this->getLogManager()->createError($vendingMachine->id, "Key not matching. Key provided: " . $key);

            return [
                "vendingMachine" => $vendingMachine,
                "extraInfo" => "The vending machine key is not matching.",
            ];
        }

        if ($currencyCode !== $vendingMachine->currency->code) {
            $this->getLogManager()->createError(
                $vendingMachine->id, "Inserted coin has different currency. Currency: " . $currencyCode);

            return [
                "vendingMachine" => $vendingMachine,
                "extraInfo" => "The inserted coin is in a different currency."
            ];
        }

        $coinStack = CoinStack::query()->where([
            ["vending_machine_id", "=", $vendingMachine->id],
            ["type", "=", $value],
        ])->first();

        if (is_null($coinStack)) {
            $this->getLogManager()->createError(
                $vendingMachine->id, "Coin not supported. Coin: " . Helpers::formatPriceForCurrency($value, $vendingMachine->currency)
            );

            return [
                "vendingMachine" => $vendingMachine,
                "extraInfo" => "The inserted coin is not supported.",
            ];
        }

        $startingBalanceAmount = $vendingMachine->balance_amount;

        try {
            DB::beginTransaction();

            $coinStack = $this->coinStackManager->update($coinStack, $vendingMachine->id, null, $coinStack->remaining_amount + 1);
            $vendingMachine = $this->vendingMachineManager->update($vendingMachine, null, null, null, $startingBalanceAmount + $coinStack->type);

            DB::commit();
        } catch (VendingMachineException|CurrencyManagerException $exception) {
            DB::rollBack();

            $this->getLogManager()->createErrorFromException(
                (isset($vendingMachine) ? $vendingMachine->id : null), $exception
            );

            $vendingMachine->balance_amount = $startingBalanceAmount;

            return [
                "vendingMachine" => $vendingMachine,
                "extraInfo" => "Could not update balance. Returning coin...",
            ];
        }

        $this->getLogManager()->createInfo(
            $vendingMachine->id, 
            "Successfully inserted coin: " . Helpers::formatPriceForCurrency(
                $coinStack->type, $vendingMachine->currency
            ) . " Current balance: " . Helpers::formatPriceForCurrency(
                $vendingMachine->balance_amount, $vendingMachine->currency
            )
        );

        return [
            "vendingMachine" => $vendingMachine,
            "coinStack" => $coinStack,
            "extraInfo" => "Successfully inserted coin: " . Helpers::formatPriceForCurrency($coinStack->type, $vendingMachine->currency),
        ];
    }

    public function purchaseItem(VendingMachine $vendingMachine, string $key, Item $item): array
    {
        if ($vendingMachine->key !== $key) {
            $this->getLogManager()->createError($vendingMachine->id, "Key not matching. Key provided: " . $key);

            return [
                "vendingMachine" => $vendingMachine,
                "extraInfo" => "The vending machine key not matching.",
            ];
        }

        $checkItem = Item::query()->where([
            ["id", "=", $item->id],
            ["vending_machine_id", "=", $vendingMachine->id],
            ["remaining_quantity", ">", 0]
        ])->first();

        if (is_null($checkItem)) {
            $this->getLogManager()->createError($vendingMachine->id, "Item: " . $item->id . " not found or has no remaning quantity");

            return [
                "vendingMachine" => $vendingMachine,
                "extraInfo" => "The item \"" . $item->name . "\" isn't in the vending machine or has no remaining quantity.",
            ];
        }

        $itemPrice = round($item->price * (round($vendingMachine->currency->euro_rate / 100, 2)));

        if ($vendingMachine->balance_amount - $itemPrice < 0) {
            $this->getLogManager()->createError(
                $vendingMachine->id, 
                "Not enough balance to buy item: " . $item->id . " priced at: " . Helpers::formatPriceForCurrency(
                $item->price, $vendingMachine->currency, true
            ));

            return [
                "vendingMachine" => $vendingMachine,
                "extraInfo" => "Not enough balance to buy \"" . $item->name . "\"."
            ];
        }

        $startingBalanceAmount = $vendingMachine->balance_amount;

        try {
            DB::beginTransaction();

            $item = $this->itemManager->update($item, $vendingMachine->id, null, null, $item->remaining_quantity - 1);
            $vendingMachine = $this->vendingMachineManager->update(
                $vendingMachine, null, null, null, 
                $startingBalanceAmount - $itemPrice
            );

            DB::commit();
        } catch (VendingMachineException|CurrencyManagerException $exception) {
            DB::rollBack();

            $this->getLogManager()->createErrorFromException(
                (isset($vendingMachine) ? $vendingMachine->id : null), $exception
            );

            $vendingMachine->balance_amount = $startingBalanceAmount;
        }

        $this->getLogManager()->createInfo(
            $vendingMachine->id, 
            "Successfully purchased item: " . $item->id . " Current balance: " . Helpers::formatPriceForCurrency(
                $vendingMachine->balance_amount, $vendingMachine->currency
            )
        );

        return [
            "vendingMachine" => $vendingMachine,
            "item" => $item,
            "extraInfo" => "Successfully purchased \"" . $item->name . "\". Enjoy!"
        ];
    }

    public function returnBalance(VendingMachine $vendingMachine, string $key, array|false $balanceInCoins): VendingMachine
    {
        if ($vendingMachine->key !== $key) {
            $this->getLogManager()->createError($vendingMachine->id, "Key not matching. Key provided: " . $key);

            return $vendingMachine;
        }

        if ($vendingMachine->balance_amount === 0) {
            return $vendingMachine;
        }

        if (empty($balanceInCoins)) {
            $this->getLogManager()->createError(
                $vendingMachine->id, 
                "Unable to return change for balance: " . Helpers::formatPriceForCurrency(
                    $vendingMachine->balance_amount, $vendingMachine->currency
                )
            );

            return $vendingMachine;
        }

        $coinStacks = CoinStack::query()->where([
            ["vending_machine_id", "=", $vendingMachine->id],
            ["remaining_amount", ">", 0],
        ])->get();

        $unchangedCoinStacksKeys = [];

        foreach ($coinStacks as $arrayKey => $coinStack) {
            if (!isset($balanceInCoins[$coinStack->type])) {
                $unchangedCoinStacksKeys[] = $arrayKey;
                continue;
            }

            $coinStack->remaining_amount -= $balanceInCoins[$coinStack->type];
        }

        $coinStacks->forget($unchangedCoinStacksKeys);

        $startingBalanceAmount = $vendingMachine->balance_amount;

        try {
            DB::beginTransaction();

            foreach ($coinStacks as $coinStack) {
                $this->coinStackManager->update($coinStack, null, null, null);
            }

            $vendingMachine = $this->vendingMachineManager->update($vendingMachine, null, null, null, 0);

            DB::commit();
        } catch (VendingMachineException|CurrencyManagerException $exception) {
            DB::rollBack();

            $this->getLogManager()->createErrorFromException(
                (isset($vendingMachine) ? $vendingMachine->id : null), $exception
            );

            $vendingMachine->balance_amount = $startingBalanceAmount;

            return $vendingMachine;
        }

        $this->getLogManager()->createInfo(
            $vendingMachine->id, 
            "Successfully returned balance: " . Helpers::formatPriceForCurrency(
                $startingBalanceAmount, $vendingMachine->currency
            ) . " in coins of: " . $this->getFormattedCoinsValuesText($balanceInCoins, $vendingMachine)
        );

        return $vendingMachine;
    }

    public function getBalanceInCoins(VendingMachine $vendingMachine, string $key): array|false
    {
        if ($vendingMachine->key !== $key) {
            $this->getLogManager()->createError($vendingMachine->id, "Key not matching. Key provided: " . $key);

            return false;
        }

        if ($vendingMachine->balance_amount === 0) {
            return [];
        }

        $leftoverBalance = $vendingMachine->balance_amount;

        $coinStacks = CoinStack::query()->where([
            ["vending_machine_id", "=", $vendingMachine->id],
            ["remaining_amount", ">", 0],
        ])->get();

        $coinStacksInfo = $this->getCoinStacksInfo($coinStacks);
        $changeCoins = [];
        $firstPassDone = false;

        while ($leftoverBalance > 0) {
            $previousLeftoverBalance = $leftoverBalance;
            $maxNumberOfPreviousLeftoverBalance = Helpers::getMaximumNumberWithSameLength($previousLeftoverBalance);
            $leftoverBalance = $vendingMachine->balance_amount;
            $previousChangeCoins = Helpers::cloneArray($changeCoins);
            $changeCoins = [];

            $previousCoinValue = null;
            $removedCoin = false;

            foreach ($previousChangeCoins as $coinValue => $coinCount) {
                $coinValue = round($coinValue, 2);

                if ($coinValue < $maxNumberOfPreviousLeftoverBalance) {
                    $previousCoinValue = $coinValue;

                    if ($coinCount > 0) {
                        $changeCoins[$coinValue] = $coinCount - 1;
                        $removedCoin = true;
                    }

                    if ($removedCoin) {
                        break;
                    }
                }

                $changeCoins[$coinValue] = $coinCount;
            }

            $changeCoins = Helpers::removeZeroValuesFromArray($changeCoins);

            if ($firstPassDone && !$removedCoin && is_null($previousCoinValue)) {
                return false;
            }

            if (!$removedCoin && !is_null($previousCoinValue)) {
                $changeCoins[$previousCoinValue] = $coinCount - 1;

                if ($changeCoins[$previousCoinValue] === 0 && count($changeCoins)) {
                    return false;
                }
            }

            foreach ($coinStacksInfo as $coinValue => $coinCount) {
                if (isset($changeCoins[$coinValue])) {
                    $leftoverBalance = $leftoverBalance - $coinValue * $changeCoins[$coinValue];
                    continue;
                }

                while ($leftoverBalance >= $coinValue && $coinStacksInfo[$coinValue] > 0) {
                    $leftoverBalance = round($leftoverBalance - $coinValue, 2);
                    $coinStacksInfo[$coinValue] = $coinStacksInfo[$coinValue] - 1;

                    if (isset($changeCoins[$coinValue])) {
                        $changeCoins[$coinValue] = $changeCoins[$coinValue] + 1;
                    }
                    else {
                        $changeCoins[$coinValue] = 1;
                    }
                }
            }

            if (Helpers::sameTwoArrays($previousChangeCoins, $changeCoins)) {
                return false;
            }

            $firstPassDone = true;
        }

        //Run checks to ensure that the selected change coins returned really are available and that something did not go wrong.

        $changeCoins = Helpers::removeZeroValuesFromArray($changeCoins);

        if (!$changeCoins) {
            return false;
        }

        $totalChange = 0;

        foreach($changeCoins as $coinValue => $coinCount) {
            $totalChange = $totalChange + $coinValue * $coinCount;
        }

        if ($totalChange !== $vendingMachine->balance_amount) {
            return false;
        }

        $availableCoinsInfo = $this->getCoinStacksInfo($coinStacks);

        foreach ($changeCoins as $coinValue => $coinCount) {
            if (!isset($availableCoinsInfo[$coinValue])) {
                return false;
            }

            if ($availableCoinsInfo[$coinValue] < $changeCoins[$coinValue]) {
                return false;
            }
        }

        return $changeCoins;
    }

    public function getFormattedCoinsValuesText(array $coins, VendingMachine $vendingMachine)
    {
        return implode(", ", $this->getFormattedCoinsValues($coins, $vendingMachine));
    }



    private function getCoinStacksInfo(Collection $coinStacks): array
    {
        $coinStacksInfo = [];

        foreach ($coinStacks as $coin) {
            $coinStacksInfo[(string)$coin->type] = $coin->remaining_amount;
        }

        return $coinStacksInfo;
    }

    private function getFormattedCoinsValues(array $coins, VendingMachine $vendingMachine): array
    {
        $formattedCoins = [];

        foreach ($coins as $coinValue => $coinCount) {
            $formattedCoins[] = $coinCount . "x" . Helpers::formatPriceForCurrency($coinValue, $vendingMachine->currency);
        }

        return $formattedCoins;
    }
}
