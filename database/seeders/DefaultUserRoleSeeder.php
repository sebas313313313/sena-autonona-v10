<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Users_Role;
use App\Models\User;
use App\Models\Municipality;

class DefaultUserRoleSeeder extends Seeder
{
    public function run()
    {
        // Obtener el primer usuario (asumiendo que es el administrador)
        $user = User::first();
        
        if ($user) {
            // Verificar si ya tiene un rol asignado
            $existingRole = Users_Role::where('user_id', $user->id)->first();
            
            if (!$existingRole) {
                // Obtener el primer municipio
                $municipality = Municipality::first();
                
                // Crear el rol de usuario
                Users_Role::create([
                    'identification' => '123456789',
                    'name' => 'Admin',
                    'Last_name' => 'System',
                    'date_birth' => '1990-01-01',
                    'direction' => 'Default Address',
                    'contact' => '1234567890',
                    'user_id' => $user->id,
                    'identification_type_id' => 1, // Asumiendo que 1 es el tipo de identificación CC
                    'municipality_id' => $municipality->id
                ]);
            }
        }
    }
}
