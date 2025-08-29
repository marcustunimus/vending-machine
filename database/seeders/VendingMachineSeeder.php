<?php

namespace Database\Seeders;

use App\Models\VendingMachine;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VendingMachineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        VendingMachine::insert([
            [
                "currency_code" => "EUR",
                "key" => "VendingMachine1",
                "location" => "Berlin, Germany",
                "balance_amount" => 0,
            ],
            [
                "currency_code" => "BGN",
                "key" => "VendingMachine2",
                "location" => "Sofia, Bulgaria",
                "balance_amount" => 0,
            ],
            [
                "currency_code" => "USD",
                "key" => "VendingMachine3",
                "location" => "Dallas, Texas",
                "balance_amount" => 0,
            ],
        ]);
    }
}
