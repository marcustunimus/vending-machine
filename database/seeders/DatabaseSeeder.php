<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Database\Seeders\CoinStackSeeder;
use Database\Seeders\CurrencySeeder;
use Database\Seeders\ItemSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        (new CurrencySeeder())->run();
        (new VendingMachineSeeder())->run();
        (new CoinStackSeeder())->run();
        (new ItemSeeder())->run();
    }
}
