<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountrySeeder extends Seeder
{
    public function run(): void
    {
        $countries = [
            ['name' => 'Germany', 'code' => 'DE', 'currency_code' => 'EUR', 'region' => 'Europe'],
            ['name' => 'China', 'code' => 'CN', 'currency_code' => 'CNY', 'region' => 'Asia'],
            ['name' => 'Indonesia', 'code' => 'ID', 'currency_code' => 'IDR', 'region' => 'Asia'],
            ['name' => 'Australia', 'code' => 'AU', 'currency_code' => 'AUD', 'region' => 'Oceania'],
            ['name' => 'United States', 'code' => 'US', 'currency_code' => 'USD', 'region' => 'Americas'],
        ];

        DB::table('countries')->insert($countries);
    }
}