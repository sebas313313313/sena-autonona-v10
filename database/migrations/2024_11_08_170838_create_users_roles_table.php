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
        Schema::create('users_roles', function (Blueprint $table) {
            $table->id();
            $table->string('identification', 15)->nullable();
            $table->string('name', 50)->nullable();
            $table->string('Last_name', 50)->nullable();
            $table->date('date_birth')->nullable();
            $table->text('direction')->nullable();
            $table->string('contact', 10)->nullable();
            $table->string('role')->default('user');
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('identification_type_id')->references('id')->on('identification_types')->onDelete('cascade');
            $table->foreignId('municipality_id')->references('id')->on('municipalities')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_roles');
    }
};
