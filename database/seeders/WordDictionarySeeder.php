<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WordDictionarySeeder extends Seeder
{
    public function run(): void
    {
        $positiveWords = [
            ['word' => 'growth'],
            ['word' => 'increase'],
            ['word' => 'profit'],
            ['word' => 'stable'],
            ['word' => 'improve'],
            ['word' => 'recovery'], 
        ];

        $negativeWords = [
            ['word' => 'war'],
            ['word' => 'crisis'],
            ['word' => 'inflation'],
            ['word' => 'delay'],
            ['word' => 'disaster'],
            ['word' => 'decrease'], 
        ];

        DB::table('positive_words')->insert($positiveWords);
        DB::table('negative_words')->insert($negativeWords);
    }
}