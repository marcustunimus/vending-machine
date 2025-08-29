<?php

namespace App\Http\Controllers;

use App\Http\HttpHelpers;
use App\Http\Resources\ResourceHelpers;
use App\Http\Resources\VendingMachineResource;
use App\Models\Item;
use App\Models\VendingMachine;
use App\Services\VendingMachine\CurrencyManagerException;
use App\Services\VendingMachine\Helpers;
use App\Services\VendingMachine\VendingMachineService;
use App\Services\VendingMachine\VendingMachineException;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class VendingMachineController extends Controller
{
    private VendingMachineService $vendingMachineService;

    private $relationships = [
        "currency",
        "coinStacks",
        "items",
        "logs",
    ];



    public function __construct(VendingMachineService $vendingMachineService)
    {
        $this->vendingMachineService = $vendingMachineService;
    }



    public function vendingMachinesPage(Request $request): Factory|View
    {
        return view("vending-machines", [
            "vendingMachines" => VendingMachine::all(),
        ]);
    }

    public function vendingMachinePage(Request $request, VendingMachine $vendingMachine): Factory|View
    {
        return view("vending-machine", [
            "vendingMachine" => $vendingMachine,
            "coinStacks" => $vendingMachine->coinStacks,
            "items" => $vendingMachine->items,
        ]);
    }

    public function manageVendingMachinesPage(Request $request): Factory|View
    {
        return view("manage-vending-machines", [
            "vendingMachines" => VendingMachine::all(),
        ]);
    }

    public function manageVendingMachinePage(Request $request, VendingMachine $vendingMachine): Factory|View
    {
        return view("manage-vending-machine", [
            "vendingMachine" => $vendingMachine,
            "coinStacks" => $vendingMachine->coinStacks,
            "items" => $vendingMachine->items,
        ]);
    }



    public function index(Request $request): AnonymousResourceCollection
    {
        return VendingMachineResource::collection(VendingMachine::all());
    }

    public function show(Request $request, VendingMachine $vendingMachine): VendingMachineResource
    {
        $withQuery = $request->get("with", "");
        
        $withQuery = explode(",", $withQuery);

        $with = [];

        foreach ($with as $relation) {
            if (in_array($relation, $this->relationships)) {
                if (!in_array($relation, $with)) {
                    $with[] = $relation;
                }
            }
        }

        if (!empty($with)) {
            $vendingMachine->load($with);
        }

        return new VendingMachineResource($vendingMachine);
    }



    public function insertCoin(Request $request, VendingMachine $vendingMachine): VendingMachineResource
    {
        $attributes = $request->validate([
            "key" => ["required", "string"],
            "value" => ["required", "string"],
            "currencyCode" => ["required", "string"],
        ]);

        $data = $this->vendingMachineService->insertCoin($vendingMachine, $attributes["key"], $attributes["value"], $attributes["currencyCode"]);

        if (!isset($data["coinStack"])) {
            return new VendingMachineResource($data["vendingMachine"])->additional([
                "formattedBalance" => Helpers::formatPriceForCurrency($data["vendingMachine"]->balance_amount, $data["vendingMachine"]->currency),
                "extraInfo" => $data["extraInfo"],
            ]);
        }

        return new VendingMachineResource($data["vendingMachine"])->additional([
            "coinStack" => $data["coinStack"], 
            "formattedBalance" => Helpers::formatPriceForCurrency($data["vendingMachine"]->balance_amount, $data["vendingMachine"]->currency),
            "extraInfo" => $data["extraInfo"],
        ]);
    }

    public function purchaseItem(Request $request, VendingMachine $vendingMachine, Item $item): VendingMachineResource
    {
        $attributes = $request->validate([
            "key" => ["required", "string"],
        ]);

        $data = $this->vendingMachineService->purchaseItem($vendingMachine, $attributes["key"], $item);

        if (!isset($data["item"])) {
            return new VendingMachineResource($data["vendingMachine"])->additional([
                "formattedBalance" => Helpers::formatPriceForCurrency($data["vendingMachine"]->balance_amount, $data["vendingMachine"]->currency),
                "extraInfo" => $data["extraInfo"],
            ]);
        }

        return new VendingMachineResource($data["vendingMachine"])->additional([
            "item" => $data["item"], 
            "formattedBalance" => Helpers::formatPriceForCurrency($data["vendingMachine"]->balance_amount, $data["vendingMachine"]->currency),
            "extraInfo" => $data["extraInfo"],
        ]);
    }

    public function returnBalance(Request $request, VendingMachine $vendingMachine): VendingMachineResource
    {
        $attributes = $request->validate([
            "key" => ["required", "string"],
        ]);

        $previousBalance = $vendingMachine->balance_amount;

        $balanceInCoins = $this->vendingMachineService->getBalanceInCoins($vendingMachine, $attributes["key"]);

        if ($balanceInCoins) {
            $formattedBalanceInCoins = $this->vendingMachineService->getFormattedCoinsValuesText($balanceInCoins, $vendingMachine);
        }

        $vendingMachine = $this->vendingMachineService->returnBalance($vendingMachine, $attributes["key"], $balanceInCoins);

        if ($previousBalance === 0) {
            return new VendingMachineResource($vendingMachine)->additional([
                "formattedBalance" => Helpers::formatPriceForCurrency($vendingMachine->balance_amount, $vendingMachine->currency),
                "extraInfo" => "No balance to return.",
            ]);
        }

        if ($vendingMachine->balance_amount === $previousBalance) {
            return new VendingMachineResource($vendingMachine)->additional([
                "formattedBalance" => Helpers::formatPriceForCurrency($vendingMachine->balance_amount, $vendingMachine->currency),
                "extraInfo" => "Could not return balance.",
            ]);
        }

        $vendingMachine->load("coinStacks");

        return new VendingMachineResource($vendingMachine)->additional([
            "returnedBalance" => $previousBalance,
            "returnedBalanceInCoins" => $balanceInCoins,
            "formattedBalanceInCoins" => $formattedBalanceInCoins,
            "formattedBalance" => Helpers::formatPriceForCurrency($vendingMachine->balance_amount, $vendingMachine->currency),
            "extraInfo" => "Successfully returned balance: " . Helpers::formatPriceForCurrency(
                $previousBalance, $vendingMachine->currency
            ) . " in coins of: " . $this->vendingMachineService->getFormattedCoinsValuesText($balanceInCoins, $vendingMachine)
        ]);
    }



    public function create(Request $request): VendingMachineResource
    {
        $attributes = $request->validate([
            "currency_code" => ["required", "string", "exists:currencies,code"],
            "key" => ["string", "nullable"],
            "location" => ["required", "string"],
            "balance_amount" => ["numeric", "min:0", "nullable"],
        ]);

        $vendingMachine = null;

        if (isset($attributes["balance_amount"])) {
            $attributes["balance_amount"] = round($attributes["balance_amount"] * 100);
        }

        try {
            $vendingMachine = $this->vendingMachineService->getVendingMachineManager()->create(
                ((isset($attributes["currency_code"])) ? $attributes["currency_code"] : null),
                ((isset($attributes["key"])) ? $attributes["key"] : null),
                ((isset($attributes["location"])) ? $attributes["location"] : null),
                ((isset($attributes["balance_amount"])) ? $attributes["balance_amount"] : null), 
            );
        }
        catch (VendingMachineException|CurrencyManagerException $exception) {
            $this->vendingMachineService->getLogManager()->createErrorFromException(
                (isset($vendingMachine) ? $vendingMachine->id : null), $exception
            );

            // throw HttpHelpers::validationExceptionFromVendingMachineService($exception);

            return new VendingMachineResource($vendingMachine)->additional(["error" => ResourceHelpers::errorMessageFromVendingMachineService($exception)]);
        }

        return new VendingMachineResource($vendingMachine);
    }

    public function update(Request $request, VendingMachine $vendingMachine): VendingMachineResource
    {
        $attributes = $request->validate([
            "currency_code" => ["required", "string", "exists:currencies,code"],
            "key" => ["string", "nullable"],
            "location" => ["string", "nullable"],
            "balance_amount" => ["numeric", "min:0", "nullable"],
        ]);

        if (isset($attributes["balance_amount"])) {
            $attributes["balance_amount"] = round($attributes["balance_amount"] * 100);
        }

        try {
            $vendingMachine = $this->vendingMachineService->getVendingMachineManager()->update(
                $vendingMachine, 
                ((isset($attributes["currency_code"])) ? $attributes["currency_code"] : null),
                ((isset($attributes["key"])) ? $attributes["key"] : null),
                ((isset($attributes["location"])) ? $attributes["location"] : null),
                ((isset($attributes["balance_amount"])) ? $attributes["balance_amount"] : null), 
            );
        }
        catch (VendingMachineException|CurrencyManagerException $exception) {
            $this->vendingMachineService->getLogManager()->createErrorFromException($vendingMachine->id, $exception);

            // throw HttpHelpers::validationExceptionFromVendingMachineService($exception);

            return new VendingMachineResource($vendingMachine)->additional(["error" => ResourceHelpers::errorMessageFromVendingMachineService($exception)]);
        }

        return new VendingMachineResource($vendingMachine);
    }

    public function delete(Request $request, VendingMachine $vendingMachine): VendingMachineResource
    {
        $success = false;

        try {
            $success = $this->vendingMachineService->getVendingMachineManager()->delete($vendingMachine);
        }
        catch (VendingMachineException|CurrencyManagerException $exception) {
            $this->vendingMachineService->getLogManager()->createErrorFromException($vendingMachine->id, $exception);

            // throw HttpHelpers::validationExceptionFromVendingMachineService($exception);

            return new VendingMachineResource($vendingMachine)->additional(["error" => ResourceHelpers::errorMessageFromVendingMachineService($exception)]);
        }

        return new VendingMachineResource($vendingMachine)->additional(["success" => $success]);
    }
}
