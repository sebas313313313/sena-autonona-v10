<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Municipality;

class MunicipalitySeeder extends Seeder
{
    public function run(): void
    {
        $municipalities = [
            ['name' => 'Medellín'],
            ['name' => 'Bello'],
            ['name' => 'Itagüí'],
            ['name' => 'Envigado'],
            ['name' => 'Sabaneta'],
            ['name' => 'La Estrella'],
            ['name' => 'Caldas'],
            ['name' => 'Copacabana'],
            ['name' => 'Girardota'],
            ['name' => 'Barbosa'],
        ];

        foreach ($municipalities as $municipality) {
            Municipality::create($municipality);
        }
    }
}
