<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear usuario admin
        $adminUserId = DB::table('users')->insertGetId([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Crear usuario regular
        $regularUserId = DB::table('users')->insertGetId([
            'name' => 'User',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Crear roles de usuario
        DB::table('users_roles')->insert([
            [
                'identification' => '1234567890',
                'name' => 'Admin',
                'Last_name' => 'User',
                'date_birth' => '1990-01-01',
                'direction' => 'Calle Principal 123',
                'contact' => '3001234567',
                'user_id' => $adminUserId,
                'identification_type_id' => 1,
                'municipality_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'identification' => '0987654321',
                'name' => 'Regular',
                'Last_name' => 'User',
                'date_birth' => '1995-01-01',
                'direction' => 'Avenida Secundaria 456',
                'contact' => '3007654321',
                'user_id' => $regularUserId,
                'identification_type_id' => 1,
                'municipality_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
