<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IdentificationTypeSeeder extends Seeder
{
    public function run()
    {
        // Tipos de identificación estándar en Colombia
        $types = [
            ['description' => 'Cédula de Ciudadanía (CC)'],
            ['description' => 'Tarjeta de Identidad (TI)'],
            ['description' => 'Registro Civil (RC)'],
            ['description' => 'Pasaporte (PA)'],
            ['description' => 'Cédula de Extranjería (CE)'],
            ['description' => 'Número de Identificación Tributaria (NIT)']
        ];

        foreach ($types as $type) {
            DB::table('identification_types')->insertOrIgnore($type);
        }
    }
}
