<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sensors', function (Blueprint $table) {
            $table->id();
            $table->string('description');
            $table->enum('farm_type', ['acuaponica', 'hidroponica', 'vigilancia', 'riego']);
            $table->timestamps();
        });

        // Insertar sensores disponibles
        DB::table('sensors')->insert([
            [
                'description' => 'Sensor de pH',
                'farm_type' => 'acuaponica',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'description' => 'Sensor de Temperatura del Agua',
                'farm_type' => 'acuaponica',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'description' => 'Sensor de Oxígeno Disuelto',
                'farm_type' => 'acuaponica',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'description' => 'Sensor de Conductividad Eléctrica',
                'farm_type' => 'hidroponica',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'description' => 'Sensor de Temperatura Ambiente',
                'farm_type' => 'hidroponica',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'description' => 'Sensor de Humedad',
                'farm_type' => 'hidroponica',
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
        Schema::dropIfExists('sensors');
    }
};
