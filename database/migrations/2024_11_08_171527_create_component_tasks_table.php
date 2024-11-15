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
            $table->index('date');
            $table->time('time');
            $table->index('status');
            $table->string('status', 50);
            $table->text('comments')->nullable();
            $table->foreignId('job_id')->references('id')->on('jobs')->onDelete('cascade');
            $table->foreignId('farm_component_id')->references('id')->on('farm_components')->onDelete('cascade');
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamp('created_at');
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
