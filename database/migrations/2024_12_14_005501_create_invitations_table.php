<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migración para crear la tabla de invitaciones
 * 
 * Esta tabla almacena las invitaciones enviadas a usuarios para unirse a granjas.
 * Cada invitación tiene un token único, un rol asignado y una fecha de expiración.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('invitations', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->foreignId('farm_id')->constrained()->onDelete('cascade');
            $table->string('role');
            $table->string('token')->unique();
            $table->boolean('accepted')->default(false);
            $table->timestamp('expires_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invitations');
    }
};
