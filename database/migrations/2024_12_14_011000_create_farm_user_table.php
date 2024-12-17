<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migración para crear la tabla pivote entre usuarios y granjas
 * 
 * Esta tabla maneja la relación muchos a muchos entre usuarios y granjas,
 * permitiendo que un usuario pueda pertenecer a múltiples granjas con diferentes roles,
 * y que una granja pueda tener múltiples usuarios.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Crea la tabla farm_user con:
     * - user_id: ID del usuario
     * - farm_id: ID de la granja
     * - role: Rol del usuario en la granja (admin, operario)
     * - timestamps: Fechas de creación y actualización
     */
    public function up()
    {
        Schema::create('farm_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('farm_id')->constrained()->onDelete('cascade');
            $table->string('role'); // admin, operario
            $table->timestamps();

            $table->unique(['user_id', 'farm_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('farm_user');
    }
};
