<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DeviceTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $inserts = [
            ['name' => 'temperature_sensor', 'text' => 'Teplotní senzor', 'description' => 'Senzor'],
            ['name' => 'relay', 'text' => 'Relay', 'description' => 'Relé']
        ];

        foreach ($inserts as $insert){
            DB::table('device_types')->insert($insert);
        }

    }
}
