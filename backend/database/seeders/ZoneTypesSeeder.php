<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ZoneTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $inserts = [
            ['name' => 'heating_zone', 'description' => 'Zóna vytápění'],
            ['name' => 'boiler', 'description' => 'Zóna pro bojler'],
            ['name' => 'water_switch', 'description' => 'Zóna pro přepínač vody'],
            ['name' => 'empty_zone', 'description' => 'Obecná zóna']
        ];

        foreach ($inserts as $insert){
            DB::table('zone_types')->insert($insert);
        }    }
}
