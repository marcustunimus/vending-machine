<?php

namespace Database\Seeders;

use App\Models\CoinStack;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CoinStackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CoinStack::insert([
            [
                "vending_machine_id" => 1,
                "type" => 100,
                "remaining_amount" => 25,
            ],
            [
                "vending_machine_id" => 1,
                "type" => 20,
                "remaining_amount" => 25,
            ],
            [
                "vending_machine_id" => 1,
                "type" => 10,
                "remaining_amount" => 25,
            ],
            [
                "vending_machine_id" => 1,
                "type" => 5,
                "remaining_amount" => 25,
            ],
            [
                "vending_machine_id" => 1,
                "type" => 2,
                "remaining_amount" => 25,
            ],
            [
                "vending_machine_id" => 1,
                "type" => 1,
                "remaining_amount" => 25,
            ],
        ]);

        CoinStack::insert([
            [
                "vending_machine_id" => 2,
                "type" => 42,
                "remaining_amount" => 25,
            ],
            [
                "vending_machine_id" => 2,
                "type" => 23,
                "remaining_amount" => 25,
            ],
            [
                "vending_machine_id" => 2,
                "type" => 17,
                "remaining_amount" => 25,
            ],
            [
                "vending_machine_id" => 2,
                "type" => 15,
                "remaining_amount" => 25,
            ],
            [
                "vending_machine_id" => 2,
                "type" => 7,
                "remaining_amount" => 25,
            ],
            [
                "vending_machine_id" => 2,
                "type" => 1,
                "remaining_amount" => 25,
            ],
        ]);

        CoinStack::insert([
            [
                "vending_machine_id" => 3,
                "type" => 57,
                "remaining_amount" => 25,
            ],
            [
                "vending_machine_id" => 3,
                "type" => 21,
                "remaining_amount" => 25,
            ],
            [
                "vending_machine_id" => 3,
                "type" => 12,
                "remaining_amount" => 25,
            ],
            [
                "vending_machine_id" => 3,
                "type" => 3,
                "remaining_amount" => 30,
            ],
            [
                "vending_machine_id" => 3,
                "type" => 2,
                "remaining_amount" => 50,
            ],
            [
                "vending_machine_id" => 3,
                "type" => 1,
                "remaining_amount" => 50,
            ],
        ]);
    }
}
