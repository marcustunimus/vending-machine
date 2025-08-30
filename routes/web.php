<?php

use App\Http\Controllers\CoinStackController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\VendingMachineController;
use App\Http\Middleware\CheckAdminAPIKey;
use Illuminate\Support\Facades\Route;



// Vending Machines

Route::get('/vending-machines', [VendingMachineController::class, "vendingMachinesPage"])->name("vending-machines.page");
Route::get('/vending-machines/{vendingMachine}', [VendingMachineController::class, "vendingMachinePage"])->name("vending-machine.page");

Route::middleware(CheckAdminAPIKey::class)->group(function() {

    // Vending Machines

    Route::get('/manage/vending-machines', [VendingMachineController::class, "manageVendingMachinesPage"])->name("vending-machines.manage");
    Route::get('/manage/vending-machines/{vendingMachine}', [VendingMachineController::class, "manageVendingMachinePage"])->name("vending-machine.manage");

    // Currencies

    Route::get('/manage/currencies', [CurrencyController::class, "manageCurrenciesPage"])->name("currencies.manage");

    // Coin Stacks

    Route::get('/manage/coin-stacks', [CoinStackController::class, "manageCoinStacksPage"])->name("coin-stacks.manage");

    // Items

    Route::get('/manage/items', [ItemController::class, "manageItemsPage"])->name("items.manage");

    // Logs

    Route::get('/manage/logs', [LogController::class, "manageLogsPage"])->name("logs.manage");
});
