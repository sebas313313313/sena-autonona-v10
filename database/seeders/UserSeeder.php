<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Users_Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Crear usuario administrador
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);

        // Crear rol de administrador
        Users_Role::create([
            'user_id' => $user->id,
            'role' => 'admin',
            'name' => 'Administrador',
            'Last_name' => 'Sistema',
            'identification' => '1234567890',
            'identification_type_id' => 1,
            'municipality_id' => 1,
            'date_birth' => '1990-01-01',
            'direction' => 'Dirección Administrativa',
            'contact' => '3001234567'
        ]);
    }
}
