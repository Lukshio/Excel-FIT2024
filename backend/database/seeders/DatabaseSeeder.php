<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'admin',
            'email' => 'dev@dev.cz',
            'password' => '$2a$10$1TighDTpduzFxoILYBUpAuHLG4SVcoVJK13EXxa/BTAUns9XUgP6G',
            'role' => 'admin'
        ]);

        $this->call([
            DeviceTypesSeeder::class,
            ProtocolsSeeder::class,
            ZoneTypesSeeder::class,
            SettingsSeeder::class,
            HeatingTypesSeeder::class
        ]);
    }
}
