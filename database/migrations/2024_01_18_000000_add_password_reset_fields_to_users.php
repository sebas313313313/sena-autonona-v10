<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPasswordResetFieldsToUsers extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('password_reset_token')->nullable();
            $table->timestamp('password_reset_expires_at')->nullable();
            $table->string('recovery_question')->nullable();
            $table->string('recovery_answer')->nullable();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('password_reset_token');
            $table->dropColumn('password_reset_expires_at');
            $table->dropColumn('recovery_question');
            $table->dropColumn('recovery_answer');
        });
    }
}
