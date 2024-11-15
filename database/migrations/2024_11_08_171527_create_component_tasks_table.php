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
        Schema::create('component_tasks', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->time('time');
            $table->string('status', 50);
            $table->text('comments')->nullable();
            $table->foreignId('job_id')->constrained()->onDelete('cascade');
            $table->foreignId('farm_component_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            // Índices
            $table->index('date');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('component_tasks');
    }
};
