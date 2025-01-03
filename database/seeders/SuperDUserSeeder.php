<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SuperDUserSeeder extends Seeder
{
    public function run()
    {
        // Crear el usuario especial de SuperD si no existe
        if (!User::where('email', 'super.d@example.com')->exists()) {
            User::create([
                'name' => 'Super D',
                'email' => 'super.d@example.com',
                'password' => Hash::make('superD2024'), // Cambia esta contraseña en producción
            ]);
        }
    }
}
