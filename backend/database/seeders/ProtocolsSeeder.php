<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProtocolsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $inserts = [
            ['name' => '1wire', 'text' => '1-Wire'],
            ['name' => 'mqtt', 'text' => 'MQTT' ],
            ['name' => 'gpio', 'text' => 'GPIO Output', 'description' => 'Wired directly to controller']
        ];

        foreach ($inserts as $insert){
            DB::table('protocols')->insert($insert);
        }
    }
}
