<?php

namespace App\Http\Controllers;

use App\Http\HttpHelpers;
use App\Http\Resources\CurrencyResource;
use App\Http\Resources\ResourceHelpers;
use App\Models\Currency;
use App\Services\VendingMachine\CurrencyManager;
use App\Services\VendingMachine\CurrencyManagerException;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CurrencyController extends Controller
{
    private CurrencyManager $currencyManager;



    public function __construct()
    {
        $this->currencyManager = new CurrencyManager();
    }


    public function manageCurrenciesPage(Request $request): Factory|View
    {
        return view("manage-currencies", [
            "currencies" => Currency::all(),
        ]);
    }



    public function index(Request $request): AnonymousResourceCollection
    {
        return CurrencyResource::collection(Currency::all());
    }

    public function show(Request $request, Currency $currency): CurrencyResource
    {
        return new CurrencyResource($currency);
    }

    

    public function create(Request $request): CurrencyResource
    {
        $attributes = $request->validate([
            "code" => ["required", "string"],
            "euro_rate" => ["required", "numeric"],
            "format" => ["required", "string"],
        ]);

        $currency = null;

        if (!isset($attributes["euro_rate"])) {
            $attributes["euro_rate"] = round($attributes["euro_rate"] * 100);
        }

        try {
            $currency = $this->currencyManager->create(
                ((isset($attributes["code"])) ? $attributes["code"] : null),
                ((isset($attributes["euro_rate"])) ? $attributes["euro_rate"] : null),
                ((isset($attributes["format"])) ? $attributes["format"] : null),
            );
        }
        catch (CurrencyManagerException $exception) {
            // throw HttpHelpers::validationExceptionFromVendingMachineService($exception);

            return new CurrencyResource($currency)->additional(["error" => ResourceHelpers::errorMessageFromVendingMachineService($exception)]);
        }

        return new CurrencyResource($currency);
    }

    public function update(Request $request, Currency $currency): CurrencyResource
    {
        $attributes = $request->validate([
            "euro_rate" => ["numeric", "nullable"],
            "format" => ["string", "nullable"],
        ]);

        if (!isset($attributes["euro_rate"])) {
            $attributes["euro_rate"] = round($attributes["euro_rate"] * 100);
        }

        try {
            $currency = $this->currencyManager->update(
                $currency, 
                null,
                ((isset($attributes["euro_rate"])) ? $attributes["euro_rate"] : null),
                ((isset($attributes["format"])) ? $attributes["format"] : null),
            );
        }
        catch (CurrencyManagerException $exception) {
            // throw HttpHelpers::validationExceptionFromVendingMachineService($exception);

            return new CurrencyResource($currency)->additional(["error" => ResourceHelpers::errorMessageFromVendingMachineService($exception)]);
        }

        return new CurrencyResource($currency);
    }

    public function delete(Request $request, Currency $currency): CurrencyResource
    {
        $success = false;

        try {
            $success = $this->currencyManager->delete($currency);
        }
        catch (CurrencyManagerException $exception) {
            // throw HttpHelpers::validationExceptionFromVendingMachineService($exception);

            return new CurrencyResource($currency)->additional(["error" => ResourceHelpers::errorMessageFromVendingMachineService($exception)]);
        }

        return new CurrencyResource($currency)->additional(["success" => $success]);
    }
}
