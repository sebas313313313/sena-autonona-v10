<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('municipalities', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('description', 100)->nullable();
            $table->string('department', 50)->nullable();
            $table->timestamps();
        });

        // Insertar municipios por defecto
        DB::table('municipalities')->insert([
            [
                'name' => 'Bogotá',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Medellín',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Cali',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Barranquilla',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Cartagena',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Bucaramanga',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Pereira',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Santa Marta',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Ibagué',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Pasto',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('municipalities');
    }
};
