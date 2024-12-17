<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\IdentificationTypeSeeder;
use Database\Seeders\MunicipalitySeeder;
use Database\Seeders\SensorDataSeeder;
use Database\Seeders\UserSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            IdentificationTypeSeeder::class,
            MunicipalitySeeder::class,
            UserSeeder::class,         // Primero crear usuario y rol
            SensorDataSeeder::class,   // Luego crear datos de sensores y granjas
        ]);
    }
}
