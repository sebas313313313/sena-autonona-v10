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
        Schema::create('sensor_components', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farm_component_id')->references('id')->on('farm_components')->onDelete('cascade');
            $table->foreignId('sensor_id')->references('id')->on('sensors')->onDelete('cascade');
            $table->double('min'); // Equivalente a 'min double precision'
            $table->double('max'); // Equivalente a 'max double precision'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sensor_components');
    }
};
