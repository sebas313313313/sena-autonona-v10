<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('farms', function (Blueprint $table) {
            $table->id(); // Equivalente a 'id_granja serial'
            $table->double('latitude', 15, 8); // Equivalente a 'coordenadas_latitud double precision'
            $table->double('longitude', 15, 8); // Equivalente a 'coordenadas_longitud double precision'
            $table->string('address', 50); // Equivalente a 'direccion character varying(50)'
            $table->string('vereda', 50); // Equivalente a 'vereda character varying(50)'
            $table->string('extension', 50); // Equivalente a 'extension character varying(50)'
            $table->timestamps();
            
            // Relaciones de claves foráneas (si existen tablas de referencia)
            $table->foreignId('users_role_id')->constrained('users_roles')->onDelete('cascade');
            $table->foreignId('municipality_id')->constrained('municipalities')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('farms');
    }
};
