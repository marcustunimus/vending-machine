<?php

use App\Http\Controllers\CoinStackController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\VendingMachineController;
use App\Http\Middleware\CheckAdminAPIKey;
use Illuminate\Support\Facades\Route;



// Currencies

Route::get("/currencies", [CurrencyController::class, "index"])
    ->name("currencies.index");

Route::get("/currencies/{currency}", [CurrencyController::class, "show"])
    ->where("currency", '^[A-Z]{3}$')
    ->name("currencies.show");

// Vending Machines

Route::get("/vending-machines", [VendingMachineController::class, "index"])
    ->name("vending-machines.index");

Route::get("/vending-machines/{vendingMachine}", [VendingMachineController::class, "show"])
    ->where("vendingMachine", '^[0-9]+$')
    ->name("vending-machines.show");

// Vending Machines Actions

Route::post("/vending-machine/{vendingMachine}/balance/coins", [VendingMachineController::class, "insertCoin"])
    ->where("vendingMachine", '^[0-9]+$')
    ->name('vending-machines.insert-coin');

Route::post("/vending-machine/{vendingMachine}/balance/purchaseItems/{item}", [VendingMachineController::class, "purchaseItem"])
    ->where("vendingMachine", '^[0-9]+$')
    ->where("item", '^[0-9]+$')
    ->name('vending-machines.purchase-item');

Route::post("/vending-machine/{vendingMachine}/balance/return", [VendingMachineController::class, "returnBalance"])
    ->where("vendingMachine", '^[0-9]+$')
    ->name('vending-machines.return-balance');

// Coin Stacks

Route::get("/coin-stacks", [CoinStackController::class, "index"])
    ->name('coin-stacks.index');

Route::get("/coin-stacks/{coinStack}", [CoinStackController::class, "show"])
    ->where("coinStack", '^[0-9]+$')
    ->name('coin-stacks.show');

// Items

Route::get("/items", [ItemController::class, "index"])
    ->name('items.index');

Route::get("/items/{item}", [ItemController::class, "show"])
    ->where("item", '^[0-9]+$')
    ->name('items.show');



// Authenticate Only Admin Routes

Route::middleware(CheckAdminAPIKey::class)->group(function() {

    // Currencies

    Route::post("/currencies", [CurrencyController::class, "create"])
        ->name("currencies.create");

    Route::patch("/currencies/{currency}", [CurrencyController::class, "update"])
        ->where("currency", '^[A-Z]{3}$')
        ->name("currencies.update");

    // Route::delete("/currencies/{currency}", [CurrencyController::class, "delete"])
    //     ->where("currency", '^[A-Z]{3}$')
    //     ->name("currencies.delete");

    // Vending Machines

    Route::post("/vending-machines", [VendingMachineController::class, "create"])
        ->name("vending-machines.create");

    Route::patch("/vending-machines/{vendingMachine}", [VendingMachineController::class, "update"])
        ->where("vendingMachine", '^[0-9]+$')
        ->name("vending-machines.update");

    Route::delete("/vending-machines/{vendingMachine}", [VendingMachineController::class, "delete"])
        ->where("vendingMachine", '^[0-9]+$')
        ->name("vending-machines.delete");
    
    // Coin Stacks

    Route::post("/coin-stacks", [CoinStackController::class, "create"])
        ->name("coin-stacks.create");

    Route::patch("/coin-stacks/{coinStack}", [CoinStackController::class, "update"])
        ->where("coinStack", '^[0-9]+$')
        ->name("coin-stacks.update");

    Route::delete("/coin-stacks/{coinStack}", [CoinStackController::class, "delete"])
        ->where("coinStack", '^[0-9]+$')
        ->name("coin-stacks.delete");
    
    // Items

    Route::post("/items", [ItemController::class, "create"])
        ->name("items.create");

    Route::patch("/items/{item}", [ItemController::class, "update"])
        ->where("item", '^[0-9]+$')
        ->name("items.update");

    Route::delete("/items/{item}", [ItemController::class, "delete"])
        ->where("item", '^[0-9]+$')
        ->name("items.delete");

    // Logs

    Route::get("/logs", [LogController::class, "index"])
    ->name('logs.index');

    Route::get("/logs/{log}", [LogController::class, "show"])
        ->where("log", '^[0-9]+$')
        ->name('logs.show');

    Route::post("/logs", [LogController::class, "create"])
        ->name("logs.create");

    Route::patch("/logs/{log}", [LogController::class, "update"])
        ->where("log", '^[0-9]+$')
        ->name("logs.update");

    Route::delete("/logs/{log}", [LogController::class, "delete"])
        ->where("log", '^[0-9]+$')
        ->name("logs.delete");
});
