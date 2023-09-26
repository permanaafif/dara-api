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
        Schema::create('lupa_passwords', function (Blueprint $table) {
            $table->id();
            $table->string('email',100)->unique();
            $table->string('token',100)->unique();
            $table->string('otp',4);
            $table->dateTime('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lupa_passwords');
    }
};
