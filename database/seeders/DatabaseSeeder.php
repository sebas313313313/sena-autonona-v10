<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\IdentificationTypeSeeder;
use Database\Seeders\MunicipalitySeeder;
use Database\Seeders\SensorDataSeeder;
use Database\Seeders\SensorSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\SecurityQuestionsSeeder;
use Database\Seeders\ComponentSeeder;
use Database\Seeders\UsersRoleSeeder;
use Database\Seeders\SampleSeeder;
use Database\Seeders\BaseSensorsSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            SecurityQuestionsSeeder::class,
            IdentificationTypeSeeder::class,
            MunicipalitySeeder::class,
            UsersRoleSeeder::class,        // Primero roles de usuario
            ComponentSeeder::class,
            BaseSensorsSeeder::class,
            SensorSeeder::class,
            UserSeeder::class,
            SensorDataSeeder::class,
            SampleSeeder::class  // Agregando el nuevo seeder
        ]);
    }
}
