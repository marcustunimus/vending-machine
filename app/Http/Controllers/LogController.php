<?php

namespace App\Http\Controllers;

use App\Http\HttpHelpers;
use App\Http\Resources\LogResource;
use App\Http\Resources\ResourceHelpers;
use App\Models\Log;
use App\Services\VendingMachine\CurrencyManagerException;
use App\Services\VendingMachine\VendingMachineService;
use App\Services\VendingMachine\VendingMachineException;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class LogController extends Controller
{
    public VendingMachineService $vendingMachineService;



    public function __construct(VendingMachineService $vendingMachineService)
    {
        $this->vendingMachineService = $vendingMachineService;
    }



    public function manageLogsPage(Request $request): Factory|View
    {
        return view("manage-logs", [
            "logs" => Log::all(),
        ]);
    }



    public function index(Request $request): AnonymousResourceCollection
    {
        return LogResource::collection(Log::all());
    }

    public function show(Request $request, Log $log): LogResource
    {
        return new LogResource($log);
    }



    public function create(Request $request): LogResource
    {
        $attributes = $request->validate([
            "vending_machine_id" => ["required", "integer", "min:0", "exists:vending_machines,id"],
            "type" => ["required", "string"],
            "text" => ["required", "string"],
        ]);

        $log = null;

        try {
            $log = $this->vendingMachineService->getLogManager()->create(
                ((isset($attributes["vending_machine_id"])) ? (int)$attributes["vending_machine_id"] : null),
                ((isset($attributes["type"])) ? $attributes["type"] : null),
                ((isset($attributes["text"])) ? $attributes["text"] : null),
            );
        }
        catch (VendingMachineException $exception) {
            $this->vendingMachineService->getLogManager()->createErrorFromException(
                ((isset($attributes["vending_machine_id"])) ? (int)$attributes["vending_machine_id"] : null), $exception
            );

            // throw HttpHelpers::validationExceptionFromVendingMachineService($exception);

            return new LogResource($log)->additional(["error" => ResourceHelpers::errorMessageFromVendingMachineService($exception)]);
        }

        return new LogResource($log);
    }

    public function update(Request $request, Log $log): LogResource
    {
        $attributes = $request->validate([
            "vending_machine_id" => ["integer", "min:0", "exists:vending_machines,id", "nullable"],
            "type" => ["string", "nullable"],
            "text" => ["string", "nullable"],
        ]);

        try {
            $log = $this->vendingMachineService->getLogManager()->update(
                $log,
                ((isset($attributes["vending_machine_id"])) ? (int)$attributes["vending_machine_id"] : $log->vending_machine_id),
                ((isset($attributes["type"])) ? $attributes["type"] : null),
                ((isset($attributes["text"])) ? $attributes["text"] : null),
            );
        }
        catch (VendingMachineException|CurrencyManagerException $exception) {
            $this->vendingMachineService->getLogManager()->createErrorFromException(
                (isset($attributes["vending_machine_id"]) ? (int)$attributes["vending_machine_id"] : $log->vending_machine_id), $exception
            );

            // throw HttpHelpers::validationExceptionFromVendingMachineService($exception);

            return new LogResource($log)->additional(["error" => ResourceHelpers::errorMessageFromVendingMachineService($exception)]);
        }

        return new LogResource($log);
    }

    public function delete(Request $request, Log $log): LogResource
    {
        $success = false;

        try {
            $success = $this->vendingMachineService->getLogManager()->delete($log);
        }
        catch (VendingMachineException|CurrencyManagerException $exception) {
            $this->vendingMachineService->getLogManager()->createErrorFromException($log->vending_machine_id, $exception);

            // throw HttpHelpers::validationExceptionFromVendingMachineService($exception);

            return new LogResource($log)->additional(["error" => ResourceHelpers::errorMessageFromVendingMachineService($exception)]);
        }

        return new LogResource($log)->additional(["success" => $success]);
    }
}
