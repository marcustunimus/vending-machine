<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Currency::insert([
            [
                "code" => "EUR",
                "euro_rate" => "100",
                "format" => "€{PRICE}"
            ],
            [
                "code" => "BGN",
                "euro_rate" => "196",
                "format" => "{PRICE} лв."
            ],
            [
                "code" => "USD",
                "euro_rate" => "117",
                "format" => "\${PRICE}"
            ],
        ]);
    }
}
