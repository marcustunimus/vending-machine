<?php

namespace App\Http\Controllers;

use App\Http\HttpHelpers;
use App\Http\Resources\ItemResource;
use App\Http\Resources\ResourceHelpers;
use App\Models\Item;
use App\Services\VendingMachine\CurrencyManagerException;
use App\Services\VendingMachine\VendingMachineService;
use App\Services\VendingMachine\VendingMachineException;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ItemController extends Controller
{
    public VendingMachineService $vendingMachineService;



    public function __construct(VendingMachineService $vendingMachineService)
    {
        $this->vendingMachineService = $vendingMachineService;
    }



    public function manageItemsPage(Request $request): Factory|View
    {
        return view("manage-items", [
            "items" => Item::all(),
        ]);
    }



    public function index(Request $request): AnonymousResourceCollection
    {
        return ItemResource::collection(Item::all());
    }

    public function show(Request $request, Item $item): ItemResource
    {
        return new ItemResource($item);
    }

    

    public function create(Request $request): ItemResource
    {
        $attributes = $request->validate([
            "vending_machine_id" => ["required", "integer", "min:0", "exists:vending_machines,id"],
            "name" => ["required", "string"],
            "price" => ["required", "numeric"],
            "remaining_quantity" => ["integer", "min:0", "nullable"],
        ]);

        $item = null;

        if (isset($attributes["price"])) {
            $attributes["price"] = round($attributes["price"] * 100);
        }

        try {
            $item = $this->vendingMachineService->getItemManager()->create(
                ((isset($attributes["vending_machine_id"])) ? (int)$attributes["vending_machine_id"] : null),
                ((isset($attributes["name"])) ? $attributes["name"] : null),
                ((isset($attributes["price"])) ? $attributes["price"] : null), 
                ((isset($attributes["remaining_quantity"])) ? (int)$attributes["remaining_quantity"] : null),
            );
        }
        catch (VendingMachineException|CurrencyManagerException $exception) {
            $this->vendingMachineService->getLogManager()->createErrorFromException(
                ((isset($attributes["vending_machine_id"])) ? (int)$attributes["vending_machine_id"] : null), $exception
            );

            // throw HttpHelpers::validationExceptionFromVendingMachineService($exception);

            return new ItemResource($item)->additional(["error" => ResourceHelpers::errorMessageFromVendingMachineService($exception)]);
        }

        return new ItemResource($item);
    }

    public function update(Request $request, Item $item): ItemResource
    {
        $attributes = $request->validate([
            "vending_machine_id" => ["integer", "min:0", "exists:vending_machines,id", "nullable"],
            "name" => ["string", "nullable"],
            "price" => ["numeric", "nullable"],
            "remaining_quantity" => ["integer", "min:0", "nullable"],
        ]);

        if (isset($attributes["price"])) {
            $attributes["price"] = round($attributes["price"] * 100);
        }

        try {
            $item = $this->vendingMachineService->getItemManager()->update(
                $item, 
                ((isset($attributes["vending_machine_id"])) ? (int)$attributes["vending_machine_id"] : null),
                ((isset($attributes["name"])) ? $attributes["name"] : null),
                ((isset($attributes["price"])) ? $attributes["price"] : null), 
                ((isset($attributes["remaining_quantity"])) ? (int)$attributes["remaining_quantity"] : null),
            );
        }
        catch (VendingMachineException|CurrencyManagerException $exception) {
            $this->vendingMachineService->getLogManager()->createErrorFromException(
                (isset($attributes["vending_machine_id"]) ? (int)$attributes["vending_machine_id"] : $item->vending_machine_id), $exception
            );

            // throw HttpHelpers::validationExceptionFromVendingMachineService($exception);

            return new ItemResource($item)->additional(["error" => ResourceHelpers::errorMessageFromVendingMachineService($exception)]);
        }

        return new ItemResource($item);
    }

    public function delete(Request $request, Item $item): ItemResource
    {
        $success = false;

        try {
            $success = $this->vendingMachineService->getItemManager()->delete($item);
        }
        catch (VendingMachineException|CurrencyManagerException $exception) {
            $this->vendingMachineService->getLogManager()->createErrorFromException($item->vending_machine_id, $exception);

            // throw HttpHelpers::validationExceptionFromVendingMachineService($exception);

            return new ItemResource($item)->additional(["error" => ResourceHelpers::errorMessageFromVendingMachineService($exception)]);
        }

        return new ItemResource($item)->additional(["success" => $success]);
    }
}
 