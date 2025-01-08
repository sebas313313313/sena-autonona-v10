<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\IdentificationTypeSeeder;
use Database\Seeders\MunicipalitySeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\SecurityQuestionsSeeder;
use Database\Seeders\ComponentSeeder;
use Database\Seeders\UsersRoleSeeder;
use Database\Seeders\SuperDUserSeeder;

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
            UsersRoleSeeder::class,
            ComponentSeeder::class,
            UserSeeder::class,
            SuperDUserSeeder::class,
        ]);
    }
}
