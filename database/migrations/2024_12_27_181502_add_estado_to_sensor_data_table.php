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
        Schema::table('sensors', function (Blueprint $table) {
            $table->enum('estado', ['activo', 'inactivo', 'mantenimiento'])->default('activo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sensors', function (Blueprint $table) {
            $table->dropColumn('estado');
        });
    }
};
