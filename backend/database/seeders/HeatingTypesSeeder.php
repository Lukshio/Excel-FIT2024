<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HeatingTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $inserts = [
            ['name' => 'fireplace', 'text' => 'Vytápění krbem', 'description' => 'Vytápění krbem'],
            ['name' => 'gas_boiler', 'text' => 'Vytápění kotlem', 'description' => 'Vytápění kotlem'],
            ['name' => 'combined', 'text' => 'Kombinované vytápění', 'description' => 'Kombinované vytápění'],
            ['name' => 'turned_off', 'text' => 'Vytápění vypnuto', 'description' => 'Vytápění vypnuto']
        ];

        foreach ($inserts as $insert){
            DB::table('heating_modes')->insert($insert);
        }
    }
}
