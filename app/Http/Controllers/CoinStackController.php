<?php

namespace App\Http\Controllers;

use App\Http\HttpHelpers;
use App\Http\Resources\CoinStackResource;
use App\Http\Resources\ResourceHelpers;
use App\Models\CoinStack;
use App\Services\VendingMachine\CurrencyManagerException;
use App\Services\VendingMachine\VendingMachineService;
use App\Services\VendingMachine\VendingMachineException;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CoinStackController extends Controller
{
    public VendingMachineService $vendingMachineService;



    public function __construct(VendingMachineService $vendingMachineService)
    {
        $this->vendingMachineService = $vendingMachineService;
    }


    public function manageCoinStacksPage(Request $request): Factory|View
    {
        return view("manage-coin-stacks", [
            "coinStacks" => CoinStack::all(),
        ]);
    }



    public function index(Request $request): AnonymousResourceCollection
    {
        return CoinStackResource::collection(CoinStack::all());
    }

    public function show(Request $request, CoinStack $coinStack): CoinStackResource
    {
        return new CoinStackResource($coinStack);
    }

    

    public function create(Request $request): CoinStackResource
    {
        $attributes = $request->validate([
            "vending_machine_id" => ["required", "integer", "min:0", "exists:vending_machines,id"],
            "type" => ["required", "numeric"],
            "remaining_amount" => ["integer", "min:0", "nullable"],
        ]);

        $coinStack = null;

        if (isset($attributes["type"])) {
            $attributes["type"] = round($attributes["type"] * 100);
        }

        try {
            $coinStack = $this->vendingMachineService->getCoinStackManager()->create(
                ((isset($attributes["vending_machine_id"])) ? (int)$attributes["vending_machine_id"] : null),
                ((isset($attributes["type"])) ? $attributes["type"] : null), 
                ((isset($attributes["remaining_amount"])) ? (int)$attributes["remaining_amount"] : null),
            );
        }
        catch (VendingMachineException|CurrencyManagerException $exception) {
            $this->vendingMachineService->getLogManager()->createErrorFromException(
                ((isset($attributes["vending_machine_id"])) ? (int)$attributes["vending_machine_id"] : null), $exception
            );

            // throw HttpHelpers::validationExceptionFromVendingMachineService($exception);

            return new CoinStackResource($coinStack)->additional(["error" => ResourceHelpers::errorMessageFromVendingMachineService($exception)]);
        }

        return new CoinStackResource($coinStack);
    }

    public function update(Request $request, CoinStack $coinStack): CoinStackResource
    {
        $attributes = $request->validate([
            "vending_machine_id" => ["integer", "min:0", "exists:vending_machines,id", "nullable"],
            "type" => ["string", "nullable"],
            "remaining_amount" => ["integer", "min:0", "nullable"],
        ]);

        try {
            $coinStack = $this->vendingMachineService->getCoinStackManager()->update(
                $coinStack, 
                ((isset($attributes["vending_machine_id"])) ? (int)$attributes["vending_machine_id"] : null),
                ((isset($attributes["type"])) ? $attributes["type"] : null), 
                ((isset($attributes["remaining_amount"])) ? (int)$attributes["remaining_amount"] : null),
            );
        }
        catch (VendingMachineException|CurrencyManagerException $exception) {
            $this->vendingMachineService->getLogManager()->createErrorFromException(
                (isset($attributes["vending_machine_id"]) ? (int)$attributes["vending_machine_id"] : $coinStack->vending_machine_id), $exception
            );

            // throw HttpHelpers::validationExceptionFromVendingMachineService($exception);

            return new CoinStackResource($coinStack)->additional(["error" => ResourceHelpers::errorMessageFromVendingMachineService($exception)]);
        }

        return new CoinStackResource($coinStack);
    }

    public function delete(Request $request, CoinStack $coinStack): CoinStackResource
    {
        $success = false;

        try {
            $success = $this->vendingMachineService->getCoinStackManager()->delete($coinStack);
        }
        catch (VendingMachineException|CurrencyManagerException $exception) {
            $this->vendingMachineService->getLogManager()->createErrorFromException($coinStack->vending_machine_id, $exception);

            // throw HttpHelpers::validationExceptionFromVendingMachineService($exception);

            return new CoinStackResource($coinStack)->additional(["error" => ResourceHelpers::errorMessageFromVendingMachineService($exception)]);
        }

        return new CoinStackResource($coinStack)->additional(["success" => $success]);
    }
}
