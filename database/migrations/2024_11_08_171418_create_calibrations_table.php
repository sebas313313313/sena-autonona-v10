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
        Schema::create('calibraciones', function (Blueprint $table) {
            $table->id('id_calibracion');
            $table->foreignId('id_sensor_cte')->constrained('sensor_componentes')->onDelete('cascade');
            $table->date('fecha');
            $table->string('parametros', 50);
            $table->string('alerta', 10)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calibraciones');
    }
};
