<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $inserts = [
            ['name' => 'offset', 'value' => 0, 'description' => 'Offset used for thermostat'],
            ['name' => 'temperature_log_interval', 'value' => 0, 'description' => 'Interval for logging'],
            ['name' => 'thermostat_check_interval', 'value' => 0, 'description' => 'Interval for thermostat temp check'],
            ['name' => 'heating_mode', 'value' => 1, 'description' => 'Heating mode'],
            ['name' => 'el_heating', 'value' => 0, 'description' => 'electric heating for water mode'],
            ['name' => 'combined_switch_min_temp', 'value' => 55, 'description' => ''],
            ['name' => 'protection_temperature', 'value' => 95, 'description' => 'open all heating zones if temperature is higher than value'],
            ['name' => 'default_skip_cycles', 'value' => 10, 'description' => 'combined heating skip cycles'],
            ['name' => 'current_skip_cycles', 'value' => 0, 'description' => 'combined heating current skip cycles'],
        ];

        foreach ($inserts as $insert){
            DB::table('settings')->insert($insert);
        }
    }
}
