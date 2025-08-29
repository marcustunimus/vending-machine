<?php

namespace Database\Seeders;

use App\Models\Item;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Item::insert([
            [
                "vending_machine_id" => 1,
                "name" => "Premium CAFFE™",
                "price" => 120,
                "remaining_quantity" => 20,
            ],
            [
                "vending_machine_id" => 1,
                "name" => "Espresso",
                "price" => 60,
                "remaining_quantity" => 10,
            ],
            [
                "vending_machine_id" => 1,
                "name" => "Latte",
                "price" => 40,
                "remaining_quantity" => 10,
            ],
            [
                "vending_machine_id" => 1,
                "name" => "Cappuccino",
                "price" => 50,
                "remaining_quantity" => 10,
            ],
            [
                "vending_machine_id" => 1,
                "name" => "Americano",
                "price" => 55,
                "remaining_quantity" => 10,
            ],
            [
                "vending_machine_id" => 1,
                "name" => "Hot chocolate",
                "price" => 40,
                "remaining_quantity" => 10,
            ],
            [
                "vending_machine_id" => 1,
                "name" => "Mocha",
                "price" => 45,
                "remaining_quantity" => 10,
            ],
            [
                "vending_machine_id" => 1,
                "name" => "Long Espresso",
                "price" => 65,
                "remaining_quantity" => 10,
            ],
            [
                "vending_machine_id" => 1,
                "name" => "Milk",
                "price" => 30,
                "remaining_quantity" => 10,
            ],
        ]);

        Item::insert([
            [
                "vending_machine_id" => 2,
                "name" => "Premium CAFFE™",
                "price" => 120,
                "remaining_quantity" => 20,
            ],
            [
                "vending_machine_id" => 2,
                "name" => "Espresso",
                "price" => 60,
                "remaining_quantity" => 10,
            ],
            [
                "vending_machine_id" => 2,
                "name" => "Latte",
                "price" => 40,
                "remaining_quantity" => 10,
            ],
            [
                "vending_machine_id" => 2,
                "name" => "Cappuccino",
                "price" => 50,
                "remaining_quantity" => 10,
            ],
            [
                "vending_machine_id" => 2,
                "name" => "Americano",
                "price" => 55,
                "remaining_quantity" => 10,
            ],
            [
                "vending_machine_id" => 2,
                "name" => "Hot chocolate",
                "price" => 40,
                "remaining_quantity" => 10,
            ],
            [
                "vending_machine_id" => 2,
                "name" => "Mocha",
                "price" => 45,
                "remaining_quantity" => 10,
            ],
            [
                "vending_machine_id" => 2,
                "name" => "Long Espresso",
                "price" => 65,
                "remaining_quantity" => 10,
            ],
            [
                "vending_machine_id" => 2,
                "name" => "Milk",
                "price" => 30,
                "remaining_quantity" => 10,
            ],
        ]);

        Item::insert([
            [
                "vending_machine_id" => 3,
                "name" => "Premium CAFFE™",
                "price" => 120,
                "remaining_quantity" => 20,
            ],
            [
                "vending_machine_id" => 3,
                "name" => "Espresso",
                "price" => 60,
                "remaining_quantity" => 10,
            ],
            [
                "vending_machine_id" => 3,
                "name" => "Latte",
                "price" => 40,
                "remaining_quantity" => 10,
            ],
            [
                "vending_machine_id" => 3,
                "name" => "Cappuccino",
                "price" => 50,
                "remaining_quantity" => 10,
            ],
            [
                "vending_machine_id" => 3,
                "name" => "Americano",
                "price" => 55,
                "remaining_quantity" => 10,
            ],
            [
                "vending_machine_id" => 3,
                "name" => "Hot chocolate",
                "price" => 40,
                "remaining_quantity" => 10,
            ],
            [
                "vending_machine_id" => 3,
                "name" => "Mocha",
                "price" => 45,
                "remaining_quantity" => 10,
            ],
            [
                "vending_machine_id" => 3,
                "name" => "Long Espresso",
                "price" => 65,
                "remaining_quantity" => 10,
            ],
            [
                "vending_machine_id" => 3,
                "name" => "Milk",
                "price" => 30,
                "remaining_quantity" => 10,
            ],
        ]);
    }
}
