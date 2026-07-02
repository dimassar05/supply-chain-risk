<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PortSeeder extends Seeder
{
    public function run(): void
    {
        $ports = [
            // country_id 1 = Germany, 2 = China, 3 = Indonesia (Sesuai seeder sebelumnya)
            ['country_id' => 1, 'name' => 'Port of Hamburg', 'latitude' => '53.5488', 'longitude' => '9.9872'],
            ['country_id' => 2, 'name' => 'Port of Shanghai', 'latitude' => '31.2222', 'longitude' => '121.4581'],
            ['country_id' => 3, 'name' => 'Tanjung Priok', 'latitude' => '-6.1105', 'longitude' => '106.8796'],
        ];

        DB::table('ports')->insert($ports);
    }
}