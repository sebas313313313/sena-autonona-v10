<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Component;
use Illuminate\Support\Facades\DB;

class ComponentSeeder extends Seeder
{
    public function run()
    {
        // Limpiar la tabla primero
        DB::table('components')->truncate();

        // Insertar solo los componentes especificados
        DB::table('components')->insert([
            [
                'description' => 'Acuaponia',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'description' => 'Hidroponia',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'description' => 'Sistema de Riego',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'description' => 'Sistema de Vigilancia',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
